CREATE DATABASE IF NOT EXISTS db_mahasiswa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_mahasiswa;

CREATE TABLE IF NOT EXISTS mahasiswa (
    id          INT(11) AUTO_INCREMENT PRIMARY KEY,
    nim         VARCHAR(20) NOT NULL UNIQUE,
    nama        VARCHAR(100) NOT NULL,
    jurusan     VARCHAR(100) NOT NULL,
    angkatan    YEAR NOT NULL,
    ipk         DECIMAL(3,2) NOT NULL DEFAULT 0.00,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO mahasiswa (nim, nama, jurusan, angkatan, ipk) VALUES
('2026001001', 'Ahmad Fauzi', 'Teknik Informatika', 2026, 3.75),
('2026002001', 'Siti Rahayu', 'Sistem Informasi', 2026, 3.82),
('2026001002', 'Budi Santoso', 'Teknik Informatika', 2026, 3.50),
('2026003001', 'Dewi Lestari', 'Manajemen', 2026, 3.90),
('2026004001', 'Rizky Pratama', 'Akuntansi', 2026, 3.65),
('2026001003', 'Nurul Hidayah', 'Teknik Informatika', 2026, 3.78),
('2026002002', 'Eko Prasetyo', 'Sistem Informasi', 2026, 3.45),
('2026003002', 'Fitri Ananda', 'Manajemen', 2026, 3.88),
('2026005001', 'Hendra Wijaya', 'Teknik Elektro', 2026, 3.55),
('2026004002', 'Indah Permata', 'Akuntansi', 2026, 3.70),
('2026001004', 'Joko Susilo', 'Teknik Informatika', 2026, 3.60),
('2026002003', 'Kartika Sari', 'Sistem Informasi', 2026, 3.95);