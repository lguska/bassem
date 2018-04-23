<?php
session_start();
require 'config.php';

if(isset($_POST['titular']) && empty($_POST['titular']) == false) {
	$titular = addslashes($_POST['titular']);
	$email = addslashes($_POST['email']);
	$caixa = addslashes($_POST['caixa']);
	$senha = md5(addslashes($_POST['senha']));
    
	$sql = "INSERT INTO contas (titular, email, caixa, senha, ativo) VALUES('$titular','$email','$caixa','$senha',1)";
	$pdo->query($sql);

	header("Location: usuarios.php");
}
?>
<form method="POST">
	Nome:<br/>
	<input type="text" name="titular" /><br/><br/>
	E-mail:<br/>
	<input type="text" name="email" /><br/><br/>
	Caixa:<br/>
	<input type="text" name="caixa" /><br/><br/>
	Senha:<br/>
	<input type="password" name="senha" /><br/><br/>

	<input type="submit" value="Cadastrar" />
</form>