<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}
require_once 'koneksi.php';

// Load CSV data
$csvData = [];
if (($handle = fopen("finalcapstone.csv", "r")) !== false) {
    $header = fgetcsv($handle); // Read header
    while (($row = fgetcsv($handle)) !== false) {
        $csvData[] = [
            'total' => (int)$row[1],
            'payment' => (int)$row[2],
            'hour' => (int)$row[5],
        ];
    }
    fclose($handle);
}

// Prepare Total per Hour
$hourly_totals = [];
$payment_methods = [];
foreach ($csvData as $entry) {
    $hour = $entry['hour'];
    $hourly_totals[$hour] = ($hourly_totals[$hour] ?? 0) + $entry['total'];
    $payment = $entry['payment'];
    $payment_methods[$payment] = ($payment_methods[$payment] ?? 0) + 1;
}
ksort($hourly_totals); // Sort by hour
$hours = array_keys($hourly_totals);
$totals_by_hour = array_values($hourly_totals);

// Payment Method Labels Mapping
$payment_labels_map = [
    0 => 'Cash',
    1 => 'QRIS',
    2 => 'Brizzi',
    3 => 'Contactless',
    -1 => 'Debit/Credit Card',
];
$payment_labels = [];
$payment_counts = [];
foreach ($payment_methods as $method => $count) {
    $payment_labels[] = $payment_labels_map[$method] ?? 'Unknown';
    $payment_counts[] = $count;
}

// Statistik dari database
$menu_biasa_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM menu"))['total'];
$menu_catering_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM menu_catering"))['total'];
$order_biasa_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM ordermenu"))['total'];
$order_catering_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM ordercatering"))['total'];

