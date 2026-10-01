// Hitung total bayar dan kembalian otomatis di halaman Pembayaran
var pilihSiswa = document.getElementById('nisn');
var inputBulan = document.getElementById('bulan');
var inputUang = document.getElementById('uang');
var tampilNominal = document.getElementById('nominal');
var tampilTotal = document.getElementById('total');
var tampilKembali = document.getElementById('kembalian');

function rupiah(angka) {
    return 'Rp ' + Number(angka).toLocaleString('id-ID');
}

function hitung() {
    var opsi = pilihSiswa.options[pilihSiswa.selectedIndex];
    var nominal = opsi ? Number(opsi.dataset.nominal) || 0 : 0;
    var bulan = Number(inputBulan.value) || 0;
    var uang = Number(inputUang.value) || 0;
    var total = nominal * bulan;

    tampilNominal.value = rupiah(nominal);
    tampilTotal.value = rupiah(total);
    tampilKembali.value = uang >= total ? rupiah(uang - total) : '-';
}

pilihSiswa.addEventListener('change', hitung);
inputBulan.addEventListener('input', hitung);
inputUang.addEventListener('input', hitung);
hitung();
