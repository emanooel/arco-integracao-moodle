<?php

namespace Arcoinformatica\IntegracaoMoodle\controller;

use Arcoinformatica\IntegracaoMoodle\config\Token;
use Arcoinformatica\IntegracaoMoodle\model\Usuario;
use MoodleRest;

require "./src/bootstrap.php";

class UsuarioController
{
    public static function createUser(Usuario $usuario)
    {
        $moodleRest = new MoodleRest($_ENV['URL_WEBSERVICE'], Token::generate());
        
        $userCreate = $moodleRest->request("core_user_create_users", array(
            'users'=> [
                [
                    'username' => $usuario->getUsername(),
                    'password' => $usuario->getPassword(),
                    'firstname' => $usuario->getFirstName(),
                    'lastname' => $usuario->getLastName(),
                    'email' => $usuario->getEmail(),
                ]
            ]
        ));

        return $userCreate;
    }

    public static function listUsers(){
        $moodleRest = new MoodleRest($_ENV['URL_WEBSERVICE'], Token::generate());
        
        $userList = $moodleRest->request("core_user_get_users", array(
            'criteria' => [
                [
                    'key' => 'email',
                    'value' => '%'
                ]
            ]
        ));

        print_r($userList);
    }

    public static function deleteUser(int $id){
        $moodleRest = new MoodleRest($_ENV['URL_WEBSERVICE'], Token::generate());
        
        $userDelete = $moodleRest->request("core_user_delete_users", array(
            'userids' => [
                $id
            ]
        ));

        print_r($userDelete);
    }

    public static function updateUser(int $id, Usuario $usuario){
        $moodleRest = new MoodleRest($_ENV['URL_WEBSERVICE'], Token::generate());
        
        $userUpdate = $moodleRest->request("core_user_update_users", array(
            'users' => [
                [
                    'id' => $id,
                    'username' => $usuario->getUsername(),
                    'password' => $usuario->getPassword(),
                    'firstname' => $usuario->getFirstName(),
                    'lastname' => $usuario->getLastName(),
                    'email' => $usuario->getEmail(),
                ]
            ]
        ));

        print_r($userUpdate);
    }
}
