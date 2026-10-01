<?php
// Detail Pembayaran = laporan semua transaksi
include 'koneksi.php';
$judul = 'Detail Pembayaran';
include 'atas.php';

$data = mysqli_query($koneksi, "SELECT p.*, s.nama, pt.nama_petugas
    FROM tb_pembayaran p
    JOIN tb_siswa s ON s.nisn = p.nisn
    LEFT JOIN tb_petugas pt ON pt.id_petugas = p.id_petugas
    ORDER BY p.tgl_bayar DESC, p.id_pembayaran DESC");
$total_masuk = 0;
?>
<table class="table table-bordered table-sm">
  <tr><th>ID</th><th>Tgl Bayar</th><th>NISN</th><th>Nama</th><th>Bulan</th><th>Total</th><th>Dibayar</th><th>Kembalian</th><th>Lunas Sampai</th><th>Status</th><th>Petugas</th></tr>
  <?php while ($r = mysqli_fetch_assoc($data)) {
      $total_masuk += $r['jumlah_bayar']; ?>
    <tr>
      <td><?= h($r['id_pembayaran']) ?></td>
      <td><?= $r['tgl_bayar'] ?></td>
      <td><?= h($r['nisn']) ?></td>
      <td><?= h($r['nama']) ?></td>
      <td><?= h($r['jumlah_bulan']) ?></td>
      <td><?= number_format($r['jumlah_bayar']) ?></td>
      <td><?= number_format($r['nominal_bayar']) ?></td>
      <td><?= number_format($r['kembalian']) ?></td>
      <td><?= $r['tgl_terakhir_bayar'] ?></td>
      <td><?= $r['status'] ?></td>
      <td><?= h($r['nama_petugas']) ?></td>
    </tr>
  <?php } ?>
  <tr><th colspan="5">Total Pemasukan</th><th colspan="6">Rp <?= number_format($total_masuk) ?></th></tr>
</table>
<?php include 'bawah.php'; ?>
