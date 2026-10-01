-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Okt 2026 pada 13.22
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pembayaran_spp`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cek_pembayaran`
--

CREATE TABLE `cek_pembayaran` (
  `nisn` varchar(10) NOT NULL,
  `tgl_terakhir_bayar` date NOT NULL,
  `tgl_sekarang` date NOT NULL,
  `status_pembayaran` enum('Belum Lunas','Sudah Lunas') NOT NULL,
  `jumlah_bulan` varchar(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `no_telp` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_kelas`
--

CREATE TABLE `tb_kelas` (
  `id_kelas` varchar(11) NOT NULL,
  `nama_kelas` varchar(10) NOT NULL,
  `komp_keahlian` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_kelas`
--

INSERT INTO `tb_kelas` (`id_kelas`, `nama_kelas`, `komp_keahlian`) VALUES
('K001', 'X RPL 1', 'Rekayasa Perangkat Lunak'),
('K002', 'XI RPL 2', 'Rekayasa Perangkat Lunak'),
('K003', 'XII RPL 3', 'Rekayasa Perangkat Lunak'),
('K004', 'X RPL 2', 'Rekayasa Perangkat Lunak');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pembayaran`
--

CREATE TABLE `tb_pembayaran` (
  `id_pembayaran` varchar(11) NOT NULL,
  `id_petugas` varchar(11) DEFAULT NULL,
  `status` enum('Sudah Lunas','Belum Lunas') NOT NULL,
  `nisn` varchar(10) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `tgl_terakhir_bayar` date NOT NULL,
  `batas_pembayaran` date NOT NULL,
  `jumlah_bulan` varchar(10) NOT NULL,
  `id_spp` varchar(40) NOT NULL,
  `nominal_bayar` int(11) DEFAULT NULL,
  `jumlah_bayar` int(11) DEFAULT NULL,
  `kembalian` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_pembayaran`
--

INSERT INTO `tb_pembayaran` (`id_pembayaran`, `id_petugas`, `status`, `nisn`, `tgl_bayar`, `tgl_terakhir_bayar`, `batas_pembayaran`, `jumlah_bulan`, `id_spp`, `nominal_bayar`, `jumlah_bayar`, `kembalian`) VALUES
('PB00001', 'P001', 'Sudah Lunas', '0051234564', '2026-10-01', '2026-10-01', '2026-11-10', '1', 'SPP123', 350000, 350000, 0),
('PB00002', 'P001', 'Sudah Lunas', '0051234565', '2026-10-01', '2026-11-01', '2026-11-01', '1', 'SPP234', 450000, 450000, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_petugas`
--

CREATE TABLE `tb_petugas` (
  `id_petugas` varchar(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(32) NOT NULL,
  `nama_petugas` varchar(35) NOT NULL,
  `level` enum('admin','petugas','siswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_petugas`
--

INSERT INTO `tb_petugas` (`id_petugas`, `username`, `password`, `nama_petugas`, `level`) VALUES
('P001', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Rojali', 'admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_siswa`
--

CREATE TABLE `tb_siswa` (
  `nisn` varchar(10) NOT NULL,
  `nis` varchar(8) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `id_kelas` varchar(11) NOT NULL,
  `alamat` text NOT NULL,
  `no_telp` varchar(13) NOT NULL,
  `id_spp` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_siswa`
--

INSERT INTO `tb_siswa` (`nisn`, `nis`, `nama`, `id_kelas`, `alamat`, `no_telp`, `id_spp`) VALUES
('0051234564', '3423', 'Adbul Kholiks', 'K001', 'Kp. Banjar Baru', '08572385811', 'SPP123'),
('0051234565', '3424', 'Yuda Prima', 'K003', 'Muncang', '085723858112', 'SPP234'),
('0051234566', '3425', 'Nur Aisyah', 'K002', 'Kelapa Gading', '081234556778', 'SPP123'),
('0051234567', '3426', 'Arralia Juana', 'K004', 'Kp. Sangereng', '085824526782', 'SPP123');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_spp`
--

CREATE TABLE `tb_spp` (
  `id_spp` varchar(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `nominal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_spp`
--

INSERT INTO `tb_spp` (`id_spp`, `tahun`, `nominal`) VALUES
('SPP123', 2026, 350000),
('SPP234', 2026, 450000);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indeks untuk tabel `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indeks untuk tabel `tb_petugas`
--
ALTER TABLE `tb_petugas`
  ADD PRIMARY KEY (`id_petugas`);

--
-- Indeks untuk tabel `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD PRIMARY KEY (`nisn`);

--
-- Indeks untuk tabel `tb_spp`
--
ALTER TABLE `tb_spp`
  ADD PRIMARY KEY (`id_spp`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
