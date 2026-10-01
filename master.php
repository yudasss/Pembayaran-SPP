<?php
// master.php - CRUD (Tambah, Ubah, Hapus, Tampil) untuk 4 tabel master: kelas, siswa, petugas, spp.
// Satu kode dipakai bersama; yang membedakan hanya isi array $cfg di bawah.
require 'inti.php'; require 'layout.php'; wajib_login();

$cfg = [
  'kelas' => ['judul' => 'Data Kelas', 'tabel' => 'tb_kelas', 'pk' => 'id_kelas',
     'form' => ['id_kelas' => 'ID Kelas', 'nama_kelas' => 'Nama Kelas', 'komp_keahlian' => 'Kompetensi Keahlian'],
     'sql' => 'SELECT * FROM tb_kelas'],
  'siswa' => ['judul' => 'Data Siswa', 'tabel' => 'tb_siswa', 'pk' => 'nisn',
     'form' => ['nisn' => 'NISN', 'nis' => 'NIS', 'nama' => 'Nama', 'id_kelas' => 'ID Kelas', 'alamat' => 'Alamat', 'no_telp' => 'No. Telp', 'id_spp' => 'No. SPP'],
     'list' => ['nisn' => 'NISN', 'nis' => 'NIS', 'nama' => 'Nama', 'nama_kelas' => 'Kelas', 'komp_keahlian' => 'Keahlian', 'alamat' => 'Alamat', 'no_telp' => 'No. Telp', 'id_spp' => 'No. SPP'],
     'sql' => 'SELECT s.*, k.nama_kelas, k.komp_keahlian FROM tb_siswa s LEFT JOIN tb_kelas k ON k.id_kelas = s.id_kelas'],
  'petugas' => ['judul' => 'Data Petugas', 'tabel' => 'tb_petugas', 'pk' => 'id_petugas',
     'form' => ['id_petugas' => 'ID Petugas', 'username' => 'Username', 'password' => 'Password', 'nama_petugas' => 'Nama Petugas', 'level' => 'Level (admin/petugas/siswa)'],
     'list' => ['id_petugas' => 'ID Petugas', 'username' => 'Username', 'nama_petugas' => 'Nama Petugas', 'level' => 'Level'],
     'sql' => 'SELECT * FROM tb_petugas'],
  'spp' => ['judul' => 'Data SPP', 'tabel' => 'tb_spp', 'pk' => 'id_spp',
     'form' => ['id_spp' => 'ID SPP', 'tahun' => 'Tahun', 'nominal' => 'Nominal per bulan'],
     'sql' => 'SELECT * FROM tb_spp'],
];

$t = $_GET['t'] ?? 'kelas';
if (!isset($cfg[$t])) die('Tabel tidak dikenal');   // nama tabel hanya boleh dari daftar $cfg (whitelist)
$c = $cfg[$t]; $pk = $c['pk']; $pesan = '';

// HAPUS
if (isset($_GET['hapus'])) {
    try { $db->jalankan("DELETE FROM {$c['tabel']} WHERE $pk = ?", [$_GET['hapus']]); $pesan = 'Data dihapus'; }
    catch (PDOException $e) { $pesan = 'Gagal hapus: data masih dipakai tabel lain'; }
}

// SIMPAN: mode "tambah" -> INSERT, mode "ubah" -> UPDATE
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [];
    foreach ($c['form'] as $k => $label) {
        $v = $_POST[$k];
        if ($k == 'password') { if ($v === '') continue; $v = md5($v); }   // password kosong saat ubah = tidak diganti
        $data[$k] = $v;
    }
    try {
        if ($_POST['mode'] == 'ubah') {
            unset($data[$pk]);
            $set = implode(',', array_map(fn($k) => "$k = ?", array_keys($data)));
            $db->jalankan("UPDATE {$c['tabel']} SET $set WHERE $pk = ?", [...array_values($data), $_POST[$pk]]);
        } else {
            $kol = implode(',', array_keys($data));
            $tanya = implode(',', array_fill(0, count($data), '?'));
            $db->jalankan("INSERT INTO {$c['tabel']} ($kol) VALUES ($tanya)", array_values($data));
        }
        $pesan = 'Data tersimpan';
    } catch (PDOException $e) { $pesan = 'Gagal simpan: ' . $e->getMessage(); }
}

// Data yang akan diubah (jika klik tombol Ubah)
$edit = isset($_GET['ubah']) ? ($db->ambil("SELECT * FROM {$c['tabel']} WHERE $pk = ?", [$_GET['ubah']])[0] ?? []) : [];

awal($c['judul']);
if ($pesan) echo "<div class='alert alert-info'>" . h($pesan) . "</div>";

// FORM
echo '<form method="post" class="row g-2 mb-4"><input type="hidden" name="mode" value="' . ($edit ? 'ubah' : 'tambah') . '">';
foreach ($c['form'] as $k => $label) {
    $nilai = $k == 'password' ? '' : h($edit[$k] ?? '');
    $readonly = ($edit && $k == $pk) ? 'readonly' : '';                       // primary key tidak boleh diubah
    $wajib = ($k == 'password' && $edit) ? '' : 'required';
    echo "<div class='col-md-3'><input class='form-control' name='$k' value='$nilai' placeholder='$label' $readonly $wajib></div>";
}
echo '<div class="col-md-3"><button class="btn btn-primary">Simpan</button> <a class="btn btn-secondary" href="?t=' . $t . '">Batal</a></div></form>';

// TABEL
$list = $c['list'] ?? $c['form'];
echo '<table class="table table-bordered table-sm"><tr>';
foreach ($list as $label) echo "<th>$label</th>";
echo '<th>Aksi</th></tr>';
foreach ($db->ambil($c['sql']) as $r) {
    echo '<tr>';
    foreach ($list as $k => $label) echo '<td>' . h($r[$k]) . '</td>';
    $id = urlencode($r[$pk]);
    echo "<td><a class='btn btn-sm btn-warning' href='?t=$t&ubah=$id'>Ubah</a>
          <a class='btn btn-sm btn-danger' href='?t=$t&hapus=$id' onclick=\"return confirm('Hapus data ini?')\">Hapus</a></td></tr>";
}
echo '</table>';
akhir();
