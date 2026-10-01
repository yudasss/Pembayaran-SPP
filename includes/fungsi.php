<?php
require_once __DIR__ . '/koneksi.php';

/* ---------- database ---------- */

// Ambil banyak baris
function ambil($sql, $params = [])
{
    global $koneksi;
    $st = $koneksi->prepare($sql);
    if (!$st) {
        die('Query error: ' . $koneksi->error);
    }
    if ($params) {
        $st->bind_param(str_repeat('s', count($params)), ...$params);
    }
    $st->execute();
    $hasil = $st->get_result();
    $baris = $hasil ? $hasil->fetch_all(MYSQLI_ASSOC) : [];
    $st->close();
    return $baris;
}

// Ambil satu baris (atau null)
function ambilSatu($sql, $params = [])
{
    $baris = ambil($sql, $params);
    return $baris ? $baris[0] : null;
}

// INSERT / UPDATE / DELETE
function jalankan($sql, $params = [])
{
    global $koneksi;
    $st = $koneksi->prepare($sql);
    if (!$st) {
        die('Query error: ' . $koneksi->error);
    }
    if ($params) {
        $st->bind_param(str_repeat('s', count($params)), ...$params);
    }
    $ok = $st->execute();
    $st->close();
    return $ok;
}

// Buat ID otomatis, contoh: nextId('tb_kelas', 'id_kelas', 'K', 3) => K001, K002, ...
function nextId($tabel, $kolom, $awalan, $digit)
{
    $r = ambilSatu("SELECT MAX($kolom) AS maks FROM $tabel WHERE $kolom LIKE ?", [$awalan . '%']);
    $angka = $r && $r['maks'] ? (int) substr($r['maks'], strlen($awalan)) : 0;
    return $awalan . str_pad($angka + 1, $digit, '0', STR_PAD_LEFT);
}

/* ---------- tampilan ---------- */

function e($teks)
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

function rp($angka)
{
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
}

function tgl($tanggal)
{
    return date('d/m/Y', strtotime($tanggal));
}

function bulanTahun($tanggal)
{
    if (!$tanggal) {
        return 'Belum pernah bayar';
    }
    $nama = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $t = strtotime($tanggal);
    return $nama[(int) date('n', $t)] . ' ' . date('Y', $t);
}

function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

function flash($pesan, $tipe = 'success')
{
    $_SESSION['flash'] = [$pesan, $tipe];
}

function tampilFlash()
{
    if (!empty($_SESSION['flash'])) {
        list($pesan, $tipe) = $_SESSION['flash'];
        echo '<div class="alert alert-' . $tipe . ' py-2">' . e($pesan) . '</div>';
        unset($_SESSION['flash']);
    }
}

/* ---------- logika SPP ---------- */

// Semua siswa + kelas + nominal SPP + bulan terakhir yang sudah dibayar
function daftarSiswa($cari = '')
{
    $sql = "SELECT s.*, k.nama_kelas, k.komp_keahlian, sp.nominal,
                   (SELECT MAX(p.tgl_terakhir_bayar) FROM tb_pembayaran p WHERE p.nisn = s.nisn) AS terakhir
            FROM tb_siswa s
            LEFT JOIN tb_kelas k ON k.id_kelas = s.id_kelas
            LEFT JOIN tb_spp sp ON sp.id_spp = s.id_spp";
    $params = [];
    if ($cari !== '') {
        $sql .= " WHERE s.nama LIKE ? OR s.nisn LIKE ? OR s.nis LIKE ?";
        $params = ["%$cari%", "%$cari%", "%$cari%"];
    }
    $sql .= " ORDER BY s.nama";
    return ambil($sql, $params);
}

// Jumlah bulan yang belum dibayar sampai bulan ini.
// Siswa yang belum pernah bayar dianggap menunggak 1 bulan (bulan ini).
function tunggakan($terakhir)
{
    if (!$terakhir) {
        return 1;
    }
    $t = explode('-', $terakhir);
    $selisih = ((int) date('Y') - (int) $t[0]) * 12 + ((int) date('n') - (int) $t[1]);
    return max(0, $selisih);
}

function siswaLunas()
{
    return array_values(array_filter(daftarSiswa(), function ($s) {
        return tunggakan($s['terakhir']) === 0;
    }));
}

function siswaBelumLunas()
{
    return array_values(array_filter(daftarSiswa(), function ($s) {
        return tunggakan($s['terakhir']) > 0;
    }));
}
