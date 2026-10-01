<?php
// proses_pembayaran.php - memproses transaksi pembayaran, lalu kembali ke pembayaran.php
include 'koneksi.php';

$nisn  = bersih($_POST['nisn']);
$bulan = (int)$_POST['jumlah_bulan'];
$uang  = (int)$_POST['nominal_bayar'];
$petugas = ($_POST['id_petugas'] == '') ? 'NULL' : "'" . bersih($_POST['id_petugas']) . "'";

// 1. Ambil id_spp dan nominal SPP milik siswa
$q = mysqli_query($koneksi, "SELECT s.id_spp, p.nominal FROM tb_siswa s JOIN tb_spp p ON p.id_spp = s.id_spp WHERE s.nisn='$nisn'");
$siswa = mysqli_fetch_assoc($q);
if (!$siswa || $bulan < 1) {
    kembali('pembayaran.php', 'NISN tidak ditemukan (atau siswa belum punya SPP) / jumlah bulan salah', 'danger');
}

// 2. Hitung total tagihan, tolak jika uang kurang
$total = hitung_tagihan($siswa['nominal'], $bulan);
if ($uang < $total) {
    kembali('pembayaran.php', 'Uang kurang. Total tagihan Rp ' . number_format($total), 'danger');
}
$kembalian = $uang - $total;

// 3. Tanggal "dibayar sampai": lanjut dari pembayaran terakhir (atau hari ini jika belum pernah bayar)
$q = mysqli_query($koneksi, "SELECT MAX(tgl_terakhir_bayar) AS t FROM tb_pembayaran WHERE nisn='$nisn'");
$terakhir = mysqli_fetch_assoc($q)['t'] ?? date('Y-m-d');
$akhir  = date('Y-m-d', strtotime("$terakhir +$bulan month"));
$status = tentukan_status($akhir);

// 4. Buat ID baru: PB00001, PB00002, dst
$q = mysqli_query($koneksi, "SELECT IFNULL(MAX(CAST(SUBSTRING(id_pembayaran, 3) AS UNSIGNED)), 0) + 1 AS n FROM tb_pembayaran");
$id = 'PB' . str_pad(mysqli_fetch_assoc($q)['n'], 5, '0', STR_PAD_LEFT);

// 5. Simpan transaksi
$sql = "INSERT INTO tb_pembayaran
    (id_pembayaran, id_petugas, status, nisn, tgl_bayar, tgl_terakhir_bayar, batas_pembayaran, jumlah_bulan, id_spp, nominal_bayar, jumlah_bayar, kembalian)
    VALUES ('$id', $petugas, '$status', '$nisn', CURDATE(), '$akhir', '$akhir', '$bulan', '{$siswa['id_spp']}', $uang, $total, $kembalian)";
if (mysqli_query($koneksi, $sql)) {
    kembali('pembayaran.php', "$id berhasil. Total Rp " . number_format($total) . ", kembalian Rp " . number_format($kembalian), 'success');
}
kembali('pembayaran.php', 'Gagal: ' . mysqli_error($koneksi), 'danger');
