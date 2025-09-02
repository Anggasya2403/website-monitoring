<?php
session_start();
include('koneksi.php');
// Pastikan user sudah login
if (!isset($_SESSION['users'])) {
    header("Location: index.php");
    exit;
}
// Proses update data (edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    // Validasi id
    if (empty($_POST['id']) || !is_numeric($_POST['id']) || intval($_POST['id']) <= 0) {
        die("ID tidak valid.");
    }
    $id = intval($_POST['id']);
    $tanggal = $_POST['tanggal'] ?? '';
    $penanggung_jawab = $_POST['penanggung_jawab'] ?? '';
    $instalasi = $_POST['instalasi'] ?? '';
    $kategori_aset = $_POST['kategori_aset'] ?? '';
    $nama_aset = $_POST['nama_aset'] ?? '';
    $jumlah = isset($_POST['jumlah']) && is_numeric($_POST['jumlah']) ? intval($_POST['jumlah']) : 0;
    $kondisi = $_POST['kondisi'] ?? '';
    $catatan = $_POST['catatan'] ?? '';
    if ($jumlah <= 0) {
        die("Jumlah harus lebih dari 0.");
    }
    $sql = "UPDATE aset SET tanggal=?, penanggung_jawab=?, instalasi=?, kategori_aset=?, nama_aset=?, jumlah=?, kondisi=?, catatan=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare statement gagal: " . $conn->error);
    }
    $stmt->bind_param("ssssssssi", $tanggal, $penanggung_jawab, $instalasi, $kategori_aset, $nama_aset, $jumlah, $kondisi, $catatan, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit;
} else {
    // Jika akses bukan POST edit, redirect ke index
    header("Location: index.php");
    exit;
}
?>