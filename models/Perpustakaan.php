<?php

class Perpustakaan
{
    public $lokasi;
    public $daftarBuku = [];

    public function __construct($lokasi)
    {
        $this->lokasi = $lokasi;
    }

    public function tambahBuku($buku)
    {
        $this->daftarBuku[] = $buku;
    }

    public function getDaftarBuku()
    {
        $hasil = "Daftar Buku di Perpustakaan {$this->lokasi}: <br><br>";

        foreach ($this->daftarBuku as $buku) {
            $hasil .= $buku->getDetailBuku() . "<br>";
        }

        return $hasil;
    }
}