Acesse: https://testead.femar.com.br/ este é o ambiente de teste
usuário: vitor.arco
Senha: 3jI{3gs4
# Links uteis
https://testead.femar.com.br/admin/webservice/documentation.php - Lista das funções da API para usar com a lib moodle no método request.

https://testead.femar.com.br/admin/roles/manage.php - Gerênciar papéis, no ambiente de teste, estamos usando o papel Arco REST Web Service. aparentemente aqui na edição de um papel, tem se **acesso a todas funções** nas quais em cada uma é possivel conceder permissão ou não para o papel. 

https://testead.femar.com.br/admin/roles/allow.php?mode=assign - Para atribuir permissão a um papel, para que este papel possa atribuir papeis a outros usuários. De uso útil quando se deparar com um erro semelhante a este.

 `[exception] => moodle_exception
    [errorcode] => wsusercannotassign
    [message] => Você não tem permissão para atribuir esse papel (5) para esse usuário  (24) nesse curso (12).`

https://testead.femar.com.br/admin/webservice/service_functions.php?id=3 - Funções que estão ativas para o papel Arco REST Web Service, aqui é possivel desativar funções que não serão usadas, para que o papel não tenha acesso a elas. E também é possível ver a descrição que cada função faz.

# Progresso
Já é possível gerênciar usuário.
Colocar um usuário como participante de um curso. 
## Pontos criticos de desenvolvimento
- Senha do usuario no banco de dados e no moodle, no banco de dados vai ser salvo a senha criptografada, no moodle não, então é necessário criptografar a senha antes de enviar para o moodle.

# Funcionamento com o site cursos-femar
- Colocar na tabela de usuarios/clientes uma coluna chamada id_user_moodle, pois a funcao de criar um usuário no moodle, retorna o ID do usuário criado, então é interessante salvar este ID para utilidades futuras.  
- Ficar verificando a tabela de retorno de pagamento, 

documentação da API: https://testead.femar.com.br/admin/webservice/documentation.php
# CURSOS
### Listar cursos
`echo CursoController::getCursos();`
# USUARIO
usuario para contexto de moodle, no banco de dados é cliente
### Usar a integração para gerênciar usuários no moodle.
O usuário precisa está associado a um papel, e este papel tenha a permissão de criar usuários.
Isso pode ser verificado seguindo o caminho: 
Painel >> Administração do site >> Usuários >> Permissões >> Definir papéis. clique na engrenagem que aparece e ao passar o mouser vai aparecer 'editar'
### criar um usuário no moodle
#### Descrição dos parâmetros da classe Usuario
é necessário passar um usuário para função createUser, os parametros são:
1. string $nome,
2. string $telefone = '', //não é obrigatório
3. string $email,
4. string $rua = '', //não é obrigatório
5. string $numero = '', //não é obrigatório
6. string $bairro = '', //não é obrigatório
7. string $cidade = '', //não é obrigatório
8. string $estado = '', //não é obrigatório
9. string $cep = '', //não é obrigatório
10. string $pais = 'Brasil', //não é obrigatório
11. string $senha

`$usuario = new Usuario('João da Silva','2835112855','emanoelanun@gmail.com','','',
    '',
    '',
    '',
    '',
    '',
    'Brasil1612');
UsuarioController::createUser($usuario);`
### Listar usuários
Pode ser util para recuperar o id de um usuário e excluir ou editar por exemplo.
`echo UsuarioController::listUsers();`
### Excluir usuário
#### Descrição dos parametros
1. int $id - id do usuário no moodle, pode usar a função listar usuários para saber o id do usuário que deseja excluir.
`UsuarioController::deleteUser($id);`
### Atualizar usuário
#### Descrição dos parametros
1. int $id - id do usuário no moodle, pode usar a função listar usuários para saber o id do usuário que deseja atualizar.
2. Usuario $usuario - objeto do tipo Usuario, onde os atributos(parâmetros que devem ser passados) são os mesmos da função criar usuário.
`UsuarioController::updateUser($id,$usuario);`
exemplo de uso:

`$usuario = new Usuario('João da Silva','2835112855','joaodasilva@gmail.com','','',
    '',
    '',
    '',
    '',
    '',
    'Brasil1612');
UsuarioController::updateUser(24,$usuario);`
### Inscrever usuário em um curso
#### Descrição dos parametros
1. int $idUsuario - id do usuário no moodle, pode usar a função listar usuários para saber o id do usuário que deseja inscrever no curso.
2. int $idCurso - id do curso no moodle, pode usar a função listar cursos para saber o id do curso que deseja inscrever o usuário.
`echo InscricaoController::inscreverUsuarioEmCurso(24, 12);`
