<?php

class Book
{
    public $judul;
    public $pengarang;
    public $tahunTerbit;
    public $genre;

    public function __construct($judul, $pengarang, $tahunTerbit, $genre)
    {
        $this->judul = $judul;
        $this->pengarang = $pengarang;
        $this->tahunTerbit = $tahunTerbit;
        $this->genre = $genre;
    }

    public function getDetailBuku()
    {
        return "Judul: {$this->judul}, 
                Pengarang: {$this->pengarang}, 
                Tahun Terbit: {$this->tahunTerbit}, 
                Genre: {$this->genre}";
    }
}