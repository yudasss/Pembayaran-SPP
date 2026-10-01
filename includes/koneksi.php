<?php
// Pengaturan koneksi database (sesuaikan kalau MySQL kamu pakai password)
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_nama = 'pembayaran_spp';

mysqli_report(MYSQLI_REPORT_OFF);
$koneksi = new mysqli($db_host, $db_user, $db_pass, $db_nama);

if ($koneksi->connect_error) {
    die('Koneksi database gagal: ' . $koneksi->connect_error .
        '<br>Pastikan MySQL di XAMPP sudah jalan dan database "' . $db_nama . '" sudah di-import.');
}
$koneksi->set_charset('utf8mb4');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
