<?php
namespace PrinterFiscal\Controllers;

use PrinterFiscal\Library\Printer;
use PrinterFiscal\Library\Database;

class PrinterController
{
    public function index()
    {

        $data = $_POST ? $_POST : json_decode(file_get_contents('php://input'), true);

        $error = Printer::connect();
        $head = [
            'type_doc' => key_exists('type_doc', $data) ? $data['type_doc'] : 'A',
            'sucursal' => key_exists('sucursal', $data) ? $data['sucursal'] : '001',
            'caja' => key_exists('caja', $data) ? $data['caja'] : '001',
            'ncf' => $data['ncf'],
            'client_name' => key_exists('client_name', $data) ? $data['client_name'] : '',
            'client_rnc' => key_exists('client_rnc', $data) ? $data['client_rnc'] : '',
            'ncf_ref' => key_exists('ncf_ref', $data) ? $data['ncf_ref'] : ''
        ];

        $error = Printer::open($head);
        foreach ($data['items'] as $item) {
            $item = json_decode($item, true);
            $printer = Printer::setItem($item['description'], $item['cant'], $item['price'], $item['itbis']);
        }
        Printer::subTotal();

        foreach($data['payments_methods'] as $payment){
            $payment = json_decode($payment, true);
            Printer::setPayments($payment['method'], $payment['amount']);
        }

        $printer = Printer::close();
    }
}
