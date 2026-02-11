<?php

class LibraryService {

    private $books = [];

    public function __construct($books) {
        $this->books = $books;
    }

    public function getBooks() {
        return $this->books;
    }

    public function pinjamBuku($judul) {
        foreach ($this->books as $book) {
            if ($book->judul == $judul && $book->tersedia) {
                $book->tersedia = false;
                return "Buku berhasil dipinjam.";
            }
        }
        return "Buku tidak tersedia.";
    }

    public function kembalikanBuku($judul) {
        foreach ($this->books as $book) {
            if ($book->judul == $judul && !$book->tersedia) {
                $book->tersedia = true;
                return "Buku berhasil dikembalikan.";
            }
        }
        return "Buku tidak ditemukan atau sudah tersedia.";
    }
}