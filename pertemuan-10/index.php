<?php
require_once 'koneksi.php';

$per_halaman = 5;
$halaman     = max(1, intval($_GET['page'] ?? 1));
$offset      = ($halaman - 1) * $per_halaman;

$cari     = trim($_GET['cari'] ?? '');
$cariLike = "%{$cari}%";

if ($cari !== '') {
    $stmt_count = $koneksi->prepare("SELECT COUNT(*) FROM mahasiswa WHERE nim LIKE ? OR nama LIKE ? OR jurusan LIKE ?");
    $stmt_count->bind_param('sss', $cariLike, $cariLike, $cariLike);
} else {
    $stmt_count = $koneksi->prepare("SELECT COUNT(*) FROM mahasiswa");
}
$stmt_count->execute();
$stmt_count->bind_result($total_data);
$stmt_count->fetch();
$stmt_count->close();

$total_halaman = max(1, ceil($total_data / $per_halaman));
$halaman       = min($halaman, $total_halaman);
$offset        = ($halaman - 1) * $per_halaman;

if ($cari !== '') {
    $stmt = $koneksi->prepare("SELECT * FROM mahasiswa WHERE nim LIKE ? OR nama LIKE ? OR jurusan LIKE ? ORDER BY angkatan DESC, nama ASC LIMIT ? OFFSET ?");
    $stmt->bind_param('sssii', $cariLike, $cariLike, $cariLike, $per_halaman, $offset);
} else {
    $stmt = $koneksi->prepare("SELECT * FROM mahasiswa ORDER BY angkatan DESC, nama ASC LIMIT ? OFFSET ?");
    $stmt->bind_param('ii', $per_halaman, $offset);
}
$stmt->execute();
$result    = $stmt->get_result();
$mahasiswa = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$stmt_stat = $koneksi->prepare("SELECT COUNT(*) as total, ROUND(AVG(ipk),2) as avg_ipk, COUNT(DISTINCT jurusan) as jml_jurusan, COUNT(DISTINCT angkatan) as jml_angkatan FROM mahasiswa");
$stmt_stat->execute();
$stat = $stmt_stat->get_result()->fetch_assoc();
$stmt_stat->close();

$flash_pesan = urldecode($_GET['pesan'] ?? '');
$flash_tipe  = $_GET['tipe'] ?? '';

function ipkBadge(float $ipk): string {
    if ($ipk >= 3.5) return 'success';
    if ($ipk >= 3.0) return 'primary';
    if ($ipk >= 2.5) return 'warning';
    return 'danger';
}

