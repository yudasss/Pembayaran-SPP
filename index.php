<?php
// Dashboard: jumlah siswa sudah lunas dan belum lunas
include 'koneksi.php';
$judul = 'Dashboard';
include 'atas.php';

$lunas = 0;
$belum = 0;
foreach (status_siswa() as $s) {
    if ($s['status'] == 'Sudah Lunas') { $lunas++; } else { $belum++; }
}
?>
<!-- Satu kotak bergaris tepi bulat, diletakkan di tengah halaman -->
<div class="d-flex justify-content-center mt-5">
  <div class="card border-dark border-2 rounded-4 bg-white" style="width:520px">
    <div class="card-body">
      <div class="row text-center">
        <div class="col-6">
          <h6 class="fw-bold">Siswa Yang Sudah Lunas</h6>
          <p class="mb-0">Total : <b><?= $lunas ?> Siswa</b></p>
        </div>
        <div class="col-6">
          <h6 class="fw-bold">Siswa Yang Belum Lunas</h6>
          <p class="mb-0">Total : <b><?= $belum ?> Siswa</b></p>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include 'bawah.php'; ?>
