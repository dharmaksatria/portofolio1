<?php
session_start();
include 'db.php';
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

// Inisialisasi variabel untuk form
$editData = [
  'id' => '',
  'judul' => '',
  'deskripsi' => '',
  'tipe' => 'project',
];

// Tambah atau Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
  $judul = $_POST['judul'];
  $deskripsi = $_POST['deskripsi'];
  $tipe = $_POST['tipe'];
  $sumber = '';
  $edit_id = $_POST['edit_id'] ?? '';

  // Upload gambar dari file
  if (!empty($_FILES['gambar']['name'])) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir);
    $nama_file = basename($_FILES["gambar"]["name"]);
    $target_file = $target_dir . time() . "_" . $nama_file;
    move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file);
    $sumber = $target_file;
  } elseif (!empty($_POST['gambar_url'])) {
    $sumber = $_POST['gambar_url'];
  }

  if ($edit_id) {
    if ($sumber) {
      $stmt = $conn->prepare("UPDATE {$tipe}s SET judul=?, deskripsi=?, gambar=?  HERE id=?");
      $stmt->bind_param("sssi", $judul, $deskripsi, $sumber, $edit_id);
    } else {
      $stmt = $conn->prepare("UPDATE {$tipe}s SET judul=?, deskripsi=? WHERE id=?");
      $stmt->bind_param("ssi", $judul, $deskripsi, $edit_id);
    }
  } else {
    $stmt = $conn->prepare("INSERT INTO {$tipe}s (judul, deskripsi, gambar) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $judul, $deskripsi, $sumber);
  }

  if ($stmt) {
    $stmt->execute();
    header("Location: admin.php");
    exit;
  }
}

// Hapus
if (isset($_GET['delete'], $_GET['type'])) {
  $id = (int)$_GET['delete'];
  $type = $_GET['type'];
  $stmt = $conn->prepare("DELETE FROM {$type}s WHERE id=?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  header("Location: admin.php");
  exit;
}

// Edit
if (isset($_GET['edit'], $_GET['type'])) {
  $editId = (int)$_GET['edit'];
  $editType = $_GET['type'];
  $result = $conn->query("SELECT * FROM {$editType}s WHERE id=$editId");
  if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $editData = [
      'id' => $data['id'],
      'judul' => $data['judul'],
      'deskripsi' => $data['deskripsi'],
      'tipe' => $editType
    ];
  }
}

// Ambil data
$projects = $conn->query("SELECT * FROM projects ORDER BY id DESC");
$certificates = $conn->query("SELECT * FROM certificates ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel</title>
  <style>
    body {
      background: #0e0e0e;
      color: #f0f0f0;
      font-family: 'Segoe UI', sans-serif;
      padding: 2rem;
    }
    h1, h2 {
      color: #00c3ff;
    }
    form {
      background: #1c1c1c;
      padding: 1rem;
      border-radius: 10px;
      margin-bottom: 2rem;
      box-shadow: 0 0 15px #00c3ff33;
    }
    input, textarea, select {
      width: 100%;
      padding: 0.6rem;
      margin-bottom: 1rem;
      border-radius: 6px;
      border: none;
      background: #333;
      color: #fff;
    }
    button {
      background: #00c3ff;
      color: #000;
      padding: 0.7rem 1.5rem;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }
    button:hover {
      background: #009ac9;
    }
    .card {
      background: #1e1e1e;
      padding: 1rem;
      margin-bottom: 1rem;
      border-radius: 8px;
      box-shadow: 0 0 10px #00c3ff22;
    }
    .card img {
      max-width: 20%;
      margin-top: 0.5rem;
      border-radius: 6px;
    }
    .actions a {
      color: #00c3ff;
      text-decoration: none;
      margin-right: 10px;
    }
    .actions a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<h1>Panel Admin</h1>
<form method="POST" enctype="multipart/form-data">
  <input type="hidden" name="edit_id" value="<?= htmlspecialchars($editData['id']) ?>">

  <label>Judul</label>
  <input type="text" name="judul" required value="<?= htmlspecialchars($editData['judul']) ?>">

  <label>Deskripsi</label>
  <textarea name="deskripsi" rows="3" required><?= htmlspecialchars($editData['deskripsi']) ?></textarea>

  <label>Upload Gambar (File)</label>
  <input type="file" name="gambar" accept="image/*">

  <label>Atau Gambar dari URL</label>
  <input type="url" name="gambar_url" placeholder="https://...">

  <label>Tipe</label>
  <select name="tipe" required>
    <option value="project" <?= $editData['tipe'] === 'project' ? 'selected' : '' ?>>Project</option>
    <option value="certificate" <?= $editData['tipe'] === 'certificate' ? 'selected' : '' ?>>Certificate</option>
  </select>

  <button type="submit" name="submit"><?= $editData['id'] ? 'Update' : 'Tambah' ?></button>
</form>

<h2>Projects</h2>
<?php while ($row = $projects->fetch_assoc()): ?>
  <div class="card">
    <h3><?= htmlspecialchars($row['judul']) ?></h3>
    <p><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></p>
    <?php if ($row['gambar']): ?>
      <img src="<?= htmlspecialchars($row['gambar']) ?>" alt="gambar">
    <?php endif; ?>
    <div class="actions">
      <a href="?edit=<?= $row['id'] ?>&type=project">Edit</a>
      <a href="?delete=<?= $row['id'] ?>&type=project" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
    </div>
  </div>
<?php endwhile; ?>

<h2>Sertifikat</h2>
<?php while ($row = $certificates->fetch_assoc()): ?>
  <div class="card">
    <h3><?= htmlspecialchars($row['judul']) ?></h3>
    <p><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></p>
    <?php if ($row['gambar']): ?>
      <img src="<?= htmlspecialchars($row['gambar']) ?>" alt="gambar" width="300">
    <?php endif; ?>
    <div class="actions">
      <a href="?edit=<?= $row['id'] ?>&type=certificate">Edit</a>
      <a href="?delete=<?= $row['id'] ?>&type=certificate" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
    </div>
  </div>
<?php endwhile; ?>

<div style="display: flex; justify-content: space-between; align-items: center; background: #222; padding: 1rem;">
  <h2 style="color: #fff; margin: 0;">Logout</h2>
  <a href="logout.php" 
     style="padding: 0.5rem 1rem; background: #ff5c5c; color: white; text-decoration: none; border-radius: 5px;">
     Logout
  </a>
</div>


</body>
</html>