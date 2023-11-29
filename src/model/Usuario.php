<?php

namespace Arcoinformatica\IntegracaoMoodle\model;

use Arcoinformatica\IntegracaoMoodle\utils\Utils;

class Usuario
{
    private string $nome;
    private string $telefone;
    private string $email;
    private string $rua;
    private string $numero;
    private string $bairro;
    private string $cidade;
    private string $estado;
    private string $cep;
    private string $pais;
    private string $senha;

    public function __construct(string $nome, string $telefone = '', string $email, string $rua = '', string $numero = '', string $bairro = '', string $cidade = '', string $estado = '', string $cep = '', string $pais = 'BR', string $senha)
    {
        $this->nome = $nome;
        $this->telefone = $telefone;
        $this->email = $email;
        $this->rua = $rua;
        $this->numero = $numero;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
        $this->estado = $estado;
        $this->cep = $cep;
        $this->pais = $pais;
        $this->senha = $senha;
    }

    public function getUsername(): string{
        return strtolower(Utils::tira_acentos(str_replace(" ",".",$this->nome)));
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getTelefone(): string {
        return $this->telefone;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->senha;
    }

    public function getFirstName(): string {
        return explode(" ", $this->nome)[0];
    }

    public function getLastName(): string {
        $preposicao = ['da', 'de', 'do', 'das', 'dos'];

        if(in_array(explode(" ", $this->nome)[1], $preposicao)) {
            return explode(" ", $this->nome)[2] ?? "";
        }
    }

    public function __toString()
    {
        return json_encode([
            'nome' => $this->nome,
            'telefone' => $this->telefone,
            'email' => $this->email,
            'rua' => $this->rua,
            'numero' => $this->numero,
            'bairro' => $this->bairro,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
            'cep' => $this->cep,
            'pais' => $this->pais,
            'senha' => $this->senha
        ]);
    }

    public function toArray()
    {
        $preposicao = ['da', 'de', 'do', 'das', 'dos'];

        $primeiroNome = explode(" ", $this->nome)[0];
        $ultimoNome = explode(" ", $this->nome)[1] ?? "";

        if (in_array($ultimoNome, $preposicao)) {
            $ultimoNome = explode(" ", $this->nome)[2] ?? "";
        }

        return [
            'username' => $this->nome,
            'password' => $this->senha,
            'firstname' => $primeiroNome,
            'lastname' => $ultimoNome,
            'email' => $this->email
        ];
    }
}
