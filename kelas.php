<?php
// Data Kelas - tampilan (HTML). Proses tambah/ubah/hapus ada di proses_kelas.php
include 'koneksi.php';
$judul = 'Data Kelas';

// Jika tombol Ubah diklik, ambil datanya untuk diisi ke form
$edit = [];
if (isset($_GET['ubah'])) {
    $id = bersih($_GET['ubah']);
    $edit = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_kelas WHERE id_kelas='$id'")) ?? [];
}
include 'atas.php';
?>
<form method="post" action="proses_kelas.php" class="row g-2 mb-4">
  <input type="hidden" name="aksi" value="<?= $edit ? 'ubah' : 'tambah' ?>">
  <div class="col-md-3"><input name="id_kelas" class="form-control" placeholder="ID Kelas" value="<?= h($edit['id_kelas'] ?? '') ?>" <?= $edit ? 'readonly' : '' ?> required></div>
  <div class="col-md-3"><input name="nama_kelas" class="form-control" placeholder="Nama Kelas" value="<?= h($edit['nama_kelas'] ?? '') ?>" required></div>
  <div class="col-md-3"><input name="komp_keahlian" class="form-control" placeholder="Kompetensi Keahlian" value="<?= h($edit['komp_keahlian'] ?? '') ?>" required></div>
  <div class="col-md-3">
    <button class="btn btn-primary">Simpan</button>
    <a href="kelas.php" class="btn btn-secondary">Batal</a>
  </div>
</form>

<table class="table table-bordered">
  <tr><th>ID Kelas</th><th>Nama Kelas</th><th>Kompetensi Keahlian</th><th>Aksi</th></tr>
  <?php
  $data = mysqli_query($koneksi, "SELECT * FROM tb_kelas");
  while ($r = mysqli_fetch_assoc($data)) { ?>
    <tr>
      <td><?= h($r['id_kelas']) ?></td>
      <td><?= h($r['nama_kelas']) ?></td>
      <td><?= h($r['komp_keahlian']) ?></td>
      <td>
        <a href="?ubah=<?= urlencode($r['id_kelas']) ?>" class="btn btn-sm btn-warning">Ubah</a>
        <a href="proses_kelas.php?aksi=hapus&id=<?= urlencode($r['id_kelas']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
      </td>
    </tr>
  <?php } ?>
</table>
<?php include 'bawah.php'; ?>