// Menu Biasa Paling Laris
$top_menu_biasa = mysqli_query($conn, "
    SELECT m.nama_menu, SUM(o.jumlah) as total_terjual
    FROM ordermenu o
    JOIN menu m ON o.id_menu = m.id_menu
    GROUP BY o.id_menu
    ORDER BY total_terjual DESC
    LIMIT 5
");
$menu_biasa_labels = [];
$menu_biasa_data = [];
while ($row = mysqli_fetch_assoc($top_menu_biasa)) {
    $menu_biasa_labels[] = $row['nama_menu'];
    $menu_biasa_data[] = $row['total_terjual'];
}

// Menu Catering Paling Laris
$top_menu_catering = mysqli_query($conn, "
    SELECT mc.nama_paket, SUM(oc.jumlah_porsi) as total_terjual
    FROM ordercatering oc
    JOIN menu_catering mc ON oc.id_paket = mc.id_menu
    GROUP BY oc.id_paket
    ORDER BY total_terjual DESC
    LIMIT 5
");
$menu_catering_labels = [];
$menu_catering_data = [];
while ($row = mysqli_fetch_assoc($top_menu_catering)) {
    $menu_catering_labels[] = $row['nama_paket'];
    $menu_catering_data[] = $row['total_terjual'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - WarOenk Kangmas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
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
            color: rgb(19, 18, 13);
            text-decoration: none;
            font-weight: bold;
            display: block;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
            transition: background 0.3s, color 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #FFD700;
            color: #181818 !important;
        }
    </style>
</head>

<body style="background:#181818; color:#FFD700;">
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3" style="width: 250px; background:#232323; min-height: 100vh; border-right: 2px solid #FFD700;">
            <h2 class="text-warning mb-4 text-center">Admin Panel</h2>
            <a href="dashboard.php" class="nav-link text-warning fw-bold mb-2 active">🏠 Dashboard</a>
            <a href="order_management.php" class="nav-link text-warning fw-bold mb-2">📦 Orders</a>
            <a href="menu_management.php" class="nav-link text-warning fw-bold mb-2">📋 Menu</a>
            <a href="fastapi.php" class="nav-link text-warning fw-bold mb-2">⚙️ Fast API</a>
            <a href="?logout=true" class="btn btn-danger mt-4 w-100">Logout</a>
        </div>

        <!-- Main content -->
        <div class="flex-grow-1">
            <!-- Header Tanpa Gambar -->
            <div class="container mb-5">
                <div class="p-4 rounded-4 mb-4" style="background:rgba(24,24,24,0.85); text-align:center;">
                    <h1 class="fw-bold text-warning mb-2 text-shadow-hero">Dashboard Admin</h1>
                    <span class="fs-4 text-light text-shadow-hero">Selamat datang kembali, Admin!</span>
                    <div class="text-secondary fst-italic fs-6 mt-2 text-shadow-hero">
                        “Kontrol yang baik hari ini, adalah kesuksesan layanan esok hari.”
                    </div>
                </div>
            </div>

            <!-- Statistik Transaksi -->
            <div class="container mb-5">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card text-center p-4">
                            <div style="font-size:2.5rem;">💸</div>
                            <h5 class="card-title mt-2">Total Transaksi</h5>
                            <p class="card-text fs-1 fw-bold"><?= count($csvData) ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-4">
                            <div style="font-size:2.5rem;">💰</div>
                            <h5 class="card-title mt-2">Total Pendapatan</h5>
                            <p class="card-text fs-1 fw-bold">Rp <?= number_format(array_sum($totals_by_hour), 0, ',', '.') ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-4">
                            <div style="font-size:2.5rem;">🕒</div>
                            <h5 class="card-title mt-2">Total Jam Operasional</h5>
                            <p class="card-text fs-1 fw-bold"><?= count($hours) ?> Jam</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Penjualan dan Metode Pembayaran -->
            <div class="container mb-5">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card p-4">
                            <h5 class="card-title text-warning mb-4">Total Penjualan per Jam</h5>
                            <canvas id="hourlySalesChart" height="120"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card p-4">
                            <h5 class="card-title text-warning mb-4">Metode Pembayaran</h5>
                            <canvas id="paymentMethodChart" height="120"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Menu & Order -->
            <div class="container mb-5">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="card text-center p-4">
                            <div style="font-size:2.2rem;">📋</div>
                            <h5 class="card-title mt-2">Menu Biasa</h5>
                            <p class="card-text fs-2 fw-bold"><?= $menu_biasa_count ?></p>
                            <a href="menu_management.php" class="btn btn-warning w-100 mt-2">Lihat</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center p-4">
                            <div style="font-size:2.2rem;">🍱</div>
                            <h5 class="card-title mt-2">Menu Catering</h5>
                            <p class="card-text fs-2 fw-bold"><?= $menu_catering_count ?></p>
                            <a href="menu_management.php#catering" class="btn btn-warning w-100 mt-2">Lihat</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center p-4">
                            <div style="font-size:2.2rem;">🧾</div>
                            <h5 class="card-title mt-2">Order Biasa</h5>
                            <p class="card-text fs-2 fw-bold"><?= $order_biasa_count ?></p>
                            <a href="order_management.php" class="btn btn-warning w-100 mt-2">Lihat</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center p-4">
                            <div style="font-size:2.2rem;">🥡</div>
                            <h5 class="card-title mt-2">Order Catering</h5>
                            <p class="card-text fs-2 fw-bold"><?= $order_catering_count ?></p>
                            <a href="order_management.php#catering" class="btn btn-warning w-100 mt-2">Lihat</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Menu Paling Laris -->
            <div class="container mb-5">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card p-4">
                            <h5 class="card-title text-warning mb-4">Menu Biasa Paling Laris</h5>
                            <canvas id="topMenuBiasaChart" height="120"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card p-4">
                            <h5 class="card-title text-warning mb-4">Menu Catering Paling Laris</h5>
                            <canvas id="topMenuCateringChart" height="120"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Grafik Menu Biasa Paling Laris
        new Chart(document.getElementById('topMenuBiasaChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($menu_biasa_labels) ?>,
                datasets: [{
                    label: 'Jumlah Terjual',
                    data: <?= json_encode($menu_biasa_data) ?>,
                    backgroundColor: '#FFD700',
                    borderRadius: 8,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#FFD700', font: { weight: 'bold' } }, grid: { color: '#333' } },
                    y: { beginAtZero: true, ticks: { color: '#FFD700' }, grid: { color: '#333' } }
                }
            }
        });

        // Grafik Menu Catering Paling Laris
        new Chart(document.getElementById('topMenuCateringChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($menu_catering_labels) ?>,
                datasets: [{
                    label: 'Jumlah Terjual',
                    data: <?= json_encode($menu_catering_data) ?>,
                    backgroundColor: '#FFD700',
                    borderRadius: 8,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#FFD700', font: { weight: 'bold' } }, grid: { color: '#333' } },
                    y: { beginAtZero: true, ticks: { color: '#FFD700' }, grid: { color: '#333' } }
                }
            }
        });

        // Grafik Penjualan per Jam
        new Chart(document.getElementById('hourlySalesChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($hours) ?>,
                datasets: [{
                    label: 'Total Penjualan (Rp)',
                    data: <?= json_encode($totals_by_hour) ?>,
                    backgroundColor: '#33FF57',
                    borderRadius: 8,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#FFD700' }, grid: { color: '#333' } },
                    y: { beginAtZero: true, ticks: { color: '#FFD700' }, grid: { color: '#333' } }
                }
            }
        });

        // Grafik Metode Pembayaran
        new Chart(document.getElementById('paymentMethodChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($payment_labels) ?>,
                datasets: [{
                    data: <?= json_encode($payment_counts) ?>,
                    backgroundColor: ['#FFD700', '#FF5733', '#33A6FF', '#8D33FF', '#33FFCE'],
                    borderRadius: 8
                }]
            },
            options: {
                plugins: {
                    legend: { labels: { color: '#FFD700' } }
                }
            }
        });
    </script>
</body>
</html>