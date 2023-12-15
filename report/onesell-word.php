<?php
include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/UserData.php";
include "../core/app/model/SellData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/OperationTypeData.php";
include "../core/app/model/ProductData.php";

require_once '../tcpdf/tcpdf.php';


ob_start();
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Oscar Cuevas');
$pdf->SetTitle('Remision Electrónica ');
$pdf->SetSubject('Remisión Electrónica');
$pdf->SetKeywords('TCPDF, PDF, factura, test, guía');

// set default header data
$pdf->SetHeaderData('ruta/imagen1.png', PDF_HEADER_LOGO_WIDTH, 'Remisión Electrónica', 'Generado por: Oscar Cuevas');

// set header and footer fonts
$pdf->setHeaderFont(Array('helvetica', 'B', 12));
$pdf->setFooterFont(Array('helvetica', '', 10));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    //$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 12);

$pdf->AddPage();

// Configurar la zona horaria
date_default_timezone_set('America/Bogota');

$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);


if ($sell->person_id != null) {
    $client = $sell->getPerson();
}

$user = $sell->getUser();


$html = '<h1 style="text-align: right; color: #337ab7;">RESUMEN DE VENTA</h1>';

$html .= '<p style="text-align: right;">Fecha de Emisión: ' . date('Y-m-d') . '</p>';
$html .= '<table border="1" style="border-collapse: collapse; width: 100%;">';
$html .= '<tr style="background-color: #f5f5f5;"><td><strong>ATENDIDO POR</strong></td><td>' . $user->name . ' ' . $user->lastname . '</td></tr>';
if ($sell->person_id != null) {
    // Cliente
    $html .= '<tr style="background-color: #f5f5f5;">';
    $html .= '<td><strong>CLIENTE</strong></td>';
    $html .= '<td colspan="4">' . $client->name . ' ' . $client->lastname . '</td>';
    $html .= '</tr>';
    // Dirección
    $html .= '<tr style="background-color: #f5f5f5;">';
    $html .= '<td><strong>DIRECCIÓN</strong></td>';
    $html .= '<td colspan="4">' .  $client->address1  . '</td>';
    $html .= '</tr>';
    // Correo Electrónico
    $html .= '<tr style="background-color: #f5f5f5;">';
    $html .= '<td><strong>CORREO ELECTRÓNICO</strong></td>';
    $html .= '<td colspan="4">' .  $client->email1 . '</td>';
    $html .= '</tr>';

    // Teléfono
    $html .= '<tr style="background-color: #f5f5f5;">';
    $html .= '<td><strong>NIT/CEDULA</strong></td>';
    $html .= '<td colspan="4">' .  $client->phone2 . '</td>';
    $html .= '</tr>';


    // Teléfono
    $html .= '<tr style="background-color: #f5f5f5;">';
    $html .= '<td><strong>TELÉFONO</strong></td>';
    $html .= '<td colspan="4">' .  $client->phone1 . '</td>';
    $html .= '</tr>';
    $html .= '<br></br>';
}

$html .= '</table>';
$html .= '<br>';
$html .= '<br>';
$html .= '<table border="1" style="border-collapse: collapse; width: 100%; font-size: 12px;">'; // Ajusta el tamaño de la fuente aquí
$html .= '<tr style="background-color: #337ab7; color: #fff;">';
$html .= '<th class="small-column">Referencia Producto</th>';
$html .= '<th style="font-size: 12px;">Cantidad</th>'; // Ajusta el tamaño de la fuente aquí
$html .= '<th style="font-size: 12px;">Nombre del producto</th>'; // Ajusta el tamaño de la fuente aquí
$html .= '<th style="font-size: 12px;">Precio Unitario</th>'; // Ajusta el tamaño de la fuente aquí
$html .= '<th style="font-size: 12px;">Total</th>'; // Ajusta el tamaño de la fuente aquí
$html .= '</tr>';
$total = 0;

foreach ($operations as $operation) {
    $product = $operation->getProduct();
    $html .= '<tr>';
    $html .= '<td class="small-column">' . $product->barcode . '</td>'; // Clase de estilo para la columna más pequeña
    $html .= '<td>' . $operation->q . '</td>';
    $html .= '<td>' . $product->name . '</td>';
    $html .= '<td>$' . number_format($product->price_out, 2, ".", ",") . '</td>';
    $html .= '<td>$' . number_format($operation->q * $product->price_out, 2, ".", ",") . '</td>';
    $html .= '</tr>';
    $total += $operation->q * $product->price_out;
}

$html .= '</table>';
$html .= '<br>';
$html .= '<p style="text-align: right;"><strong>Total:</strong> $' . number_format($total, 2, ".", ",") . '</p>';

// Agregar el contenido HTML al PDF
$pdf->writeHTML($html);
// Limpiar el búfer de salida
ob_end_clean();

// Generar un código de barras
$style = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => true,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false,
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 8,
    'stretchtext' => 4
);


// Salvar el PDF en un archivo (o mostrarlo en el navegador con 'I')
$pdf->Output('Resumen_Compra' . '.pdf', 'I');
// Guardar el PDF en un archivo
$filename = "Resumen_Compra" . time() . ".pdf";
$pdf->Output($filename, 'I');

// Descargar el PDF
header("Content-Disposition: attachment; filename=$filename");
readfile($filename);
unlink($filename);  // Eliminar el archivo
ob_end_flush();
?>
