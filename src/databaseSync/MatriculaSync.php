<?php

namespace Arcoinformatica\IntegracaoMoodle\databaseSync;

use Arcoinformatica\IntegracaoMoodle\controller\InscricaoController;
use Arcoinformatica\IntegracaoMoodle\controller\UsuarioController;
use Arcoinformatica\IntegracaoMoodle\databaseSync\database\Connection;
use Arcoinformatica\IntegracaoMoodle\model\Usuario;
use Arcoinformatica\IntegracaoMoodle\utils\Utils;
use PDO;

/**
 * Classe responsável por realizar a matricula do usuario no moodle, de acordo com o status de pagamento.
 */
class MatriculaSync
{
    private $conn;

    public function __construct()
    {
        $this->conn = Connection::conectar();
    }


    public function watchPedidos()
    {
        $sql_pedidos = "SELECT * FROM pedidos";

        $pedidos = $this->conn->execute($sql_pedidos)->fetchAll(PDO::FETCH_OBJ);

        if (count($pedidos) > 0) {

            foreach ($pedidos as $pedido) {
                if ($pedido->id_ultimo_evento_pagamento == '3') {
                    /**
                     * Pegar o cliente do pedido/dono do pedido e ir até a tabela cliente para pegar o id_user_moodle
                     */
                    $sql_pedido = "SELECT c.id as cliente_id, 
                    c.id_user_moodle, 
                    cs.id as curso_id,
                    cs.id_curso_moodle
                    FROM clientes c
                    INNER JOIN pedidos p ON p.id_cliente = c.id
                    INNER JOIN pedidos_itens ip ON ip.id_pedido = p.id
                    INNER JOIN cursos cs ON cs.id = ip.id_curso
                    WHERE p.id = {$pedido->id_cliente}";

                    $pedido = $this->conn->execute($sql_pedido)->fetch(PDO::FETCH_OBJ);

                    if (!$pedido->id_user_moodle) {
                        $sql_search_cliente = "SELECT * FROM clientes WHERE id = {$pedido->cliente_id}";

                        $cliente_founded = $this->conn->execute($sql_search_cliente)->fetch(PDO::FETCH_OBJ);

                        $usuario = new Usuario(
                            $cliente_founded->nome,
                            $cliente_founded->telefone,
                            $cliente_founded->email,
                            $cliente_founded->rua,
                            $cliente_founded->numero,
                            $cliente_founded->bairro,
                            $cliente_founded->cidade,
                            $cliente_founded->estado,
                            $cliente_founded->cep,
                            $cliente_founded->pais,
                            $cliente_founded->senha
                        );

                        //print_r($usuario->toArray());exit;

                        $user_created = UsuarioController::createUser($usuario);

                        if ($user_created[0]['id']) {
                            $sql_update_cliente = "UPDATE clientes SET id_user_moodle = {$user_created[0]['id']} WHERE id = {$cliente_founded->id}";
                            $this->conn->execute($sql_update_cliente);

                            if (!InscricaoController::verificaUsuarioMatriculado($user_created[0]['id'], $pedido->id_curso_moodle)) {

                                $matricula = InscricaoController::inscreverUsuarioEmCurso($user_created[0]['id'], $pedido->id_curso_moodle);
                                if ($matricula) {
                                    Utils::feedback("success", "Matricula realizada com sucesso");
                                } else {
                                    Utils::feedback("error", "Erro ao realizar matricula");
                                }

                                echo "Usuário criado com sucesso! e atualizado no banco de dados\n";
                            }
                            
                        } else {
                            echo "Erro ao criar usuário!\n";
                        }
                    } else {
                        $matricula = InscricaoController::inscreverUsuarioEmCurso($pedido->id_user_moodle, $pedido->id_curso_moodle);
                        if ($matricula) {
                            Utils::feedback("success", "Matricula realizada com sucesso");
                        } else {
                            Utils::feedback("error", "Erro ao realizar matricula");
                        }
                    }
                }
            }
        } else {
            echo "Não há pedidos para serem sincronizados!\n";
        }
    }
}
