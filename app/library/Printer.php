<?php

namespace PrinterFiscal\Library;

use Exception;

class Printer
{
    public static function connect()
    {
        for ($i = 1;$i < 6;$i++) {
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
        $db = new Database;
        if ($db->lastZ() >= 24 || (!$db->lastZ() && $db->lastZ() != 'integer')) {
            print_r('Error , la jornada fiscal excedio las 24 horas, Por favor realize el cierre Z' . "\n");
        }
        IF_WRITE('@TicketCancel|');

        if ($db->existNcf($head['ncf']) && strlen($head['ncf_ref']) == 0) {
            throw new Exception('Este NCF ya existe');
        }
        $nError = IF_WRITE("@TicketOpen|{$head['type_doc']}|{$head['sucursal']}|{$head['caja']}|{$head['ncf']}|{$head['client_rnc']}|40226696199|{$head['ncf_ref']}|P|0|");
        if ($nError != 0) {
            throw new Exception('Error abriendo ticket');
        }

        $db->createNcf($head['ncf']);
    }

    public static function setItem($description, $cant, $price, $itbis = '16.00', $calificadorOperacion = 'M')
    {
        $command = "@TicketItem|$description|$cant|$price|$itbis|$calificadorOperacion|N";
        $nError = IF_WRITE($command);
        if ($nError != 0) {
            throw new Exception('Error con items');
        }
    }

    public static function subTotal()
    {
        $nError = IF_WRITE('@TicketSubtotal');
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
        $nError = IF_WRITE('@TicketSubtotal');
        $nError = IF_WRITE('@TicketClose');
        $nError = IF_WRITE('@PaperCut');

        $nError = IF_CLOSE();

        $db = new Database;

        if ($db->lastZ() > 24 || (!$db->lastZ() && $db->lastZ() != 'integer')) {
            $db->createZ();
        }
    }
}
