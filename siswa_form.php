<?php
require_once 'includes/fungsi.php';

$nisnLama = $_GET['nisn'] ?? '';
$data = null;
if ($nisnLama !== '') {
    $data = ambilSatu('SELECT * FROM tb_siswa WHERE nisn = ?', [$nisnLama]);
    if (!$data) {
        flash('Data siswa tidak ditemukan.', 'danger');
        redirect('siswa.php');
    }
}

$listKelas = ambil('SELECT * FROM tb_kelas ORDER BY nama_kelas');

$nisn = $data['nisn'] ?? '';
$nis = $data['nis'] ?? '';
$nama = $data['nama'] ?? '';
$idKelas = $data['id_kelas'] ?? '';
$alamat = $data['alamat'] ?? '';
$telp = $data['no_telp'] ?? '';
$idSpp = $data['id_spp'] ?? '';
$nominal = '';
if ($data) {
    $sppLama = ambilSatu('SELECT nominal FROM tb_spp WHERE id_spp = ?', [$data['id_spp']]);
    $nominal = $sppLama ? $sppLama['nominal'] : '';
}
$galat = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn = $data ? $data['nisn'] : trim($_POST['nisn'] ?? '');
    $nis = trim($_POST['nis'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $idKelas = $_POST['id_kelas'] ?? '';
    $alamat = trim($_POST['alamat'] ?? '');
    $telp = trim($_POST['no_telp'] ?? '');
    $idSpp = trim($_POST['id_spp'] ?? '');
    $nominal = trim($_POST['nominal'] ?? '');

    if (!$data) {
        if (!ctype_digit($nisn) || strlen($nisn) > 10) {
            $galat[] = 'NISN harus berupa angka, maksimal 10 digit.';
        } elseif (ambilSatu('SELECT nisn FROM tb_siswa WHERE nisn = ?', [$nisn])) {
            $galat[] = 'NISN sudah terdaftar.';
        }
    }
    if (!ctype_digit($nis) || strlen($nis) > 8) {
        $galat[] = 'NIS harus berupa angka, maksimal 8 digit.';
    }
    if ($nama === '' || mb_strlen($nama) > 50) {
        $galat[] = 'Nama harus diisi, maksimal 50 karakter.';
    }
    if (!ambilSatu('SELECT id_kelas FROM tb_kelas WHERE id_kelas = ?', [$idKelas])) {
        $galat[] = 'Pilih kelas terlebih dahulu.';
    }
    if ($alamat === '') {
        $galat[] = 'Alamat harus diisi.';
    }
    if (!ctype_digit($telp) || strlen($telp) > 13) {
        $galat[] = 'No. telp harus berupa angka, maksimal 13 digit.';
    }
    $sppAda = null;
    if ($idSpp === '' || mb_strlen($idSpp) > 11) {
        $galat[] = 'No. SPP harus diisi, maksimal 11 karakter.';
    } else {
        $sppAda = ambilSatu('SELECT id_spp FROM tb_spp WHERE id_spp = ?', [$idSpp]);
        if (!$sppAda && (!ctype_digit($nominal) || (int) $nominal < 1)) {
            $galat[] = 'No. SPP ini belum terdaftar. Isi nominal SPP per bulan (angka).';
        }
    }

    if (!$galat) {
        // No. SPP baru otomatis dibuat di tb_spp supaya nominalnya bisa dipakai saat pembayaran
        if (!$sppAda) {
            jalankan('INSERT INTO tb_spp (id_spp, tahun, nominal) VALUES (?, ?, ?)', [$idSpp, date('Y'), $nominal]);
        }
        if ($data) {
            jalankan('UPDATE tb_siswa SET nis = ?, nama = ?, id_kelas = ?, alamat = ?, no_telp = ?, id_spp = ? WHERE nisn = ?',
                [$nis, $nama, $idKelas, $alamat, $telp, $idSpp, $nisn]);
            flash('Data siswa berhasil diubah.');
        } else {
            jalankan('INSERT INTO tb_siswa (nisn, nis, nama, id_kelas, alamat, no_telp, id_spp) VALUES (?, ?, ?, ?, ?, ?, ?)',
                [$nisn, $nis, $nama, $idKelas, $alamat, $telp, $idSpp]);
            flash('Data siswa berhasil ditambahkan.');
        }
        redirect('siswa.php');
    }
}

$judul = $data ? 'Edit Siswa' : 'Tambah Siswa';
$menu = 'siswa';
include 'includes/header.php';
?>
<?php foreach ($galat as $g): ?><div class="alert alert-danger py-2"><?= e($g) ?></div><?php endforeach; ?>
<?php if (!$listKelas): ?><div class="alert alert-warning py-2">Data kelas masih kosong. Tambahkan kelas dulu di menu Data Kelas.</div><?php endif; ?>

<div class="card form-kecil"><div class="card-body">
    <form method="post">
        <label class="form-label">NISN</label>
        <input type="text" name="nisn" class="form-control form-control-sm" maxlength="10" value="<?= e($nisn) ?>" <?= $data ? 'readonly' : 'required' ?>>

        <label class="form-label mt-2">NIS</label>
        <input type="text" name="nis" class="form-control form-control-sm" maxlength="8" value="<?= e($nis) ?>" required>

        <label class="form-label mt-2">Nama</label>
        <input type="text" name="nama" class="form-control form-control-sm" maxlength="50" value="<?= e($nama) ?>" required>

        <label class="form-label mt-2">Kelas</label>
        <select name="id_kelas" class="form-select form-select-sm" required>
            <option value="">-- pilih kelas --</option>
            <?php foreach ($listKelas as $k): ?>
                <option value="<?= e($k['id_kelas']) ?>" <?= $idKelas === $k['id_kelas'] ? 'selected' : '' ?>>
                    <?= e($k['nama_kelas']) ?> - <?= e($k['komp_keahlian']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label class="form-label mt-2">Alamat</label>
        <textarea name="alamat" rows="2" class="form-control form-control-sm" required><?= e($alamat) ?></textarea>

        <label class="form-label mt-2">No. Telp</label>
        <input type="text" name="no_telp" class="form-control form-control-sm" maxlength="13" value="<?= e($telp) ?>" required>

        <label class="form-label mt-2">No. SPP</label>
        <input type="text" name="id_spp" class="form-control form-control-sm" maxlength="11" value="<?= e($idSpp) ?>" placeholder="contoh: SPP2026" required>

        <label class="form-label mt-2">Nominal SPP per bulan</label>
        <input type="number" name="nominal" class="form-control form-control-sm" min="0" value="<?= e($nominal) ?>" placeholder="contoh: 250000">
        <div class="form-text">Isi hanya kalau No. SPP baru. Kalau No. SPP sudah ada, nominal yang lama tetap dipakai.</div>

        <div class="mt-3">
            <button class="btn btn-secondary">Simpan</button>
            <a href="siswa.php" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div></div>
<?php include 'includes/footer.php'; ?>
