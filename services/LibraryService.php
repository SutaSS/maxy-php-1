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

    public function exportToCSV() {
        $books = $this->perpustakaan->getDaftarBuku();
        $csv = "Judul,Pengarang,Tahun Terbit,Genre,Tersedia\n";
        
        foreach ($books as $book) {
            $tersedia = $book->tersedia ? "Ya" : "Tidak";
            $csv .= "\"{$book->judul}\",\"{$book->pengarang}\",{$book->tahunTerbit},\"{$book->genre}\",{$tersedia}\n";
        }
        
        return $csv;
    }

    public function importFromCSV($filePath) {
        $file = fopen($filePath, 'r');
        if (!$file) {
            return "File tidak dapat dibuka.";
        }

        $header = fgetcsv($file);
        $imported = 0;

        while ($row = fgetcsv($file)) {
            if (count($row) >= 4) {
                $judul = trim($row[0]);
                $pengarang = trim($row[1]);
                $tahunTerbit = trim($row[2]);
                $genre = trim($row[3]);
                $tersedia = isset($row[4]) && strtolower(trim($row[4])) !== 'tidak' ? true : false;

                if ($judul && $pengarang) {
                    $book = new Book($judul, $pengarang, $tahunTerbit, $genre, $tersedia);
                    $this->perpustakaan->tambahBuku($book);
                    $imported++;
                }
            }
        }

        fclose($file);
        return "Berhasil import $imported buku.";
    }
}