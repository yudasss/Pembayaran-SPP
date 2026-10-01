<?php
// layout.php - kerangka halaman: navbar di kiri + Bootstrap (dari CDN)

function awal($judul) {
    $menu = ['index.php' => 'Dashboard', 'master.php?t=kelas' => 'Data Kelas', 'master.php?t=siswa' => 'Data Siswa',
        'cek_pembayaran.php' => 'Cek Pembayaran', 'pembayaran.php' => 'Pembayaran',
        'detail_pembayaran.php' => 'Detail Pembayaran', 'master.php?t=petugas' => 'Data Petugas', 'master.php?t=spp' => 'Data SPP'];
    echo '<!doctype html><html><head><meta charset="utf-8"><title>' . $judul . '</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>@media print{.no-print{display:none}}</style></head>
    <body><div class="d-flex"><div class="bg-dark p-3 min-vh-100 no-print" style="width:230px">
    <h5 class="text-white">Menu SPP</h5><div class="nav flex-column">';
    foreach ($menu as $url => $nama) echo "<a class='nav-link text-white' href='$url'>$nama</a>";
    echo '<a class="nav-link text-warning" href="logout.php">Logout</a></div></div>
    <div class="flex-fill p-4"><h3 class="mb-3">' . $judul . '</h3>';
}

/** Menutup halaman. */
function akhir() { echo '</div></div></body></html>'; }
