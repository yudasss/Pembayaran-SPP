<?php
// inti.php - koneksi database (class OOP) + fungsi bantu yang dipakai semua halaman
session_start();

/** Class Database: membungkus PDO supaya setiap query cukup 1 baris. */
class Database {
    private $pdo;

    /** Membuka koneksi. XAMPP default: user root, password kosong. */
    public function __construct() {
        $this->pdo = new PDO('mysql:host=127.0.0.1;dbname=pembayaran_spp;charset=utf8mb4', 'root', '');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /** Menjalankan SELECT, mengembalikan semua baris (array). Parameter pakai tanda ? (anti SQL injection). */
    public function ambil($sql, $param = []) {
        $st = $this->pdo->prepare($sql);
        $st->execute($param);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Menjalankan INSERT/UPDATE/DELETE, mengembalikan jumlah baris yang terpengaruh. */
    public function jalankan($sql, $param = []) {
        $st = $this->pdo->prepare($sql);
        $st->execute($param);
        return $st->rowCount();
    }
}
$db = new Database();

/** Halaman wajib login; kalau belum login dilempar ke login.php. */
function wajib_login() {
    if (!isset($_SESSION['petugas'])) { header('Location: login.php'); exit; }
}

/** Mengamankan teks sebelum ditampilkan (anti XSS). */
function h($teks) { return htmlspecialchars((string)$teks); }

/** Total tagihan = nominal SPP per bulan x jumlah bulan. */
function hitung_tagihan($nominal, $bulan) { return $nominal * $bulan; }

/** Lunas jika tanggal terakhir bayar belum lewat dari hari ini. Belum pernah bayar = Belum Lunas. */
function tentukan_status($tgl_akhir, $hari_ini = null) {
    $hari_ini = $hari_ini ?: date('Y-m-d');
    return ($tgl_akhir !== null && $tgl_akhir >= $hari_ini) ? 'Sudah Lunas' : 'Belum Lunas';
}

/** Mengembalikan semua siswa + status + jumlah bulan tunggakan (dipakai Dashboard & Cek Pembayaran). */
function status_siswa($db) {
    $rows = $db->ambil("SELECT s.nisn, s.nama, s.no_telp, MAX(p.tgl_terakhir_bayar) AS terakhir
        FROM tb_siswa s LEFT JOIN tb_pembayaran p ON p.nisn = s.nisn
        GROUP BY s.nisn, s.nama, s.no_telp");
    foreach ($rows as $i => $r) {
        $r['status'] = tentukan_status($r['terakhir']);
        $r['bulan'] = '-';                       // '-' = belum pernah bayar
        if ($r['terakhir']) {
            $d = (new DateTime($r['terakhir']))->diff(new DateTime());
            $r['bulan'] = $r['status'] == 'Sudah Lunas' ? 0 : $d->y * 12 + $d->m;
        }
        $rows[$i] = $r;
    }
    return $rows;
}
