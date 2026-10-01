<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function bulanLalu($n)
{
    return date('Y-m-d', strtotime("-$n month", strtotime(date('Y-m-10'))));
}

if (!isset($_SESSION['kelas'])) {
    $_SESSION['kelas'] = [
        ['id' => 'K001', 'nama' => 'X RPL 1',   'keahlian' => 'Rekayasa Perangkat Lunak'],
        ['id' => 'K002', 'nama' => 'XI TKJ 2',  'keahlian' => 'Teknik Komputer dan Jaringan'],
        ['id' => 'K003', 'nama' => 'XII AKL 1', 'keahlian' => 'Akuntansi dan Keuangan Lembaga'],
    ];

    $_SESSION['spp'] = [
        ['id' => 'SPP2026', 'tahun' => 2026, 'nominal' => 250000],
    ];

    $_SESSION['siswa'] = [
        ['nisn' => '0051234561', 'nis' => '10231', 'nama' => 'Ahmad Fauzi',     'kelas' => 'K001', 'alamat' => 'Jl. Melati No. 4',     'telp' => '081211110001', 'spp' => 'SPP2026', 'terakhir' => bulanLalu(0)],
        ['nisn' => '0051234562', 'nis' => '10232', 'nama' => 'Siti Rahmawati',  'kelas' => 'K001', 'alamat' => 'Jl. Kenanga No. 12',   'telp' => '081211110002', 'spp' => 'SPP2026', 'terakhir' => bulanLalu(0)],
        ['nisn' => '0051234563', 'nis' => '10233', 'nama' => 'Dimas Prasetyo',  'kelas' => 'K002', 'alamat' => 'Jl. Mawar No. 7',      'telp' => '081211110003', 'spp' => 'SPP2026', 'terakhir' => bulanLalu(2)],
        ['nisn' => '0051234564', 'nis' => '10234', 'nama' => 'Nur Aisyah',      'kelas' => 'K002', 'alamat' => 'Perum Griya Asri B/3', 'telp' => '081211110004', 'spp' => 'SPP2026', 'terakhir' => bulanLalu(0)],
        ['nisn' => '0051234565', 'nis' => '10235', 'nama' => 'Rizki Ramadhan',  'kelas' => 'K003', 'alamat' => 'Jl. Anggrek No. 21',   'telp' => '081211110005', 'spp' => 'SPP2026', 'terakhir' => bulanLalu(1)],
        ['nisn' => '0051234566', 'nis' => '10236', 'nama' => 'Putri Wulandari', 'kelas' => 'K003', 'alamat' => 'Jl. Cempaka No. 9',    'telp' => '081211110006', 'spp' => 'SPP2026', 'terakhir' => bulanLalu(3)],
    ];

    $_SESSION['petugas'] = [
        ['id' => 'P001', 'username' => 'admin', 'nama' => 'Budi Santoso', 'level' => 'admin'],
        ['id' => 'P002', 'username' => 'sinta', 'nama' => 'Sinta Dewi',   'level' => 'petugas'],
    ];

    $_SESSION['pembayaran'] = [
        ['id' => 'PB001', 'tgl' => bulanLalu(1), 'nisn' => '0051234561', 'bulan' => 1, 'nominal' => 250000, 'dibayar' => 250000, 'kembali' => 0,     'petugas' => 'P001', 'status' => 'Sudah Lunas'],
        ['id' => 'PB002', 'tgl' => bulanLalu(0), 'nisn' => '0051234562', 'bulan' => 2, 'nominal' => 500000, 'dibayar' => 500000, 'kembali' => 0,     'petugas' => 'P002', 'status' => 'Sudah Lunas'],
        ['id' => 'PB003', 'tgl' => bulanLalu(2), 'nisn' => '0051234563', 'bulan' => 1, 'nominal' => 250000, 'dibayar' => 300000, 'kembali' => 50000, 'petugas' => 'P001', 'status' => 'Belum Lunas'],
    ];
}
