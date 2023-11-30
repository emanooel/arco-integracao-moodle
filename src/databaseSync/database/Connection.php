<?php
namespace Arcoinformatica\IntegracaoMoodle\databaseSync\database;

use Exception;

require "./src/bootstrap.php";

class Connection{
    public static function conectar(){
        $conn = new Database();
        try {
            //code...
            $conn->conectar($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASS"]);
            return $conn;
        } catch (Exception $e) {
            //throw $th;
            echo $e->getMessage();
        }
    }
}