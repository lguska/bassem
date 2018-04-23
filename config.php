<?php
try {
	$pdo = new PDO("mysql:dbname=banco;host=localhost", "root", "");
} catch(PDOException $e) {
	echo "ERRO: ".$e->getMessage();
	exit;
}