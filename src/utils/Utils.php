<?php 
namespace Arcoinformatica\IntegracaoMoodle\utils;

class Utils{
    public static function tira_acentos(string $string): string{
        return preg_replace(
            array(
                "/(á|à|ã|â|ä)/",
                "/(Á|À|Ã|Â|Ä)/",
                "/(é|è|ê|ë)/",
                "/(É|È|Ê|Ë)/",
                "/(í|ì|î|ï)/",
                "/(Í|Ì|Î|Ï)/",
                "/(ó|ò|õ|ô|ö)/",
                "/(Ó|Ò|Õ|Ô|Ö)/",
                "/(ú|ù|û|ü)/",
                "/(Ú|Ù|Û|Ü)/",
                "/(ñ)/",
                "/(Ñ)/"
            ),
            explode(" ", "a A e E i I o O u U n N"),
            $string
        );
    }

    public static function urlfy(string $string): string{
        $string = self::tira_acentos($string);
        $string = strtolower($string);
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        $string = preg_replace("/[\s-]+/", " ", $string);
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }

    /**
     * Função que pode ser usada para gerar um retorno json após uma requisição
     * retorno de sucesso ou erro, com mensagem e dados
     * @return string
     */
    public static function feedback(string $type, string $message): string{
        return json_encode(
            [
                "type" => $type,
                "message" => $message
            ]
        );
    }
}