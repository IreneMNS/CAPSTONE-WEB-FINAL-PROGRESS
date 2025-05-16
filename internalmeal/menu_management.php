<?php
// filepath: c:\xampp\htdocs\mealystics\menu_management.php

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "mealystics");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Inisialisasi variabel untuk form makanan biasa
$nama_menu = $deskripsi = $harga = $jumlah_porsi = $foto_menu_db = "";
$id_edit = 0; // id menu yang diedit

// Inisialisasi variabel untuk form catering
$nama_paket = "";
$id_edit_catering = 0;

// Fungsi untuk upload foto menu
function uploadFotoMenu($file)
{
    $target_dir = "../Mealystics/gambar/";
    $target_file = $target_dir . basename($file["name"]);
    move_uploaded_file($file["tmp_name"], $target_file);
    return $target_file; // Kembalikan path foto yang diupload
}

// Tambah menu makanan biasa
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_menu"])) {
    $nama_menu = $_POST["nama_menu"];
    $deskripsi = $_POST["deskripsi"];
    $harga = $_POST["harga"];
    $jumlah_porsi = $_POST["jumlah_porsi"];
    $foto_menu = $_FILES["foto_menu"]["name"];

    // Upload gambar ke folder gambar/
    if ($foto_menu) {
        $target_dir = "../Mealystics/gambar/";
        $target_file = $target_dir . basename($foto_menu);
        move_uploaded_file($_FILES["foto_menu"]["tmp_name"], $target_file);
        $foto_menu_db = $target_file; // <-- ini yang dimasukkan ke database
    } else {
        $foto_menu_db = "";
    }

    $sql = "INSERT INTO menu (nama_menu, deskripsi, harga, jumlah_porsi, foto_menu) 
            VALUES ('$nama_menu', '$deskripsi', '$harga', '$jumlah_porsi', '$foto_menu_db')";
    $conn->query($sql);
    header("Location: menu_management.php");
    exit();
}

// Tambah menu catering
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_catering"])) {
    $nama_paket = $_POST["nama_paket"];
    $deskripsi = $_POST["deskripsi"];
    $harga = $_POST["harga"];
    $jumlah_porsi = $_POST["jumlah_porsi"];
    $foto_menu = $_FILES["foto_menu"]["name"];

    // Upload gambar ke folder gambar/
    if ($foto_menu) {
        $target_dir = "../Mealystics/gambar/";
        $target_file = $target_dir . basename($foto_menu);
        move_uploaded_file($_FILES["foto_menu"]["tmp_name"], $target_file);
        $foto_menu_db = $target_file; // <-- ini yang dimasukkan ke database
    } else {
        $foto_menu_db = "";
    }

    $sql = "INSERT INTO menu_catering (nama_paket, deskripsi, harga, jumlah_porsi, foto_menu) 
            VALUES ('$nama_paket', '$deskripsi', '$harga', '$jumlah_porsi', '$foto_menu_db')";
    $conn->query($sql);
    header("Location: menu_management.php");
    exit();
}

// ** PROSES UPDATE MENU MAKANAN BIASA **
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_menu"])) {
    $id_edit = (int)$_POST["id_menu"];
    $nama_menu = $conn->real_escape_string($_POST["nama_menu"]);
    $deskripsi = $conn->real_escape_string($_POST["deskripsi"]);
    $harga = (int)$_POST["harga"];
    $jumlah_porsi = (int)$_POST["jumlah_porsi"];

    // Cek upload foto baru
    if (isset($_FILES["foto_menu"]) && $_FILES["foto_menu"]["error"] == 0) {
        $foto_menu_db = uploadFotoMenu($_FILES["foto_menu"]);
        $sql = "UPDATE menu SET nama_menu='$nama_menu', deskripsi='$deskripsi', harga=$harga, jumlah_porsi=$jumlah_porsi, foto_menu='$foto_menu_db' WHERE id_menu=$id_edit";
    } else {
        // Update tanpa ganti foto
        $sql = "UPDATE menu SET nama_menu='$nama_menu', deskripsi='$deskripsi', harga=$harga, jumlah_porsi=$jumlah_porsi WHERE id_menu=$id_edit";
    }
    $conn->query($sql);
    header("Location: menu_management.php");
    exit();
}

