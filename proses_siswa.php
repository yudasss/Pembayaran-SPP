<?php
// proses_siswa.php - memproses Tambah / Ubah / Hapus data siswa, lalu kembali ke siswa.php
include 'koneksi.php';
$aksi = $_REQUEST['aksi'];   // tambah / ubah / hapus

if ($aksi == 'hapus') {
    $nisn = bersih($_GET['id']);
    jalankan("DELETE FROM tb_siswa WHERE nisn='$nisn'", 'siswa.php');
}

$nisn   = bersih($_POST['nisn']);
$nis    = bersih($_POST['nis']);
$nama   = bersih($_POST['nama']);
$kelas  = bersih($_POST['id_kelas']);
$alamat = bersih($_POST['alamat']);
$telp   = bersih($_POST['no_telp']);
$spp    = bersih($_POST['id_spp']);

// Cek data kembar: NISN dan NIS tidak boleh sama dengan siswa lain
if ($aksi == 'tambah' && sudah_ada('tb_siswa', 'nisn', $nisn)) {
    kembali('siswa.php', 'NISN sudah terdaftar', 'danger');
}
if (sudah_ada('tb_siswa', 'nis', $nis, 'nisn', $nisn)) {
    kembali('siswa.php', 'NIS sudah dipakai siswa lain', 'danger');
}

if ($aksi == 'tambah') {
    jalankan("INSERT INTO tb_siswa (nisn, nis, nama, id_kelas, alamat, no_telp, id_spp)
              VALUES ('$nisn', '$nis', '$nama', '$kelas', '$alamat', '$telp', '$spp')", 'siswa.php');
}
if ($aksi == 'ubah') {
    jalankan("UPDATE tb_siswa SET nis='$nis', nama='$nama', id_kelas='$kelas', alamat='$alamat', no_telp='$telp', id_spp='$spp'
              WHERE nisn='$nisn'", 'siswa.php');
}
