<?php
session_start();
require 'config.php';

if(isset($_SESSION['banco']) && empty($_SESSION['banco']) == false) {
	$id = $_SESSION['banco'];

	$sql = $pdo->prepare("SELECT * FROM contas WHERE id = :id");
	$sql->bindValue(":id", $id);
	$sql->execute();

	if($sql->rowCount() > 0) {
		$info = $sql->fetch();
		if($info['caixa']!='99'){header("Location: index.php");exit;}
	} else {
		header("Location: login.php");
		exit;
	}

} else {
	header("Location: login.php");
	exit;
}
?>
<html>
<head>
	<title>Tesoureiro</title>
</head>
<body>
	<h1>Tesoureiro - Banco da CRESSEM</h1>
	Titular: <?php echo $info['titular']; ?><br/>
	Caixa: <?php echo $info['caixa']; ?><br/>
	Saldo: <?php echo number_format($info['saldo'],2,',','.'); ?><br/>

	Data Selecionada: 
	
	
	
    <a href="sair.php">Sair</a><hr/>
	<a href="add-transacao.php">Adicionar Transação</a><br/>
	<a href="usuarios.php">Usuários</a><br/>
	<a href="add-usuarios.php">Novo Usuário</a>
    <h3>Movimentação/Extrato</h3>
	<table border="1" width="500">
		<tr>
			<th>Data</th>
			<th>Debito</th>
			<th>Credito</th>
		</tr>
		<?php
		$sql = $pdo->prepare("SELECT * FROM historico WHERE id_conta = :id_conta");
		$sql->bindValue(":id_conta", $id);
		$sql->execute();

		if($sql->rowCount() > 0) {
			foreach($sql->fetchAll() as $item) {
				?>
				<tr>
					<td><?php echo date('d/m/Y H:i', strtotime($item['data_operacao'])); ?></td>
						<?php if($item['tipo'] == '0'): ?> 
							    <td> </td>
							    <td> <font color="green">R$ <?php echo number_format($item['valor'],2,',','.') ?></font></td>
							  <?php elseif ($item['tipo'] == '1'): ?>
								<td> <font color="red">R$ <?php echo number_format($item['valor'],2,',','.') ?></font></td> 
								<td> </td>
						<?php endif; ?>
				</tr>
				<?php
			}
		}
		?>
	</table>
</body>
</html>