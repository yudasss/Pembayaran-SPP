<?php
require_once 'includes/fungsi.php';

$id = $_GET['id'] ?? '';
$data = null;
if ($id !== '') {
    $data = ambilSatu('SELECT * FROM tb_kelas WHERE id_kelas = ?', [$id]);
    if (!$data) {
        flash('Data kelas tidak ditemukan.', 'danger');
        redirect('kelas.php');
    }
}

$nama = $data['nama_kelas'] ?? '';
$keahlian = $data['komp_keahlian'] ?? '';
$galat = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_kelas'] ?? '');
    $keahlian = trim($_POST['komp_keahlian'] ?? '');

    if ($nama === '') {
        $galat[] = 'Nama kelas harus diisi.';
    } elseif (mb_strlen($nama) > 10) {
        $galat[] = 'Nama kelas maksimal 10 karakter.';
    }
    if ($keahlian === '') {
        $galat[] = 'Kompetensi keahlian harus diisi.';
    } elseif (mb_strlen($keahlian) > 50) {
        $galat[] = 'Kompetensi keahlian maksimal 50 karakter.';
    }

    if (!$galat) {
        if ($data) {
            jalankan('UPDATE tb_kelas SET nama_kelas = ?, komp_keahlian = ? WHERE id_kelas = ?', [$nama, $keahlian, $id]);
            flash('Data kelas berhasil diubah.');
        } else {
            $idBaru = nextId('tb_kelas', 'id_kelas', 'K', 3);
            jalankan('INSERT INTO tb_kelas (id_kelas, nama_kelas, komp_keahlian) VALUES (?, ?, ?)', [$idBaru, $nama, $keahlian]);
            flash('Data kelas berhasil ditambahkan.');
        }
        redirect('kelas.php');
    }
}

$judul = $data ? 'Edit Kelas' : 'Tambah Kelas';
$menu = 'kelas';
include 'includes/header.php';
?>
<?php foreach ($galat as $g): ?><div class="alert alert-danger py-2"><?= e($g) ?></div><?php endforeach; ?>

<div class="card form-kecil"><div class="card-body">
    <form method="post">
        <?php if ($data): ?>
            <label class="form-label">ID Kelas</label>
            <input type="text" class="form-control form-control-sm" value="<?= e($data['id_kelas']) ?>" readonly>
        <?php endif; ?>

        <label class="form-label mt-2">Nama Kelas</label>
        <input type="text" name="nama_kelas" class="form-control form-control-sm" maxlength="10" value="<?= e($nama) ?>" placeholder="contoh: X RPL 1" required>

        <label class="form-label mt-2">Kompetensi Keahlian</label>
        <input type="text" name="komp_keahlian" class="form-control form-control-sm" maxlength="50" value="<?= e($keahlian) ?>" placeholder="contoh: Rekayasa Perangkat Lunak" required>

        <div class="mt-3">
            <button class="btn btn-secondary">Simpan</button>
            <a href="kelas.php" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div></div>
<?php include 'includes/footer.php'; ?>
