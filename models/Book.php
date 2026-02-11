class Book {
    public $judul;
    public $penulis;
    public $tersedia;

    public function __construct($judul, $penulis, $tersedia = true) {
        $this->judul = $judul;
        $this->penulis = $penulis;
        $this->tersedia = $tersedia;
    }
}