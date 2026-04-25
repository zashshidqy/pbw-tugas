-- =============================================
-- DATABASE: pemrograman_web_contoh
-- Praktikum 9 - Studi Kasus Pengelolaan Buku
-- =============================================

CREATE DATABASE IF NOT EXISTS pemrograman_web_contoh;
USE pemrograman_web_contoh;

-- Tabel Buku
CREATE TABLE IF NOT EXISTS Buku (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Judul VARCHAR(255) NOT NULL,
    Penulis VARCHAR(255) NOT NULL,
    Tahun_Terbit INT NOT NULL,
    Harga DECIMAL(10,2) NOT NULL,
    Stok INT NOT NULL DEFAULT 0
);

-- Tabel Pelanggan
CREATE TABLE IF NOT EXISTS Pelanggan (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Nama VARCHAR(255) NOT NULL,
    Email VARCHAR(255),
    Telepon VARCHAR(20)
);

-- Tabel Pesanan
CREATE TABLE IF NOT EXISTS Pesanan (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Tanggal_Pesanan DATE NOT NULL,
    Pelanggan_ID INT NOT NULL,
    Total_Harga DECIMAL(10,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (Pelanggan_ID) REFERENCES Pelanggan(ID)
);

-- Tabel Detail_Pesanan
CREATE TABLE IF NOT EXISTS Detail_Pesanan (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Pesanan_ID INT NOT NULL,
    Buku_ID INT NOT NULL,
    Kuantitas INT NOT NULL,
    Harga_Per_Satuan DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (Pesanan_ID) REFERENCES Pesanan(ID),
    FOREIGN KEY (Buku_ID) REFERENCES Buku(ID)
);

-- Data sample Buku
INSERT INTO Buku (Judul, Penulis, Tahun_Terbit, Harga, Stok) VALUES
('Pemrograman Web dengan PHP', 'Budi Santoso', 2023, 85000, 20),
('Belajar MySQL untuk Pemula', 'Andi Kurniawan', 2022, 75000, 15),
('Dasar-Dasar Algoritma', 'Siti Rahayu', 2021, 90000, 10),
('Desain Web Modern', 'Rizky Pratama', 2024, 95000, 25),
('JavaScript Lengkap', 'Dewi Lestari', 2023, 80000, 18);

-- Data sample Pelanggan
INSERT INTO Pelanggan (Nama, Email, Telepon) VALUES
('Ahmad Fauzi', 'ahmad@email.com', '081234567890'),
('Budi Santoso', 'budi@email.com', '082345678901'),
('Citra Dewi', 'citra@email.com', '083456789012');
