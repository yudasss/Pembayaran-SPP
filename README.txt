CARA MENJALANKAN (XAMPP)
1. Folder "spp" ke C:\xampp\htdocs\spp. Start Apache + MySQL.
2. phpMyAdmin: import pembayaran_spp.sql (semua tabel KOSONG).
3. Buka http://localhost/spp/  (langsung dashboard, tanpa login)
4. Isi data lewat menu, urutan: Data Kelas > Data SPP > Data Petugas > Data Siswa > Pembayaran.
5. Pengujian unit: http://localhost/spp/test_unit.php (pakai data sementara, otomatis dihapus lagi)

PENJELASAN SINGKAT UNTUK ASESOR
- koneksi.php        : koneksi database (mysqli) + fungsi bantu (bersih, h, jalankan, hitung_tagihan, tentukan_status, status_siswa).
- atas.php / bawah.php : kerangka halaman, navbar kiri, Bootstrap dari CDN (library pre-existing).
- kelas / siswa / spp / petugas.php : TAMPILAN master data (HTML: form + tabel).
- proses_kelas / siswa / spp / petugas / pembayaran.php : PROSES tambah, ubah, hapus, lalu kembali ke halaman tampilan.
- pembayaran.php     : tagihan = nominal x bulan; uang kurang ditolak; kembalian = uang - tagihan.
- cek_pembayaran.php : Lunas jika tgl_terakhir_bayar >= hari ini.
- detail_pembayaran.php : laporan transaksi.
- test_unit.php      : 13 skenario uji (hitung, tambah/ubah/hapus, cek data kembar, laporan).
