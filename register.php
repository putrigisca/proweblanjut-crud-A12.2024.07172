<?php
require_once 'koneksi.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    
    if (strlen($password) < 6) {
        $error = "Password harus minimal 6 karakter!";
    } elseif ($password !== $confirm_password) {
        $error = "Password tidak cocok!";
    } else {
        
        $stmt_cek = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt_cek->execute([':username' => $username]);
        
        if ($stmt_cek->rowCount() > 0) {
            $error = "Username sudah digunakan!";
        } else {
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
           
            $stmt_ins = $pdo->prepare("INSERT INTO users (username, password, nama_lengkap, email) VALUES (:username, :password, :nama_lengkap, :email)");
            
            $simpan = $stmt_ins->execute([
                ':username' => $username,
                ':password' => $hashed_password,
                ':nama_lengkap' => $nama_lengkap,
                ':email' => $email
            ]);
            
            if ($simpan) {
                $success = "Pendaftaran berhasil! Silakan login.";
            } else {
                $error = "Terjadi kesalahan saat mendaftar.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi - Sistem Inventaris</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="register-container">
        
        <div class="register-left">
            <div class="header-text">
                <h1>Buat Akun</h1>
                <p>Isi data diri Anda dengan lengkap.</p>
            </div>

            <?php if($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required placeholder="Contoh: Gisca Dwi">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required placeholder="nama@email.com">
                </div>
                
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required placeholder="Buat username unik">
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required minlength="6" placeholder="Min. 6 karakter">
                </div>
                
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="confirm_password" required placeholder="Ulangi password">
                </div>
                
                <button type="submit" class="btn-register">Daftar Sekarang</button>
                
                <div class="login-link">
                    Sudah punya akun? <a href="login.php">Login di sini</a>
                </div>
            </form>
        </div>

        <div class="register-right">
            <h2>Ayo Gabung!</h2>
            <p> Mulai kelola stok barangmu dengan sistem yang terorganisir.</p>
            <img src="assets/grils.png" alt="Register"> 
        </div>
    </div>

</body>
</html>