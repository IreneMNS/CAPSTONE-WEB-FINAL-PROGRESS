<?php

// Start session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'koneksi.php'; // Include database connection

// Fetch orders from the database
$order_query = mysqli_query($conn, "SELECT o.id_order, o.nama_pelanggan, o.no_hp, m.nama_menu, o.jumlah, o.waktu_pengambilan, o.catatan, o.bukti_pembayaran, o.tanggal_order 
                                    FROM ordermenu o 
                                    JOIN menu m ON o.id_menu = m.id_menu 
                                    ORDER BY o.tanggal_order DESC");


$order_catering_query = mysqli_query($conn, "
    SELECT oc.id_order, oc.nama_pemesan, oc.no_hp, mc.nama_paket, oc.jumlah_porsi, oc.catatan, oc.bukti_pembayaran, oc.tanggal_order, mc.harga
    FROM ordercatering oc
    JOIN menu_catering mc ON oc.id_paket = mc.id_menu
    ORDER BY oc.tanggal_order DESC
");


// Fetch data for the bar chart
$chart_query = mysqli_query($conn, "SELECT m.nama_menu, COUNT(o.id_order) AS total_orders 
                                    FROM ordermenu o 
                                    JOIN menu m ON o.id_menu = m.id_menu 
                                    GROUP BY m.nama_menu 
                                    ORDER BY total_orders DESC");

$chart_data = [];
while ($row = mysqli_fetch_assoc($chart_query)) {
    $chart_data[] = $row;
}


$income_query = mysqli_query($conn, "
    SELECT mc.nama_paket, SUM(oc.jumlah_porsi * mc.harga) AS total_income
    FROM ordercatering oc
    JOIN menu_catering mc ON oc.id_paket = mc.id_menu
    GROUP BY mc.nama_paket
    ORDER BY total_income DESC
");
$income_data = [];
while ($row = mysqli_fetch_assoc($income_query)) {
    $income_data[] = $row;
}


?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management - WarOenk Kangmas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            color:rgb(19, 18, 13);
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
            color: #181818 !important; /* warna hitam */
        }
    </style>
</head>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3" style="width: 250px; background:#232323; min-height: 100vh; border-right: 2px solid #FFD700;">
        <h2 class="text-warning mb-4 text-center">Admin Panel</h2>
        <a href="dashboard.php" class="nav-link text-warning fw-bold mb-2">🏠 Dashboard</a>
        <a href="order_management.php" class="nav-link text-warning fw-bold mb-2 active">📦 Orders</a>
        <a href="menu_management.php" class="nav-link text-warning fw-bold mb-2">📋 Menu</a>
        <a href="fastapi.php" class="nav-link text-warning fw-bold mb-2">⚙️ Fast API</a>
        <a href="?logout=true" class="btn btn-danger mt-4 w-100">Logout</a>
    </div>

    <body style="background:#181818;">
        <div class="container my-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="fw-bold" style="color:#FFD700;">Order Management</h1>
                <a href="dashboard.php" class="btn btn-warning fw-bold" style="color:#181818; border-radius:8px;">Back to Dashboard</a>
            </div>
            </header>

            <div class="container py-4">
                <!-- Bar Chart Section -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card shadow-sm mb-4" style="background:#232323; border:1.5px solid #FFD700; border-radius:16px;">
                            <div class="card-body">
                                <h5 class="card-title" style="color:#FFD700;">Order Statistics</h5>
                                <canvas id="orderChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card shadow-sm mb-4" style="background:#232323; border:1.5px solid #FFD700; border-radius:16px;">
                            <div class="card-body">
                                <h5 class="card-title" style="color:#FFD700;">Income Statistics (Order Catering)</h5>
                                <canvas id="incomeChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders Table -->
                <div class="card shadow-sm mb-4" style="background:#232323; border:1.5px solid #FFD700; border-radius:16px;">
                    <div class="card-body">
                        <h5 class="card-title" style="color:#FFD700;">Customer Orders</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" style="color:#fffbe7;">
                                <thead style="background:#FFD700; color:#181818;">
                                    <tr>
                                        <th>#</th>
                                        <th>Customer Name</th>
                                        <th>Phone</th>
                                        <th>Menu</th>
                                        <th>Quantity</th>
                                        <th>Pickup Time</th>
                                        <th>Notes</th>
                                        <th>Payment Proof</th>
                                        <th>Order Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (mysqli_num_rows($order_query) > 0) {
                                        while ($order = mysqli_fetch_assoc($order_query)) {
                                            echo '<tr>
                                            <td>' . htmlspecialchars($order['id_order']) . '</td>
                                            <td>' . htmlspecialchars($order['nama_pelanggan']) . '</td>
                                            <td>' . htmlspecialchars($order['no_hp']) . '</td>
                                            <td>' . htmlspecialchars($order['nama_menu']) . '</td>
                                            <td>' . htmlspecialchars($order['jumlah']) . '</td>
                                            <td>' . ($order['waktu_pengambilan'] ? htmlspecialchars($order['waktu_pengambilan']) : '-') . '</td>
                                            <td>' . ($order['catatan'] ? htmlspecialchars($order['catatan']) : '-') . '</td>
                                            <td>' . ($order['bukti_pembayaran'] ? '<a href="uploads/' . htmlspecialchars($order['bukti_pembayaran']) . '" target="_blank" style="color:#FFD700;">View</a>' : '-') . '</td>
                                            <td>' . htmlspecialchars($order['tanggal_order']) . '</td>
                                        </tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="9" class="text-center" style="color:#FFD700;">No orders found</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Catering Orders Table -->
                <div class="card shadow-sm mb-4" style="background:#232323; border:1.5px solid #FFD700; border-radius:16px;">
                    <div class="card-body">
                        <h5 class="card-title" style="color:#FFD700;">Order Catering</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" style="color:#fffbe7;">
                                <thead style="background:#FFD700; color:#181818;">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Pemesan</th>
                                        <th>Phone</th>
                                        <th>Paket</th>
                                        <th>Jumlah Porsi</th>
                                        <th>Catatan</th>
                                        <th>Bukti Pembayaran</th>
                                        <th>Tanggal Order</th>
                                        <th>Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $base_url = 'uploads/';
                                    if (mysqli_num_rows($order_catering_query) > 0) {
                                        while ($order = mysqli_fetch_assoc($order_catering_query)) {
                                            $total_harga = $order['jumlah_porsi'] * $order['harga'];
                                            echo '<tr>
                                                <td>' . htmlspecialchars($order['id_order']) . '</td>
                                                <td>' . htmlspecialchars($order['nama_pemesan']) . '</td>
                                                <td>' . htmlspecialchars($order['no_hp']) . '</td>
                                                <td>' . htmlspecialchars($order['nama_paket']) . '</td>
                                                <td>' . htmlspecialchars($order['jumlah_porsi']) . '</td>
                                                <td>' . ($order['catatan'] ? htmlspecialchars($order['catatan']) : '-') . '</td>
                                                <td>' . ($order['bukti_pembayaran']
                                                        ? '<a href="mealis/uploads/' . htmlspecialchars($order['bukti_pembayaran']) . '" target="_blank" style="color:#FFD700;">View</a>'
                                                        : '-') . '</td>
                                                <td>' . htmlspecialchars($order['tanggal_order']) . '</td>
                                                <td>Rp ' . number_format($total_harga, 0, ',', '.') . '</td>
                                            </tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="9" class="text-center" style="color:#FFD700;">No catering orders found</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Prepare data for the chart
            const chartLabels = <?php echo json_encode(array_column($chart_data, 'nama_menu')); ?>;
            const chartData = <?php echo json_encode(array_column($chart_data, 'total_orders')); ?>;
            const incomeLabels = <?php echo json_encode(array_column($income_data, 'nama_paket')); ?>;
            const incomeValues = <?php echo json_encode(array_column($income_data, 'total_income')); ?>;

            // Create the bar chart
            const ctx = document.getElementById('orderChart').getContext('2d');
            const orderChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Total Orders',
                        data: chartData,
                        backgroundColor: 'rgba(255, 193, 7, 0.7)',
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Menu Items'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Orders'
                            }
                        }
                    }
                }
            });


            // Create the income chart
            const incomeCtx = document.getElementById('incomeChart').getContext('2d');
            const incomeChart = new Chart(incomeCtx, {
                type: 'bar',
                data: {
                    labels: incomeLabels,
                    datasets: [{
                        label: 'Total Income (Rp)',
                        data: incomeValues,
                        backgroundColor: 'rgba(255, 193, 7, 0.7)',
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Paket Catering'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Pendapatan (Rp)'
                            }
                        }
                    }
                }
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>