<?php
// =====================================================
// Konfigurasi koneksi database (XAMPP)
// =====================================================
$host    = 'localhost';
$user    = 'root';
$pass    = '';
$dbname  = 'shixuan_mlbb';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
