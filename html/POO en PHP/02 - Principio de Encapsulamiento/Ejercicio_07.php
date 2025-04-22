<?php
class Libro {
    private $numeroPaginas;

    public function __construct($numeroPaginas) {
        $this->setPaginas($numeroPaginas);
    }

    public function getPaginas() {
        return $this->numeroPaginas;
    }

    public function setPaginas($numeroPaginas) {
        if ($numeroPaginas > 0) {
            $this->numeroPaginas = $numeroPaginas;
        }
    }
}

$libro = new Libro(150);
echo $libro->getPaginas() . "\n";
$libro->setPaginas(200);
echo $libro->getPaginas() . "\n";