function pageUrl(int $p, string $cari): string {
    $q = http_build_query(['page' => $p, 'cari' => $cari]);
    return "index.php?{$q}";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa - UNSIKA</title>
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
        .stat-card {
            border-radius: 1.25rem;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #ffffff;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
        }
        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .main-card {
            border-radius: 1.5rem;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.03);
            background: #ffffff;
            overflow: hidden;
        }
        .table-custom thead th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 1.25rem 1rem;
            border-bottom: 2px solid #e2e8f0;
        }
        .table-custom tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-weight: 500;
        }
        .table-custom tbody tr:hover td {
            background-color: #f8fafc;
        }
        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }
        .search-pill {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 50rem;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        .search-pill:focus-within {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }
        .search-pill input {
            border: none;
            box-shadow: none;
            background: transparent;
            padding-left: 1rem;
        }
        .search-pill input:focus {
            box-shadow: none;
            background: transparent;
        }
        .search-pill .btn {
            border-radius: 50rem;
            padding: 0.4rem 1.25rem;
            font-weight: 600;
        }
        .btn-add {
            border-radius: 50rem;
            padding: 0.5rem 1.5rem;
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
        .action-btn {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            transform: scale(1.05);
        }
        .badge-custom {
            padding: 0.5em 0.8em;
            font-weight: 600;
            border-radius: 0.5rem;
        }
        .pagination .page-link {
            border-radius: 0.5rem;
            margin: 0 0.15rem;
            border: none;
            color: #475569;
            font-weight: 500;
        }
        .pagination .page-item.active .page-link {
            background-color: #3b82f6;
            color: white;
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-5">

    <div class="row g-4 mb-5">
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="fs-3 fw-bold text-dark mb-0 lh-1"><?= $stat['total'] ?></div>
                        <div class="text-muted small fw-medium mt-1">Total Mahasiswa</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div>
                        <div class="fs-3 fw-bold text-dark mb-0 lh-1"><?= number_format($stat['avg_ipk'], 2) ?></div>
                        <div class="text-muted small fw-medium mt-1">Rata-rata IPK</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <div class="fs-3 fw-bold text-dark mb-0 lh-1"><?= $stat['jml_jurusan'] ?></div>
                        <div class="text-muted small fw-medium mt-1">Total Jurusan</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div>
                        <div class="fs-3 fw-bold text-dark mb-0 lh-1"><?= $stat['jml_angkatan'] ?></div>
                        <div class="text-muted small fw-medium mt-1">Angkatan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($flash_pesan && $flash_tipe): ?>
    <div class="alert alert-<?= htmlspecialchars($flash_tipe) ?> alert-dismissible fade show border-0 shadow-sm rounded-4 d-flex align-items-center" role="alert">
        <i class="bi bi-<?= $flash_tipe === 'success' ? 'check-circle' : ($flash_tipe === 'warning' ? 'exclamation-triangle' : 'x-circle') ?>-fill fs-4 me-3"></i>
        <div class="fw-medium"><?= $flash_pesan ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="card main-card">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-3 px-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <h4 class="mb-0 fw-bold text-dark d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-3 d-inline-flex">
                        <i class="bi bi-table"></i>
                    </div>
                    Data Mahasiswa
                    <?php if ($cari): ?>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary ms-3 fs-6 rounded-pill fw-medium">Hasil: <?= $total_data ?> data</span>
                    <?php endif; ?>
                </h4>
                
                <div class="d-flex flex-wrap gap-3">
                    <form method="GET" action="index.php" class="search-pill">
                        <i class="bi bi-search text-muted ms-2"></i>
                        <input type="text" name="cari" class="form-control" style="width:240px"
                               placeholder="Cari NIM, nama, jurusan..."
                               value="<?= htmlspecialchars($cari) ?>">
                        <button type="submit" class="btn btn-primary text-white">Cari</button>
                        <?php if ($cari): ?>
                        <a href="index.php" class="btn btn-light ms-1 text-danger rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                        <?php endif; ?>
                    </form>
                    <a href="tambah.php" class="btn btn-add text-white d-flex align-items-center">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Data
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:60px">No</th>
                            <th>NIM</th>
                            <th>Nama Lengkap</th>
                            <th>Jurusan</th>
                            <th class="text-center">Angkatan</th>
                            <th class="text-center">IPK</th>
                            <th class="text-center" style="width:140px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($mahasiswa)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                    <i class="bi bi-folder-x text-muted fs-1"></i>
                                </div>
                                <h5 class="fw-bold text-dark">Data Tidak Ditemukan</h5>
                                <p class="text-muted mb-0">
                                    <?= $cari ? "Pencarian untuk \"<strong class='text-dark'>" . htmlspecialchars($cari) . "</strong>\" tidak membuahkan hasil." : 'Belum ada data mahasiswa yang terdaftar dalam sistem.' ?>
                                </p>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($mahasiswa as $i => $mhs): ?>
                        <tr>
                            <td class="text-center text-muted"><?= $offset + $i + 1 ?></td>
                            <td>
                                <span class="badge bg-light text-primary border border-primary-subtle rounded-pill px-3 py-2">
                                    <?= htmlspecialchars($mhs['nim']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 40px; height: 40px;">
                                        <?= strtoupper(substr($mhs['nama'], 0, 1)) ?>
                                    </div>
                                    <span class="fw-bold text-dark"><?= htmlspecialchars($mhs['nama']) ?></span>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($mhs['jurusan']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($mhs['angkatan']) ?></td>
                            <td class="text-center">
                                <span class="badge badge-custom bg-<?= ipkBadge((float)$mhs['ipk']) ?> bg-opacity-10 text-<?= ipkBadge((float)$mhs['ipk']) ?> border border-<?= ipkBadge((float)$mhs['ipk']) ?>-subtle">
                                    <?= number_format($mhs['ipk'], 2) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $mhs['id'] ?>" class="btn btn-light action-btn text-warning border shadow-sm me-1" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <button type="button" class="btn btn-light action-btn text-danger border shadow-sm" title="Hapus"
                                        onclick="konfirmasiHapus(<?= $mhs['id'] ?>, '<?= htmlspecialchars(addslashes($mhs['nama'])) ?>')">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if ($total_halaman > 1): ?>
        <div class="card-footer bg-white border-top-0 p-4 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <span class="text-muted fw-medium small">
                Menampilkan <span class="text-dark fw-bold"><?= $offset + 1 ?></span> hingga <span class="text-dark fw-bold"><?= min($offset + $per_halaman, $total_data) ?></span> dari <span class="text-dark fw-bold"><?= $total_data ?></span> data
            </span>
            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item <?= $halaman <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link shadow-sm" href="<?= pageUrl($halaman - 1, $cari) ?>">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>

                    <?php
                    $range = 2;
                    $start = max(1, $halaman - $range);
                    $end   = min($total_halaman, $halaman + $range);
                    
                    if ($start > 1): ?>
                        <li class="page-item">
                            <a class="page-link shadow-sm" href="<?= pageUrl(1, $cari) ?>">1</a>
                        </li>
                        <?php if ($start > 2): ?>
                            <li class="page-item disabled"><span class="page-link border-0 bg-transparent text-muted">…</span></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php for ($p = $start; $p <= $end; $p++): ?>
                    <li class="page-item <?= $p === $halaman ? 'active' : '' ?>">
                        <a class="page-link shadow-sm" href="<?= pageUrl($p, $cari) ?>"><?= $p ?></a>
                    </li>
                    <?php endfor; ?>

                    <?php if ($end < $total_halaman): ?>
                        <?php if ($end < $total_halaman - 1): ?>
                            <li class="page-item disabled"><span class="page-link border-0 bg-transparent text-muted">…</span></li>
                        <?php endif; ?>
                        <li class="page-item">
                            <a class="page-link shadow-sm" href="<?= pageUrl($total_halaman, $cari) ?>">
                                <?= $total_halaman ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="page-item <?= $halaman >= $total_halaman ? 'disabled' : '' ?>">
                        <a class="page-link shadow-sm" href="<?= pageUrl($halaman + 1, $cari) ?>">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>

    <p class="text-center text-muted mt-5 mb-0 fw-medium" style="font-size: 0.85rem;">
        Tugas Pertemuan 10 - Zhundy Miftahulfalah A
    </p>
</div>

<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0 mt-3 px-4">
                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                </div>
                <h5 class="modal-title fw-bold text-dark mt-2">Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close mt-1 me-1" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pt-3 pb-2">
                <p class="mb-2 text-muted fw-medium">Anda yakin ingin menghapus data mahasiswa berikut?</p>
                <div class="bg-light rounded-3 p-3 mb-3 border">
                    <span class="fw-bold fs-5 text-dark" id="namaHapus"></span>
                </div>
                <p class="text-danger small fw-medium mb-0"><i class="bi bi-info-circle me-1"></i>Tindakan ini permanen dan tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-2">
                <button type="button" class="btn btn-light text-dark fw-bold px-4 rounded-pill border" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="linkHapus" class="btn btn-danger fw-bold px-4 rounded-pill shadow-sm">
                    <i class="bi bi-trash-fill me-2"></i>Ya, Hapus Data
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function konfirmasiHapus(id, nama) {
    document.getElementById('namaHapus').textContent = nama;
    document.getElementById('linkHapus').href = 'hapus.php?id=' + id;
    new bootstrap.Modal(document.getElementById('modalHapus')).show();
}
</script>
</body>
</html>