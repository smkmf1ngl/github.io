<?php
include 'config.php';

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM siswa WHERE id=$id")->fetch_assoc();

if(!$data){
    die("Data tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Data Siswa</title>
<style>
body{
    background:#f4f7f2;
    font-family:Arial;
    padding:30px;
}
.container{
    max-width:600px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}
h2{
    color:#1b5e20;
    margin-bottom:20px;
}
input,select{
    width:100%;
    padding:14px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:10px;
}
button{
    width:100%;
    padding:14px;
    background:#2e7d32;
    color:white;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
}
button:hover{
    background:#1b5e20;
}
a{
    color:#2e7d32;
    text-decoration:none;
}
</style>
</head>
<body>

<div class="container">

    <h2>Edit Data Siswa</h2>

    <form action="admin.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?= $data['id']; ?>">

        <label>NISN</label>
        <input type="text" name="nisn" value="<?= $data['nisn']; ?>" required>

        <label>Nama</label>
        <input type="text" name="nama" value="<?= $data['nama']; ?>" required>

        <label>Status Kelulusan</label>
        <select name="status_kelulusan" required>
            <option value="LULUS" <?= $data['status_kelulusan']=='LULUS' ? 'selected' : ''; ?>>
                LULUS
            </option>
            <option value="TIDAK LULUS" <?= $data['status_kelulusan']=='TIDAK LULUS' ? 'selected' : ''; ?>>
                TIDAK LULUS
            </option>
        </select>

        <label>PDF Saat Ini:</label><br>
        <a href="uploads/<?= $data['pdf_file']; ?>" target="_blank">Lihat PDF</a><br><br>

        <label>Upload PDF Baru (Opsional)</label>
        <input type="file" name="pdf">

        <button type="submit" name="update_data">Update Data</button>

    </form>

</div>

</body>
</html>