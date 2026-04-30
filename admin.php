<?php
include 'config.php';

/* =========================
   UPLOAD DATA BARU
========================= */
if(isset($_POST['upload_pdf'])) {

    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $status_kelulusan = $_POST['status_kelulusan'];

    $fileName = time().'_'.basename($_FILES['pdf']['name']);
    $targetFile = 'uploads/'.$fileName;

    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if($fileType != 'pdf'){
        die("File harus PDF.");
    }

    if(move_uploaded_file($_FILES['pdf']['tmp_name'], $targetFile)){

        $stmt = $conn->prepare("
            INSERT INTO siswa (nisn, nama, status_kelulusan, pdf_file)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("ssss", $nisn, $nama, $status_kelulusan, $fileName);
        $stmt->execute();

        echo "<script>alert('Upload berhasil');</script>";
    }
}


/* =========================
   UPDATE DATA / EDIT
========================= */
if(isset($_POST['update_data'])) {

    $id = $_POST['id'];
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $status_kelulusan = $_POST['status_kelulusan'];

    if(!empty($_FILES['pdf']['name'])){

        $lama = $conn->query("
            SELECT pdf_file FROM siswa WHERE id=$id
        ")->fetch_assoc();

        if($lama && file_exists("uploads/".$lama['pdf_file'])){
            unlink("uploads/".$lama['pdf_file']);
        }

        $fileName = time().'_'.basename($_FILES['pdf']['name']);

        move_uploaded_file(
            $_FILES['pdf']['tmp_name'],
            "uploads/".$fileName
        );

        $stmt = $conn->prepare("
            UPDATE siswa
            SET nisn=?, nama=?, status_kelulusan=?, pdf_file=?
            WHERE id=?
        ");

        $stmt->bind_param(
            "ssssi",
            $nisn,
            $nama,
            $status_kelulusan,
            $fileName,
            $id
        );

    } else {

        $stmt = $conn->prepare("
            UPDATE siswa
            SET nisn=?, nama=?, status_kelulusan=?
            WHERE id=?
        ");

        $stmt->bind_param(
            "sssi",
            $nisn,
            $nama,
            $status_kelulusan,
            $id
        );
    }

    $stmt->execute();

    echo "<script>
        alert('Data berhasil diupdate');
        window.location='admin.php';
    </script>";
}


/* =========================
   HAPUS DATA
========================= */
if(isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    $ambil = $conn->query("
        SELECT pdf_file FROM siswa WHERE id=$id
    ");

    $pdf = $ambil->fetch_assoc();

    if($pdf && file_exists('uploads/'.$pdf['pdf_file'])){
        unlink('uploads/'.$pdf['pdf_file']);
    }

    $conn->query("
        DELETE FROM siswa WHERE id=$id
    ");
}


/* =========================
   AMBIL DATA
========================= */
$data = $conn->query("
    SELECT * FROM siswa ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}
body{
    background:#f4f7f2;
    padding:30px;
    color:#333;
}
.container{
    max-width:1200px;
    margin:auto;
}
.header{
    background:linear-gradient(135deg,#2e7d32,#1b5e20);
    color:white;
    padding:25px 30px;
    border-radius:15px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    box-shadow:0 8px 20px rgba(0,0,0,.12);
}
.header h2{
    font-size:28px;
}
.logout{
    text-decoration:none;
    background:white;
    color:#1b5e20;
    padding:10px 18px;
    border-radius:10px;
    font-weight:bold;
}
.card{
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
    margin-bottom:25px;
}
.card h3{
    margin-bottom:18px;
    color:#1b5e20;
}
form{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:15px;
}
input,
select{
    padding:14px;
    border:1px solid #ccc;
    border-radius:10px;
    font-size:14px;
    width:100%;
}
button{
    background:#2e7d32;
    color:white;
    border:none;
    padding:14px;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
}
button:hover{
    background:#1b5e20;
}
.table-wrapper{
    overflow-x:auto;
}
table{
    width:100%;
    border-collapse:collapse;
}
table th{
    background:#2e7d32;
    color:white;
    padding:14px;
    text-align:left;
}
table td{
    padding:14px;
    border-bottom:1px solid #eee;
}
table tr:hover{
    background:#f1f8e9;
}
.btn{
    padding:8px 14px;
    border-radius:8px;
    text-decoration:none;
    font-size:14px;
    font-weight:bold;
    display:inline-block;
}
.btn-edit{
    background:#0288d1;
    color:white;
}
.btn-delete{
    background:#d32f2f;
    color:white;
}
.btn-pdf{
    background:#388e3c;
    color:white;
}
.badge-lulus{
    background:#4caf50;
    color:white;
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
}
.badge-tidak{
    background:#d32f2f;
    color:white;
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
}
</style>
</head>
<body>

<div class="container">

    <div class="header">
        <h2>Dashboard Admin Kelulusan</h2>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <div class="card">
        <h3>Tambah Data Siswa</h3>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="nisn" placeholder="NISN" required>

            <input type="text" name="nama" placeholder="Nama Siswa" required>

            <select name="status_kelulusan" required>
                <option value="" disabled selected>-- Pilih Status Kelulusan --</option>
                <option value="LULUS">LULUS</option>
                <option value="TIDAK LULUS">TIDAK LULUS</option>
            </select>

            <input type="file" name="pdf" required>

            <button name="upload_pdf">Upload PDF</button>
        </form>
    </div>

    <div class="card">
        <h3>Data Siswa</h3>

        <div class="table-wrapper">
            <table>
                <tr>
                    <th>ID</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>PDF</th>
                    <th>Aksi</th>
                </tr>

                <?php while($row = $data->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['nisn']; ?></td>
                    <td><?= $row['nama']; ?></td>

                    <td>
                        <?php if($row['status_kelulusan'] == 'LULUS'): ?>
                            <span class="badge-lulus">LULUS</span>
                        <?php else: ?>
                            <span class="badge-tidak">TIDAK LULUS</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <a class="btn btn-pdf" href="uploads/<?= $row['pdf_file']; ?>" target="_blank">
                            Lihat PDF
                        </a>
                    </td>

                    <td>
                        <a class="btn btn-edit" href="edit.php?id=<?= $row['id']; ?>">Edit</a>
                        <a class="btn btn-delete"
                           href="admin.php?hapus=<?= $row['id']; ?>"
                           onclick="return confirm('Hapus data ini?')">
                           Hapus
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>

            </table>
        </div>
    </div>

</div>

</body>
</html>