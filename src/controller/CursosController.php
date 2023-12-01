<?php
namespace Arcoinformatica\IntegracaoMoodle\controller;

use Arcoinformatica\IntegracaoMoodle\config\Token;
use MoodleRest;

require "./src/bootstrap.php";

class CursosController{
    public static function getCursos(){
        $moodleRest = new MoodleRest($_ENV['URL_WEBSERVICE'],Token::generate());
        $cursos = $moodleRest->request("core_course_get_courses");

        //print_r($cursos);

        return $cursos;
    }

    public static function getCursoById(int $id){
        $moodleRest = new MoodleRest($_ENV['URL_WEBSERVICE'],Token::generate());
        $curso = $moodleRest->request("core_course_get_courses_by_field", array(
            'field' => 'id',
            'value' => $id
        ));

        return $curso;
    }



}