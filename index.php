<?php include 'config.php'; ?>

<?php
$pdfUrl = '';
$statusKelulusan = '';
$namaSiswa = '';

if (isset($_POST['cek_kelulusan'])) {
    $nisnCari = $_POST['search_nisn'];

    $stmt = $conn->prepare("SELECT * FROM siswa WHERE nisn = ?");
    $stmt->bind_param("s", $nisnCari);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $pdfUrl = 'uploads/' . $row['pdf_file'];
        $statusKelulusan = strtoupper($row['status_kelulusan']);
        $namaSiswa = $row['nama'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cek Kelulusan</title>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}
body{
    min-height:100vh;
    background:linear-gradient(rgba(0,0,0,.55),rgba(0,0,0,.55)),
    url('assets/bg-smk.jpg') center/cover no-repeat;
    padding:20px;
}

/* NAVBAR */
.navbar{
    width:100%;
    max-width:1200px;
    margin:auto;
    margin-bottom:25px;
    padding:15px 25px;
    background:rgba(255,255,255,0.12);
    backdrop-filter:blur(10px);
    border-radius:15px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    color:white;
}
.navbar .logo{
    font-size:22px;
    font-weight:bold;
}
.navbar ul{
    list-style:none;
    display:flex;
    gap:20px;
}
.navbar ul li a{
    text-decoration:none;
    color:white;
    font-weight:500;
    transition:.3s;
}
.navbar ul li a:hover{
    color:#0d6efd;
}

/* CONTAINER */
.container{
    width:100%;
    max-width:950px;
    margin:auto;
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(12px);
    border-radius:20px;
    padding:30px;
    color:#fff;
    box-shadow:0 8px 30px rgba(0,0,0,.35);
}
.header{
    text-align:center;
    margin-bottom:25px;
}
.header img{
    width:85px;
}
form{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin-bottom:25px;
}
input{
    flex:1;
    padding:14px;
    border:none;
    border-radius:10px;
}
button{
    padding:14px 25px;
    border:none;
    border-radius:10px;
    background:#0d6efd;
    color:#fff;
    cursor:pointer;
}
.status-box{
    padding:20px;
    border-radius:15px;
    text-align:center;
    margin-bottom:20px;
    font-size:22px;
    font-weight:bold;
}
.lulus{
    background:rgba(25,135,84,.25);
    border:2px solid #198754;
}
.tidak{
    background:rgba(220,53,69,.25);
    border:2px solid #dc3545;
}
iframe{
    width:100%;
    height:600px;
    border:none;
    border-radius:15px;
    background:#fff;
}
@media(max-width:768px){
    .navbar{
        flex-direction:column;
        gap:10px;
    }
    .navbar ul{
        flex-direction:column;
        text-align:center;
    }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">🎓 Kelulusan SMK</div>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="login.php">Login Admin</a></li>
    </ul>
</div>

<!-- CONTENT -->
<div class="container">

    <div class="header">
        <img src="assets/logo-sekolah.png">
        <h2>Pengumuman Kelulusan Siswa</h2>
        <h2>SMK MA'ARIF 1 NANGGULAN</h2>
        <p>Masukkan NISN untuk melihat hasil kelulusan</p>
    </div>

    <form method="POST">
        <input type="text" name="search_nisn" placeholder="Masukkan NISN..." required>
        <button name="cek_kelulusan">Cek Kelulusan</button>
    </form>

    <?php if($statusKelulusan): ?>
        <div class="status-box <?= $statusKelulusan == 'LULUS' ? 'lulus' : 'tidak' ?>">
            <?= $namaSiswa ?><br>
            STATUS: <?= $statusKelulusan ?>
        </div>
    <?php endif; ?>

    <?php if($pdfUrl): ?>
        <iframe src="<?= $pdfUrl ?>"></iframe>
    <?php endif; ?>

</div>

<?php if($statusKelulusan == 'LULUS'): ?>
<script>
confetti({
    particleCount:200,
    spread:100,
    origin:{y:0.6}
});
</script>
<?php endif; ?>

</body>
</html>