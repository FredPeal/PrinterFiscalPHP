<?php
require_once '../vendor/autoload.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

try {
    $data = (new PrinterFiscal\Controllers\PrinterController)->index();
    // echo json_response('Impresion exitossa');
} catch (Exception $e) {
    echo $e->getMessage();
}
