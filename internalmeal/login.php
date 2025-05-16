<?php
require_once 'koneksi.php'; // Include database connection file
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Query to check admin credentials using mysqli
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    // Compare password directly (no hashing)
    if ($admin && $inputPassword === $admin['password']) {
        // Store admin ID in session and redirect to dashboard
        $_SESSION['admin_id'] = $admin['id_admin'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Username atau password salah.';
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - WarOenk Kangmas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body style="background:#181818; color:#FFD700;">

    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card shadow-lg" style="background:#232323; border:1.5px solid #FFD700; border-radius:16px; padding:20px; width: 100%; max-width: 400px;">
            <h2 class="text-center mb-4" style="color:#FFD700;">Admin Login</h2>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label" style="color:#FFD700;">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username" required style="background:#181818; color:#FFD700; border:1px solid #FFD700;">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label" style="color:#FFD700;">Password</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required style="background:#181818; color:#FFD700; border:1px solid #FFD700;">
                        <button type="button" class="btn btn-outline-warning" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn btn-warning w-100" style="color:#181818; font-weight:bold;">Login</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
        });
    </script>
</body>

</html>
