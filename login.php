<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['login']) && isset($_COOKIE['username'])) {
    $username_cookie = $_COOKIE['username'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute([':username' => $username_cookie]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
        $_SESSION['login'] = true;
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        header("Location: index.php");
        exit();
    }
}

if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: index.php");
    exit();
}
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        if (password_verify($password, $user['password'])) {
            
            $_SESSION['login'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap']; 
            
            if (isset($_POST['remember'])) {
            setcookie("username", $username, time() + (86400 * 7), "/");
        }
            header("Location: index.php"); 
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Inventaris</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="body-auth">

    <div class="login-container">
        
        <div class="login-left">
            <div class="header-text" style="margin-bottom: 20px;">
                <h1 style="color: #1e293b; font-size: 28px; font-weight: 800; margin-bottom: 5px;">Login Sistem 👋</h1>
                <p style="color: #64748b; font-size: 14px;">Silakan masuk untuk mengelola inventaris.</p>
            </div>

            <?php if($error): ?>
                <div style="background-color: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; border-left: 4px solid #b91c1c;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'logout'): ?>
                <div style="background-color: #dcfce3; color: #166534; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; border-left: 4px solid #166534;">
                    Logout berhasil! Sampai jumpa lagi. 👋
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Username</label>
                    
            <form method="POST" action="">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required placeholder="Masukkan username">
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Masukkan password">
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px;">
                    <input type="checkbox" name="remember" id="remember" style="width: auto;">
                    <label for="remember" style="margin: 0; color: #666; font-weight: normal; cursor: pointer;">Remember Me</label>
                </div>

                <button type="submit" class="btn-login">Login Sekarang</button>
                
                <div style="text-align: center; margin-top: 20px; font-size: 14px; color: #666;">
                    Belum punya akun? <a href="register.php" style="color: #FF7E5F; font-weight: bold; text-decoration: none;">Daftar di sini</a>
                </div>
            </form>
        </div>

        <div class="login-right">
            <h2 style="margin-bottom: 10px;">Selamat Datang!</h2>
            <p style="opacity: 0.9; font-size: 14px; margin-bottom: 25px;">Kelola inventori Anda dengan mudah dan efisien.</p>
            
            <img src="assets/grils.png" alt="Ilustrasi Login">
        </div>

    </div>

</body>
</html>