<?php
require_once 'koneksi.php';

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: index.php?pesan=invalid&tipe=danger');
    exit;
}

$stmt = $koneksi->prepare("SELECT nama FROM mahasiswa WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$mhs    = $result->fetch_assoc();
$stmt->close();

if (!$mhs) {
    header('Location: index.php?pesan=notfound&tipe=warning');
    exit;
}

$stmt = $koneksi->prepare("DELETE FROM mahasiswa WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    $nama   = urlencode($mhs['nama']);
    $pesan  = urlencode("Data mahasiswa <strong>{$mhs['nama']}</strong> berhasil dihapus.");
    header("Location: index.php?pesan={$pesan}&tipe=success");
} else {
    header('Location: index.php?pesan=Gagal+menghapus+data.&tipe=danger');
}

$stmt->close();
exit;
