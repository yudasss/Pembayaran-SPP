<?php
// proses_kelas.php - memproses Tambah / Ubah / Hapus data kelas, lalu kembali ke kelas.php
include 'koneksi.php';
$aksi = $_REQUEST['aksi'];   // tambah / ubah / hapus

if ($aksi == 'hapus') {
    $id = bersih($_GET['id']);
    jalankan("DELETE FROM tb_kelas WHERE id_kelas='$id'", 'kelas.php');
}

$id       = bersih($_POST['id_kelas']);
$nama     = bersih($_POST['nama_kelas']);
$keahlian = bersih($_POST['komp_keahlian']);

// Cek data kembar: ID kelas dan nama kelas tidak boleh sama dengan kelas lain
if ($aksi == 'tambah' && sudah_ada('tb_kelas', 'id_kelas', $id)) {
    kembali('kelas.php', 'ID Kelas sudah terdaftar', 'danger');
}
if (sudah_ada('tb_kelas', 'nama_kelas', $nama, 'id_kelas', $id)) {
    kembali('kelas.php', 'Nama kelas sudah dipakai kelas lain', 'danger');
}

if ($aksi == 'tambah') {
    jalankan("INSERT INTO tb_kelas (id_kelas, nama_kelas, komp_keahlian) VALUES ('$id', '$nama', '$keahlian')", 'kelas.php');
}
if ($aksi == 'ubah') {
    jalankan("UPDATE tb_kelas SET nama_kelas='$nama', komp_keahlian='$keahlian' WHERE id_kelas='$id'", 'kelas.php');
}