// ** PROSES UPDATE MENU CATERING **
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_catering"])) {
    $id_edit_catering = (int)$_POST["id_menu"];
    $nama_paket = $conn->real_escape_string($_POST["nama_paket"]);
    $deskripsi = $conn->real_escape_string($_POST["deskripsi"]);
    $harga = (int)$_POST["harga"];
    $jumlah_porsi = (int)$_POST["jumlah_porsi"];

    if (isset($_FILES["foto_menu"]) && $_FILES["foto_menu"]["error"] == 0) {
        $foto_menu_db = uploadFotoMenu($_FILES["foto_menu"]);
        $sql = "UPDATE menu_catering SET nama_paket='$nama_paket', deskripsi='$deskripsi', harga=$harga, jumlah_porsi=$jumlah_porsi, foto_menu='$foto_menu_db' WHERE id_menu=$id_edit_catering";
    } else {
        $sql = "UPDATE menu_catering SET nama_paket='$nama_paket', deskripsi='$deskripsi', harga=$harga, jumlah_porsi=$jumlah_porsi WHERE id_menu=$id_edit_catering";
    }
    $conn->query($sql);
    header("Location: menu_management.php");
    exit();
}

// ** LOAD DATA UNTUK EDIT MENU MAKANAN BIASA **
if (isset($_GET["edit"])) {
    $id_edit = (int)$_GET["edit"];
    $res = $conn->query("SELECT * FROM menu WHERE id_menu=$id_edit");
    if ($res->num_rows > 0) {
        $data = $res->fetch_assoc();
        $nama_menu = $data["nama_menu"];
        $deskripsi = $data["deskripsi"];
        $harga = $data["harga"];
        $jumlah_porsi = $data["jumlah_porsi"];
        $foto_menu_db = $data["foto_menu"];
    }
}

// ** LOAD DATA UNTUK EDIT MENU CATERING **
if (isset($_GET["edit_catering"])) {
    $id_edit_catering = (int)$_GET["edit_catering"];
    $res = $conn->query("SELECT * FROM menu_catering WHERE id_menu=$id_edit_catering");
    if ($res->num_rows > 0) {
        $data = $res->fetch_assoc();
        $nama_paket = $data["nama_paket"];
        $deskripsi = $data["deskripsi"];
        $harga = $data["harga"];
        $jumlah_porsi = $data["jumlah_porsi"];
        $foto_menu_db = $data["foto_menu"];
    }
}


// Hapus menu makanan biasa
if (isset($_GET["delete"])) {
    $id_menu = $_GET["delete"];
    $conn->query("DELETE FROM menu WHERE id_menu = $id_menu");
    header("Location: menu_management.php");
    exit();
}

// Hapus menu catering
if (isset($_GET["delete_catering"])) {
    $id_menu = $_GET["delete_catering"];
    $conn->query("DELETE FROM catering WHERE id_menu = $id_menu");
    header("Location: menu_management.php");
    exit();
}

// Ambil semua menu makanan biasa
$result_menu = $conn->query("SELECT * FROM menu");

