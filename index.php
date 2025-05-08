<?php 
include 'db.php'; 
$projects = $conn->query("SELECT * FROM projects ORDER BY id DESC");
$certificates = $conn->query("SELECT * FROM certificates ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Portofolio Siswa</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(135deg, #0f0f0f, #1e1e1e);
      font-family: 'Segoe UI', sans-serif;
      color: #eee;
      line-height: 1.6;
    }

    header {
      background: #111;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #00c3ff44;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    header h1 {
      color: #00c3ff;
      font-size: 1.8rem;
      text-shadow: 0 0 5px #00c3ff88;
    }

    nav a {
      margin-left: 1.5rem;
      color: #ccc;
      text-decoration: none;
      font-weight: bold;
      transition: color 0.3s ease;
    }

    nav a:hover {
      color: #00c3ff;
    }

    section {
      padding: 3rem 2rem;
      max-width: 1200px;
      margin: auto;
    }

    .project-scroll, .certificate-scroll {
      display: flex;
      gap: 1rem;
      overflow-x: auto;
      padding-bottom: 1rem;
      margin-top: 2rem;
    }

    .project-scroll::-webkit-scrollbar,
    .certificate-scroll::-webkit-scrollbar {
      height: 8px;
    }

    .project-scroll::-webkit-scrollbar-thumb,
    .certificate-scroll::-webkit-scrollbar-thumb {
      background: #00c3ff55;
      border-radius: 4px;
    }

    .project-frame,
    .certificate-frame {
      background: #222;
      flex: 0 0 280px;
      border-radius: 10px;
      box-shadow: 0 0 12px rgba(0, 195, 255, 0.15);
      padding: 1rem;
      text-align: center;
    }

    .project-frame img,
    .certificate-frame img {
      width: 100%;
      height: auto;
      border-radius: 8px;
      margin-bottom: 0.5rem;
    }

    .project-frame h3,
    .certificate-frame h3 {
      font-size: 1.1rem;
      color: #ffcc00;
      margin-bottom: 0.4rem;
    }

    .project-frame p,
    .certificate-frame p {
      font-size: 0.9rem;
      color: #ccc;
    }

    footer {
      text-align: center;
      padding: 1rem;
      margin-top: 2rem;
      font-size: 0.9rem;
      color: #666;
    }

    .admin-btn {
      background: #00c3ff;
      color: #000;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      text-decoration: none;
      transition: background 0.3s;
    }

    .admin-btn:hover {
      background: #00a8d1;
    }
  </style>
</head>
<body>

<header>
  <h1>Dharma Ksatria</h1>
  <nav>
    <a href="#home">Home</a>
    <a href="#about">About</a>
    <a href="#contact">Contact</a>
    <a href="login.php" class="admin-btn">Admin</a>
  </nav>
</header>

<!-- Gambar Tengah dan Kata Pembuka -->
<section id="home">
<div style="text-align: center; margin: 3rem 0;">
  <img src="https://img.pikbest.com/png-images/20250104/coffee-tree-or-leaf-foam-with-beans-cartoon-icon_11339568.png!bw700"
       alt="Profil Siswa"
       style="border-radius: 50%; width: 200px; height: 200px; object-fit: cover; box-shadow: 0 0 10px #00c3ff88;">
  <p style="margin-top: 1.5rem; font-size: 1.1rem; color: #ccc; max-width: 700px; margin-left: auto; margin-right: auto;">
    Selamat datang di halaman portofolio Dharma! Di bawah ini adalah koleksi sertifikat yang telah diperoleh sebagai bukti pencapaian dan dedikasi dalam berbagai bidang pembelajaran dan pengembangan diri.
  </p>
</div>
</section>

  <h2>Projek</h2>
  <div class="project-scroll">
    <?php while ($row = $projects->fetch_assoc()): ?>
      <div class="project-frame">
        <?php if ($row['gambar']): ?>
          <img src="<?= htmlspecialchars($row['gambar']) ?>" alt="gambar">
        <?php endif; ?>
        <h3><?= htmlspecialchars($row['judul']) ?></h3>
        <p><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></p>
      </div>
    <?php endwhile; ?>
  </div>

  <h2 style="margin-top: 3rem;">Sertifikat</h2>
  <div class="certificate-scroll">
    <?php while ($row = $certificates->fetch_assoc()): ?>
      <div class="certificate-frame">
        <?php if ($row['gambar']): ?>
          <img src="<?= htmlspecialchars($row['gambar']) ?>" alt="gambar sertifikat">
        <?php endif; ?>
        <h3><?= htmlspecialchars($row['judul']) ?></h3>
        <p><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></p>
      </div>
    <?php endwhile; ?>
  </div>

<section id="about">
  <h2>About</h2>
  <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 2rem;">
    <div style="flex: 1; min-width: 250px;">
      <p><strong>Portofolio</strong> Saya adalah seorang siswa di SMK Negeri 1 Ciomas yang saat ini menduduki bangku kelas 10 PPLG-2. Saya tertarik mempelajari dunia pemograman, dan juga saya ingin mengikuti perkembangan dunia digital.</p>
      <p>Saya harap saya bisa mengembangkan skills saya dalam bidang pemograman</p>
    </div>
    <div style="flex: 1; min-width: 250px; text-align: center;">
      <img src="https://img.pikbest.com/png-images/20250104/coffee-tree-or-leaf-foam-with-beans-cartoon-icon_11339568.png!bw700"
           alt="About Image"
           style="width: 100%; max-width: 400px; border-radius: 12px; box-shadow: 0 0 15px #00c3ff55;">
    </div>
  </div>
</section>

<section id="contact">
  <h2>Contact</h2>
  <p>Jika kamu ingin menghubungi saya untuk menanyakan sesuatu, silakan kirim email ke <a href="mailto:dharmaksatria39@gmail.com" style="color:#00c3ff;">dharmaksatria39@gmail.com</a>.</p>
</section>

<footer>
  &copy; <?= date("Y") ?> Portofolio Dharma.
</footer>

</body>
</html>
