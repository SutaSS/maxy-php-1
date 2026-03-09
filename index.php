<?php
require_once "models/Book.php";
require_once "models/Perpustakaan.php";
require_once "services/LibraryService.php";

session_start();

/*
| Inisialisasi Perpustakaan di Session
*/
if (!isset($_SESSION['perpus'])) {

    $perpus = new Perpustakaan("Jakarta");
    
    $books = require "data/books.php";
    
    foreach ($books as $book) {
        $perpus->tambahBuku($book);
    }

    $_SESSION['perpus'] = $perpus;
}

$perpus = $_SESSION['perpus'];
$library = new LibraryService($perpus);

/*
| Handle Request
*/
$message = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['pinjam'])) {
        $message = $library->pinjamBuku($_POST['judul']);
    }

    if (isset($_POST['kembali'])) {
        $message = $library->kembalikanBuku($_POST['judul']);
    }

    if (isset($_POST['download'])) {
        $csv = $library->exportToCSV();
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="data_buku.csv"');
        echo $csv;
        exit();
    }

    if (isset($_FILES['upload_file'])) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadedFile = $uploadDir . basename($_FILES['upload_file']['name']);
        if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $uploadedFile)) {
            $message = $library->importFromCSV($uploadedFile);
        } else {
            $message = "Gagal upload file.";
        }
    }

    $_SESSION['perpus'] = $perpus;
}

$books = $perpus->getDaftarBuku();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistem Manajemen Perpustakaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 40px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #2c3e50;
            color: white;
            padding: 12px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .status-available {
            color: green;
            font-weight: bold;
        }

        .status-borrowed {
            color: red;
            font-weight: bold;
        }

        button {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-pinjam {
            background-color: #27ae60;
            color: white;
        }

        .btn-kembali {
            background-color: #e74c3c;
            color: white;
        }

        .btn-pinjam:hover {
            background-color: #219150;
        }

        .btn-kembali:hover {
            background-color: #c0392b;
        }

        .btn-download {
            background-color: #3498db;
            color: white;
        }

        .btn-download:hover {
            background-color: #2980b9;
        }

        .actions-bar {
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .upload-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .upload-form input[type="file"] {
            padding: 5px;
        }

        .upload-form button {
            background-color: #2ecc71;
            color: white;
            padding: 6px 12px;
        }

        .upload-form button:hover {
            background-color: #27ae60;
        }

        .message {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Sistem Manajemen Perpustakaan</h1>

    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <div class="actions-bar">
        <form method="POST" style="margin: 0;">
            <button type="submit" name="download" class="btn-download">Download CSV</button>
        </form>

        <form method="POST" enctype="multipart/form-data" class="upload-form">
            <input type="file" name="upload_file" accept=".csv" required>
            <button type="submit" class="btn-download">Upload CSV</button>
        </form>
    </div>

    <table>
        <tr>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php foreach ($books as $book): ?>
        <tr>
            <td><?= $book->judul ?></td>
            <td><?= $book->pengarang ?></td>
            <td>
                <?php if ($book->tersedia): ?>
                    <span class="status-available">Tersedia</span>
                <?php else: ?>
                    <span class="status-borrowed">Dipinjam</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($book->tersedia): ?>
                    <form method="POST">
                        <input type="hidden" name="judul" value="<?= $book->judul ?>">
                        <button class="btn-pinjam" name="pinjam">Pinjam</button>
                    </form>
                <?php else: ?>
                    <form method="POST">
                        <input type="hidden" name="judul" value="<?= $book->judul ?>">
                        <button class="btn-kembali" name="kembali">Kembalikan</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>
</div>

</body>
</html>