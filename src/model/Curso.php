<?php 
namespace Arcoinformatica\IntegracaoMoodle\model;

class Curso{
    private string $nome; 
	private string $url;
	private string $nome_breve;
	private string $id_curso_moodle;	
	private string $categoria;	
	private float $preco;			
	private string $descricao;	
	private string $timestamp;
	private string $time_cadastro;	
	private string $status;	
	private string $apagado;
	private $time_apagado;

	public function __construct(string $nome, string $url, string $nome_breve, string $id_curso_moodle, string $categoria, float $preco, string $descricao, string $timestamp, string $time_cadastro, string $status, string $apagado, $time_apagado){
		$this->nome = $nome;
		$this->url = $url;
		$this->nome_breve = $nome_breve;
		$this->id_curso_moodle = $id_curso_moodle;
		$this->categoria = $categoria;
		$this->preco = $preco;
		$this->descricao = $descricao;
		$this->timestamp = $timestamp;
		$this->time_cadastro = $time_cadastro;
		$this->status = $status;
		$this->apagado = $apagado;
		$this->time_apagado = $time_apagado;
	}

	public function getNome(): string{
		return $this->nome;
	}

	public function getUrl(): string{
		return $this->url;
	}

	public function getNome_breve(): string{
		return $this->nome_breve;
	}

	public function getId_curso_moodle(): string{
		return $this->id_curso_moodle;
	}

	public function getCategoria(): string{
		return $this->categoria;
	}

	public function getPreco(): float{
		return $this->preco;
	}

	public function getDescricao(): string{
		return $this->descricao;
	}

	public function getTimestamp(): string{
		return $this->timestamp;
	}

	public function getTime_cadastro(): string{
		return $this->time_cadastro;
	}

	public function getStatus(): string{
		return $this->status;
	}

	public function getApagado(): string{
		return $this->apagado;
	}

	public function getTime_apagado(): string{
		return $this->time_apagado;
	}

	public function setNome(string $nome): void{
		$this->nome = $nome;
	}

	public function setUrl(string $url): void{
		$this->url = $url;
	}

	public function setNome_breve(string $nome_breve): void{
		$this->nome_breve = $nome_breve;
	}

	public function setId_curso_moodle(string $id_curso_moodle): void{
		$this->id_curso_moodle = $id_curso_moodle;
	}

	public function setCategoria(string $categoria): void{
		$this->categoria = $categoria;
	}

	public function setPreco(float $preco): void{
		$this->preco = $preco;
	}

	public function setDescricao(string $descricao): void{
		$this->descricao = $descricao;
	}

	public function setTimestamp(string $timestamp): void{
		$this->timestamp = $timestamp;
	}

	public function setTime_cadastro(string $time_cadastro): void{
		$this->time_cadastro = $time_cadastro;
	}

	public function setStatus(string $status): void{
		$this->status = $status;
	}

	public function setApagado(string $apagado): void{
		$this->apagado = $apagado;
	}

	public function setTime_apagado(string $time_apagado): void{
		$this->time_apagado = $time_apagado;
	}

	public function toArray(): array{
		return [
			"nome" => $this->nome,
			"url" => $this->url,
			"nome_breve" => $this->nome_breve,
			"id_curso_moodle" => $this->id_curso_moodle,
			"categoria" => $this->categoria,
			"preco" => $this->preco,
			"descricao" => $this->descricao,
			"timestamp" => $this->timestamp,
			"time_cadastro" => $this->time_cadastro,
			"status" => $this->status,
			"apagado" => $this->apagado,
			"time_apagado" => $this->time_apagado
		];
	}
}