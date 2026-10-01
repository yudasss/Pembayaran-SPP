<?php
// Pembayaran - tampilan form (HTML). Prosesnya ada di proses_pembayaran.php
include 'koneksi.php';
$judul = 'Pembayaran';
include 'atas.php';
?>
<form method="post" action="proses_pembayaran.php" class="card card-body" style="max-width:420px">
  <input name="nisn" class="form-control mb-2" placeholder="NISN" required>
  <input name="jumlah_bulan" type="number" min="1" class="form-control mb-2" placeholder="Jumlah bulan dibayar" required>
  <input name="nominal_bayar" type="number" class="form-control mb-2" placeholder="Uang yang diterima (Rp)" required>
  <select name="id_petugas" class="form-select mb-2">
    <option value="">-- Pilih Petugas --</option>
    <?php
    $data = mysqli_query($koneksi, "SELECT * FROM tb_petugas");
    while ($r = mysqli_fetch_assoc($data)) { ?>
      <option value="<?= h($r['id_petugas']) ?>"><?= h($r['nama_petugas']) ?></option>
    <?php } ?>
  </select>
  <button class="btn btn-primary">Bayar</button>
</form>
<?php include 'bawah.php'; ?>
