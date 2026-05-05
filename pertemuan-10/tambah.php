<?php
require_once 'koneksi.php';

$pesan = '';
$tipe  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim      = trim($_POST['nim'] ?? '');
    $nama     = trim($_POST['nama'] ?? '');
    $jurusan  = trim($_POST['jurusan'] ?? '');
    $angkatan = intval($_POST['angkatan'] ?? 0);
    $ipk      = floatval($_POST['ipk'] ?? 0);

    if (empty($nim) || empty($nama) || empty($jurusan) || $angkatan < 2000 || $ipk < 0) {
        $pesan = 'Semua field wajib diisi dengan benar!';
        $tipe  = 'danger';
    } else {
        $stmt = $koneksi->prepare(
            "INSERT INTO mahasiswa (nim, nama, jurusan, angkatan, ipk) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('sssid', $nim, $nama, $jurusan, $angkatan, $ipk);

        if ($stmt->execute()) {
            $pesan = "Data mahasiswa <strong class='text-dark'>{$nama}</strong> berhasil ditambahkan!";
            $tipe  = 'success';
        } else {
            if ($koneksi->errno === 1062) {
                $pesan = "NIM <strong class='text-dark'>{$nim}</strong> sudah terdaftar. Gunakan NIM lain.";
            } else {
                $pesan = 'Gagal menambahkan data: ' . $stmt->error;
            }
            $tipe = 'danger';
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
    <title>Tambah Mahasiswa - UNSIKA</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fa;
            color: #2b3440;
        }
        .main-card {
            border-radius: 1.5rem;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.03);
            background: #ffffff;
            overflow: hidden;
        }
        .form-label {
            font-weight: 600;
            color: #475569;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .form-control, .form-select {
            border-radius: 0.75rem;
            border: 1px solid #cbd5e1;
            padding: 0.75rem 1rem;
            font-weight: 500;
            color: #334155;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }
        .btn-add {
            border-radius: 50rem;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            transition: all 0.3s ease;
        }
        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }
        .btn-outline-custom {
            border-radius: 50rem;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            border: 1px solid #cbd5e1;
            color: #475569;
            background: #ffffff;
            transition: all 0.3s ease;
        }
        .btn-outline-custom:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #94a3b8;
        }
        .btn-outline-primary-custom {
            border-radius: 50rem;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            border: 1px solid #3b82f6;
            color: #3b82f6;
            background: #ffffff;
            transition: all 0.3s ease;
        }
        .btn-outline-primary-custom:hover {
            background: #3b82f6;
            color: #ffffff;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-4 d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 56px; height: 56px;">
                    <i class="bi bi-person-plus-fill fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1 text-dark">Tambah Data Mahasiswa</h3>
                    <p class="text-muted mb-0 fw-medium small">Lengkapi formulir di bawah ini untuk mendaftarkan mahasiswa ke dalam sistem.</p>
                </div>
            </div>

            <?php if ($pesan): ?>
            <div class="alert alert-<?= $tipe ?> alert-dismissible fade show border-0 shadow-sm rounded-4 d-flex align-items-center" role="alert">
                <i class="bi bi-<?= $tipe === 'success' ? 'check-circle' : 'exclamation-triangle' ?>-fill fs-4 me-3"></i>
                <div class="fw-medium"><?= $pesan ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="card main-card">
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="tambah.php" novalidate>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Nomor Induk Mahasiswa (NIM) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-4" style="border-color: #cbd5e1;">
                                        <i class="bi bi-credit-card-2-front text-muted"></i>
                                    </span>
                                    <input type="text" name="nim" class="form-control border-start-0 ps-0 rounded-end-4" placeholder="Contoh: 2024001001"
                                           value="<?= htmlspecialchars($_POST['nim'] ?? '') ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-4" style="border-color: #cbd5e1;">
                                        <i class="bi bi-person text-muted"></i>
                                    </span>
                                    <input type="text" name="nama" class="form-control border-start-0 ps-0 rounded-end-4" placeholder="Masukkan nama lengkap"
                                           value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label">Program Studi / Jurusan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-4" style="border-color: #cbd5e1;">
                                        <i class="bi bi-book text-muted"></i>
                                    </span>
                                    <select name="jurusan" class="form-select border-start-0 ps-0 rounded-end-4" required>
                                        <option value="" disabled <?= empty($_POST['jurusan']) ? 'selected' : '' ?>>Pilih program studi...</option>
                                        <?php
                                        $jurusanList = ['Teknik Informatika', 'Sistem Informasi', 'Teknik Elektro', 'Manajemen', 'Akuntansi'];
                                        foreach ($jurusanList as $j):
                                            $selected = (($_POST['jurusan'] ?? '') === $j) ? 'selected' : '';
                                        ?>
                                        <option value="<?= $j ?>" <?= $selected ?>><?= $j ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Tahun Angkatan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-4" style="border-color: #cbd5e1;">
                                        <i class="bi bi-calendar-event text-muted"></i>
                                    </span>
                                    <input type="number" name="angkatan" class="form-control border-start-0 ps-0 rounded-end-4" placeholder="Contoh: 2024"
                                           min="2000" max="<?= date('Y') ?>"
                                           value="<?= htmlspecialchars($_POST['angkatan'] ?? '') ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Indeks Prestasi Kumulatif (IPK) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-4" style="border-color: #cbd5e1;">
                                        <i class="bi bi-award text-muted"></i>
                                    </span>
                                    <input type="number" name="ipk" class="form-control border-start-0 ps-0 rounded-end-4" placeholder="Contoh: 3.75"
                                           min="0" max="4" step="0.01"
                                           value="<?= htmlspecialchars($_POST['ipk'] ?? '') ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-12 mt-5">
                                <div class="d-flex flex-wrap gap-3 align-items-center bg-light bg-opacity-50 p-3 rounded-4 border border-light-subtle">
                                    <button type="submit" class="btn btn-add text-white">
                                        <i class="bi bi-cloud-arrow-up-fill me-2"></i>Simpan Data
                                    </button>
                                    <a href="index.php" class="btn btn-outline-custom">
                                        <i class="bi bi-arrow-left me-2"></i>Kembali
                                    </a>
                                    <?php if ($tipe === 'success'): ?>
                                    <a href="tambah.php" class="btn btn-outline-primary-custom ms-md-auto">
                                        <i class="bi bi-plus-lg me-2"></i>Tambah Baru
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <p class="text-center text-muted mt-4 mb-0 fw-medium" style="font-size: 0.85rem;">
                <i class="bi bi-shield-check me-1 text-success"></i>
                Pastikan data yang dimasukkan sudah valid sebelum menyimpan.
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>