<?php
require_once '../vendor/autoload.php';

try {
    return (new PrinterFiscal\Controllers\PrinterController)->index();
} catch (Exception $e) {
     return $e->getMessages();
}
