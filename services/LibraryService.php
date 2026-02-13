<?php

class LibraryService {
    private $perpustakaan;

    public function __construct(Perpustakaan $perpustakaan) {
        $this->perpustakaan = $perpustakaan;
    }

    public function pinjamBuku($judul) {
        foreach ($this->perpustakaan->getDaftarBuku() as $buku) {
            if ($buku->judul === $judul && $buku->tersedia) {
                $buku->tersedia = false;
                return "Buku berhasil dipinjam.";
            }
        }
        return "Buku tidak tersedia atau sudah dipinjam.";
    }

    public function kembalikanBuku($judul) {
        foreach ($this->perpustakaan->getDaftarBuku() as $buku) {
            if ($buku->judul === $judul && !$buku->tersedia) {
                $buku->tersedia = true;
                return "Buku berhasil dikembalikan.";
            }
        }
        return "Buku sudah tersedia atau tidak ditemukan.";
    }
}