<?php
require_once __DIR__ . '/fungsi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($judul) ?> - Pembayaran SPP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/sidebar.php'; ?>
<div class="isi">
    <div class="topbar">
        <span>Aplikasi Pembayaran SPP Sekolah</span>
        <span><?= date('d/m/Y') ?></span>
    </div>
    <h4 class="judul-halaman"><?= e($judul) ?></h4>
    <?php tampilFlash(); ?>
