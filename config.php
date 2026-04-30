<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'db_kelulusan');
if ($conn->connect_error) die('Koneksi gagal: ' . $conn->connect_error);
?>