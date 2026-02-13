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
        return $this->daftarBuku;
    }
}