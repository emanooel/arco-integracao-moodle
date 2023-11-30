<?php

use Arcoinformatica\IntegracaoMoodle\config\Token;
use Arcoinformatica\IntegracaoMoodle\controller\CursosController;
use Arcoinformatica\IntegracaoMoodle\controller\InscricaoController;
use Arcoinformatica\IntegracaoMoodle\controller\UsuarioController;
use Arcoinformatica\IntegracaoMoodle\databaseSync\CursoSync;
use Arcoinformatica\IntegracaoMoodle\model\Usuario;

require "./vendor/autoload.php";

var_dump(CursosController::getCursos());
exit;

$sync_cursos = new CursoSync();
echo $sync_cursos->sync();