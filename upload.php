<?php
include 'db.php';

$nama = $_POST['nama'];
$tipe = $_POST['tipe'];
$deskripsi = $_POST['deskripsi'];
$gambarPath = '';

if (!empty($_FILES['gambar']['name'])) {
  $gambarPath = 'uploads/' . time() . '-' . basename($_FILES['gambar']['name']);
  move_uploaded_file($_FILES['gambar']['tmp_name'], $gambarPath);
}

$stmt = $conn->prepare("INSERT INTO items (nama, tipe, deskripsi, gambar) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nama, $tipe, $deskripsi, $gambarPath);
$stmt->execute();

echo "<p>✅ Data berhasil ditambahkan!</p>";
