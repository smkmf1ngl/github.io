<?php

include 'config.php';

$error = '';

if(isset($_POST['login'])) {
    if($_POST['username'] == 'admin' && $_POST['password'] == 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    min-height:100vh;
    background:
        linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.55)),
        url('assets/bg-smk.jpg') center/cover no-repeat;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

.login-box{
    width:100%;
    max-width:420px;
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(12px);
    -webkit-backdrop-filter:blur(12px);
    padding:35px;
    border-radius:20px;
    box-shadow:0 8px 30px rgba(0,0,0,.35);
    color:white;
}

.login-header{
    text-align:center;
    margin-bottom:25px;
}

.login-header img{
    width:80px;
    margin-bottom:10px;
}

.login-header h2{
    font-size:28px;
    margin-bottom:5px;
}

.login-header p{
    opacity:.85;
    font-size:14px;
}

.input-group{
    margin-bottom:18px;
}

.input-group label{
    display:block;
    margin-bottom:8px;
    font-size:14px;
}

.input-group input{
    width:100%;
    padding:14px;
    border:none;
    border-radius:10px;
    outline:none;
    font-size:15px;
}

button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:10px;
    background:#0d6efd;
    color:white;
    font-size:16px;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#084298;
}

.error{
    background:rgba(220,53,69,.25);
    border:1px solid #dc3545;
    padding:12px;
    border-radius:10px;
    margin-bottom:15px;
    text-align:center;
    font-size:14px;
}

.back-home{
    margin-top:18px;
    text-align:center;
}

.back-home a{
    color:white;
    text-decoration:none;
    opacity:.8;
}

.back-home a:hover{
    opacity:1;
    text-decoration:underline;
}
</style>
</head>
<body>

<div class="login-box">

    <div class="login-header">
        <img src="assets\logo-sekolah.png" alt="Admin">
        <h2>Login Admin</h2>
        <p>Silakan masuk ke panel administrator</p>
    </div>

    <?php if($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>
        </div>

        <button name="login">Login</button>
    </form>

    <div class="back-home">
        <a href="index.php">← Kembali ke Home</a>
    </div>

</div>

</body>
</html>