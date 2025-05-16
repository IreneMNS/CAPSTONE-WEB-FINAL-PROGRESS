<?php
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $rating = $_POST['rating'];
    $food_id = $_POST['food_id'];
    $experience = $_POST['experience'];

    // Validate input
    if (!empty($name) && !empty($rating) && !empty($food_id) && !empty($experience)) {
        // Save feedback to the database
        $stmt = $conn->prepare("INSERT INTO feedback (name, food_id, message, rating) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sisi", $name, $food_id, $experience, $rating);

        if ($stmt->execute()) {
            // Redirect back to index.php with a success flag
            header('Location: index.php?feedback_success=1');
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "All fields are required!";
    }
}
?>

<?php

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Feedback - WarOenk Kangmas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light"></body>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Back Button -->
    <a href="javascript:history.back()" class="back-button btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <body>
        <header class="py-3 border-bottom border-warning" style="background:#181818;">
            <div class="container">
                <h1 class="text-warning text-center">All Feedback</h1>
            </div>
        </header>

        <div class="container my-5">
            <div class="row g-4 justify-content-center">
                <?php
                // Fetch all feedback from the database
                $query = "SELECT f.name, f.rating, f.message, m.foto_menu 
                FROM feedback f 
                JOIN menu m ON f.food_id = m.id_menu";
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
        </div>

        <footer class="py-4 border-top border-warning" style="background:#181818;">
            <div class="container text-center">
                <p class="mb-0" style="color:#fffbe7;">&copy; <?php echo date("Y"); ?> WarOenk Kangmas</p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>