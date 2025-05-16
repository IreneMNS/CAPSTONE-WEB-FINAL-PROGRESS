<?php
require_once 'koneksi.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $menu_id = $_POST['menu'];
    $quantity = $_POST['quantity'];
    $pickup_time = $_POST['pickup-time'];
    $notes = $_POST['notes'];

    // Insert order into the database
    $stmt = $conn->prepare("INSERT INTO ordermenu (nama_pelanggan, no_hp, id_menu, jumlah, waktu_pengambilan, catatan) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiiis", $name, $phone, $menu_id, $quantity, $pickup_time, $notes);

if ($stmt->execute()) {
    // Redirect back to the menu page with a success message
    header('Location: menu.php?success=1');
    exit;
} else {
    echo "Error: " . $stmt->error;
}

    // Close the statement
    $stmt->close();
}
?>