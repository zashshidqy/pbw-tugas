<body>
    <h3>Tugas 6.1 - Latihan Nilai</h3>
    
    <form method="post" action="">
        <table border="0">
            <tr>
                <td>Nama Mahasiswa</td>
                <td>: <input type="text" name="nama" required></td>
            </tr>
            <tr>
                <td>Nilai Ujian</td>
                <td>: <input type="number" name="nilai" required></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="proses" value="Proses"></td>
            </tr>
        </table>
    </form>

    <?php
    if (isset($_POST['proses'])) {
        $nama = $_POST['nama'];
        $nilai = $_POST['nilai'];

        if ($nilai >= 85) {
            $predikat = "A";
        } elseif ($nilai >= 75) {
            $predikat = "B";
        } elseif ($nilai >= 65) {
            $predikat = "C";
        } elseif ($nilai >= 50) {
            $predikat = "D";
        } else {
            $predikat = "E";
        }

        if ($nilai >= 65) {
            $status = "Lulus";
        } else {
            $status = "Tidak Lulus";
        }

        echo "<br>--------------------------------<br>";
        echo "Nama : $nama <br>";
        echo "Nilai : $nilai <br>";
        echo "Predikat : $predikat <br>";
        echo "Status : $status";
    }
    ?>
</body>
</html>