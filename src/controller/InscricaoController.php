<?php

namespace Arcoinformatica\IntegracaoMoodle\controller;

use Arcoinformatica\IntegracaoMoodle\config\Token;
use Arcoinformatica\IntegracaoMoodle\databaseSync\database\Connection;
use Arcoinformatica\IntegracaoMoodle\databaseSync\database\Database;
use MoodleRest;

require "./src/bootstrap.php";

class InscricaoController
{
    /**
     * Inscreve um usuário em um curso, se for bem sucedido nada é retornado.
     * Se houver algum erro, é retornado um array com os erros.
     * @param integer $idUsuario
     * @param integer $idCurso
     * @return void
     */
    public static function inscreverUsuarioEmCurso(int $idUsuario, int $idCurso){
        $moodleRest = new MoodleRest($_ENV['URL_WEBSERVICE'], Token::generate());
        
        $inscricao = $moodleRest->request("enrol_manual_enrol_users",
            array(
                "enrolments" => array(
                    array(
                        "roleid" => 5, // roleid = 5 é o papel de Estudante
                        "userid" => $idUsuario,
                        "courseid" => $idCurso
                    )
                )
            )
        );

        /**
         * Se uma inscrição for bem sucedida, nada é retornado(Isso é definido pelo moodle), então se não houver retorno,
         * é porque a inscrição foi bem sucedida.
         */
        if(!$inscricao){
            if(!self::verificaUsuarioMatriculado($idUsuario, $idCurso)){
                self::inserirUsuarioMatriculado($idUsuario, $idCurso);
            }
            return true;
        }else{
            return false;
        }
    }

    /**
     * Verifica se na base local tem um usuario matriculado em um curso
     */
    public static function verificaUsuarioMatriculado(int $idUsuario, int $idCurso){
        $db = Connection::conectar();

        $sql = "SELECT * FROM cliente_matriculado WHERE id_user_moodle = {$idUsuario} AND id_curso_moodle = {$idCurso}";

        $matricula = $db->execute($sql)->fetch();

        if($matricula){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Faz a inserção de um cliente e um curso na tabela cliente_matriculado
     */
    public static function inserirUsuarioMatriculado(int $idUsuario, int $idCurso){
        $db = Connection::conectar();

        $insert = $db->insertQuery("cliente_matriculado", [
            "id_user_moodle" => $idUsuario,
            "id_curso_moodle" => $idCurso
        ]);

        if($insert){
            return $insert;
        }else{
            return false;
        }
    }

}
