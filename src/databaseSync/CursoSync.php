<?php

namespace Arcoinformatica\IntegracaoMoodle\databaseSync;

use Arcoinformatica\IntegracaoMoodle\controller\CursosController;
use Arcoinformatica\IntegracaoMoodle\databaseSync\database\Connection;
use Arcoinformatica\IntegracaoMoodle\model\Curso;
use Arcoinformatica\IntegracaoMoodle\utils\Utils;
use Exception;
use PDO;

class CursoSync
{
    private $conn;

    private $cursos_moodle_in_database = array();

    public function __construct()
    {
        $this->conn = Connection::conectar();
    }

    public function sync()
    {
        $cursos = CursosController::getCursos();
        /**
         * Se não tiver cursos no banco de dados, inserir todos os cursos do moodle.
         * essa função setCursosMoodleInDatabase verifica isso
         */
        if (!$this->setCursosMoodleInDatabase()) {
            foreach ($cursos as $curso) {
                $curso = new Curso(
                    $curso['fullname'],
                    Utils::urlfy($curso['fullname']),
                    $curso['shortname'],
                    $curso['id'],
                    $curso['categoryid'],
                    0.00,
                    $curso['summary'],
                    date("Y-m-d H:i:s"),
                    date("Y-m-d H:i:s"),
                    '0',
                    '0',
                    null
                );

                try {
                    $insert = $this->conn->insertQuery("cursos", $curso->toArray());

                    if ($insert) {
                        echo "Curso {$curso->getNome()} inserido com sucesso!\n";
                    } else {
                        echo "Curso {$curso->getNome()} não inserido!\n";
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
            /**
             * Se tiver cursos no banco de dados, verificar se o curso que está sendo inserido já existe no banco de dados.
             * Se não existir, inserir.
             * Se existir, atualizar todos campos daquele curso.
             */
        }else{
            foreach ($cursos as $curso) {
                if (!in_array($curso['id'], $this->cursos_moodle_in_database)) {
                    $curso = new Curso(
                        $curso['fullname'],
                        Utils::urlfy($curso['fullname']),
                        $curso['shortname'],
                        $curso['id'],
                        $curso['categoryid'],
                        0.00,
                        $curso['summary'],
                        date("Y-m-d H:i:s"),
                        date("Y-m-d H:i:s"),
                        '0',
                        '0',
                        null
                    );

                    try {
                        $insert = $this->conn->insertQuery("cursos", $curso->toArray());

                        if ($insert) {
                            echo "Curso {$curso->getNome()} inserido com sucesso!\n";
                        } else {
                            echo "Curso {$curso->getNome()} não inserido!\n";
                        }
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                    /**
                     * Se o curso já existir no banco de dados, atualizar todos os campos daquele curso.
                     * é feita uma busca para manter os campos(preço,timestamp,time_cadastro,status,apagado), caso ele já tenha sido inserido.
                     */
                }else{
                    $sql_curso_in_database = "SELECT * FROM cursos WHERE id_curso_moodle = {$curso['id']}";
                    $curso_in_database = $this->conn->execute($sql_curso_in_database)->fetch(PDO::FETCH_ASSOC);

                    $curso = new Curso(
                        $curso['fullname'],
                        Utils::urlfy($curso['fullname']),
                        $curso['shortname'],
                        $curso['id'],
                        $curso['categoryid'],
                        $curso_in_database['preco'],
                        $curso['summary'],
                        $curso_in_database['timestamp'],
                        $curso_in_database['time_cadastro'],
                        $curso_in_database['status'],
                        $curso_in_database['apagado'],
                        null
                    );

                    try {
                        $update = $this->conn->updateQuery("cursos", $curso->toArray(), "id_curso_moodle = {$curso->getId_curso_moodle()}");

                        if ($update) {
                            echo "Curso {$curso->getNome()} atualizado com sucesso!\n";
                        } else {
                            echo "Curso {$curso->getNome()} não atualizado!\n";
                        }
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                }
            }
        }

        $this->deleteCurso();
    }
    /**
     * FUNÇÃO PARA VERIFICAR SE JÁ TEM CURSOS NO BANCO DE DADOS
     * SE NÃO TIVER, IRÁ RETORNAR FALSE
     * SE TIVER, IRÁ POPULAR O ARRAY COM OS IDS DO CURSO MOODLE E IRÁ RETORNAR TRUE
     *
     * @return void
     */
    public function setCursosMoodleInDatabase(){
        // Verificar todos cursos do banco de dados
        $sql_cursos_in_database = "SELECT id_curso_moodle FROM cursos";
        $cursos = $this->conn->execute($sql_cursos_in_database)->fetchAll(PDO::FETCH_OBJ);
        // Guardar em um array os id_curso_moodle.
        if (count($cursos) > 0) {
            foreach ($cursos as $curso) {
                array_push($this->cursos_moodle_in_database, $curso->id_curso_moodle);
            }
            return true;
        } else {
            return false;
        }
    }
    /**
     * ESSA FUNÇÃO SERÁ USADA PARA DELETAR CURSOS QUE FORAM APAGADOS NO MOODLE
     * a lógica é, se existir o id_curso_moodle no banco de dados, mas não existir no moodle, deletar do banco de dados.
     * e com deletar é, colocar o campo apagado como 1 e o campo time_apagado como a data atual.
     * @return void
     */
    public function deleteCurso(){
        foreach($this->cursos_moodle_in_database as $curso){
            $curso_in_moodle = CursosController::getCursoById($curso);
            if(!$curso_in_moodle['courses']){
                $sql_curso_in_database = "SELECT * FROM cursos WHERE id_curso_moodle = {$curso}";
                $curso_in_database = $this->conn->execute($sql_curso_in_database)->fetch(PDO::FETCH_ASSOC);

                $curso = new Curso(
                    $curso_in_database['nome'],
                    $curso_in_database['url'],
                    $curso_in_database['nome_breve'],
                    $curso_in_database['id_curso_moodle'],
                    $curso_in_database['categoria'],
                    $curso_in_database['preco'],
                    $curso_in_database['descricao'],
                    $curso_in_database['timestamp'],
                    $curso_in_database['time_cadastro'],
                    $curso_in_database['status'],
                    '1',
                    date("Y-m-d H:i:s")
                );

                try {
                    $update = $this->conn->updateQuery("cursos", $curso->toArray(), "id_curso_moodle = {$curso->getId_curso_moodle()}");

                    if ($update) {
                        echo "Curso {$curso->getNome()} apagado com sucesso!\n";
                    } else {
                        echo "Curso {$curso->getNome()} não apagado!\n";
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
    }
}
