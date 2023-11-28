<?php
namespace Arcoinformatica\IntegracaoMoodle\utils;

class Request{
    private $url;
    private $method;
    private $data;
    private $headers;

    public function __construct($url, $method, $data=null, $headers=[]){
        $this->url = $url;
        $this->method = $method;
        $this->data = $data;
        $this->headers = $headers;
    }

    public function send(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $this->method,
            CURLOPT_POSTFIELDS => $this->data,
            CURLOPT_HTTPHEADER => $this->headers
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function getRequest(){
        return [
            'url' => $this->url,
            'method' => $this->method,
            'data' => $this->data,
            'headers' => $this->headers
        ];
    }
}