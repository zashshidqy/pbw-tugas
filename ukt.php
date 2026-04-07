<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tugas 6.2 - Pembayaran UKT</title>
</head>
<body>
    <h3>Diskon UKT</h3>
    
    <form method="post" action="">
        <table border="0">
            <tr>
                <td>NPM</td>
                <td>: <input type="text" name="npm" required></td>
            </tr>
            <tr>
                <td>Nama Mahasiswa</td>
                <td>: <input type="text" name="nama" required></td>
            </tr>
            <tr>    
                <td>Program Studi</td>
                <td>: <input type="text" name="prodi" required></td>
            </tr>
            <tr>
                <td>Semester</td>
                <td>: <input type="number" name="semester" required></td>
            </tr>
            <tr>
                <td>Biaya UKT (Rp)</td>
                <td>: <input type="number" name="biaya_ukt" required></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="hitung" value="Hitung Pembayaran"></td>
            </tr>
        </table>
    </form>

    <?php
    if (isset($_POST['hitung'])) {
        $npm = $_POST['npm'];
        $nama = $_POST['nama'];
        $prodi = $_POST['prodi'];
        $semester = $_POST['semester'];
        $biaya_ukt = $_POST['biaya_ukt'];

        $diskon = 0;

        if ($biaya_ukt >= 5000000) {
            if ($semester > 8) {
                $diskon = 15;
            } else {
                $diskon = 10; 
            }
        }

        $nilai_diskon = ($diskon / 100) * $biaya_ukt;
        $total_bayar = $biaya_ukt - $nilai_diskon;

        echo "<br>--------------------------------<br>";
        echo "NPM : $npm <br>";
        echo "NAMA : $nama <br>";
        echo "PRODI : $prodi <br>";
        echo "SEMESTER : $semester <br>";
        echo "BIAYA UKT : Rp " . number_format($biaya_ukt, 0, ',', '.') . " <br>";
        echo "DISKON : $diskon % <br>";
        echo "<b>YANG HARUS DIBAYAR : Rp " . number_format($total_bayar, 0, ',', '.') . "</b>";
    }
    ?>
</body>
</html>