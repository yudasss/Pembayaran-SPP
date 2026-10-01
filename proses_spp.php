<?php
// proses_spp.php - memproses Tambah / Ubah / Hapus data SPP, lalu kembali ke spp.php
include 'koneksi.php';
$aksi = $_REQUEST['aksi'];   // tambah / ubah / hapus

if ($aksi == 'hapus') {
    $id = bersih($_GET['id']);
    jalankan("DELETE FROM tb_spp WHERE id_spp='$id'", 'spp.php');
}

$id      = bersih($_POST['id_spp']);
$tahun   = (int)$_POST['tahun'];
$nominal = (int)$_POST['nominal'];

if ($aksi == 'tambah') {
    jalankan("INSERT INTO tb_spp (id_spp, tahun, nominal) VALUES ('$id', $tahun, $nominal)", 'spp.php');
}
if ($aksi == 'ubah') {
    jalankan("UPDATE tb_spp SET tahun=$tahun, nominal=$nominal WHERE id_spp='$id'", 'spp.php');
}
