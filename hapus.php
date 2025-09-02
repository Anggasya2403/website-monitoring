<?php
session_start();
include('koneksi.php');
// Pastikan user sudah login
if (!isset($_SESSION['users'])) {
    header("Location: index.php");
    exit;
}
// Proses hapus data
if (isset($_GET['id'])) {
    $delete_id = $_GET['id'];
    // Validasi id hapus
    if (!is_numeric($delete_id) || intval($delete_id) <= 0) {
        die("ID tidak valid.");
    }
    $id = intval($delete_id);
    $sql = "DELETE FROM aset WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare statement gagal: " . $conn->error);
         }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit;
} else {
    // Jika parameter id tidak ada, redirect ke index
    header("Location: index.php");
    exit;
}
?>