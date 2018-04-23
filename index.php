<?php
session_start();
require 'config.php';

if(isset($_SESSION['banco']) && !empty($_SESSION['banco']) ) {
    
	$id = $_SESSION['banco'];

	
	$sql = $pdo->prepare("SELECT * FROM contas WHERE id = :id");
	$sql->bindValue(":id", $id);
	$sql->execute();

	if($sql->rowCount() > 0) {
		$info = $sql->fetch();
		if(!empty($_POST['datentrada'])){
		   $_SESSION['datentrada'] = $_POST['datentrada'];}

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
	<script src=plugins/jquery-3.2.1.min.js></script>
	<script type="text/javascript" src=js/acesso.js></script>
	<title>Banco da CRESSEM</title>
</head>
<body>
    <meta charset="UTF-8">
    <a href="sair.php">Sair</a>
	<a href="add-transacao.php">Adicionar Transação</a><br/><br/>
	<form method="POST">
	<input type="date" id="datentrada" name="datentrada" /><br/><br/>
	<input type="submit" onlick=mudarData() value="Mudar" />
    </form>
	<h1>Banco da CRESSEM</h1>
	Titular: <?php echo $info['titular']; ?><br/>
	Caixa: <?php echo $info['caixa']; ?><br/>

	Data Selecionada: 

    <h3>Movimentação/Extrato</h3>
    
    
	<table border="4" width="500">
		<tr bgcolor="#99999">
			<th>Data</th>
			<th>Recolhimento</th>
			<th>Suprimento</th>
			<th>Saldo do Malote</th>
		</tr>
		
	    <?php
	    if(!empty($_SESSION['datentrada'])){
	       $dt=$_SESSION['datentrada'];}
	    
	    if (!empty($dt)){
        	
			$sql = $pdo->prepare("SELECT * FROM historico WHERE (id_conta = :id_conta) AND CAST(data_operacao AS DATE)=:dt");
			$sql->bindValue(":id_conta", $id);
			$sql->bindValue(":dt",$dt);
			$sql->execute();
            
			if($sql->rowCount() > 0) {
				foreach($sql->fetchAll() as $item) {
					?>
					<tr>
						<td><?php echo date('d/m/Y H:i', strtotime($item['data_operacao'])); ?></td>
							  <?php if($item['tipo'] == '0'): ?> 
							    <td> </td>
							    <td> <font  color="green">R$ <?php echo number_format($item['valor'],2,',','.') ?></font></td>
							    <td></td>
							  <?php elseif ($item['tipo'] == '1'): ?>
								<td> <font color="red">R$ <?php echo number_format($item['valor'],2,',','.') ?></font></td> 
								<td> </td>
								<td></td>
							  <?php elseif ($item['tipo'] == '4'): ?>
							  	<td></td>
							  	<td></td>
								<td> <font color="blue">R$ <?php echo number_format($item['valor'],2,',','.') ?></font></td> </tr>
							  <?php endif; ?>
					</tr>
					<?php
				}
			}
        }
		?>
	</table>
</body>
</html>