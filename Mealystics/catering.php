<?php
require_once 'koneksi.php'; // Include database connection
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Catering - WarOenk Kangmas</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
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
            <a class="nav-link text-warning fw-semibold" href="tentangkami.php">Tentang Kami</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-warning fw-semibold" href="menu.php">Menu</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-warning fw-semibold active" href="catering.php">Catering</a>
          </li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section
    class="hero text-center d-flex align-items-center justify-content-center"
    style="background: linear-gradient(rgba(24,24,24,0.8), rgba(24,24,24,0.8)), url('gambar/cte.png') no-repeat center center/cover; min-height: 400px;">
    <div>
      <h1 class="display-4 fw-bold mb-3" style="color:#FFD700;">Catering Services</h1>
      <p class="lead" style="color:#fffbe7;">Delicious food for your special events</p>
      <a href="#order-form" class="btn btn-warning btn-lg fw-bold px-5 py-2" style="color:#181818;">Order Now</a>
    </div>
  </section>

  <!-- Catering Packages -->
  <section class="catering-packaged" style="background:#232323;">
    <div class="container">
      <h2 class="text-center mb-4" style="color:#FFD700;">Our Catering Packages</h2>
      <div class="row g-4">
        <?php
        $menu_query = mysqli_query($conn, "SELECT * FROM menu_catering");
        while ($menu = mysqli_fetch_assoc($menu_query)) {
          $sold_out = $menu['sold_out'] ? '<span class="badge bg-danger">Sold Out</span>' : '';
        ?>
          <div class="col-md-4">
            <div class="card shadow-sm h-100" style="background:#232323; border:1.5px solid #FFD700; border-radius:16px;">
              <img src="<?php echo htmlspecialchars($menu['foto_menu']); ?>" class="card-img-top img-fluid product-image"
                alt="<?php echo htmlspecialchars($menu['nama_paket']); ?>"
                style="border-radius:16px 16px 0 0; height:220px; object-fit:cover;" />
              <div class="card-body text-center">
                <h5 class="card-title" style="color:#FFD700;"><?php echo htmlspecialchars($menu['nama_paket']); ?></h5>
                <p class="card-text" style="color:#fffbe7;"><?php echo htmlspecialchars($menu['deskripsi']); ?></p>
                <p class="card-text" style="color:#FFD700; font-weight:bold;">Rp <?php echo number_format($menu['harga'], 0, ',', '.'); ?></p>
                <p class="card-text" style="color:#fffbe7;">Porsi: <?php echo htmlspecialchars($menu['jumlah_porsi']); ?></p>
                <?php echo $sold_out; ?>
                <a href="#order-form" class="btn btn-primary fw-semibold mt-2 order-btn"
                  style="background:#FFD700; color:#181818; border:none;" <?php echo ($menu['sold_out'] ? 'disabled' : ''); ?>
                  data-idmenu="<?php echo $menu['id_menu']; ?>">Order</a>
              </div>
            </div>
          </div>
        <?php
        }
        ?>
      </div>
    </div>
  </section>

  <!-- Order Form -->
  <section id="order-form" class="order-form py-5" style="background:#232323;">
    <div class="container">
      <h2 class="text-center mb-4" style="color:#FFD700;">Place Your Catering Order</h2>
      <form id="cateringForm" action="order_catering.php" method="POST" enctype="multipart/form-data" class="w-50 mx-auto">
        <div class="mb-3">
          <label for="name" class="form-label" style="color:#FFD700;">Name:</label>
          <input type="text" id="name" name="name" class="form-control" style="background:#181818; color:#FFD700; border:1px solid #FFD700;" required />
        </div>
        <div class="mb-3">
          <label for="email" class="form-label" style="color:#FFD700;">Email:</label>
          <input type="email" id="email" name="email" class="form-control" style="background:#181818; color:#FFD700; border:1px solid #FFD700;" />
        </div>
        <div class="mb-3">
          <label for="phone" class="form-label" style="color:#FFD700;">Phone:</label>
          <input type="tel" id="phone" name="phone" class="form-control" style="background:#181818; color:#FFD700; border:1px solid #FFD700;" required />
        </div>
        <div class="mb-3">
          <label for="package" class="form-label" style="color:#FFD700;">Select Package:</label>
          <select id="package" name="package" class="form-select" style="background:#181818; color:#FFD700; border:1px solid #FFD700;" required>
            <?php
            $menu_query = mysqli_query($conn, "SELECT * FROM menu_catering WHERE sold_out = 0");
            while ($menu = mysqli_fetch_assoc($menu_query)) {
              echo '<option value="' . $menu['id_menu'] . '">' . htmlspecialchars($menu['nama_paket']) . '</option>';
            }
            ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="porsi" class="form-label" style="color:#FFD700;">Number of Portions:</label>
          <input type="number" id="porsi" name="porsi" class="form-control" style="background:#181818; color:#FFD700; border:1px solid #FFD700;" required min="1" />
        </div>
        <div class="mb-3">
          <label for="message" class="form-label" style="color:#FFD700;">Additional Notes:</label>
          <textarea id="message" name="message" rows="4" class="form-control" style="background:#181818; color:#FFD700; border:1px solid #FFD700;"></textarea>
        </div>
        <div class="mb-3">
          <label for="payment" class="form-label" style="color:#FFD700;">Proof of Payment:</label>
          <input type="file" id="payment" name="payment" class="form-control" style="background:#181818; color:#FFD700; border:1px solid #FFD700;" required />
        </div>
        <button type="submit" class="btn btn-warning w-100 fw-bold" style="color:#181818;">
          Submit Order
        </button>
      </form>
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
  <script>
    // JavaScript to handle the order button click
    document.querySelectorAll('.order-btn').forEach(button => {
      button.addEventListener('click', function() {
        const packageId = this.getAttribute('data-idmenu');
        document.getElementById('package').value = packageId;
      });
    });
  </script>
</body>

</html>