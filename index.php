<?php
session_start();

require_once "models/Book.php";
require "data/books.php";
require_once "services/LibraryService.php";

if (!isset($_SESSION['books'])) {
    $_SESSION['books'] = require "data/books.php";
}

$library = new LibraryService($_SESSION['books']);

// HANDLE FORM
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['pinjam'])) {
        $library->pinjamBuku($_POST['judul']);
    }

    if (isset($_POST['kembali'])) {
        $library->kembalikanBuku($_POST['judul']);
    }

    // Update session setelah perubahan
    $_SESSION['books'] = $library->getBooks();
}

$books = $library->getBooks();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Perpustakaan</title>
</head>
<body>

<h1>Daftar Buku</h1>

<table border="1">
<tr>
    <th>Judul</th>
    <th>Penulis</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

<?php foreach ($books as $book): ?>
<tr>
    <td><?= $book->judul ?></td>
    <td><?= $book->penulis ?></td>
    <td><?= $book->tersedia ? "Tersedia" : "Dipinjam" ?></td>
    <td>
        <?php if ($book->tersedia): ?>
            <form method="POST">
                <input type="hidden" name="judul" value="<?= $book->judul ?>">
                <button name="pinjam">Pinjam</button>
            </form>
        <?php else: ?>
            <form method="POST">
                <input type="hidden" name="judul" value="<?= $book->judul ?>">
                <button name="kembali">Kembalikan</button>
            </form>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>