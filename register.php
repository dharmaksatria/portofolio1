<?php
include 'db.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $new_user = trim($_POST['new_username']);
    $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $check->bind_param("s", $new_user);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $error = "Username sudah digunakan.";
    } else {
        $add = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
        $add->bind_param("ss", $new_user, $new_pass);
        $add->execute();
        $success = "Pendaftaran berhasil. Silakan <a href='login.php'>login</a>.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Akun - Portofolio Dharma</title>
  <style>
    body {
      margin: 0;
      background: linear-gradient(135deg, #0a0a0a, #1f1f1f);
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      color: #eee;
    }
    .login-box {
      background: #1a1a1a;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 0 20px #00c3ff44;
      max-width: 400px;
      width: 100%;
      text-align: center;
    }
    h2 {
      color: #00c3ff;
    }
    input {
      width: 100%;
      padding: 0.75rem;
      margin: 0.5rem 0;
      border: none;
      border-radius: 8px;
      background: #2a2a2a;
      color: #fff;
    }
    button {
      padding: 0.75rem 1.5rem;
      background: #00c3ff;
      color: #000;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 1rem;
    }
    .error { color: #ff5c5c; }
    .success { color: #00ff88; }
    .link { margin-top: 1rem; }
    .link a { color: #00c3ff; text-decoration: none; }
  </style>
</head>
<body>

  <div class="login-box">
    <h2>Daftar Akun Baru</h2>
    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="success"><?= $success ?></div>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="new_username" placeholder="Username Baru" required>
      <input type="password" name="new_password" placeholder="Password Baru" required>
      <button type="submit" name="register">Daftar</button>
    </form>
    <div class="link">
      Sudah punya akun? <a href="login.php">Login di sini</a>
    </div>
  </div>

</body>
</html>
