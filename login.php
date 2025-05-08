<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: admin.php");
        exit();
    } else {
        $error = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - Portofolio Dharma</title>
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
    .error {
      color: #ff5c5c;
    }
    .link {
      margin-top: 1rem;
    }
    .link a {
      color: #00c3ff;
      text-decoration: none;
    }
  </style>
</head>
<body>

  <div class="login-box">
    <h2>Login Admin</h2>
    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="login">Login</button>
    </form>
    <div class="link">
      Belum punya akun? <a href="register.php">Daftar di sini</a>
    </div>
  </div>

</body>
</html>
