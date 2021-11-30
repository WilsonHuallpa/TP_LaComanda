<?php
require_once('./fpdf/fpdf.php');

class PDF extends FPDF {
// Cabecera de página
    function Header() {
        // Arial bold 15
        $this->SetFont('Arial','B',18);
        // Movernos a la derecha
        $this->Cell(60);
        // Título
        $this->Cell(70,10,'Reporte de Pedidos',0,0,'C');
        // Salto de línea
        $this->Ln(20);
    
        $this->cell(30,10,'fecha',1,0,'C',0);
        $this->cell(40,10,'productos',1,0,'C',0);
        $this->cell(30,10,'cantidad',1,1,'C',0);
    }

// Pie de página
    function Footer() {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,utf8_decode('pagina ').$this->PageNo().'/{nb}',0,0,'C');
    }
}

?>