<?php
include "../core/autoload.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/CategoryData.php";

require_once '../tcpdf/tcpdf.php';

// Crear un nuevo documento PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Configurar información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('TuNombre'); // Cambia TuNombre por el autor deseado
$pdf->SetTitle('Productos');
$pdf->SetSubject('Listado de Productos');
$pdf->SetKeywords('TCPDF, PDF, productos, listado');

// Configurar márgenes y otras opciones
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Configurar la fuente
$pdf->SetFont('helvetica', '', 12);

// Agregar una página
$pdf->AddPage();

// Obtener la información de los productos
$products = ProductData::getAll();

// Configurar la tabla
$html = '<h1 style="text-align: right;">PRODUCTOS</h1>';
$html .= '<p style="text-align: right;">Fecha de Descarga: ' . date('Y-m-d') . '</p>';
$html .= '<table border="1" style="border-collapse: collapse; width: 100%;">';
$html .= '<tr><th>Nombre</th><th>Precio Entrada</th><th>Precio Salida</th><th>Unidad</th><th>Presentacion</th><th>Categoria</th><th>Minima en Inv.</th><th>Activo</th></tr>';

foreach ($products as $product) {
    $html .= '<tr>';
    //$html .= '<td>' . $product->id . '</td>';
    $html .= '<td>' . $product->name . '</td>';
    $html .= '<td>' . $product->price_in . '</td>';
    $html .= '<td>' . $product->price_out . '</td>';
    $html .= '<td>' . $product->unit . '</td>';
    $html .= '<td>' . $product->presentation . '</td>';
    $html .= '<td>' . ($product->category_id != null ? $product->getCategory()->name : '---') . '</td>';
    $html .= '<td>' . $product->inventary_min . '</td>';
    $html .= '<td>' . ($product->is_active ? 'Si' : 'No') . '</td>';
    $html .= '</tr>';
}

$html .= '</table>';

// Agregar el contenido HTML al PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Guardar el PDF en un archivo (o mostrarlo en el navegador con 'I')
$filename = "products-" . date('Y-m-d') . ".pdf";
$pdf->Output($filename, 'I');

// Descargar el PDF
header("Content-Disposition: attachment; filename=$filename");
readfile($filename);
unlink($filename); // Eliminar el archivo temporal
?>
