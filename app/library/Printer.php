<?php

namespace PrinterFiscal\Library;

use Exception;

class Printer
{
    public static function connect()
    {
        for ($i=1;$i<6;$i++) {
            $nError = IF_OPEN("COM$i", 9600);
            if ($nError == 0) {
                return $nError;
            }
        }
        
        if ($nError != 0) {
            throw new Exception('Impresora Ocpada o no disponible');
        }
    }

    public static function open($head)
    {
        IF_WRITE('@TicketCancel|');
        $nError = IF_WRITE("@TicketOpen|{$head['type_doc']}|{$head['sucursal']}|{$head['caja']}|{$head['ncf']}|{$head['client_name']}|{$head['client_rnc']}|{$head['ncf_ref']}|P|0|");
        if ($nError != 0) {
            throw new Exception('Error abriendo ticket');
        }
        return $nError;
    }

    public static function setItem($description, $cant, $price, $itbis='16.00')
    {
        $command = "@TicketItem|$description|$cant|$price|$itbis|M|N";
        $nError = IF_WRITE($command);
        if ($nError != 0) {
            throw new Exception('Error con items');
        }
    }

    public static function subTotal()
    {
        $nError = IF_WRITE("@TicketSubtotal");
        if ($nError != 0) {
            throw new Exception('Error con items');
        }
    }
    
    public static function setPayments($payment, $price)
    {
        $nError = IF_WRITE("@TicketPayment|$payment|$price");
        if ($nError != 0) {
            throw new Exception('Error con items');
        }
    }

    public static function close()
    {
        $nError = IF_WRITE("@TicketSubtotal");
        $nError = IF_WRITE("@TicketPayment|1|50000.00");
      
        $nError = IF_WRITE("@TicketClose");
        $nError = IF_WRITE("@PaperCut");
      
        $nError = IF_CLOSE();
    }
}
