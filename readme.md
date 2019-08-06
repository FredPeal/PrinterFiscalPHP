# PrinterFiscalPHP

It's a software in PHP for print in fiscal printer en Dominican Republic.

## Requirement
  PHP 5.4.0 \
  File DLL by [impresoras fiscales](http://www.impresoras-fiscales.com/republica-dominicana.html)

## Installation
git clone https://github.com/FredPeal/PrinterFiscalPHP   
composer dump

## Usage
```
let data = {
    type_doc: "A",
    sucursal: "001",
    ncf: "00000000B0200000001",
    client_name: "TEST SRL",
    client_rnc: "123456789",
    ncf_ref: "",
    items: [
            JSON.stringify({description: "Platanos Power", cant: "1", price:"18", itbis:"18.00"}),
            JSON.stringify({description: "Batata", cant: "1", price:"10", itbis:"18.00"}),
            JSON.stringify({description: "Yuca", cant: "1", price:"15", itbis:"18.00"})
    ],
    payments_methods: [
        JSON.stringify({method: "1", amount:"100"})
    ]
} 
axios.post(url, data)
```

## Keep in mind
### Types Documents 
* A = Factura a consumidor final (valor predeterminado)

* B = Factura con derecho a crédito fiscal.

* C = Nota de crédito a consumidor final

* D = Nota de crédito con derecho a crédito fiscal.

* E = Factura a consumidor final con exoneración de ITBIS.

* F = Factura con derecho a crédito fiscal con exoneración de ITBIS

* G = Nota de crédito a consumidor final con exoneración de ITBIS

* H = Nota de crédito con derecho a crédito fiscal con exoneración de ITBIS.

### Payments Methods 

* 01 Efectivo
* 02 Cheque
* 03 Transferencia
* 04 Tarjeta de crédito

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.
