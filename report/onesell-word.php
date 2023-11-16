<?php
include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/UserData.php";
include "../core/app/model/SellData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/OperationTypeData.php";
include "../core/app/model/ProductData.php";

require_once '../tcpdf/tcpdf.php';

// Crear un nuevo objeto TCPDF
$pdf = new TCPDF();
$pdf->AddPage();

$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
if ($sell->person_id != null) {
    $client = $sell->getPerson();
}


$user = $sell->getUser();

$html = '<h1 style="text-align: right;">RESUMEN DE VENTA</h1>';

$html .= '<table border="1" style="border-collapse: collapse;">';
$html .= '<tr><td>Atendido por</td><td>' . $user->name . ' ' . $user->lastname . '</td></tr>';
if ($sell->person_id != null) {
    $html .= '<tr><td>Cliente</td><td>' . $client->name . ' ' . $client->lastname . '</td></tr>';
}
$html .= '</table>';
$html .= '<br>';

$html .= '<table border="1" style="border-collapse: collapse;">';
$html .= '<tr><th>Codigo</th><th>Cantidad</th><th>Nombre del producto</th><th>P.U</th><th>Total</th></tr>';
$total = 0;
foreach ($operations as $operation) {
    $product = $operation->getProduct();
    $html .= '<tr>';
    $html .= '<td>' . $product->id . '</td>';
    $html .= '<td>' . $operation->q . '</td>';
    $html .= '<td>' . $product->name . '</td>';
    $html .= '<td>$' . number_format($product->price_out, 2, ".", ",") . '</td>';
    $html .= '<td>$' . number_format($operation->q * $product->price_out, 2, ".", ",") . '</td>';
    $html .= '</tr>';
    $total += $operation->q * $product->price_out;
}
$html .= '</table>';
$html .= '<br>';


$html .= '<p>Total: $' . number_format($total, 2, ".", ",") . '</p>';

// Agregar el contenido HTML al PDF
$pdf->writeHTML($html);

// Salvar el PDF en un archivo (o mostrarlo en el navegador con 'I')
$pdf->Output('Resumen_Compra' . time() . '.pdf', 'I');
?>
