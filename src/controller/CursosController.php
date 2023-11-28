<?php
namespace Arcoinformatica\IntegracaoMoodle\controller;

use Arcoinformatica\IntegracaoMoodle\config\Token;
use MoodleRest;

require "./src/bootstrap.php";

class CursosController{
    public static function getCursos(){
        $moodleRest = new MoodleRest($_ENV['URL_WEBSERVICE'],Token::generate());
        $cursos = $moodleRest->request("core_course_get_courses");

        print_r($cursos);
    }
}