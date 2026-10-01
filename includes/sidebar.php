<?php
$daftarMenu = [
    'dashboard' => ['index.php',              'Dashboard',         'bi-speedometer2'],
    'kelas'     => ['kelas.php',              'Data Kelas',        'bi-easel'],
    'siswa'     => ['siswa.php',              'Data Siswa',        'bi-people'],
    'cek'       => ['cek_pembayaran.php',     'Cek Pembayaran',    'bi-search'],
    'bayar'     => ['pembayaran.php',         'Pembayaran',        'bi-cash-coin'],
    'detail'    => ['detail_pembayaran.php',  'Detail Pembayaran', 'bi-receipt'],
    'petugas'   => ['petugas.php',            'Data Petugas',      'bi-person-badge'],
];
?>
<div class="sidebar">
    <div class="merek">
        <b>SPP Sekolah</b>
        <span>Menu Admin</span>
    </div>
    <?php foreach ($daftarMenu as $kode => $m): ?>
        <a href="<?= $m[0] ?>" class="<?= $menu === $kode ? 'aktif' : '' ?>">
            <i class="bi <?= $m[2] ?>"></i><?= $m[1] ?>
        </a>
    <?php endforeach; ?>
</div>
