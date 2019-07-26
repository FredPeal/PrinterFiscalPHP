<?php

namespace PrinterFiscal\Controllers;

class PrinterController
{
    public function index()
    {
        $data = $_POST;
        print_r($data);
        die();
    }
}
