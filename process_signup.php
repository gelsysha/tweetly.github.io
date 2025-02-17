<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'config.php';

// Pastikan semua input diterima dengan benar
$username = isset($_POST['username']) ? trim($_POST['username']) : null;
$email = isset($_POST['email']) ? trim($_POST['email']) : null;
$password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;

if (!$username || !$email || !$password) {
    die("Semua kolom harus diisi!");
}

// Cek apakah username sudah terdaftar
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    die("Username sudah digunakan. Silakan pilih username lain.");
}

// Cek apakah email sudah terdaftar
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    die("Email sudah digunakan. Silakan gunakan email lain.");
}

// Simpan user baru ke database
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $password);
if ($stmt->execute()) {
    echo "Successfully signed up!";
    header("Location: login.php"); // Redirect ke halaman login setelah signup
    exit();
} else {
    echo "Error: " . $conn->error;
}
?>
