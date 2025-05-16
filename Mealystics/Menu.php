<?php
require_once 'koneksi.php'; // Include database connection
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Menu - WarOenk Kangmas</title>
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
            <a class="nav-link text-warning fw-semibold active" href="menu.php">Menu</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-warning fw-semibold" href="catering.php">Catering</a>
          </li>
        </ul>
      </nav>
    </div>
  </header>


  <!-- Menu Section -->
  <section class="py-5" style="background:#232323;">
    <div class="container">
      <h2 class="text-center mb-4" style="color:#FFD700;">Menu Kami</h2>
      <div class="row g-4">
        <?php
        $menu_query = mysqli_query($conn, "SELECT * FROM menu");
        while ($menu = mysqli_fetch_assoc($menu_query)) {
          $sold_out = $menu['sold_out'] ? '<span class="badge bg-danger">Sold Out</span>' : '';
        ?>
          <div class="col-md-4">
            <div class="card shadow-sm h-100" style="background:#232323; border:1.5px solid #FFD700; border-radius:16px;">
              <img src="<?php echo htmlspecialchars($menu["foto_menu"]); ?>"
                class="card-img-top img-fluid product-image"
                alt="<?php echo htmlspecialchars($menu["nama_menu"]); ?>"
                style="border-radius:16px 16px 0 0; height:220px; object-fit:cover;" />
              <div class="card-body text-center">
                <h5 class="card-title" style="color:#FFD700;"><?php echo htmlspecialchars($menu["nama_menu"]); ?></h5>
                <p class="card-text" style="color:#fffbe7;"><?php echo htmlspecialchars($menu["deskripsi"]); ?></p>
                <p class="card-text" style="color:#FFD700; font-weight:bold;">Rp <?php echo number_format($menu["harga"], 0, ',', '.'); ?></p>
                <p class="card-text" style="color:#fffbe7;">Porsi: <?php echo htmlspecialchars($menu["jumlah_porsi"]); ?></p>
                <?php echo $sold_out; ?>
                <a href="#order-form"
                  class="btn btn-primary fw-semibold mt-2 order-btn"
                  style="background:#FFD700; color:#181818; border:none;"
                  <?php echo ($menu['sold_out'] ? 'disabled' : ''); ?>
                  data-idmenu="<?php echo $menu['id_menu']; ?>">
                  Order
                </a>
              </div>
            </div>
          </div>
        <?php
        }
        ?>
      </div>
    </div>
  </section>

  <!-- Order Form Section -->
  <section id="order-form" class="order-form py-5" style="background:#232323;">
    <div class="container">
      <h2 class="text-center mb-4" style="color:#FFD700;">Form Pemesanan</h2>

      <!-- Success Notification -->
      <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success text-center" role="alert">
          Pesanan Anda berhasil dibuat!
        </div>
      <?php endif; ?>

      <form action="process_order.php" method="post" class="w-50 mx-auto">
        <div class="mb-3">
          <label for="name" class="form-label" style="color:#FFD700;">Nama:</label>
          <input type="text" id="name" name="name" class="form-control" required style="background:#181818; color:#FFD700; border:1px solid #FFD700;" />
        </div>
        <div class="mb-3">
          <label for="phone" class="form-label" style="color:#FFD700;">Nomor Telepon:</label>
          <input type="tel" id="phone" name="phone" class="form-control" required style="background:#181818; color:#FFD700; border:1px solid #FFD700;" />
        </div>
        <div class="mb-3">
          <label for="menu" class="form-label" style="color:#FFD700;">Pilih Menu:</label>
          <select id="menu" name="menu" class="form-select" required style="background:#181818; color:#FFD700; border:1px solid #FFD700;">
            <?php
            $menu_query = mysqli_query($conn, "SELECT id_menu, nama_menu, harga FROM menu WHERE sold_out = 0");
            while ($menu = mysqli_fetch_assoc($menu_query)) {
              echo '<option value="' . htmlspecialchars($menu['id_menu']) . '">' . htmlspecialchars($menu['nama_menu']) . ' - Rp ' . number_format($menu['harga'], 0, ',', '.') . '</option>';
            }
            ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="quantity" class="form-label" style="color:#FFD700;">Jumlah:</label>
          <input type="number" id="quantity" name="quantity" min="1" required class="form-control" style="background:#181818; color:#FFD700; border:1px solid #FFD700;" />
        </div>
        <div class="mb-3">
          <label for="pickup-time" class="form-label" style="color:#FFD700;">Waktu Pengambilan:</label>
          <input type="time" id="pickup-time" name="pickup-time" required class="form-control" style="background:#181818; color:#FFD700; border:1px solid #FFD700;" />
        </div>
        <div class="mb-3">
          <label for="notes" class="form-label" style="color:#FFD700;">Catatan Tambahan:</label>
          <textarea id="notes" name="notes" rows="4" class="form-control" style="background:#181818; color:#FFD700; border:1px solid #FFD700;"></textarea>
        </div>
        <div class="mb-3">
          <label for="payment" class="form-label" style="color:#FFD700;">Proof of Payment:</label>
          <input type="file" id="payment" name="payment" class="form-control" style="background:#181818; color:#FFD700; border:1px solid #FFD700;" required />
        </div>
        <button type="submit" class="btn btn-warning w-100 fw-bold" style="color:#181818;">Pesan Sekarang</button>
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
</body>

</html>