<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// FASTAPI URL (without /docs)
$api_base_url = "https://web-production-c98d4.up.railway.app";

function sendDataToAPI($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['error' => $error];
    }

    curl_close($ch);

    $decoded = json_decode($result, true);
    if ($decoded === null) {
        return ['error' => 'Invalid JSON response: ' . htmlspecialchars($result)];
    }

    return $decoded;
}

$post_response = null;
$post_data_raw = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['post_submit'])) {
        $post_data_raw = trim($_POST['post_data']);
        $post_data = json_decode($post_data_raw, true);
        if ($post_data === null && $post_data_raw !== '') {
            $post_response = ['error' => 'Invalid JSON format in POST data'];
        } else {
            $full_url = $api_base_url . "/predict";
            $post_response = sendDataToAPI($full_url, $post_data);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FastAPI Prediction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: #181818;
            color: #FFD700;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: #232323;
            color: #FFD700;
            min-height: 100vh;
            padding: 20px;
            position: fixed;
            left: 0;
            top: 0;
        }
        .sidebar h2 {
            font-weight: bold;
            margin-bottom: 2rem;
        }
        .sidebar a {
            color: #FFD700;
            text-decoration: none;
            font-weight: bold;
            display: block;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
            transition: background 0.3s, color 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #FFD700;
            color: #181818;
        }
        .main-content {
            margin-left: 270px;
            padding: 40px 20px 20px 20px;
        }
        .form-label, .main-content h1, .main-content h5 {
            color: #FFD700;
        }
        .form-control, textarea {
            background: #232323;
            color: #FFD700;
            border: 1px solid #FFD700;
        }
        .form-control:focus, textarea:focus {
            background: #232323;
            color: #FFD700;
            border-color: #FFD700;
            box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
        }
        .btn-warning {
            background: #FFD700;
            color: #181818;
            font-weight: bold;
            border: none;
        }
        .btn-warning:hover {
            background: #e0c200;
            color: #181818;
        }
        pre {
            background: #232323;
            color: #FFD700;
            border-radius: 8px;
            padding: 16px;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: static;
                width: 100%;
                min-height: auto;
            }
            .main-content {
                margin-left: 0;
                padding: 20px 5px;
            }
        }
    </style>
</head>
<body>93
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="order_management.php">📦 Orders</a>
        <a href="menu_management.php">📋 Menu</a>
        <a href="fastapi.php" class="active">⚙️ Fast API</a>
        <a href="?logout=true" class="btn btn-danger mt-4 w-100">Logout</a>
    </div>
    <body style="background:#181818;">
        <div class="container my-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="fw-bold" style="color:#FFD700;">Fast API</h1>
                <a href="dashboard.php" class="btn btn-warning fw-bold" style="color:#181818; border-radius:8px;">Back to Dashboard</a>
            </div>
            <form method="post">
                <div class="mb-3">
                    <label for="post_data" class="form-label">Masukkan Data JSON</label>
                    <textarea id="post_data" name="post_data" class="form-control" rows="8" required placeholder='{"Total": 100, "PaymentMethod": 1, "Hour": 12, "DayOfWeek": 3}'><?= htmlspecialchars($post_data_raw) ?></textarea>
                </div>
                <button type="submit" name="post_submit" class="btn btn-warning w-100">Predict</button>
            </form>
            <?php if ($post_response !== null): ?>
                <h5 class="mt-4">Prediction Response:</h5>
                <pre><?= json_encode($post_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?></pre>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>