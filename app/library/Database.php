<?php 

namespace PrinterFiscal\Library;

use SQlite3;

class Database
{
    private $db;

    public function __construct()
    {
        $existDb = file_exists('printerfiscal.txt');
        print_r($existDb);
        $this->db = new SQLite3('printerfiscal.db');
        if (!$existDb) {
            $this->createTable();
        }
    }

    public function existNcf($ncf)
    {
        $ps = $this->db->prepare('SELECT * FROM sales WHERE ncf = ?');
        $ps->bindValue(1, $ncf);
        $result = $ps->execute();
        $result = $result->fetchArray(SQLITE3_NUM);
        return $result;
    }

    public function lastZ()
    {
        $ps = $this->db->prepare('SELECT CAST((julianday(CURRENT_TIMESTAMP) - julianday(created_at)) * 24 As Integer) AS conf FROM closeZ
        GROUP BY conf
        HAVING id = MAX(id)');
        $result = $ps->execute();
        $result = $result->fetchArray(SQLITE3_NUM);
        return $result[0];
    }

    public function createZ()
    {
        $this->db->exec('BEGIN');
        $this->db->query('INSERT INTO "closeZ"("log")
            VALUES ("Registro de Cierre")');
        $this->db->exec('COMMIT');  
    }

    public function createNcf($ncf)
    {
        $this->db->exec('BEGIN');
        $this->db->query('INSERT INTO "sales" ("ncf")
            VALUES ("'.$ncf.'")');
        $this->db->exec('COMMIT');
    }

    protected function createTable()
    {
        $this->db->query('CREATE TABLE IF NOT EXISTS "sales" (
            "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            "ncf" INTEGER,
            "created_at" DATETIME DEFAULT CURRENT_TIMESTAMP
        )');

        $this->db->query('CREATE TABLE IF NOT EXISTS "closeZ" (
                    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    "log" VARCHAR(255) DEFAULT NULL,
                    "created_at" DATETIME DEFAULT CURRENT_TIMESTAMP
        )');
    }
}
