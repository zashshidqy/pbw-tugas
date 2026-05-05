<?php
// edit.php - UPDATE data mahasiswa menggunakan Prepared Statements
require_once 'koneksi.php';

$id    = intval($_GET['id'] ?? 0);
$pesan = '';
$tipe  = '';

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Ambil data mahasiswa berdasarkan ID (Prepared Statement)
$stmt = $koneksi->prepare("SELECT * FROM mahasiswa WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$mhs    = $result->fetch_assoc();
$stmt->close();

if (!$mhs) {
    header('Location: index.php?pesan=notfound&tipe=warning');
    exit;
}

// Proses UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim      = trim($_POST['nim'] ?? '');
    $nama     = trim($_POST['nama'] ?? '');
    $jurusan  = trim($_POST['jurusan'] ?? '');
    $angkatan = intval($_POST['angkatan'] ?? 0);
    $ipk      = floatval($_POST['ipk'] ?? 0);

    if (empty($nim) || empty($nama) || empty($jurusan) || $angkatan < 2000) {
        $pesan = 'Semua field wajib diisi!';
        $tipe  = 'danger';
    } else {
        // Prepared Statement untuk UPDATE
        $stmt = $koneksi->prepare(
            "UPDATE mahasiswa SET nim=?, nama=?, jurusan=?, angkatan=?, ipk=? WHERE id=?"
        );
        $stmt->bind_param('sssidi', $nim, $nama, $jurusan, $angkatan, $ipk, $id);

        if ($stmt->execute()) {
            $pesan = "Data mahasiswa <strong>{$nama}</strong> berhasil diperbarui!";
            $tipe  = 'success';
            // Refresh data
            $mhs['nim']      = $nim;
            $mhs['nama']     = $nama;
            $mhs['jurusan']  = $jurusan;
            $mhs['angkatan'] = $angkatan;
            $mhs['ipk']      = $ipk;
        } else {
            $pesan = 'Gagal memperbarui data: ' . $stmt->error;
            $tipe  = 'danger';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container py-4">
    <div class="page-header mb-4">
        <h2><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Mahasiswa</h2>
        <p class="text-muted mb-0">Perbarui data mahasiswa: <strong><?= htmlspecialchars($mhs['nama']) ?></strong></p>
    </div>

    <?php if ($pesan): ?>
    <div class="alert alert-<?= $tipe ?> alert-dismissible fade show" role="alert">
        <i class="bi bi-<?= $tipe === 'success' ? 'check-circle' : 'exclamation-triangle' ?>-fill me-2"></i>
        <?= $pesan ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="edit.php?id=<?= $id ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                        <input type="text" name="nim" class="form-control"
                               value="<?= htmlspecialchars($mhs['nim']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control"
                               value="<?= htmlspecialchars($mhs['nama']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jurusan <span class="text-danger">*</span></label>
                        <select name="jurusan" class="form-select" required>
                            <option value="">-- Pilih Jurusan --</option>
                            <?php
                            $jurusanList = ['Teknik Informatika', 'Sistem Informasi', 'Teknik Elektro', 'Manajemen', 'Akuntansi'];
                            foreach ($jurusanList as $j):
                                $selected = ($mhs['jurusan'] === $j) ? 'selected' : '';
                            ?>
                            <option value="<?= $j ?>" <?= $selected ?>><?= $j ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Angkatan <span class="text-danger">*</span></label>
                        <input type="number" name="angkatan" class="form-control"
                               min="2000" max="<?= date('Y') ?>"
                               value="<?= htmlspecialchars($mhs['angkatan']) ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">IPK <span class="text-danger">*</span></label>
                        <input type="number" name="ipk" class="form-control"
                               min="0" max="4" step="0.01"
                               value="<?= htmlspecialchars($mhs['ipk']) ?>" required>
                    </div>
                    <div class="col-12 pt-2">
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="bi bi-save me-2"></i>Update Data
                        </button>
                        <a href="index.php" class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
