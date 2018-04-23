<?php
session_start();
require 'config.php';
?>
<a href="add-usuarios.php">Adicionar Novo Usuário</a>
<table border="0" width="100%">
	<tr>
		<th>Nome</th>
		<th>E-mail</th>
		<th>Ações</th>
	</tr>
	<?php
	$sql = "SELECT * FROM contas";
	$sql = $pdo->query($sql);
	if($sql->rowCount() > 0) {
		foreach($sql->fetchAll() as $usuario) {
			echo '<tr>';
			echo '<td>'.$usuario['titular'].'</td>';
			echo '<td>'.$usuario['email'].'</td>';
			echo '<td><a href="ed-usuarios.php?id='.$usuario['id'].'">Editar</a> - <a href="ex-usuarios.php?id='.$usuario['id'].'">Excluir</a></td>';
			echo '</tr>';
		}
	}
	?>
</table>