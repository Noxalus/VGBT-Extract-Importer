<?php

require_once('config.php');

try
{
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$pdo = new PDO('mysql:host=localhost;dbname=' . PDO_DATABASE, PDO_USER, PDO_PASSWORD, $pdo_options);
	$pdo->exec("SET NAMES utf8");
}
catch(Exception $e)
{
	die('Erreur : '.$e->getMessage());
}

?>