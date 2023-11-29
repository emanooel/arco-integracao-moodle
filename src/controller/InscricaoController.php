<?php
namespace Arcoinformatica\IntegracaoMoodle\controller;

use Arcoinformatica\IntegracaoMoodle\config\Token;
use MoodleRest;

require "./src/bootstrap.php";

class InscricaoController{
    public static function inscreverUsuarioEmCurso(int $idUsuario, int $idCurso){
        $moodleRest = new MoodleRest($_ENV['URL_WEBSERVICE'],Token::generate());
        // roleid = 5 é o papel de Estudante
        $inscricao = $moodleRest->request("enrol_manual_enrol_users", array("enrolments" => array(array("roleid" => 5, "userid" => $idUsuario, "courseid" => $idCurso))));
        
        print_r($inscricao);
    }
}