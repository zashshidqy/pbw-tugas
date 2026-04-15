<form method="post">
    Masukkkan Angka: <input type="number" name="angka_input" required>
    <button type="submit" name="cek_angka">Cek Ganjil/Genap</button>
</form>

<?php
if (isset($_POST['cek_angka'])) {
    $angka = $_POST['angka_input'];
    echo ($angka % 2 == 0) ? "Hasil: $angka adalah Genap" : "Hasil: $angka adalah Ganjil";
}
?>