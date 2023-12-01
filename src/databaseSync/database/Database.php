<?php
namespace Arcoinformatica\IntegracaoMoodle\databaseSync\database;

use Exception;
use PDO;
use PDOException;

class Database{
    private $host;
    private $db;
    private $user;
    private $pass;

    private $table;

    private $connection;

    public function __construct($table=null){
        $this->table = $table;
    }

    // estabelece a conexão
    public function conectar($host, $db, $user, $pass){
        $this->host = $host;
        $this->db = $db;
        $this->user = $user;
        $this->pass = $pass;

        try{
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->db;charset=utf8", $this->user, $this->pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->connection;
        }catch(PDOException $e){
            echo "Erro ao conectar ao banco de dados: ".$e->getMessage();
        }
    }

    // executa uma query
    public function execute($query,$params=[]){
        try{
            $pdo = $this->conectar($this->host, $this->db, $this->user, $this->pass);
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            return $stmt;
        }catch(Exception $e){
            echo "Erro ao executar a query: ".$e->getMessage()."Query: ".$query." Params: ".implode(',',$params);
        }
    }
    

    public function insertQuery($table,$values,$ignoreFields = []){
        
        array_push($ignoreFields,"g-recaptcha-response");

        $values = array_filter($values,function($key) use ($ignoreFields){
            return !in_array($key,$ignoreFields);
        },ARRAY_FILTER_USE_KEY);

        $fields = array_keys($values);
        $binds = array_pad([],count($fields),"?");

        $query =  'INSERT INTO '.$table.' ('.implode(',',$fields).') VALUES ('.implode(',',$binds).')';
        
        $this->execute($query,array_values($values));
        
        //echo $query;

        return $this->connection->lastInsertId();

    }

    public function updateQuery($table,$values,$where = null,$ignoreFields = []){

        array_push($ignoreFields,"g-recaptcha-response");

        $values = array_filter($values,function($key) use ($ignoreFields){
            return !in_array($key,$ignoreFields);
        },ARRAY_FILTER_USE_KEY);

        $fields = array_keys($values);
        $binds = array_pad([],count($fields),"?");

        $query =  'UPDATE '.$table.' SET '.implode('=?,',$fields).'=?';
        
        if($where){
            $query .= ' WHERE '.$where;
        }

        $this->execute($query,array_values($values));
        
        //echo $query;

        return true;

    }
}