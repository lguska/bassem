<?php
session_start();
require 'config.php';


if(isset($_POST['caixa']) && empty($_POST['caixa']) == false) {
	$caixa = addslashes($_POST['caixa']);
	$senha = addslashes($_POST['senha']);

	$sql = $pdo->prepare("SELECT * FROM contas WHERE caixa = :caixa AND senha = :senha");
	$sql->bindValue(":caixa", $caixa);
	$sql->bindValue(":senha", md5($senha));
	$sql->execute();

	if($sql->rowCount() > 0) {
		$sql = $sql->fetch();
        $x=strval($caixa);
		$_SESSION['banco'] = $sql['id'];
				

		if ($x=="99") {
		    header("Location: indexsp.php"); 
		}
		else{ 
			 header("Location: index.php");
		    }
		
	}


}
?>
<html>
<head>
	<title>Banco da CRESSEM</title>
</head>
<body>
	<form method="POST">
		Caixa:<br/>
		<input type="text" name="caixa" /><br/><br/>

		Senha:<br/>
		<input type="password" name="senha" /><br/><br/>
		<input type="submit" onclick="pegarData()" value="Entrar" />
		<hr/>
		<a href="esqueci.php">Esqueci minha senha</a>
	</form>
</body>
</html>