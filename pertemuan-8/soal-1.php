<form method="post">
    Masukkan Jumlah Roda: <input type="number" name="roda" required>
    <button type="submit" name="cek_roda">Cek</button>
</form>

<?php
if (isset($_POST['cek_roda'])) {
    $jumlahRoda = $_POST['roda'];
    switch ($jumlahRoda) {
        case 2: 
            echo "Hasil: Sepeda Motor"; 
            break;
        case 3: 
            echo "Hasil: Bajaj/Becak"; 
            break;
        case 4: 
            echo "Hasil: Mobil"; 
            break;
        default: 
        echo "Hasil: Kendaraan tidak terdaftar"; 
        break;
    }
}
?>

