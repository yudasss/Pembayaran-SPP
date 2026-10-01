<?php
// Data SPP - tampilan (HTML). Proses tambah/ubah/hapus ada di proses_spp.php (nominal SPP per bulan)
include 'koneksi.php';
$judul = 'Data SPP';

// Ambil data untuk form ubah
$edit = [];
if (isset($_GET['ubah'])) {
    $id = bersih($_GET['ubah']);
    $edit = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_spp WHERE id_spp='$id'")) ?? [];
}
include 'atas.php';
?>
<form method="post" action="proses_spp.php" class="row g-2 mb-4">
  <input type="hidden" name="aksi" value="<?= $edit ? 'ubah' : 'tambah' ?>">
  <div class="col-md-3"><input name="id_spp" class="form-control" placeholder="ID SPP" value="<?= h($edit['id_spp'] ?? '') ?>" <?= $edit ? 'readonly' : '' ?> required></div>
  <div class="col-md-3"><input name="tahun" type="number" class="form-control" placeholder="Tahun" value="<?= h($edit['tahun'] ?? '') ?>" required></div>
  <div class="col-md-3"><input name="nominal" type="number" class="form-control" placeholder="Nominal per bulan" value="<?= h($edit['nominal'] ?? '') ?>" required></div>
  <div class="col-md-3">
    <button class="btn btn-primary">Simpan</button>
    <a href="spp.php" class="btn btn-secondary">Batal</a>
  </div>
</form>

<table class="table table-bordered">
  <tr><th>ID SPP</th><th>Tahun</th><th>Nominal per Bulan</th><th>Aksi</th></tr>
  <?php
  $data = mysqli_query($koneksi, "SELECT * FROM tb_spp");
  while ($r = mysqli_fetch_assoc($data)) { ?>
    <tr>
      <td><?= h($r['id_spp']) ?></td>
      <td><?= h($r['tahun']) ?></td>
      <td>Rp <?= number_format($r['nominal']) ?></td>
      <td>
        <a href="?ubah=<?= urlencode($r['id_spp']) ?>" class="btn btn-sm btn-warning">Ubah</a>
        <a href="proses_spp.php?aksi=hapus&id=<?= urlencode($r['id_spp']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
      </td>
    </tr>
  <?php } ?>
</table>
<?php include 'bawah.php'; ?>
