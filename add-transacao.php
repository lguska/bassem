<?php
session_start();
require 'config.php';

if(isset($_POST['tipo']) && $_POST['valor']>0) {
	$tipo = $_POST['tipo'];
	$valor = str_replace(",", ".", $_POST['valor']);
	$valor = floatval($valor);

	$sql = $pdo->prepare("INSERT INTO historico (id_conta, tipo, valor, data_operacao) VALUES (:id_conta, :tipo, :valor, NOW())");
	$sql->bindValue(":id_conta", $_SESSION['banco']);
	$sql->bindValue(":tipo", $tipo);
	$sql->bindValue(":valor", $valor);
	$sql->execute();
    
    //atualizo saldo das contas
	if($tipo == '0') {
		// Depósito
		$sql = $pdo->prepare("UPDATE contas SET saldo = saldo + :valor WHERE id = :id");
		$sql->bindValue(":valor", $valor);
		$sql->bindValue(":id", $_SESSION['banco']);
		$sql->execute();

	} else {
		// Saque
		$sql = $pdo->prepare("UPDATE contas SET saldo = saldo - :valor WHERE id = :id");
		$sql->bindValue(":valor", $valor);
		$sql->bindValue(":id", $_SESSION['banco']);
		$sql->execute();
	}

	header("Location: index.php");
	exit;
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Controle de Tesouraria - Banco da CRESSEM</title>
</head>
<body>
	<form method="POST">
		Tipo de transação:<br/>
		<select name="tipo">
			<option value="0">Suprimento</option>
			<option value="1">Recolhimento</option>
			<option value="2">Enviar Tesouraria</option>
			<option value="3">Receber da Tesouraria</option>
			<option value="4">Malote Fim do Dia</option>
			<option value="5">Saldo Final Cofre</option>
		</select><br/><br/>

		Valor:<br/>
		<input type="text" name="valor" pattern="[0-9.,]{1,}" /><br/><br/>

		<input type="submit" value="Adicionar" />

	</form>
</body>
</html>