<?php
require_once 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>WarOenk Kangmas</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <!-- Google Fonts for modern look -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>

  <!-- Header -->
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
            <a class="nav-link text-warning fw-semibold" href="catering.php">Catering</a>
          </li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero text-center d-flex align-items-center justify-content-center"
    style="background: linear-gradient(rgba(24,24,24,0.8), rgba(24,24,24,0.8)), url('gambar/waronk1.jpg') no-repeat center center/cover; min-height: 400px;">
    <div>
      <h1 class="display-4 fw-bold mb-3" style="color:#FFD700; letter-spacing:2px;">Taste The <span style="color:#fffbe7;">Difference</span></h1>
      <a href="menu.php" class="btn btn-warning btn-lg fw-bold px-5 py-2" style="color:#181818;">Explore Menu</a>
    </div>
  </section>

  <!-- Top Products Section -->
  <section class="top-products py-5" style="background:#232323;">
    <div class="container">
      <h2 class="text-center mb-4" style="color:#FFD700;">You may like one of our dishes:</h2>
      <div class="row g-4">
        <?php
        // Fetch top 3 best-selling menu items from the database
        $menu_query = mysqli_query($conn, "SELECT * FROM menu WHERE id_menu IN (1, 3, 6) ORDER BY jumlah_porsi DESC LIMIT 3");
        while ($menu = mysqli_fetch_assoc($menu_query)) {
          // Check if the menu item is sold out
          $sold_out = $menu['sold_out'] ? '<span class="badge bg-danger">Sold Out</span>' : '';

          echo '<div class="col-md-4">
              <div class="card shadow-sm h-100" style="background:#232323; border:1.5px solid #FFD700; border-radius:16px;">
                <img src="' . htmlspecialchars($menu["foto_menu"]) . '" class="card-img-top img-fluid product-image" alt="' . htmlspecialchars($menu["nama_menu"]) . '" style="border-radius:16px 16px 0 0; height:220px; object-fit:cover;" />
                <div class="card-body text-center">
                  <h5 class="card-title" style="color:#FFD700;">' . htmlspecialchars($menu["nama_menu"]) . '</h5>
                  <p class="card-text" style="color:#fffbe7;">' . htmlspecialchars($menu["deskripsi"]) . '</p>
                  <p class="card-text" style="color:#FFD700; font-weight:bold;">Rp ' . number_format($menu["harga"], 0, ',', '.') . '</p>
                </div>
              </div>
            </div>';
        }
        ?>
      </div>
      <div class="text-center mt-5">
        <a href="menu.php" class="btn btn-warning fw-bold px-4 py-2" style="color:#181818; border-radius:8px;">See More</a>
      </div>
    </div>
  </section>

  <!-- Testimonials Section Tanpa Carousel -->
  <div class="container px-4">
    <h2 class="text-center mb-4 mt-5" style="color:#FFD700;">Feedback from customer</h2>
    <div class="row justify-content-center g-4">
      <?php
      // Fetch only 6 feedback entries from the database
      $query = "SELECT f.name, f.rating, f.message, m.foto_menu 
            FROM feedback f 
            JOIN menu m ON f.food_id = m.id_menu 
            LIMIT 6";
      $result = $conn->query($query);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo '
      <div class="col-md-4 d-flex">
        <div class="testimonial-card-modern position-relative w-100 h-100 p-4" style="background:#232323; border:1.5px solid #FFD700; border-radius:16px; box-shadow:0 8px 24px rgba(255,215,0,0.10); overflow:hidden; margin-bottom:16px;">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <span class="testimonial-author-modern" style="color:#fffbe7;font-weight:600;font-size:1.08rem;">' . htmlspecialchars($row["name"]) . '</span>
            </div>
            <div class="testimonial-rating-modern">';
          for ($i = 0; $i < $row["rating"]; $i++) {
            echo '<span class="star" style="color:#FFD700;font-size:1.1rem;">&#9733;</span>';
          }
          for ($i = $row["rating"]; $i < 5; $i++) {
            echo '<span class="star star-off" style="color:#555;font-size:1.1rem;">&#9733;</span>';
          }
          echo '  </div>
          </div>
          <div class="testimonial-text-modern" style="color:#fffbe7;font-size:1.02rem;min-height:60px;margin-top:10px;margin-bottom:0;">
            "' . htmlspecialchars($row["message"]) . '"
          </div>
          <img src="' . htmlspecialchars($row["foto_menu"]) . '" alt="dish" class="testimonial-img-modern" style="position:absolute;bottom:18px;right:18px;width:56px;height:56px;border-radius:12px;object-fit:cover;border:2px solid #FFD700;box-shadow:0 2px 8px rgba(0,0,0,0.12);background:#181818;z-index:2;" />
        </div>
      </div>';
        }
      } else {
        echo '<p class="text-center text-warning">No feedback available yet.</p>';
      }
      ?>
    </div>

    <!-- Link to View More Feedback -->
    <div class="text-center mt-4">
      <a href="feedback.php" class="btn btn-warning" style="border-radius: 8px;">View More Feedback</a>
    </div>

    <!-- Feedback Form -->
    <h2 class="text-center mb-4 mt-5" style="color:#FFD700;">Feedback</h2>
    <div class="container mb-5"> <!-- Tambahkan mb-5 di sini -->
      <div class="row g-4 justify-content-center align-items-center" style="min-height: 350px;">
        <div class="col-md-6 d-flex justify-content-center">
          <div class="card w-100" style="max-width: 450px; background:#232323; border:1.5px solid #FFD700; border-radius:16px;">
            <div class="card-body">
              <h5 class="card-title text-center" style="color:#FFD700;">Share Your Experience</h5>
              <form action="feedback.php" method="POST">
                <div class="mb-3">
                  <label for="name" class="form-label" style="color:#FFD700;">Name:</label>
                  <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label for="rating" class="form-label" style="color:#FFD700;">Rating:</label>
                  <select name="rating" id="rating" class="form-select" required>
                    <option value="1">1 Star</option>
                    <option value="2">2 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="5">5 Stars</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="food_id" class="form-label" style="color:#FFD700;">Food:</label>
                  <select name="food_id" id="food_id" class="form-select" required>
                    <option value="1">Nasi Ayam Bakar</option>
                    <option value="2">Nasi Daging Lada Hitam</option>
                    <option value="3">Nasi Ayam Kulit</option>
                    <option value="4">Nasi Ayam Katsu</option>
                    <option value="5">Lalapan Nila Bakar</option>
                    <option value="6">Nasi Ayam Goreng</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="experience" class="form-label" style="color:#FFD700;">Experience:</label>
                  <textarea name="experience" id="experience" class="form-control" rows="4" required></textarea>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-warning" style="border-radius: 8px;">Send</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="successModalLabel">Feedback Submitted</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Thank you for your feedback! We appreciate your input and will use it to improve our services.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

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
      // Show the success modal if feedback was submitted
      <?php if ($feedback_success): ?>
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
      <?php endif; ?>
    </script>

    </script>
</body>

</html>