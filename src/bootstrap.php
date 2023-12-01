<?php
require "./vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../config');
$dot = $dotenv->safeLoad();

