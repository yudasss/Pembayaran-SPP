<?php
// koneksi.php - koneksi ke database + fungsi bantu yang dipakai banyak halaman
mysqli_report(MYSQLI_REPORT_OFF);   // supaya error query tidak membuat halaman crash
$koneksi = mysqli_connect('localhost', 'root', '', 'pembayaran_spp');
if (!$koneksi) { die('Koneksi gagal: ' . mysqli_connect_error()); }

// Membersihkan input form sebelum masuk query (anti SQL injection)
function bersih($data) {
    global $koneksi;
    return mysqli_real_escape_string($koneksi, trim($data));
}

// Mengamankan teks sebelum ditampilkan di HTML (anti XSS)
function h($teks) { return htmlspecialchars((string)$teks); }

// Pindah ke halaman lain sambil membawa pesan (ditampilkan atas.php sebagai kotak alert)
function kembali($halaman, $pesan, $jenis) {
    header('Location: ' . $halaman . '?pesan=' . urlencode($pesan) . '&jenis=' . $jenis);
    exit;
}

// Menjalankan INSERT/UPDATE/DELETE, lalu kembali ke halaman asal dengan pesan berhasil / gagal
function jalankan($sql, $halaman) {
    global $koneksi;
    if (mysqli_query($koneksi, $sql)) { kembali($halaman, 'Proses berhasil', 'success'); }
    kembali($halaman, 'Gagal: ' . mysqli_error($koneksi), 'danger');
}

// Cek apakah nilai sudah ada di tabel (untuk mencegah data kembar).
// Saat ubah data, isi $kunci dan $kecuali supaya baris itu sendiri tidak dianggap kembar.
function sudah_ada($tabel, $kolom, $nilai, $kunci = '', $kecuali = '') {
    global $koneksi;
    $sql = "SELECT 1 FROM $tabel WHERE $kolom='$nilai'";
    if ($kunci != '') { $sql .= " AND $kunci != '$kecuali'"; }
    return mysqli_num_rows(mysqli_query($koneksi, $sql)) > 0;
}

// Total tagihan = nominal SPP per bulan x jumlah bulan
function hitung_tagihan($nominal, $bulan) { return $nominal * $bulan; }

// Lunas jika tanggal terakhir bayar belum lewat dari hari ini. Belum pernah bayar = Belum Lunas
function tentukan_status($tgl_akhir, $hari_ini = '') {
    if ($hari_ini == '') { $hari_ini = date('Y-m-d'); }
    if ($tgl_akhir != '' && $tgl_akhir >= $hari_ini) { return 'Sudah Lunas'; }
    return 'Belum Lunas';
}

// Daftar semua siswa + status + jumlah bulan tunggakan (dipakai Dashboard dan Cek Pembayaran)
function status_siswa() {
    global $koneksi;
    $hasil = [];
    $q = mysqli_query($koneksi, "SELECT s.nisn, s.nama, s.no_telp, MAX(p.tgl_terakhir_bayar) AS terakhir
        FROM tb_siswa s LEFT JOIN tb_pembayaran p ON p.nisn = s.nisn
        GROUP BY s.nisn, s.nama, s.no_telp");
    while ($r = mysqli_fetch_assoc($q)) {
        $r['status'] = tentukan_status($r['terakhir']);
        $r['bulan'] = '-';                                   // '-' = belum pernah bayar
        if ($r['terakhir'] != '') {
            $t = strtotime($r['terakhir']);
            $selisih = (date('Y') - date('Y', $t)) * 12 + (date('n') - date('n', $t));
            $r['bulan'] = ($r['status'] == 'Sudah Lunas') ? 0 : $selisih;
        }
        $hasil[] = $r;
    }
    return $hasil;
}
