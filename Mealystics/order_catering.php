<?php

require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $package = $_POST['package']; // Now sending id_menu
    $porsi = $_POST['porsi'];
    $message = $_POST['message'];

    // Handle file upload for proof of payment
    $payment_file = null;
    if (isset($_FILES['payment']) && $_FILES['payment']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/payments/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $payment_file = $upload_dir . basename($_FILES['payment']['name']);
        if (!move_uploaded_file($_FILES['payment']['tmp_name'], $payment_file)) {
            die("Failed to upload file.");
        }
    } else {
        die("File upload error: " . $_FILES['payment']['error']);
    }

    // Insert order into the database
    $stmt = $conn->prepare("INSERT INTO ordercatering (nama_pemesan, email, no_hp, id_paket, catatan, bukti_pembayaran, jumlah_porsi) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssissi", $name, $email, $phone, $package, $message, $payment_file, $porsi);

    if ($stmt->execute()) {
        // WhatsApp redirection
        $whatsappNumber = "6281545063864"; // Replace with your WhatsApp number
        $whatsappMessage = urlencode("Halo, saya ingin memesan catering dengan detail berikut:
- Nama: $name
- Email: $email
- No.HP: $phone
- Paket: $package
- Jumlah Porsi: $porsi
- Notes Tambahan: $message");

        echo "<script>
            alert('Orderan anda sudah berhasil di proses!');
            window.location.href = 'https://wa.me/$whatsappNumber?text=$whatsappMessage';
        </script>";
    } else {
        die("Database error: " . $stmt->error);
    }
}
?>