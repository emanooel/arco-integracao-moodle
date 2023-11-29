<?php

use Arcoinformatica\IntegracaoMoodle\config\Token;
use Arcoinformatica\IntegracaoMoodle\controller\CursosController;
use Arcoinformatica\IntegracaoMoodle\controller\InscricaoController;
use Arcoinformatica\IntegracaoMoodle\controller\UsuarioController;
use Arcoinformatica\IntegracaoMoodle\model\Usuario;

require "./vendor/autoload.php";
    
echo InscricaoController::inscreverUsuarioEmCurso(24, 12);