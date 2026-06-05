<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model {

    // Contoh: Laporan Pendapatan Berdasarkan Tanggal
    public function get_pendapatan_by_date($start_date, $end_date) {
        $this->db->select('tanggal_resep, COUNT(id_resep) as total_transaksi, SUM(total_harga) as total_pendapatan');
        $this->db->from('resep');
        $this->db->where('tanggal_resep >=', $start_date);
        $this->db->where('tanggal_resep <=', $end_date);
        $this->db->group_by('tanggal_resep');
        $this->db->order_by('tanggal_resep', 'ASC');
        
        return $this->db->get()->result();
    }

    // Contoh: Laporan Obat Paling Banyak Diresepkan
    public function get_obat_terlaris($limit = 10) {
        $this->db->select('obat.nama_obat, SUM(detail_resep.jumlah) as total_terjual');
        $this->db->from('detail_resep');
        $this->db->join('obat', 'obat.id_obat = detail_resep.id_obat');
        $this->db->group_by('detail_resep.id_obat, obat.nama_obat');
        $this->db->order_by('total_terjual', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
    
    // Total keseluruhan untuk ringkasan di halaman atas (Dashboard)
    public function get_ringkasan_hari_ini() {
        $hari_ini = date('Y-m-d');
        
        $this->db->select('COUNT(id_resep) as jumlah_resep, COALESCE(SUM(total_harga), 0) as pendapatan');
        $this->db->where('tanggal_resep', $hari_ini);
        $resep = $this->db->get('resep')->row();

        $antrean_hari_ini = $this->db->where('tanggal_antrean', $hari_ini)->count_all_results('antrean');
        $rm_hari_ini = $this->db->where('tanggal_periksa', $hari_ini)->count_all_results('rekam_medis');

        $this->db->select('COALESCE(SUM(total_tagihan), 0) as total');
        $this->db->where('status', 'Lunas');
        $this->db->where('DATE(created_at)', $hari_ini);
        $billing_lunas = $this->db->get('billing')->row();

        return (object) [
            'jumlah_resep'      => (int) ($resep->jumlah_resep ?? 0),
            'pendapatan'        => (float) ($resep->pendapatan ?? 0),
            'antrean_hari_ini'  => $antrean_hari_ini,
            'rm_hari_ini'       => $rm_hari_ini,
            'billing_lunas'     => (float) ($billing_lunas->total ?? 0),
        ];
    }

    public function get_statistik_utama() {
        $hari_ini = date('Y-m-d');
        $awal_bulan = date('Y-m-01');
        $akhir_bulan = date('Y-m-t');

        $this->db->select('COALESCE(SUM(total_harga), 0) as total');
        $this->db->where('tanggal_resep >=', $awal_bulan);
        $this->db->where('tanggal_resep <=', $akhir_bulan);
        $pendapatan_bulan = $this->db->get('resep')->row();

        $this->db->select('COALESCE(SUM(total_tagihan), 0) as total');
        $this->db->where('status', 'Lunas');
        $this->db->where('DATE(created_at) >=', $awal_bulan);
        $this->db->where('DATE(created_at) <=', $akhir_bulan);
        $billing_bulan = $this->db->get('billing')->row();

        return (object) [
            'total_pasien'         => $this->db->count_all('pasien'),
            'total_dokter'         => $this->db->count_all('dokter'),
            'total_obat'           => $this->db->count_all('obat'),
            'obat_stok_rendah'     => $this->db->where('stok <=', 5)->from('obat')->count_all_results(),
            'antrean_aktif'        => $this->db->where_in('status', ['Menunggu', 'Diperiksa'])->where('tanggal_antrean', $hari_ini)->from('antrean')->count_all_results(),
            'billing_belum_lunas'  => $this->db->where('status', 'Belum Lunas')->from('billing')->count_all_results(),
            'total_rm'             => $this->db->count_all('rekam_medis'),
            'pendapatan_bulan'     => (float) ($pendapatan_bulan->total ?? 0),
            'billing_lunas_bulan'  => (float) ($billing_bulan->total ?? 0),
        ];
    }

    public function get_trend_harian($hari = 7) {
        $labels = [];
        $pendapatan = [];
        $resep = [];
        $antrean = [];
        $kunjungan_rm = [];

        for ($i = $hari - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $labels[] = date('d M', strtotime($date));

            $row = $this->db
                ->select('COUNT(id_resep) as cnt, COALESCE(SUM(total_harga), 0) as total')
                ->where('tanggal_resep', $date)
                ->get('resep')
                ->row();
            $resep[] = (int) ($row->cnt ?? 0);
            $pendapatan[] = (float) ($row->total ?? 0);

            $antrean[] = $this->db->where('tanggal_antrean', $date)->from('antrean')->count_all_results();
            $kunjungan_rm[] = $this->db->where('tanggal_periksa', $date)->from('rekam_medis')->count_all_results();
        }

        return [
            'labels'       => $labels,
            'pendapatan'   => $pendapatan,
            'resep'        => $resep,
            'antrean'      => $antrean,
            'kunjungan_rm' => $kunjungan_rm,
        ];
    }

    public function get_antrean_per_status($tanggal = null) {
        $tanggal = $tanggal ?: date('Y-m-d');
        $this->db->select('status, COUNT(*) as jumlah');
        $this->db->from('antrean');
        $this->db->where('tanggal_antrean', $tanggal);
        $this->db->group_by('status');
        return $this->db->get()->result();
    }

    public function get_billing_per_status() {
        $this->db->select('status, COUNT(*) as jumlah, COALESCE(SUM(total_tagihan), 0) as nominal');
        $this->db->from('billing');
        $this->db->group_by('status');
        return $this->db->get()->result();
    }

    public function get_pasien_demografi() {
        $this->db->select('jenis_kelamin, COUNT(*) as jumlah');
        $this->db->from('pasien');
        $this->db->group_by('jenis_kelamin');
        return $this->db->get()->result();
    }

    public function get_kunjungan_per_layanan($hari = 30) {
        $since = date('Y-m-d', strtotime("-{$hari} days"));
        $this->db->select('layanan.nama_layanan, COUNT(antrean.id_antrean) as jumlah');
        $this->db->from('antrean');
        $this->db->join('dokter', 'dokter.id_dokter = antrean.id_dokter');
        $this->db->join('layanan', 'layanan.id_layanan = dokter.id_layanan', 'left');
        $this->db->where('antrean.tanggal_antrean >=', $since);
        $this->db->group_by('layanan.id_layanan, layanan.nama_layanan');
        $this->db->order_by('jumlah', 'DESC');
        $this->db->limit(8);
        return $this->db->get()->result();
    }

    public function get_obat_stok_rendah($threshold = 5, $limit = 8) {
        $this->db->select('nama_obat, kode_obat, stok, kategori');
        $this->db->from('obat');
        $this->db->where('stok <=', $threshold);
        $this->db->order_by('stok', 'ASC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function get_aktivitas_terbaru($limit = 10) {
        $aktivitas = [];

        $resep = $this->db
            ->select("'resep' as tipe, resep.id_resep as id, pasien.nama_lengkap as subjek, resep.tanggal_resep as tanggal, resep.total_harga as nilai")
            ->from('resep')
            ->join('pasien', 'pasien.id_pasien = resep.id_pasien')
            ->order_by('resep.id_resep', 'DESC')
            ->limit($limit)
            ->get()
            ->result();

        foreach ($resep as $r) {
            $aktivitas[] = (object) [
                'tipe'    => 'resep',
                'id'      => $r->id,
                'subjek'  => $r->subjek,
                'tanggal' => $r->tanggal,
                'nilai'   => $r->nilai,
                'label'   => 'Resep baru',
            ];
        }

        $antrean = $this->db
            ->select("'antrean' as tipe, antrean.id_antrean as id, pasien.nama_lengkap as subjek, antrean.tanggal_antrean as tanggal, antrean.status as nilai")
            ->from('antrean')
            ->join('pasien', 'pasien.id_pasien = antrean.id_pasien')
            ->order_by('antrean.id_antrean', 'DESC')
            ->limit($limit)
            ->get()
            ->result();

        foreach ($antrean as $a) {
            $aktivitas[] = (object) [
                'tipe'    => 'antrean',
                'id'      => $a->id,
                'subjek'  => $a->subjek,
                'tanggal' => $a->tanggal,
                'nilai'   => $a->nilai,
                'label'   => 'Antrean: ' . $a->nilai,
            ];
        }

        usort($aktivitas, function ($a, $b) {
            return strtotime($b->tanggal) - strtotime($a->tanggal);
        });

        return array_slice($aktivitas, 0, $limit);
    }

    public function chart_data_antrean_status($tanggal = null) {
        $rows = $this->get_antrean_per_status($tanggal);
        $labels = [];
        $data = [];
        $colors = [
            'Menunggu'  => '#F59E0B',
            'Diperiksa' => '#3B82F6',
            'Selesai'   => '#16A34A',
            'Batal'     => '#EF4444',
        ];
        $bg = [];
        foreach ($rows as $row) {
            $labels[] = $row->status;
            $data[] = (int) $row->jumlah;
            $bg[] = $colors[$row->status] ?? '#94A3B8';
        }
        return ['labels' => $labels, 'data' => $data, 'colors' => $bg];
    }

    public function chart_data_billing_status() {
        $rows = $this->get_billing_per_status();
        $labels = [];
        $data = [];
        $colors = [];
        $map = ['Lunas' => '#16A34A', 'Belum Lunas' => '#F59E0B'];
        foreach ($rows as $row) {
            $labels[] = $row->status;
            $data[] = (int) $row->jumlah;
            $colors[] = $map[$row->status] ?? '#94A3B8';
        }
        return ['labels' => $labels, 'data' => $data, 'colors' => $colors];
    }

    public function chart_data_demografi() {
        $rows = $this->get_pasien_demografi();
        $labels = [];
        $data = [];
        foreach ($rows as $row) {
            $labels[] = $row->jenis_kelamin === 'L' ? 'Laki-laki' : ($row->jenis_kelamin === 'P' ? 'Perempuan' : 'Lainnya');
            $data[] = (int) $row->jumlah;
        }
        return ['labels' => $labels, 'data' => $data];
    }

    // ==================== LAPORAN PERIODE ====================

    public function get_ringkasan_periode($start_date, $end_date) {
        $this->db->select('COUNT(id_resep) as transaksi, COALESCE(SUM(total_harga), 0) as pendapatan, COALESCE(AVG(total_harga), 0) as rata_transaksi');
        $this->db->where('tanggal_resep >=', $start_date);
        $this->db->where('tanggal_resep <=', $end_date);
        $resep = $this->db->get('resep')->row();

        $this->db->select('COUNT(*) as jumlah, COALESCE(SUM(total_tagihan), 0) as nominal');
        $this->db->where('status', 'Lunas');
        $this->db->where('DATE(created_at) >=', $start_date);
        $this->db->where('DATE(created_at) <=', $end_date);
        $billing_lunas = $this->db->get('billing')->row();

        $this->db->select('COUNT(*) as jumlah, COALESCE(SUM(total_tagihan), 0) as nominal');
        $this->db->where('status', 'Belum Lunas');
        $this->db->where('DATE(created_at) >=', $start_date);
        $this->db->where('DATE(created_at) <=', $end_date);
        $billing_pending = $this->db->get('billing')->row();

        $antrean = $this->db
            ->where('tanggal_antrean >=', $start_date)
            ->where('tanggal_antrean <=', $end_date)
            ->count_all_results('antrean');

        $rm = $this->db
            ->where('tanggal_periksa >=', $start_date)
            ->where('tanggal_periksa <=', $end_date)
            ->count_all_results('rekam_medis');

        $pasien_baru = 0;
        if ($this->db->field_exists('created_at', 'users')) {
            $this->db->from('users');
            $this->db->join('pasien', 'pasien.id_user = users.id_user');
            $this->db->where('DATE(users.created_at) >=', $start_date);
            $this->db->where('DATE(users.created_at) <=', $end_date);
            if ($this->db->field_exists('role_id', 'users')) {
                $this->db->where('users.role_id', 3);
            }
            $pasien_baru = $this->db->count_all_results();
        }

        $hari = max(1, (int) ((strtotime($end_date) - strtotime($start_date)) / 86400) + 1);

        return (object) [
            'transaksi_resep'    => (int) ($resep->transaksi ?? 0),
            'pendapatan_resep'   => (float) ($resep->pendapatan ?? 0),
            'rata_transaksi'     => (float) ($resep->rata_transaksi ?? 0),
            'billing_lunas'      => (int) ($billing_lunas->jumlah ?? 0),
            'nominal_lunas'      => (float) ($billing_lunas->nominal ?? 0),
            'billing_pending'    => (int) ($billing_pending->jumlah ?? 0),
            'nominal_pending'    => (float) ($billing_pending->nominal ?? 0),
            'total_antrean'      => $antrean,
            'total_rm'           => $rm,
            'pasien_baru'        => $pasien_baru,
            'hari_periode'       => $hari,
            'rata_pendapatan_hari' => ((float) ($resep->pendapatan ?? 0)) / $hari,
        ];
    }

    public function get_perbandingan_periode($start_date, $end_date) {
        $hari = max(1, (int) ((strtotime($end_date) - strtotime($start_date)) / 86400) + 1);
        $prev_end   = date('Y-m-d', strtotime($start_date . ' -1 day'));
        $prev_start = date('Y-m-d', strtotime($prev_end . ' -' . ($hari - 1) . ' days'));

        $current  = $this->get_ringkasan_periode($start_date, $end_date);
        $previous = $this->get_ringkasan_periode($prev_start, $prev_end);

        $pct = function ($now, $before) {
            if ($before == 0) {
                return $now > 0 ? 100.0 : 0.0;
            }
            return round((($now - $before) / $before) * 100, 1);
        };

        return (object) [
            'prev_start'          => $prev_start,
            'prev_end'            => $prev_end,
            'pendapatan_pct'      => $pct($current->pendapatan_resep, $previous->pendapatan_resep),
            'transaksi_pct'       => $pct($current->transaksi_resep, $previous->transaksi_resep),
            'antrean_pct'         => $pct($current->total_antrean, $previous->total_antrean),
            'rm_pct'              => $pct($current->total_rm, $previous->total_rm),
            'billing_lunas_pct'   => $pct($current->nominal_lunas, $previous->nominal_lunas),
        ];
    }

    public function get_obat_terlaris_periode($start_date, $end_date, $limit = 10) {
        $this->db->select('obat.nama_obat, obat.kategori, SUM(detail_resep.jumlah) as total_terjual, SUM(detail_resep.jumlah * detail_resep.harga_satuan) as nilai_penjualan');
        $this->db->from('detail_resep');
        $this->db->join('resep', 'resep.id_resep = detail_resep.id_resep');
        $this->db->join('obat', 'obat.id_obat = detail_resep.id_obat');
        $this->db->where('resep.tanggal_resep >=', $start_date);
        $this->db->where('resep.tanggal_resep <=', $end_date);
        $this->db->group_by('detail_resep.id_obat, obat.nama_obat, obat.kategori');
        $this->db->order_by('total_terjual', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function get_antrean_per_status_periode($start_date, $end_date) {
        $this->db->select('status, COUNT(*) as jumlah');
        $this->db->from('antrean');
        $this->db->where('tanggal_antrean >=', $start_date);
        $this->db->where('tanggal_antrean <=', $end_date);
        $this->db->group_by('status');
        return $this->db->get()->result();
    }

    public function get_kunjungan_per_layanan_periode($start_date, $end_date, $limit = 10) {
        $this->db->select('layanan.nama_layanan, COUNT(antrean.id_antrean) as jumlah');
        $this->db->from('antrean');
        $this->db->join('dokter', 'dokter.id_dokter = antrean.id_dokter');
        $this->db->join('layanan', 'layanan.id_layanan = dokter.id_layanan', 'left');
        $this->db->where('antrean.tanggal_antrean >=', $start_date);
        $this->db->where('antrean.tanggal_antrean <=', $end_date);
        $this->db->group_by('layanan.id_layanan, layanan.nama_layanan');
        $this->db->order_by('jumlah', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function get_dokter_teramai_periode($start_date, $end_date, $limit = 8) {
        $this->db->select('dokter.nama_dokter, dokter.spesialisasi, COUNT(resep.id_resep) as jumlah_resep, COALESCE(SUM(resep.total_harga), 0) as pendapatan');
        $this->db->from('resep');
        $this->db->join('dokter', 'dokter.id_dokter = resep.id_dokter');
        $this->db->where('resep.tanggal_resep >=', $start_date);
        $this->db->where('resep.tanggal_resep <=', $end_date);
        $this->db->group_by('resep.id_dokter, dokter.nama_dokter, dokter.spesialisasi');
        $this->db->order_by('jumlah_resep', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function get_rm_per_hari($start_date, $end_date) {
        $this->db->select('tanggal_periksa, COUNT(id_rm) as jumlah');
        $this->db->from('rekam_medis');
        $this->db->where('tanggal_periksa >=', $start_date);
        $this->db->where('tanggal_periksa <=', $end_date);
        $this->db->group_by('tanggal_periksa');
        $this->db->order_by('tanggal_periksa', 'ASC');
        return $this->db->get()->result();
    }

    public function get_billing_harian($start_date, $end_date) {
        $this->db->select('DATE(created_at) as tanggal, COUNT(*) as jumlah, COALESCE(SUM(total_tagihan), 0) as nominal');
        $this->db->from('billing');
        $this->db->where('status', 'Lunas');
        $this->db->where('DATE(created_at) >=', $start_date);
        $this->db->where('DATE(created_at) <=', $end_date);
        $this->db->group_by('DATE(created_at)');
        $this->db->order_by('tanggal', 'ASC');
        return $this->db->get()->result();
    }

    public function chart_antrean_periode($start_date, $end_date) {
        $rows = $this->get_antrean_per_status_periode($start_date, $end_date);
        $labels = [];
        $data = [];
        $colors = [
            'Menunggu'  => '#F59E0B',
            'Diperiksa' => '#3B82F6',
            'Selesai'   => '#16A34A',
            'Batal'     => '#EF4444',
        ];
        $bg = [];
        foreach ($rows as $row) {
            $labels[] = $row->status;
            $data[] = (int) $row->jumlah;
            $bg[] = $colors[$row->status] ?? '#94A3B8';
        }
        return ['labels' => $labels, 'data' => $data, 'colors' => $bg];
    }

    public function series_from_laporan_harian($laporan, $billing_harian = []) {
        $labels = [];
        $pendapatan = [];
        $transaksi = [];

        foreach ($laporan as $row) {
            $labels[] = date('d M', strtotime($row->tanggal_resep));
            $pendapatan[] = (float) $row->total_pendapatan;
            $transaksi[] = (int) $row->total_transaksi;
        }

        $billing_map = [];
        foreach ($billing_harian as $b) {
            $billing_map[$b->tanggal] = (float) $b->nominal;
        }
        $billing_series = [];
        foreach ($laporan as $row) {
            $billing_series[] = $billing_map[$row->tanggal_resep] ?? 0;
        }

        return [
            'labels'     => $labels,
            'pendapatan' => $pendapatan,
            'transaksi'  => $transaksi,
            'billing'    => $billing_series,
        ];
    }
}