// Ambil semua menu catering
$result_catering = $conn->query("SELECT * FROM menu_catering");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Management - WarOenk Kangmas</title>
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
        <a href="order_management.php" class="nav-link text-warning fw-bold mb-2">📦 Orders</a>
        <a href="menu_management.php" class="nav-link text-warning fw-bold mb-2 active">📋 Menu</a>
        <a href="fastapi.php" class="nav-link text-warning fw-bold mb-2">⚙️ Fast API</a>
        <a href="?logout=true" class="btn btn-danger mt-4 w-100">Logout</a>
    </div>

    <body style="background:#181818;">
        <div class="container my-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="fw-bold" style="color:#FFD700;">Menu Management</h1>
                <a href="dashboard.php" class="btn btn-warning fw-bold" style="color:#181818; border-radius:8px;">Back to Dashboard</a>
            </div>

            <!-- Tab Navigation -->
            <ul class="nav nav-tabs" id="menuTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="makanan-tab" data-bs-toggle="tab" data-bs-target="#makanan" type="button" role="tab" aria-controls="makanan" aria-selected="true" style="color:#FFD700; background:#232323;">Makanan Biasa</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="catering-tab" data-bs-toggle="tab" data-bs-target="#catering" type="button" role="tab" aria-controls="catering" aria-selected="false" style="color:#FFD700; background:#232323;">Menu Catering</button>
                </li>
            </ul>

            <div class="tab-content" id="menuTabsContent">
                <!-- Tab Makanan Biasa -->
                <div class="tab-pane fade <?= ($id_edit > 0) ? 'show active' : 'show active' ?>" id="makanan" role="tabpanel" aria-labelledby="makanan-tab">
                    <h3 class="mb-3 mt-4" style="color:#FFD700;"><?= ($id_edit > 0) ? 'Edit Menu Makanan Biasa' : 'Tambah Menu Makanan Biasa' ?></h3>
                    <form method="POST" enctype="multipart/form-data" class="mb-4 mx-auto form-container">
                        <input type="hidden" name="id_menu" value="<?= $id_edit ?>">
                        <div class="mb-3">
                            <label for="nama_menu" class="form-label" style="color:#FFD700;">Nama Menu</label>
                            <input type="text" name="nama_menu" id="nama_menu" class="form-control" required style="background:#232323; color:#FFD700; border:1px solid #FFD700;" value="<?= htmlspecialchars($nama_menu) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label" style="color:#FFD700;">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" style="background:#232323; color:#FFD700; border:1px solid #FFD700;"><?= htmlspecialchars($deskripsi) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label" style="color:#FFD700;">Harga</label>
                            <input type="number" name="harga" id="harga" class="form-control" required style="background:#232323; color:#FFD700; border:1px solid #FFD700;" value="<?= htmlspecialchars($harga) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_porsi" class="form-label" style="color:#FFD700;">Jumlah Porsi</label>
                            <input type="number" name="jumlah_porsi" id="jumlah_porsi" class="form-control" required style="background:#232323; color:#FFD700; border:1px solid #FFD700;" value="<?= htmlspecialchars($jumlah_porsi) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="foto_menu" class="form-label" style="color:#FFD700;">Foto Menu (Kosongkan jika tidak ingin mengganti)</label>
                            <input type="file" name="foto_menu" id="foto_menu" class="form-control" style="background:#232323; color:#FFD700; border:1px solid #FFD700;">
                            <?php if ($foto_menu_db): ?>
                                <p>Foto saat ini:</p>
                                <img src="../Mealystics/gambar/<?= htmlspecialchars($foto_menu_db) ?>" width="150" alt="Foto Menu">
                            <?php endif; ?>
                        </div>
                        <?php if ($id_edit > 0): ?>
                            <button type="submit" name="update_menu" class="btn btn-warning w-100" style="color:#181818; font-weight:bold;">Update Menu</button>
                            <a href="menu_management.php" class="btn btn-secondary w-100 mt-2">Batal</a>
                        <?php else: ?>
                            <button type="submit" name="add_menu" class="btn btn-warning w-100" style="color:#181818; font-weight:bold;">Tambah Menu</button>
                        <?php endif; ?>
                    </form>

                    <h3 class="mb-3" style="color:#FFD700;">Daftar Menu Makanan Biasa</h3>
                    <table class="table table-bordered table-dark">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Menu</th>
                                <th>Deskripsi</th>
                                <th>Harga</th>
                                <th>Jumlah Porsi</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result_menu->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row["id_menu"] ?></td>
                                    <td><?= htmlspecialchars($row["nama_menu"]) ?></td>
                                    <td><?= htmlspecialchars($row["deskripsi"]) ?></td>
                                    <td>Rp <?= number_format($row["harga"], 0, ',', '.') ?></td>
                                    <td><?= $row["jumlah_porsi"] ?></td>
                                    <td>
                                        <?php if ($row["foto_menu"]): ?>
                                            <img src="../Mealystics/gambar/<?= htmlspecialchars($row["foto_menu"]) ?>" alt="<?= htmlspecialchars($row["nama_menu"]) ?>" width="100">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="?edit=<?= $row["id_menu"] ?>" class="btn btn-primary btn-sm">Edit</a>
                                        <a href="?delete=<?= $row["id_menu"] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus menu ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Tab Menu Catering -->
                <div class="tab-pane fade <?= ($id_edit_catering > 0) ? 'show active' : '' ?>" id="catering" role="tabpanel" aria-labelledby="catering-tab">
                    <h3 class="mb-3 mt-4" style="color:#FFD700;"><?= ($id_edit_catering > 0) ? 'Edit Menu Catering' : 'Tambah Menu Catering' ?></h3>
                    <form method="POST" enctype="multipart/form-data" class="mb-4 mx-auto form-container">
                        <input type="hidden" name="id_menu" value="<?= $id_edit_catering ?>">
                        <div class="mb-3">
                            <label for="nama_paket" class="form-label" style="color:#FFD700;">Nama Paket</label>
                            <input type="text" name="nama_paket" id="nama_paket" class="form-control" required style="background:#232323; color:#FFD700; border:1px solid #FFD700;" value="<?= htmlspecialchars($nama_paket) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label" style="color:#FFD700;">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" style="background:#232323; color:#FFD700; border:1px solid #FFD700;"><?= htmlspecialchars($deskripsi) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label" style="color:#FFD700;">Harga</label>
                            <input type="number" name="harga" id="harga" class="form-control" required style="background:#232323; color:#FFD700; border:1px solid #FFD700;" value="<?= htmlspecialchars($harga) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_porsi" class="form-label" style="color:#FFD700;">Jumlah Porsi</label>
                            <input type="number" name="jumlah_porsi" id="jumlah_porsi" class="form-control" required style="background:#232323; color:#FFD700; border:1px solid #FFD700;" value="<?= htmlspecialchars($jumlah_porsi) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="foto_menu" class="form-label" style="color:#FFD700;">Foto Menu (Kosongkan jika tidak ingin mengganti)</label>
                            <input type="file" name="foto_menu" id="foto_menu" class="form-control" style="background:#232323; color:#FFD700; border:1px solid #FFD700;">
                            <?php if ($foto_menu_db): ?>
                                <p>Foto saat ini:</p>
                                <img src="../Mealystics/gambar/<?= htmlspecialchars($foto_menu_db) ?>" width="150" alt="Foto Menu">
                            <?php endif; ?>
                        </div>
                        <?php if ($id_edit_catering > 0): ?>
                            <button type="submit" name="update_catering" class="btn btn-warning w-100" style="color:#181818; font-weight:bold;">Update Menu Catering</button>
                            <a href="menu_management.php" class="btn btn-secondary w-100 mt-2">Batal</a>
                        <?php else: ?>
                            <button type="submit" name="add_catering" class="btn btn-warning w-100" style="color:#181818; font-weight:bold;">Tambah Menu Catering</button>
                        <?php endif; ?>
                    </form>

                    <h3 class="mb-3" style="color:#FFD700;">Daftar Menu Catering</h3>
                    <table class="table table-bordered table-dark">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Paket</th>
                                <th>Deskripsi</th>
                                <th>Harga</th>
                                <th>Jumlah Porsi</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result_catering->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row["id_menu"] ?></td>
                                    <td><?= htmlspecialchars($row["nama_paket"]) ?></td>
                                    <td><?= htmlspecialchars($row["deskripsi"]) ?></td>
                                    <td>Rp <?= number_format($row["harga"], 0, ',', '.') ?></td>
                                    <td><?= $row["jumlah_porsi"] ?></td>
                                    <td>
                                        <?php if ($row["foto_menu"]): ?>
                                            <img src="../Mealystics/gambar/<?= htmlspecialchars($row["foto_menu"]) ?>" alt="<?= htmlspecialchars($row["nama_paket"]) ?>" width="100">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="?edit_catering=<?= $row["id_menu"] ?>" class="btn btn-primary btn-sm">Edit</a>
                                        <a href="?delete_catering=<?= $row["id_menu"] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus menu catering ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </header>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            const triggerTabList = document.querySelectorAll('#menuTabs button');
            triggerTabList.forEach(triggerEl => {
                const tabTrigger = new bootstrap.Tab(triggerEl);
                triggerEl.addEventListener('click', event => {
                    event.preventDefault();
                    tabTrigger.show();
                });
            });
        </script>
    </body>

</html>