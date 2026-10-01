<?php
// test_unit.php - pengujian unit sederhana. Buka: http://localhost/spp/test_unit.php
include 'koneksi.php';
$hasil = [];

// Membandingkan hasil asli dengan yang diharapkan, lalu mencatat PASS / FAIL
function uji($nama, $asli, $harapan) {
    global $hasil;
    $status = ($asli === $harapan) ? 'PASS' : 'FAIL';
    $hasil[] = [$nama, var_export($harapan, true), var_export($asli, true), $status];
}

// Modul Pembayaran (fungsi hitung)
uji('Tagihan 3 bulan x 150000', hitung_tagihan(150000, 3), 450000);
uji('Tanggal belum lewat = Sudah Lunas', tentukan_status('2026-12-01', '2026-10-01'), 'Sudah Lunas');
uji('Tanggal sudah lewat = Belum Lunas', tentukan_status('2026-08-01', '2026-10-01'), 'Belum Lunas');
uji('Belum pernah bayar = Belum Lunas', tentukan_status(null), 'Belum Lunas');

// Modul Master Data (Tambah, Ubah, Hapus) memakai data kelas sementara
mysqli_query($koneksi, "DELETE FROM tb_kelas WHERE id_kelas='TEST'");
mysqli_query($koneksi, "INSERT INTO tb_kelas VALUES ('TEST', 'X TEST', 'RPL')");
uji('Tambah kelas', mysqli_affected_rows($koneksi), 1);
uji('Cek ID kelas kembar terdeteksi', sudah_ada('tb_kelas', 'id_kelas', 'TEST'), true);
uji('Cek ID kelas baru tidak kembar', sudah_ada('tb_kelas', 'id_kelas', 'TIDAKADA'), false);
uji('Ubah diri sendiri tidak dianggap kembar', sudah_ada('tb_kelas', 'nama_kelas', 'X TEST', 'id_kelas', 'TEST'), false);
mysqli_query($koneksi, "UPDATE tb_kelas SET nama_kelas='XI TEST' WHERE id_kelas='TEST'");
uji('Ubah kelas', mysqli_affected_rows($koneksi), 1);
$baris = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_kelas FROM tb_kelas WHERE id_kelas='TEST'"));
uji('Data kelas berubah', $baris['nama_kelas'], 'XI TEST');
mysqli_query($koneksi, "DELETE FROM tb_kelas WHERE id_kelas='TEST'");
uji('Hapus kelas', mysqli_affected_rows($koneksi), 1);

// Modul Laporan
$q = mysqli_query($koneksi, "SELECT p.*, s.nama FROM tb_pembayaran p JOIN tb_siswa s ON s.nisn = p.nisn");
uji('Query laporan berjalan', $q !== false, true);
$q = mysqli_query($koneksi, "SELECT nisn FROM tb_siswa");
uji('Dashboard: semua siswa terhitung', count(status_siswa()), mysqli_num_rows($q));
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"><title>Unit Test</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<h3>Hasil Pengujian Unit</h3>
<table class="table table-bordered">
  <tr><th>Skenario</th><th>Diharapkan</th><th>Hasil</th><th>Status</th></tr>
  <?php foreach ($hasil as $h) { ?>
    <tr class="<?= $h[3] == 'PASS' ? 'table-success' : 'table-danger' ?>">
      <td><?= $h[0] ?></td><td><?= $h[1] ?></td><td><?= $h[2] ?></td><td><?= $h[3] ?></td>
    </tr>
  <?php } ?>
</table>
</body>
</html>
