<?php

require_once('../tcpdf/tcpdf.php');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


// Crear un nuevo objeto TCPDF
$pdf = new TCPDF();

// Agregar una página
$pdf->AddPage();

// Configurar fuente y tamaño
$pdf->SetFont('times', 'N', 12);

// Agregar contenido
$pdf->Cell(0, 10, '¡Hola, este es un documento PDF generado con TCPDF!', 0, 1, 'C');

// Salvar el PDF en un archivo (o mostrarlo en el navegador con 'D')
//$pdf->Output('example.pdf', 'F');
$pdf->Output('example.pdf', 'I');


echo 'PDF generado con éxito!';

?>