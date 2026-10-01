<?php
// Data Siswa - tampilan (HTML). Proses tambah/ubah/hapus ada di proses_siswa.php
include 'koneksi.php';
$judul = 'Data Siswa';

// Ambil data untuk form ubah
$edit = [];
if (isset($_GET['ubah'])) {
    $id = bersih($_GET['ubah']);
    $edit = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_siswa WHERE nisn='$id'")) ?? [];
}
include 'atas.php';
?>
<form method="post" action="proses_siswa.php" class="row g-2 mb-4">
  <input type="hidden" name="aksi" value="<?= $edit ? 'ubah' : 'tambah' ?>">
  <div class="col-md-3"><input name="nisn" class="form-control" placeholder="NISN" value="<?= h($edit['nisn'] ?? '') ?>" <?= $edit ? 'readonly' : '' ?> required></div>
  <div class="col-md-3"><input name="nis" class="form-control" placeholder="NIS" value="<?= h($edit['nis'] ?? '') ?>" required></div>
  <div class="col-md-3"><input name="nama" class="form-control" placeholder="Nama" value="<?= h($edit['nama'] ?? '') ?>" required></div>
  <div class="col-md-3">
    <select name="id_kelas" class="form-select" required>
      <option value="">-- Pilih Kelas --</option>
      <?php $kelas = mysqli_query($koneksi, "SELECT * FROM tb_kelas");
      while ($k = mysqli_fetch_assoc($kelas)) { ?>
        <option value="<?= h($k['id_kelas']) ?>" <?= $k['id_kelas'] == ($edit['id_kelas'] ?? '') ? 'selected' : '' ?>><?= h($k['nama_kelas']) ?></option>
      <?php } ?>
    </select>
  </div>
  <div class="col-md-3"><input name="alamat" class="form-control" placeholder="Alamat" value="<?= h($edit['alamat'] ?? '') ?>" required></div>
  <div class="col-md-3"><input name="no_telp" class="form-control" placeholder="No. Telp" value="<?= h($edit['no_telp'] ?? '') ?>" required></div>
  <div class="col-md-3">
    <select name="id_spp" class="form-select" required>
      <option value="">-- Pilih SPP (tahun) --</option>
      <?php $spp = mysqli_query($koneksi, "SELECT * FROM tb_spp");
      while ($p = mysqli_fetch_assoc($spp)) { ?>
        <option value="<?= h($p['id_spp']) ?>" <?= $p['id_spp'] == ($edit['id_spp'] ?? '') ? 'selected' : '' ?>><?= h($p['tahun']) ?></option>
      <?php } ?>
    </select>
  </div>
  <div class="col-md-3">
    <button class="btn btn-primary">Simpan</button>
    <a href="siswa.php" class="btn btn-secondary">Batal</a>
  </div>
</form>

<table class="table table-bordered table-sm">
  <tr><th>NISN</th><th>NIS</th><th>Nama</th><th>Kelas</th><th>Keahlian</th><th>Alamat</th><th>No. Telp</th><th>No. SPP</th><th>Aksi</th></tr>
  <?php
  $data = mysqli_query($koneksi, "SELECT s.*, k.nama_kelas, k.komp_keahlian
                                  FROM tb_siswa s LEFT JOIN tb_kelas k ON k.id_kelas = s.id_kelas");
  while ($r = mysqli_fetch_assoc($data)) { ?>
    <tr>
      <td><?= h($r['nisn']) ?></td>
      <td><?= h($r['nis']) ?></td>
      <td><?= h($r['nama']) ?></td>
      <td><?= h($r['nama_kelas']) ?></td>
      <td><?= h($r['komp_keahlian']) ?></td>
      <td><?= h($r['alamat']) ?></td>
      <td><?= h($r['no_telp']) ?></td>
      <td><?= h($r['id_spp']) ?></td>
      <td>
        <a href="?ubah=<?= urlencode($r['nisn']) ?>" class="btn btn-sm btn-warning">Ubah</a>
        <a href="proses_siswa.php?aksi=hapus&id=<?= urlencode($r['nisn']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</a>
      </td>
    </tr>
  <?php } ?>
</table>
<?php include 'bawah.php'; ?>
