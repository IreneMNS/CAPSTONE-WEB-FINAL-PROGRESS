<?php
require_once 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tentang Kami - Mealystics</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    .map-container {
      height: 500px;
      width: 100%;
      border: 2px solid #333;
      border-radius: 10px;
      overflow: hidden;
    }
  </style>
</head>

<body>
  <header class="py-3 border-bottom border-warning" style="background:#181818;">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="logo">
        <img src="gambar/mealytics.png" alt="Logo" class="img-fluid" style="width: 100px" />
      </div>
      <nav>
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link text-warning fw-semibold" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-warning fw-semibold active" href="tentangkami.php">Tentang Kami</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-warning fw-semibold" href="menu.php">Menu</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-warning fw-semibold" href="catering.php">Catering</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-warning fw-semibold" href="order.php">Order</a>
          </li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="py-5" style="background:#232323;">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-7 text-center text-md-start mb-4 mb-md-0">
          <h1 class="fw-bold" style="color:#FFD700; font-size:2.5rem; letter-spacing:2px;">
            IT'S A BREAK WITH <span style="color:#fffbe7;">MEALYSTICS</span>
          </h1>
          <p class="lead mt-3" style="color:#fffbe7; max-width:500px;">
            Mealystics adalah restoran yang menyajikan menu khas dengan cita rasa terbaik, suasana nyaman, dan harga terjangkau. Kami berkomitmen memberikan pelayanan terbaik untuk setiap pelanggan.
          </p>
        </div>
        <div class="col-md-5 text-center">
          <img src="gambar/waronk1.jpg" alt="Tentang Kami" class="img-fluid rounded" style="max-height:320px; object-fit:cover;" />
        </div>
      </div>
    </div>
  </section>

  <!-- Siapa Kami & Visi Misi (2 kolom) -->
  <section class="about py-5" style="background:#232323;">
    <div class="container">
      <div class="row g-5 align-items-center">
        <!-- Siapa Kami -->
        <div class="col-md-6">
          <div class="p-4 h-100" style="background:#181818; border-radius:14px; border:1.5px solid #FFD700;">
            <h2 class="mb-3" style="color:#FFD700; font-weight:700;">Siapa Kami?</h2>
            <p style="color:#fffbe7; font-size:1.08rem;">
              Mealystics adalah tempat makan yang menyajikan makanan lezat dengan harga terjangkau. Kami berdedikasi untuk memberikan pengalaman kuliner terbaik kepada pelanggan kami dengan bahan-bahan berkualitas dan pelayanan yang ramah.
            </p>
          </div>
        </div>
        <!-- Visi & Misi -->
        <div class="col-md-6">
          <div class="p-4 h-100" style="background:#181818; border-radius:14px; border:1.5px solid #FFD700;">
            <h3 style="color:#FFD700; font-weight:700;">Visi Kami</h3>
            <p style="color:#fffbe7; font-size:1.08rem;">
              Menjadi tempat makan favorit yang menyediakan menu dengan harga kaki lima dan rasa bintang lima bagi anak kos😉
            </p>
            <h3 class="mt-4" style="color:#FFD700; font-weight:700;">Misi Kami</h3>
            <ul style="color:#fffbe7; font-size:1.08rem; padding-left: 1.2em;">
              <li>Menyediakan makanan berkualitas dengan harga terjangkau.</li>
              <li>Memberikan pelayanan yang ramah dan cepat.</li>
              <li>Menciptakan suasana yang nyaman untuk pelanggan.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Map Section -->
  <section class="map py-5" style="background:#232323;">
    <div class="container">
      <h2 class="text-center mb-4" style="color:#FFD700;">Lokasi Mealystics</h2>
      <p class="text-center" style="color:#fffbe7;">
        Kami berada di lokasi strategis yang mudah dijangkau. Gunakan peta di bawah ini untuk menemukan kami.
      </p>
      <div class="map-container">
        <!-- Google Maps Embed -->
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.686504452663!2d117.15207810000001!3d-0.4657428!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df679a3307ed2f1%3A0x7ab6db7f0f248c9d!2sWaroenk%20Kangmas!5e0!3m2!1sid!2sid!4v1747093026975!5m2!1sid!2sid"
          width="100%"
          height="100%"
          style="border:0;"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </section>


  <!-- Footer -->
  <footer class="py-4 border-top border-warning" style="background:#181818;">
    <div class="container text-center">
      <div class="d-flex flex-column flex-md-row align-items-center justify-content-center mb-3 gap-3">
        <img src="gambar/mealytics.png" alt="Logo" class="img-fluid" style="width: 70px; height:70px; object-fit:contain; background:#232323; border-radius:12px; box-shadow:0 2px 8px rgba(255,215,0,0.10);" />
        <div class="text-start ms-md-3">
        </div>
      </div>
      <section id="contact" class="section text-center">
        <h3 class="text-warning mb-4">Contact Us</h3>
        <div class="contact-icons">
          <?php
          $kontak_query = mysqli_query($conn, "SELECT * FROM kontak");
          while ($kontak = mysqli_fetch_assoc($kontak_query)) {
            echo '<a href="' . $kontak['link'] . '" target="_blank" style="margin: 0 10px;">';
            echo '<img src="' . $kontak['icon_path'] . '" alt="' . $kontak['plattform'] . '" style="width: 40px; height: 40px; object-fit: contain;">';
            echo '</a>';
          }
          ?>
        </div>
      </section>
      <p class="mb-0" style="color:#fffbe7; font-size:1.05rem;">&copy; <?php echo date("Y"); ?> Mealystics | Universitas Mulawarman</p>
    </div>
  </footer>

  <script src="script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>