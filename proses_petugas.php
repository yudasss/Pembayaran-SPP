<?php
// proses_petugas.php - memproses Tambah / Ubah / Hapus data petugas, lalu kembali ke petugas.php
include 'koneksi.php';
$aksi = $_REQUEST['aksi'];   // tambah / ubah / hapus

if ($aksi == 'hapus') {
    $id = bersih($_GET['id']);
    jalankan("DELETE FROM tb_petugas WHERE id_petugas='$id'", 'petugas.php');
}

$id    = bersih($_POST['id_petugas']);
$user  = bersih($_POST['username']);
$nama  = bersih($_POST['nama_petugas']);
$level = bersih($_POST['level']);

if ($aksi == 'tambah') {
    $pass = md5($_POST['password']);   // password disimpan dalam bentuk MD5
    jalankan("INSERT INTO tb_petugas (id_petugas, username, password, nama_petugas, level)
              VALUES ('$id', '$user', '$pass', '$nama', '$level')", 'petugas.php');
}
if ($aksi == 'ubah') {
    // password hanya diganti kalau kolomnya diisi
    $sql_pass = ($_POST['password'] != '') ? ", password='" . md5($_POST['password']) . "'" : "";
    jalankan("UPDATE tb_petugas SET username='$user', nama_petugas='$nama', level='$level' $sql_pass WHERE id_petugas='$id'", 'petugas.php');
}
