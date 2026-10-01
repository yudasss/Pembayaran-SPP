<?php
// Cek Pembayaran: cari NISN lewat nama, cek status lewat NISN, daftar siswa lunas dan belum lunas
include 'koneksi.php';
$judul = 'Cek Pembayaran';
include 'atas.php';

$nama = $_GET['nama'] ?? '';
$nisn = $_GET['nisn'] ?? '';

$hasil = []; $lunas = []; $belum = [];
foreach (status_siswa() as $s) {
    if ($nisn != '' && $s['nisn'] == $nisn) { $hasil[] = $s; }
    if ($s['status'] == 'Sudah Lunas') { $lunas[] = $s; } else { $belum[] = $s; }
}

$ketemu = [];
if ($nama != '') {
    $q = mysqli_query($koneksi, "SELECT nisn, nama FROM tb_siswa WHERE nama LIKE '%" . bersih($nama) . "%'");
    while ($r = mysqli_fetch_assoc($q)) { $ketemu[] = $r; }
}

function tabel($data) { ?>
  <table class="table table-bordered table-sm">
    <tr><th>NISN</th><th>Nama</th><th>No. Telp</th><th>Terakhir Bayar</th><th>Status</th><th>Tunggakan (bulan)</th></tr>
    <?php foreach ($data as $r) { ?>
      <tr>
        <td><?= h($r['nisn']) ?></td>
        <td><?= h($r['nama']) ?></td>
        <td><?= h($r['no_telp']) ?></td>
        <td><?= h($r['terakhir']) ?></td>
        <td><?= $r['status'] ?></td>
        <td><?= $r['bulan'] ?></td>
      </tr>
    <?php } ?>
  </table>
<?php } ?>

<div class="row">
  <div class="col-md-6">
    <form class="mb-3">
      <label class="form-label">Cari NISN dengan memasukkan nama</label>
      <div class="input-group">
        <input name="nama" class="form-control" value="<?= h($nama) ?>">
        <button class="btn btn-secondary">Cari</button>
      </div>
    </form>
    <?php foreach ($ketemu as $r) { ?>
      <div><?= h($r['nama']) ?> - NISN: <b><?= h($r['nisn']) ?></b></div>
    <?php } ?>

    <form class="mt-3">
      <label class="form-label">Cek pembayaran menggunakan NISN</label>
      <div class="input-group">
        <input name="nisn" class="form-control" value="<?= h($nisn) ?>">
        <button class="btn btn-primary">Cek Pembayaran</button>
      </div>
    </form>
  </div>
  <div class="col-md-6">
    <h6>Data Hasil Pencarian</h6>
    <?php tabel($hasil); ?>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-6">
    <h6><?= count($lunas) ?> Siswa yang Sudah Lunas</h6>
    <?php tabel($lunas); ?>
  </div>
  <div class="col-md-6">
    <h6><?= count($belum) ?> Siswa yang Belum Lunas</h6>
    <?php tabel($belum); ?>
  </div>
</div>
<?php include 'bawah.php'; ?>
