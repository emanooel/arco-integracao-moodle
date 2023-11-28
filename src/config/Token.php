<?php
namespace Arcoinformatica\IntegracaoMoodle\config;

use Arcoinformatica\IntegracaoMoodle\utils\Request;

require "./src/bootstrap.php";

class Token{
    public static function generate(){

        // var_dump($_ENV);exit;
        $endpoint_login = "login/token.php";
        $url = $_ENV['URL'].$endpoint_login.'?username='.$_ENV['_USERNAME'].'&password='.$_ENV['PASSWORD'].'&service='.$_ENV['SERVICE'];
        $request = new Request($url, 'GET');
        $response = $request->send();

        $token = json_decode($response)->token;

        return $token;
    }
}