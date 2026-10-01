<?php
require_once 'includes/fungsi.php';

$id = $_GET['id'] ?? '';
$data = null;
if ($id !== '') {
    $data = ambilSatu('SELECT * FROM tb_petugas WHERE id_petugas = ?', [$id]);
    if (!$data) {
        flash('Data petugas tidak ditemukan.', 'danger');
        redirect('petugas.php');
    }
}

$username = $data['username'] ?? '';
$nama = $data['nama_petugas'] ?? '';
$level = $data['level'] ?? 'petugas';
$galat = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $nama = trim($_POST['nama_petugas'] ?? '');
    $level = $_POST['level'] ?? '';

    if ($username === '' || mb_strlen($username) > 25) {
        $galat[] = 'Username harus diisi, maksimal 25 karakter.';
    } else {
        $sama = ambilSatu('SELECT id_petugas FROM tb_petugas WHERE username = ? AND id_petugas <> ?', [$username, $id]);
        if ($sama) $galat[] = 'Username sudah dipakai petugas lain.';
    }
    if (!$data && strlen($password) < 4) {
        $galat[] = 'Password minimal 4 karakter.';
    }
    if ($data && $password !== '' && strlen($password) < 4) {
        $galat[] = 'Password baru minimal 4 karakter.';
    }
    if ($nama === '' || mb_strlen($nama) > 35) {
        $galat[] = 'Nama petugas harus diisi, maksimal 35 karakter.';
    }
    if (!in_array($level, ['admin', 'petugas', 'siswa'], true)) {
        $galat[] = 'Pilih level terlebih dahulu.';
    }

    if (!$galat) {
        if ($data) {
            if ($password !== '') {
                jalankan('UPDATE tb_petugas SET username = ?, password = ?, nama_petugas = ?, level = ? WHERE id_petugas = ?',
                    [$username, md5($password), $nama, $level, $id]);
            } else {
                jalankan('UPDATE tb_petugas SET username = ?, nama_petugas = ?, level = ? WHERE id_petugas = ?',
                    [$username, $nama, $level, $id]);
            }
            flash('Data petugas berhasil diubah.');
        } else {
            $idBaru = nextId('tb_petugas', 'id_petugas', 'P', 3);
            jalankan('INSERT INTO tb_petugas (id_petugas, username, password, nama_petugas, level) VALUES (?, ?, ?, ?, ?)',
                [$idBaru, $username, md5($password), $nama, $level]);
            flash('Data petugas berhasil ditambahkan.');
        }
        redirect('petugas.php');
    }
}

$judul = $data ? 'Edit Petugas' : 'Tambah Petugas';
$menu = 'petugas';
include 'includes/header.php';
?>
<?php foreach ($galat as $g): ?><div class="alert alert-danger py-2"><?= e($g) ?></div><?php endforeach; ?>

<div class="card form-kecil"><div class="card-body">
    <form method="post">
        <?php if ($data): ?>
            <label class="form-label">ID Petugas</label>
            <input type="text" class="form-control form-control-sm" value="<?= e($data['id_petugas']) ?>" readonly>
        <?php endif; ?>

        <label class="form-label mt-2">Username</label>
        <input type="text" name="username" class="form-control form-control-sm" maxlength="25" value="<?= e($username) ?>" required>

        <label class="form-label mt-2">Password</label>
        <input type="password" name="password" class="form-control form-control-sm" <?= $data ? '' : 'required' ?>>
        <?php if ($data): ?><div class="form-text">Kosongkan kalau password tidak diganti.</div><?php endif; ?>

        <label class="form-label mt-2">Nama Petugas</label>
        <input type="text" name="nama_petugas" class="form-control form-control-sm" maxlength="35" value="<?= e($nama) ?>" required>

        <label class="form-label mt-2">Level</label>
        <select name="level" class="form-select form-select-sm">
            <?php foreach (['admin', 'petugas', 'siswa'] as $l): ?>
                <option value="<?= $l ?>" <?= $level === $l ? 'selected' : '' ?>><?= $l ?></option>
            <?php endforeach; ?>
        </select>

        <div class="mt-3">
            <button class="btn btn-secondary">Simpan</button>
            <a href="petugas.php" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div></div>
<?php include 'includes/footer.php'; ?>
