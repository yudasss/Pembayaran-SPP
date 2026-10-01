<?php
// Data Petugas - tampilan (HTML). Proses tambah/ubah/hapus ada di proses_petugas.php
include 'koneksi.php';
$judul = 'Data Petugas';

// Ambil data untuk form ubah
$edit = [];
if (isset($_GET['ubah'])) {
    $id = bersih($_GET['ubah']);
    $edit = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_petugas WHERE id_petugas='$id'")) ?? [];
}
include 'atas.php';
?>
<form method="post" action="proses_petugas.php" class="row g-2 mb-4">
  <input type="hidden" name="aksi" value="<?= $edit ? 'ubah' : 'tambah' ?>">
  <div class="col-md-3"><input name="id_petugas" class="form-control" placeholder="ID Petugas" value="<?= h($edit['id_petugas'] ?? '') ?>" <?= $edit ? 'readonly' : '' ?> required></div>
  <div class="col-md-3"><input name="username" class="form-control" placeholder="Username" value="<?= h($edit['username'] ?? '') ?>" required></div>
  <div class="col-md-3"><input name="password" type="password" class="form-control" placeholder="<?= $edit ? 'Password (kosongkan jika tidak diganti)' : 'Password' ?>" <?= $edit ? '' : 'required' ?>></div>
  <div class="col-md-3"><input name="nama_petugas" class="form-control" placeholder="Nama Petugas" value="<?= h($edit['nama_petugas'] ?? '') ?>" required></div>
  <div class="col-md-3">
    <select name="level" class="form-select">
      <?php foreach (['admin', 'petugas', 'siswa'] as $l) { ?>
        <option <?= ($edit['level'] ?? '') == $l ? 'selected' : '' ?>><?= $l ?></option>
      <?php } ?>
    </select>
  </div>
  <div class="col-md-3">
    <button class="btn btn-primary">Simpan</button>
    <a href="petugas.php" class="btn btn-secondary">Batal</a>
  </div>
</form>

<table class="table table-bordered">
  <tr><th>ID Petugas</th><th>Username</th><th>Nama Petugas</th><th>Level</th><th>Aksi</th></tr>
  <?php
  $data = mysqli_query($koneksi, "SELECT * FROM tb_petugas");
  while ($r = mysqli_fetch_assoc($data)) { ?>
    <tr>
      <td><?= h($r['id_petugas']) ?></td>
      <td><?= h($r['username']) ?></td>
      <td><?= h($r['nama_petugas']) ?></td>
      <td><?= h($r['level']) ?></td>
      <td>
        <a href="?ubah=<?= urlencode($r['id_petugas']) ?>" class="btn btn-sm btn-warning">Ubah</a>
        <a href="proses_petugas.php?aksi=hapus&id=<?= urlencode($r['id_petugas']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
      </td>
    </tr>
  <?php } ?>
</table>
<?php include 'bawah.php'; ?>
