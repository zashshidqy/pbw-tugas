<?php
$data_barang = [
    "Monitor"  => 1500000,
    "Keyboard" => 500000,
    "Mouse"    => 300000,
    "Printer"  => 1200000
];

echo "<h3>Daftar Barang & Harga:</h3>";
foreach ($data_barang as $nama => $harga) {
    echo "- $nama: Rp " . number_format($harga, 0, ',', '.') . "<br>";
}

echo "<h3>Form Pembelian</h3>";
echo "<form method='post'>
    Pilih Barang: 
    <select name='barang_pilihan'>";
    foreach ($data_barang as $nama => $harga) {
        echo "<option value='$nama'>$nama</option>";
    }
echo "</select><br><br>
    Jumlah Beli: <input type='number' name='jumlah_beli' required><br><br>
    <input type='submit' name='tombol_hitung' value='Hitung Sekarang'>
</form>";

if (isset($_POST['tombol_hitung'])) {
    $pilihan = $_POST['barang_pilihan'];
    $jumlah  = $_POST['jumlah_beli'];

    $harga_satuan = $data_barang[$pilihan];
    $subtotal    = $harga_satuan * $jumlah;
    $diskon      = 0.1 * $subtotal; 
    $total_bayar = $subtotal - $diskon;

    echo "<hr>";
    echo "<h3>Hasil Transaksi:</h3>";
    echo "Barang terpilih: <b>$pilihan</b> <br>";
    echo "Harga Satuan: Rp " . number_format($harga_satuan, 0, ',', '.') . "<br>";
    echo "Jumlah Beli: $jumlah <br>";
    echo "Subtotal: Rp " . number_format($subtotal, 0, ',', '.') . "<br>";
    echo "Potongan Diskon (10%): Rp " . number_format($diskon, 0, ',', '.') . "<br>";
    echo "<b>Total Bayar: Rp " . number_format($total_bayar, 0, ',', '.') . "</b>";
}
?>