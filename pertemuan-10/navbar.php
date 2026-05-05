<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-3 sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bold fs-4 text-dark" href="index.php">
            <div class="bg-primary text-white rounded-4 d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 44px; height: 44px; background: linear-gradient(135deg, #3b82f6, #2563eb);">
                <i class="bi bi-mortarboard-fill fs-5"></i>
            </div>
            <span style="letter-spacing: -0.5px;">Data Mahasiswa</span>
        </a>
        
        <button class="navbar-toggler border-0 shadow-none bg-light p-2 rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse mt-3 mt-lg-0" id="navMenu">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-2">
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-4 py-2 d-flex align-items-center fw-semibold custom-nav-hover <?= $current_page === 'index.php' ? 'active-nav' : 'text-secondary' ?>" href="index.php">
                        <i class="bi bi-table me-2 fs-5"></i>Data
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-4 py-2 d-flex align-items-center fw-semibold custom-nav-hover <?= $current_page === 'tambah.php' ? 'active-nav' : 'text-secondary' ?>" href="tambah.php">
                        <i class="bi bi-person-plus me-2 fs-5"></i>Tambah
                    </a>
                </li>
            </ul>
            
            <div class="d-none d-lg-block border-start ms-2 me-4 border-2 border-light" style="height: 30px;"></div>
            
            <div class="navbar-text d-inline-flex align-items-center bg-light bg-opacity-50 border border-light-subtle rounded-pill px-4 py-2 text-dark small fw-bold">
                <i class="bi bi-building me-2 text-primary fs-6"></i>UNSIKA
            </div>
        </div>
    </div>
</nav>

<style>
    .active-nav {
        background-color: rgba(59, 130, 246, 0.1);
        color: #2563eb !important;
    }
    .custom-nav-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .custom-nav-hover:not(.active-nav):hover {
        background-color: #f8fafc;
        color: #0f172a !important;
        transform: translateY(-1px);
    }
</style>