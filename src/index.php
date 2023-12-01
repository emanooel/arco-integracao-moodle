<?php

use Arcoinformatica\IntegracaoMoodle\config\Token;
use Arcoinformatica\IntegracaoMoodle\controller\CursosController;
use Arcoinformatica\IntegracaoMoodle\controller\InscricaoController;
use Arcoinformatica\IntegracaoMoodle\controller\UsuarioController;
use Arcoinformatica\IntegracaoMoodle\databaseSync\CursoSync;
use Arcoinformatica\IntegracaoMoodle\databaseSync\MatriculaSync;
use Arcoinformatica\IntegracaoMoodle\model\Usuario;

require "./vendor/autoload.php";

// InscricaoController::inscreverUsuarioEmCurso(25, 12);
// exit;

$matricula = new MatriculaSync();

echo $matricula->watchPedidos();