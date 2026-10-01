<?php
// atas.php - bagian atas semua halaman: Bootstrap + navbar kiri. Isi $judul sebelum include file ini.
$menu = ['index.php' => 'Dashboard', 'kelas.php' => 'Data Kelas', 'siswa.php' => 'Data Siswa',
    'cek_pembayaran.php' => 'Cek Pembayaran', 'pembayaran.php' => 'Pembayaran',
    'detail_pembayaran.php' => 'Detail Pembayaran', 'petugas.php' => 'Data Petugas', 'spp.php' => 'Data SPP'];
$pesan = $_GET['pesan'] ?? '';
$jenis = $_GET['jenis'] ?? 'info';
$halaman = basename($_SERVER['PHP_SELF']);   // nama file yang sedang dibuka, untuk menandai menu aktif
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= $judul ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="d-flex">
  <div class="bg-dark p-3 min-vh-100" style="width:250px">
    <h5 class="text-white px-2 mb-3">Aplikasi SPP</h5>
    <ul class="nav nav-pills flex-column gap-1">
      <?php foreach ($menu as $file => $nama) { ?>
        <li class="nav-item">
          <a class="nav-link text-white <?= $file == $halaman ? 'active' : '' ?>" href="<?= $file ?>"><?= $nama ?></a>
        </li>
      <?php } ?>
    </ul>
  </div>
  <div class="flex-fill p-4">
    <h3 class="mb-4"><?= $judul ?></h3>
    <?php if ($pesan != '') { ?>
      <div class="alert alert-<?= h($jenis) ?>"><?= h($pesan) ?></div>
    <?php } ?>
