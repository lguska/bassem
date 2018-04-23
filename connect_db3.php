<?php
	/*
	ESTE PROGRAMA E TODO O SEU CONTE�DO � PROTEGIO PELA LICEN�A DE USO
		    GNU GENERAL PUBLIC LICENSE
		       Version 2, June 1991
		       
	A LICENCA EM UMA TRADU��O N�O OFICIAL PARA A L�NGUA PORTUGUESA DEVE ESTAR ANEXA
	A ESTE ARQUIVO DENOMINADA GPL.TXT
	
	Autor: Willian Fernando Soares
	Contato: wfsoares@unimep.br ou wildsoares@bol.com.br ou wsoares@olokal.com.br

	=> Conexao com o banco de dados MySQL

   	Criado: 29/09/03 - 11:49
   	23/08/2004 - Atualizada, adi��o da fun��o fecha() para
   	encerrar a conex�o com o banco de dados, dica fornecida pelo
   	internauta Leandro Fernandes.
   	08/10/2004 - Modifica��es seguindo sugest�o enviada pelo internauta
   	Alfred Reinold Baudisch.
   	As fun��es de navega��o existentes na classe conexao do arquivo connect_db.php
   	n�o s�o mais necess�rias, o posicionamento do ponteiro passa a ser autom�tico.
   	23/03/2005 - Inclu�do o m�todo sobre() que tr�s informa��es sobre a classe.
   	Inclu�do o m�todo "conectado" que indica se existe ou n�o uma conex�o ativa.
   	Inclu�do o m�todo "sql" que na verdade chama o m�todo "executa", sendo apenas
   	um nome mais simples e f�cil de se lembrar.
   	Os m�todos executa e manipula passam a verificar se existe uma conex�o ativa (vari�vel conectado)
   	antes de executar a SQL ou DML.
	*/

DEFINE("MSG_NAO_CONECTADO", "N�o h� nenhuma conex�o ativa.");
DEFINE("NAO_LISTA_TABELA", "N�o foi poss�vel listar os dados da tabela ");
DEFINE("CONSULTA_NAO_INFO", "Consulta n�o informada.");
DEFINE("NAO_CRIA_FORM", "N�o foi poss�vel criar o formul�rio.");

class wConn {
	var $id;	     // Identificador da conex�o
	var $res;	     // Armazena resultado das consultas
	var $qtd;	     // Quantidade de linhas retornadas
	var $data;	     // Armazena os dados retornados
	var $erro;	     // Armazena o �ltmo erro
	var $conectado;  // Indica se h� uma conex�o aberta ou n�o.
	var $titcolun;   // T�tulo das colunas de uma tabela � listar.
	var $ocultar;    // Colunas que n�o devem aparecer em uma tabela.
	var $cor_lin1;   // Cor da linha de uma tabela.
	var $cor_lin2;   // Cor da linha de uma tabela ao mover o mouse.
	var $cor_tit;    // Cor do t�tulo de uma tabela.
	var $cor_font;   // Cor da fonte de uma tabela.
	var $cor_titfont;// Cor da fonte do t�tulo da tabela.
	var $cor_sel;    // Cor das linhas selecionadas de uma tabela.
	var $tam_font;   // O tamanho da fonte de uma tabela.
	var $fonte;      // A fonte de uma tabela.
	var $setar;      // Indica se os itens possuir�o campo para sele��o.
	var $borda;      // Espessura da borda.
	var $action;     // P�gina para onde os dados do form ou tabela s�o enviados.
	var $bt_excluir; // Mostra o bot�o excluir.
	var $bt_incluir; // Mostra o bot�o incluir.
	var $totais;     // Grava os totais de uma tabela.
	var $vsubtotais; // Valores dos subtotais.
	var $vtotais;    // Valores dos totais.
	var $agrupar;    // Agrupa uma lista de dados por um determinado campo.
	var $max_texto;  // Quantidade m�xima de caracteres na primeira linha de um campo de texto.
	var $load_img;   // Identifica se carrega as imagens referentes ao path escrito no b.d.
	var $larg_img;   // Identifica a largura padr�o das imagens de uma tabela.
	var $bt_form;    // Identifica se mostra ou n�o os bot�es enviar e limpar do formul�rio.
	var $val_campos; // Armazena o script de valida��o de campos do formul�rio.
	var $filtros;    // Armazena a condi��o WHERE para listar uma tabela.
	var $campo_esp;  // Identifica os campos especiais (referenciados) da tabela ou formul�rio.
	var $opcoes_val; // Array que armazena os valores das op��es programas para cada registro de uma lista.
	var $opcoes_eti; // Array que armazena as etiquetas das op��es programas para cada registro de uma lista.
	var $campo_file; // Define quais campos ser�o do tipo FILE (arquivos).
	var $show_forms; // Indica se mostra ou n�o formul�rios junto com tabela.
	var $readonly;   // Vetor que indica quais campos s�o do tipo "somente leitura"
	var $paginacao;  // Quantidade de registros aceitos por pagina.
	var $paginar;    // Indica se deve ou n�o utilizae a pagina��o.

	var $contador;   // Contador para os campos de hidden ocultos na tabela.

	function wConn($servidor="127.0.0.1", $usuario="root", $senha="", $nomebd="test") {
		/*
		Sugest�o para o programador:
		Ao utilizar esta classe no c�digo de seu site,
		coloque os dados da conex�o como default neste m�todo, 
		desta forma n�o � preciso digit�-los toda vez que instanciar
		a classe, exemplo:

		function conexao($servidor="127.0.0.1", $usuario="root", $senha="testes", $nomebd="loja1")

		*/
		$this->id = mysql_connect("$servidor", "$usuario", "$senha") // conectando o servidor
			or die ("Problemas ao conectar ao banco de dados!");
		mysql_selectdb("$nomebd") // abrindo o banco de dados
			or die ("Problemas ao selecionar o banco de dados!");		
			
		$this->conectado  = TRUE;
		$this->res        = 0;
		$this->qtd        = 0;
		$this->data       = "";
		$this->erro       = "";
		$this->titcolun   = array();
		$this->ocultar    = array();
		$this->cor_lin1   = "#F4F4F4";
		$this->cor_lin2   = "#79BCFF";
		$this->cor_tit    = "#CACACA";
		$this->cor_font   = "#000000";
		$this->cor_titfont= "#FFFFFF";
		$this->cor_sel    = "#FFD9B3";
		$this->tam_font   = "8pt";
		$this->fonte      = "verdana";
		$this->setar      = TRUE;
		$this->borda      = "0";
		$this->action     = "";
		$this->bt_excluir = $this->bt_incluir = TRUE;
		$this->totais     = array();
		$this->vtotais    = array();
		$this->vsubtotais = array();
		$this->max_texto  = 50;
		$this->load_img   = FALSE;
		$this->larg_img   = 100;
		$this->bt_form    = TRUE;
		$this->val_campos = "";
		$this->agrupar    = array();
		$this->filtros    = "";
		$this->campo_esp  = array();
		$this->opcoes_val = array();
		$this->opcoes_eti = array();
		$this->campo_file = array();
		$this->show_forms = TRUE;
		$this->readonly   = array();
		$this->paginacao  = 20;
		$this->paginar    = TRUE;
		
		$this->contador   = 0;
	}

	function executa($sqltext="")	{
	//Executa uma query no bd e retorna os dados.
		$this->res = 0; // Sem resultados
		$this->qtd = 0; // Sem linhas
		if ($this->conectado)	{		
			if ($sqltext=="")	{       // Se n�o foi passada nenhuma SQL,
				$this->erro = CONSULTA_NAO_INFO;
				return FALSE;
			} else {                // Se passou uma SQL,
				$this->res = mysql_query($sqltext, $this->id); // Executa a query
				if ($this->res) { // Se houve um resultado,
					$this->qtd = mysql_num_rows($this->res); // Armazena a qtd. de linhas
					return TRUE;
				} else {
					$this->erro = mysql_error($this->id); // Aramzena o �ltimo erro.			
					return FALSE;
				}
			}
		} else {
			$this->erro = MSG_NAO_CONECTADO;
			return FALSE;
		}
	}
	
	function sql($sqltext="")	{
	//Isto � apenas mais um nome para a fun��o executa, um pouco mais simples de usar e lembrar.
		return $this->executa($sqltext);
	}
	
	function manipula($sqltext="")	{
	//Executa uma query de DDL ou DML (manipula��o de dados)
		if ($this->conectado)	{
			if (mysql_query($sqltext, $this->id))	{
				return TRUE; // Se OK, retorna TRUE.
			} else {
				$this->erro = mysql_error($this->id); // Aramzena o �ltimo erro.
				return FALSE;
			}
		} else {
			$this->erro = MSG_NAO_CONECTADO;
			return FALSE;
		}
	}

	function dados()	{
		/* Busca os dados de uma linha do resultado e
		   posiciona o ponteiro na pr�xima.
                   Para listar os dados no c�digo basta utilizar por exemplo:
                   while ($obj->dados())  {
                      echo $obj->data["nome"];
                   }
		*/
		if (($this->conectado)&&($this->data = mysql_fetch_array($this->res)))	{
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function fecha()	{
	// Encerra a conex�o com o banco de dados.
		if($this->conectado)	{
			mysql_close($this->id);
			$this->id = "";
			$this->res = 0;
			$this->qtd = 0;
			$this->data = "";
			$this->conectado = FALSE;
		}
	}
	
	function formatar($tipo="", $valor="", $nome="")	{
	/* A fun��o formatar verifica o tipo de dado enviado e retorna a formata��o do dado para exibi��o
	   na p�gina com HTML.
	*/
	    $tipo = strtolower($tipo);
	    if (strpos($tipo, "(") > 0)
		    $tipo = substr($tipo, 0, strpos($tipo, "("));

		if (isset($this->campo_esp[strtolower($nome)]))	{
			return str_replace("\n", "<br>", $valor);			
		} elseif ($tipo=="date")	{ // AAAA-MM-DD para DD/MM/AAAA
			if (strlen($valor)==10)	{
				return substr($valor, 8, 2) . "/" . substr($valor, 5, 2) . "/" . substr($valor, 0, 4);
			}
		} elseif ($tipo=="datetime")	{ // AAAA-MM-DD HH:MM:SS para DD-MM-AAAA HH:MM:SS
			if (strlen($valor)==19)	{
				return substr($valor, 8, 2) . "/" . substr($valor, 5, 2) . "/" . substr($valor, 0, 4) . " " . substr($valor, 11, 8);
			}
		} elseif ($tipo=="timestamp")	{ // AAAAMMDDHHMMSS para DD-MM-AAAA HH:MM:SS
			if (strlen($valor)==14)	{
				return substr($valor, 6, 2) . "/" . substr($valor, 4, 2) . "/" . substr($valor, 0, 4) . " " .
					   substr($valor, 8, 2) . ":" . substr($valor, 10, 2) . ":" . substr($valor, 12, 2);
			}
		} elseif (($tipo=="decimal") or ($tipo=="float") or ($tipo=="double"))	{
			return number_format($valor, 2, ',', '.');
		} else {
			if ($this->load_img)	{
			// este if carrega imagens cujo caminho esteja gravado no banco de dados.
				if ($this->imagem($valor))	{
					return "<center><a href=\"javascript:mImagem('$valor')\"><img src=\"$valor\" width=\"" . $this->larg_img . "\" border=\"0\" alt=\"\"></a><br>";
				}
			}
			if (strlen($valor) > $this->max_texto)	{
			// para os textos com muitos caracteres, a exibi��o � reduzia para melhorar o layout.
				$texto = "\n" . str_replace("\n", "<br>", substr($valor, 0, $this->max_texto));
				$texto.= "\n &nbsp; ";
				$texto.= "\n<input type=hidden name=TEXTO" . $nome . ++$this->contador . " value=\"" . str_replace("\n", "<br>", str_replace("\"", "", str_replace("\'", "", $valor))) . "\">";
				$texto.= " <a href=\"javascript: mtexto(FRMLISTA.TEXTO" . $nome . $this->contador . ".value)\">[mais...]</a>";

				return $texto;
			}
	
			// no caso de n�o reconhecer o dado, retorna como texto simples.
			return str_replace("\n", "<br>", $valor);
		}
	}
	
	function ad_campo_esp($nome, $valores, $etiquetas, $tabref="", $camporef="", $campomostra="", $tipo="lista")	{
		// cria os campos especiais (referenciados)
		
		// se o usu�rio informou os valores e etiquetas manualmente, adiciona-os
		if ((sizeof($valores) > 0) && (sizeof($valores)==sizeof($etiquetas))) {
			$this->campo_esp[strtolower($nome)]["tipo"] = strtolower($tipo);
			for ($z = 0; $z < sizeof($valores); $z++)	{
				$this->campo_esp[strtolower($nome)]["valores"][$z]   = $valores[$z];
				$this->campo_esp[strtolower($nome)]["etiquetas"][$z] = $etiquetas[$z];
			}
		} elseif (($tabref != "") and ($camporef != "") and ($campomostra != "")) {
			// se o usu�rio informou a tabela de refer�ncia, busca os dados nela.
			$sql = "select $camporef, $campomostra from $tabref";
			// busca os dados na tabela
			if ($this->sql($sql))	{
				if ($this->qtd > 0)	{
					// se encontrou algum
					$z = 0;
					$this->campo_esp[strtolower($nome)]["tipo"] = strtolower($tipo);						
					while ($this->dados())	{
						// vai carregando os dados
						$this->campo_esp[strtolower($nome)]["valores"][$z]   = $this->data[$camporef];
						$this->campo_esp[strtolower($nome)]["etiquetas"][$z] = $this->data[$campomostra];
						$z++;
					}
				}
			}
		}
	}
	
	function retornaesp($campo, $valor)	{
		// retorna a etiqueta de um campo calculado
		$campo = strtolower($campo);
		if (isset($this->campo_esp[$campo]["valores"]))	{
			for ($i = 0; $i < sizeof($this->campo_esp[$campo]["valores"]); $i++)	{
				if (strtolower($this->campo_esp[$campo]["valores"][$i])==strtolower($valor))	{
					return $this->campo_esp[$campo]["etiquetas"][$i];
				}
			}
		}
		return $valor;
	}
	
	function alinhar($tipo, $nome)	{
	// esta fun��o cria o alinhamento do dado na tabela HTML.
	    $tipo = strtolower($tipo);
	    if (strpos($tipo, "(") > 0)
		    $tipo = substr($tipo, 0, strpos($tipo, "("));
		if (isset($this->campo_esp[strtolower($nome)]))	{
			return "align=\"left\" width=200";
		} elseif (($tipo=="date") or
		   ($tipo=="datetime") or
		   ($tipo=="timestamp") or 
		   ($tipo=="char"))	{
			return "align=\"center\"";
		} elseif (($tipo=="decimal") or ($tipo=="float") or ($tipo=="double") or 
		(substr($tipo, strlen($tipo) - 3, 3)=="int"))	{
			return "align=\"right\"";	
		} else {
			return "align=\"left\" width=200";
		}
	}
	
	function campo($nome, $tipo, $nulo, $padrao, $extra)	{
	// esta fun��o retorna o HTML do campo do formul�rio.
	    $tipo = strtolower($tipo);
	    if (strpos($tipo, "(") > 0)	{
	    	$tam = substr($tipo, strpos($tipo, "(") + 1, strpos($tipo, ")") - strpos($tipo, "("));
	    	$tipo = substr($tipo, 0, strpos($tipo, "("));
	    } else {
	    	$tam = 0;
	    }
	    
    	$som_leit = ($this->somente_leitura($nome)) ? " readonly " : "";
	    
	    // se for para criar um campo do tipo "file"...
	    if ($this->criar_campo_file($nome))	{
	    	echo "\n<input type=\"file\" $som_leit name=\"$nome\" maxlength=\"500000\" style=\"font-family: verdana; font-size: 8 pt; color: #0000BB\">";
	    	return "";
	    }

		// se for um campo espec�fico...
		if (isset($this->campo_esp[strtolower($nome)]))	{
			// se for campo do tipo lista
			$tam = sizeof($this->campo_esp[strtolower($nome)]["valores"]);
			if ($this->campo_esp[strtolower($nome)]["tipo"] == "lista")	{		
				// cria o combo do campo.
				echo "\n<select $som_leit name=\"$nome\" size=\"1\" style=\"font-family: verdana; font-size: 8 pt\">";
				for ($z = 0; $z <= $tam; $z++)	{
					if (isset($this->campo_esp[strtolower($nome)]["valores"][$z]))	{
						$sel = (strtolower($padrao)==strtolower($this->campo_esp[strtolower($nome)]["valores"][$z])) ? " selected" : "";
						echo "	\n<option value=\"" . $this->campo_esp[strtolower($nome)]["valores"][$z] . "\"$sel>" . 
					    	 $this->campo_esp[strtolower($nome)]["etiquetas"][$z] . "</option>";
					}
				}
				echo "\n</select>";
			} else {
			// se nao for lista, cria radiobuttons
				for ($z = 0; $z <= $tam; $z++)	{
					if (isset($this->campo_esp[strtolower($nome)]["valores"][$z]))	{
						$sel = (strtolower($padrao)==strtolower($this->campo_esp[strtolower($nome)]["valores"][$z])) ? " checked" : "";
						echo "	\n<input type=\"radio\" $som_leit name=\"$nome\" value=\"" . $this->campo_esp[strtolower($nome)]["valores"][$z] . "\"$sel>" . 
					    	 $this->campo_esp[strtolower($nome)]["etiquetas"][$z] . "<br>";
					}
				}
			}
			return "";
		}

		if ($tipo=="date") {
			// campo de data dd/mm/aaaa.
			if ($padrao=="0000-00-00")	{
				$padrao = "";
			}
			if ($padrao!="")
				$padrao = substr($padrao, 8, 2) . "/" . substr($padrao, 5, 2) . "/" . substr($padrao, 0, 4);
			echo "<input type=\"text\" $som_leit name=\"$nome\" onkeypress=\"return ImpDt(this)\" value=\"$padrao\" size=\"10\" maxlength=\"10\" style=\"font-family: verdana; font-size: 8 pt; color: #0000BB\">" . 
			     "<i><font color=\"#808080\"> ex: 25/12/2004";
		} elseif ($tipo=="datetime")	{
		    // campo dd/mm/aaaa hh:mm:ss.
		    if ($padrao!="")
		    	$padrao = substr($padrao, 8, 2) . "/" . substr($padrao, 5, 2) . "/" . substr($padrao, 0, 4) . " " . substr($padrao, 11, 8);
			echo "<input type=\"text\" $som_leit name=\"$nome\" onkeypress=\"return ImpDtTime(this)\" value=\"$padrao\" size=\"19\" maxlength=\"19\" style=\"font-family: verdana; font-size: 8 pt; color: #0000BB\">" . 
			     "<i><font color=\"#808080\"> ex: 25/12/2004 23:59";
		} elseif ($tipo=="timestamp")	{
		    // campo aaaammddhhmmss
		    if ($padrao!="") {
				$padrao = substr($padrao, 6, 2) . "/" . substr($padrao, 4, 2) . "/" . substr($padrao, 0, 4) . " " .
			    		  substr($padrao, 8, 2) . ":" . substr($padrao, 10, 2) . ":" . substr($padrao, 12, 2);		    
		    }
			echo "<input type=\"text\" $som_leit name=\"$nome\" onkeypress=\"return ImpDtTime(this)\" value=\"$padrao\" size=\"19\" maxlength=\"19\" style=\"font-family: verdana; font-size: 8 pt; color: #0000BB\">" . 
			     "<i><font color=\"#808080\"> ex: 25/12/2004 23:59";
		} elseif ($tipo=="char")	{
			// campo texto com comprimento obrigatorio.
			$size = ($tam > 50) ? 50 : $tam;
			echo "<input type=\"text\" $som_leit name=\"$nome\" value=\"$padrao\" size=\"$size\" maxlength=\"$tam\" style=\"font-family: verdana; font-size: 8 pt; color: #0000BB\">";
		} elseif ($tipo=="varchar")	{
			// campo texto com comprimento m�ximo obrigat�rio.
			$size = ($tam > 50) ? 50 : $tam;			
			if ($tam > 100)	{
				echo "<textarea $som_leit name=\"$nome\" cols=\"50\" rows=\"4\" style=\"font-family: verdana; font-size: 8 pt; color: #0000BB\">$padrao</textarea>";
			} else {
				echo "<input type=\"text\" $som_leit name=\"$nome\" value=\"$padrao\" size=\"$size\" maxlength=\"$tam\" style=\"font-family: verdana; font-size: 8 pt; color: #0000BB\">";
			}
		} elseif ($tipo=="year")	{
			// campo tipo ano
			if ($padrao=="0000")
				$padrao = "";
			echo "<input type=\"text\" $som_leit name=\"$nome\" onkeypress=\"return Valida_Num();\" value=\"$padrao\" size=\"4\" maxlength=\"4\" style=\"font-family: verdana; font-size: 8 pt; color: #0000BB\">" . 
			     "<i><font color=\"#808080\"> ex: 2005";
		} elseif ($tipo=="time")	{
			// campo tipo hora hh:mm:ss
			if ($padrao=="00:00:00")
				$padrao = "";
			echo "<input type=\"text\" $som_leit name=\"$nome\" onkeypress=\"return ImpHora(this)\" value=\"$padrao\" size=\"8\" maxlength=\"8\" style=\"font-family: verdana; font-size: 8 pt; color: #0000BB\">" . 
			     "<i><font color=\"#808080\"> ex: 22:21:00";
		} elseif (($tipo=="decimal") or ($tipo=="float") or ($tipo=="double")) {
			// campo num�rico com casas decimais +/-
			echo "<input type=\"text\" $som_leit value=\"$padrao\" name=\"$nome\" size=\"10\" onkeypress=\"return Valida_Num2(this.value);\" maxlength=\"20\" style=\"font-family: verdana; font-weight: bold; font-size: 8 pt; color: #0000BB\">" . 
			     "<i><font color=\"#808080\"> ex: 232,52";
		} elseif (substr($tipo, strlen($tipo) - 3, 3)=="int")	{
			// campo num�rico inteiro +/-
			$cor      = ($extra=="auto_increment") ? " background: #FFF1BB;" : "";
			$readonly = ($extra=="auto_increment") ? " readonly" : "";
			$msg      = ($extra=="auto_increment") ? " <i><font color=\"#808080\"> auto-incremento" : "";
			$padrao   = ($padrao=="") ? "0" : $padrao;
			echo "<input type=\"text\" $som_leit value=\"$padrao\" onkeypress=\"return Valida_Num();\" name=\"$nome\"$readonly maxlength=\"15\" size=\"7\" style=\"font-family: verdana; font-weight: bold;$cor font-size: 8 pt; color: #0000BB\">" . $msg;
		} elseif (($tipo != "enum") and ($tipo != "set")) {
			// campo texto multi-linhas sem tamanho definido.
			echo "<textarea name=\"$nome\" $som_leit cols=\"50\" rows=\"4\" style=\"font-family: verdana; font-size: 8 pt; color: #0000BB\">$padrao</textarea>";
		}
	}	
	
	function imagem($texto)	{
	// esta fun��o verifica se o texto enviado representa uma imagem armazenada no site.
		if (strlen($texto) > 4)	{
			$ext = substr($texto, strlen($texto) - 4, 4);
			
			if (($ext==".jpg")||($ext==".jpe")||($ext==".gif")||($ext==".png"))	{
				if (file_exists($texto))	{
					return TRUE;
				}
			}
		}
		return FALSE;
	}
	
	function mostrar($campo)	{
	// esta fun��o retorna true se o campo em quest�o poder ser mostrado na p�gina.
		if (sizeof($this->ocultar) > 0)	{
			$int = 0;
			while($int < sizeof($this->ocultar))	{
				if (strtolower($this->ocultar[$int])==strtolower($campo))
					return FALSE;
				$int++;
			}
		}
		return TRUE;
	}
	
	function somente_leitura($campo)	{
	// esta fun��o retorna true se o campo for do tipo somente leitura
		if (sizeof($this->readonly) > 0)	{
			$int = 0;
			while($int < sizeof($this->readonly))	{
				if (strtolower($this->readonly[$int])==strtolower($campo))
					return TRUE;
				$int++;
			}
		}
		return FALSE;
	}	
	
	function criar_campo_file($campo)	{
		// esta fun��o determina se o campo ser� um campo "file" no formul�rio.
		if (sizeof($this->campo_file) > 0)	{
			$int = 0;
			while ($int < sizeof($this->campo_file))	{
				if (strtolower($this->campo_file[$int]==strtolower($campo)))
					return TRUE;
				$int++;
			}
		}
		return FALSE;
	}
	
	function filtrar($condicao="")	{
	// adiciona uma condi��o de filtro a consulta.
	// Ex. "VALOR = 225.52" ou "NOME LIKE '%JO�O'"
		if (Trim($condicao)!="")	{
			if (substr($this->filtros, 0, 5)=="WHERE")	{
				$this->filtros.=" AND (" . $condicao . ")";
			} else {
				$this->filtros = "WHERE (" . $condicao . ")";
			}
		}
	}
	
	function dados_notnull($campo="", $id_nome=0)	{
		/* Esta fun��o cria a valida��o dos dados obrigat�rios da tabela via javascript */
		if (isset($this->titcolun[$id_nome]))	{
			$nomecampo = $this->titcolun[$id_nome];
		} else {
			$nomecampo = $campo;
		}
		$this->val_campos.= "\n\n		/* Valida o campo $campo. */";
		$this->val_campos.= "\n		if (FRMCAD.$campo.value==\"\")	{";
		$this->val_campos.= "\n			alert(\"O campo '$nomecampo' � obrigat�rio.\");";
		$this->val_campos.= "\n 			FRMCAD.$campo.focus();";
		$this->val_campos.= "\n			return false;";
		$this->val_campos.= "\n		}";
	}

	function ad_opcao($etiquetas, $valor)	{
	/*
		Esta fun��o cria op��es para cada registro de uma tabela (lista de dados).
		As op��es s�o links que chamam os valores programados.
		Para que o valor tenha o id da lista, basta indica o texto '#Id#'
		Exemplo: ad_opcao("Alterar", "alterar.php?codigo=#Id#");
	*/
		if (($etiquetas!="") and ($valor !=""))	{
			$this->opcoes_val[sizeof($this->opcoes_val)] = $valor;
			$this->opcoes_eti[sizeof($this->opcoes_eti)] = $etiquetas;
		}
	}

	function insere($tabela="", $campos)	{
	/*
		Esta fun��o executa uma rotina de inclus�o de dados em uma tabela a partir
		dos dados enviados pelo m�todo POST de um formul�rio.
	*/
		if ((Trim($tabela=="")) or (sizeof($campos) == 0))
			return FALSE;

		$sql = "SHOW COLUMNS FROM $tabela"; //lista os campos da tabela
		$insertinto = "";
		$values = "";
		if ($this->sql($sql))	{
			while ($this->dados())	{
				for ($ind = 0; $ind < sizeof($campos); $ind++)	{ //procura os campos
					if (strtolower($campos[$ind])==strtolower($this->data[0]))	{
						// se achou
						if (isset($_POST[$campos[$ind]]))	{
							// verifica se o campo foi passado via form POST
						
							$insertinto.= ($insertinto=="") ? $campos[$ind] : "," . $campos[$ind];

							$valor = $_POST[$campos[$ind]];
							$tipo = strtolower($this->data[1]);
							if (substr($tipo, strlen($tipo) - 3, 3)=="int")	{
								$values.= ($values=="") ? $valor : "," . $valor;
							} elseif (($tipo=="decimal") or ($tipo=="float") or ($tipo=="double"))	{
								$values.= ($values=="") ? str_replace(",", ".", $valor) : "," . str_replace(",", ".", $valor);
							} elseif ($tipo=="date")	{
								$valor = substr($valor, 6, 4) . "-" . substr($valor, 3, 2) . "-" . substr($valor, 0, 2);
								$values.= ($values=="") ? "'$valor'" : ",'$valor'";
							} elseif ($tipo=="datetime")	{
								$valor = substr($valor, 6, 4) . "-" . substr($valor, 3, 2) . "-" . substr($valor, 0, 2) .
								" " . substr($valor, 11, 8);
								$values.= ($values=="") ? "'$valor'" : ",'$valor'";								
							} elseif ($tipo=="timestamp")	{
								$valor = substr($valor, 6, 2) . substr($valor, 3, 2) . substr($valor, 0, 2) .
								         substr($valor, 11, 2) . substr($valor, 14, 2) . substr($valor, 17, 2);
								$values.= ($values=="") ? "'$valor'" : ",'$valor'";	
							} elseif (($tipo!="set")&&($tipo!="enum")) {
								$values.= ($values=="") ? "'$valor'" : ",'$valor'";								
							}												
						}
					}
				}
			}
			
			if (($insertinto!="")&&($values!=""))
				return ($this->manipula("insert into $tabela($insertinto) values($values)"));
		} else {
			return FALSE;
		}
	}

	function atualiza($tabela="", $condicao="", $campos)	{
	/*
		Esta fun��o executa uma rotina de atualiza��o de dados em uma tabela a partir
		dos dados enviados pelo m�todo POST de um formul�rio.
	*/
		if ((Trim($tabela=="")) or (sizeof($campos) == 0))
			return FALSE;

		$sql = "SHOW COLUMNS FROM $tabela"; //lista os campos da tabela
		$update = "";
		if ($this->sql($sql))	{
			while ($this->dados())	{
				for ($ind = 0; $ind < sizeof($campos); $ind++)	{ //procura os campos
					if (strtolower($campos[$ind])==strtolower($this->data[0]))	{
						// se achou
						if (isset($_POST[$campos[$ind]]))	{
							// verifica se o campo foi passado via form POST
						
							$update.= ($update=="") ? $campos[$ind] : "," . $campos[$ind];
							$update.= " = ";

							$valor = $_POST[$campos[$ind]];
							$tipo = strtolower($this->data[1]);
							if (substr($tipo, strlen($tipo) - 3, 3)=="int")	{
								$update.= $valor;
							} elseif (($tipo=="decimal") or ($tipo=="float") or ($tipo=="double"))	{
								$update.= str_replace(",", ".", $valor);
							} elseif ($tipo=="date")	{
								$valor = substr($valor, 6, 4) . "-" . substr($valor, 3, 2) . "-" . substr($valor, 0, 2);
								$update.= "'$valor'";
							} elseif ($tipo=="datetime")	{
								$valor = substr($valor, 6, 4) . "-" . substr($valor, 3, 2) . "-" . substr($valor, 0, 2) .
								" " . substr($valor, 11, 8);
								$update.= "'$valor'";
							} elseif ($tipo=="timestamp")	{
								$valor = substr($valor, 6, 2) . substr($valor, 3, 2) . substr($valor, 0, 2) .
								         substr($valor, 11, 2) . substr($valor, 14, 2) . substr($valor, 17, 2);
								$update.= "'$valor'";
							} elseif (($tipo!="set")&&($tipo!="enum")) {
								$update.= "'$valor'";
							}												
						}
					}
				}
			}
			
			if ($update!="")
				return ($this->manipula("update $tabela set $update where $condicao"));
		} else {
			return FALSE;
		}
	}
	
	function remove($tabela="", $id="", $condicao="")	{
	/*
		Esta fun��o remove os registros de uma tabela de acordo com os par�metros enviados, sendo:
		Se $id (nome do campo chave da tabela) for <> "" ent�o o sistema eliminar�
		todos os registros existentes e marcados na lista de dados.
		Se $id for ignorado a remo��o ser� feita apenas com base no par�metro $condicao.
	*/
		if ($id=="")	{
			$sql = "delete from $tabela where $condicao";
		} else {
			$sql = "delete from $tabela ";
			$cond = "";
			if (isset($_POST["TotRegs"]))	{
				$tot = (int) $_POST["TotRegs"];
				if ($tot > 0)	{
					for ($i=1; $i <= $tot; $i++)	{
						if (isset($_POST["CHECK$i"]))
							$cond.= ($cond=="") ? " where ($id=" . $_POST["CHECK$i"] . ")" : " or ($id=" . $_POST["CHECK$i"] . ")";
					}
				}
				$sql .= $cond;
			} else {
				$this->erro = "Total de registros a remover n�o localizados.";
				return false;
			}
		}
		
		return $this->manipula($sql);
	}

	function listartabela($tabela="", $ordem="", $id="", $inicio=0, $limite=0)	{
	/*
		Esta fun��o lista os dados de uma tabela no browser em HTML.
		$tabela = nome da tabela existente no banco de dados.
		$ordem = a ordena��o da tabela por ser : CAMPO ou CAMPO1, CAMPO2 DESC.
		$id = representa a chave que identifica os registros da tabela, em geral � a 
		chave prim�ria da tabela.
		$inicio = inicio de captura dos registros na tabela.
		$limite = quantidade m�xima de registros que devem ser retornados da tabela.
	*/
		if ($this->conectado)	{
			if ($this->sql("show columns from $tabela"))	{
				$cont = -1;
				$linha = 0;
?>
<style>
	TABLE {
		font-size: <? echo $this->tam_font ?>;
		font-family: <? echo $this->fonte ?>
	}
</style>
<script language="javascript">
	CorSel  = "<? echo $this->cor_sel ?>";
	CorLin1 = "<? echo $this->cor_lin1 ?>";
	CorLin2 = "<? echo $this->cor_lin2 ?>";	
	TotalRegs = 0;
	
	function SelecionaTudo()	{
		for (x = 1; x <= TotalRegs; x++)	{
			document.all["CHECK"+x].checked = FRMLISTA.CHECKALL.checked;
			if (FRMLISTA.CHECKALL.checked)	{
				document.all["LIN"+x].bgColor = CorSel;
			} else {
				document.all["LIN"+x].bgColor = CorLin1;			
			}
		}
	}
	
	function Selecionou()	{
		for (x = 1; x <= TotalRegs; x++)
			if (document.all["CHECK"+x].checked)
				return true;
		return false;
	}
	
	function mtexto(texto)	{
		var Jan = window.open("", "", "top=200, left=200, resizable=yes, scrollbars=yes, width=300, height=200");
		Jan.document.write("<html><title>Texto</title>");
		Jan.document.write("<body bgcolor=#0078B3>");
		Jan.document.write("<font face=verdana size=1 color=#FFFFFF><b>");
		Jan.document.write(texto);
		Jan.document.write("<P><center>");
		Jan.document.write("<input type='button' onclick='window.close()' name='B1' value=':: fechar ::' style='font-family: verdana; font-size: 8pt; color: #000000; background: #f0f0f0; cursor: hand'");
		Jan.document.write("</body>");
		Jan.document.write("</html>"); 
	} 
	
	function mImagem(caminho)	{
		var Jan = window.open("", "", "top=200, left=200, resizable=yes, menubar=yes, scrollbars=yes, width=400, height=300");	
		Jan.document.write("<html><title>Imagem</title>");
		Jan.document.write("<body bgcolor=#0078B3>");
		Jan.document.write("<center>");
		Jan.document.write("<img src='" + caminho + "' border='0' alt='0'><P>");
		Jan.document.write("<font face=verdana size=1 color=#FFFFFF><b>");
		Jan.document.write(caminho);
		Jan.document.write("<P><input type='button' onclick='window.close()' name='B1' value=':: fechar ::' style='font-family: verdana; font-size: 8pt; color: #000000; background: #f0f0f0; cursor: hand'");
		Jan.document.write("</body>");
		Jan.document.write("</html>"); 
	}

	function Paginas(nome, visao)	{
		document.all[nome].style.visibility = visao;
	}
<?

if ($this->bt_excluir)	{	
?>
	function cExcluir()	{
		if (Selecionou())	{
			if (confirm("Deseja mesmo excluir este(s) registro(s)?"))	{
				FRMLISTA.conAcao.value="cexcluir";
				FRMLISTA.submit();	
			}
		} else {
			alert("Selecione ao menos um registro para excluir.");
		}
	}
<?
}
if ($this->bt_incluir)	{	
?>	
	function cIncluir()	{
			FRMLISTA.conAcao.value="cincluir";
			FRMLISTA.submit();	
	}
<?
}
?>
</script>
<form name="FRMLISTA" action="<? echo $this->action ?>" method="post">
<?	
				//************************************************************//
				// Cria o cabe�alho da tabela                                 //
				//************************************************************//

				$cabecalho = "";
					
				$cabecalho.= "\n<TABLE cellspacing='1' border='" . $this->borda . "'>\n";
				
				if (isset($this->agrupar[0]))	{
					$cabecalho.= "<tr bgcolor=\"" . $this->cor_tit . "\">";
					$cabecalho.= "<td background=\"bar_titulo.jpg\" colspan=\"";
					$cabecalho.= ($this->qtd) + (($this->setar) ? 1 : 0);
					$cabecalho.= "\">";
			
					$cabecalho.= "\n      <font color=\"#FFFFFF\"><b>Agrupado por ";
					$cabecalho.= (isset($this->agrupar[1])) ? $this->agrupar[1] : $this->agrupar[0];
					$cabecalho.= "</b></font>";					
					$cabecalho.= "</td></tr>";
				}
				
				echo "  <TR bgcolor=\"" . $this->cor_tit . "\">";
				if ($this->setar)	{
					$cabecalho.= "<td background=\"bar_titulo.jpg\">";
					$cabecalho.= "<input type=\"checkbox\" name=\"CHECKALL\" value=\"OK\" onclick=\"SelecionaTudo()\">";
					$cabecalho.= "</td>";
				}
				
				$col_id = -1;
				$grupo  = (isset($this->agrupar[0])) ? $this->agrupar[0] : "";
				while ($this->dados())	{
					$cont++;
					$nomes[$cont] = $this->data[0];
					$tipos[$cont] = $this->data[1];
					if (strtolower($id)==strtolower($nomes[$cont]))
						$col_id = $cont;
					if (($this->mostrar($nomes[$cont])) && (strtolower($grupo)!=strtolower($nomes[$cont])))	{
						$cabecalho.= "\n    <TD height=\"20\" background=\"bar_titulo.jpg\" nowrap " . $this->alinhar($tipos[$cont], $nomes[$cont]);
						$cabecalho.= (strtolower($ordem)==strtolower($nomes[$cont])) ? " bgcolor=\"#ADADAD\" " : "";
						$cabecalho.= "><b>";
						$cabecalho.= "<font color=\"" . $this->cor_titfont . "\">";
						if (isset($this->titcolun[$cont]))	{
							$cabecalho.= $this->titcolun[$cont];
						} else {				
							$cabecalho.= $nomes[$cont];
						}
						$cabecalho.= "\n    </TD>";
					}
				}
				
				$cabecalho.= "\n  </TR>";						

				//************************************************************//
				// Fim do cabe�alho da tabela                                 //
				//************************************************************//
				
				//************************************************************//
				// Cria o rodap� da tabela                                    //
				//************************************************************//				
				
				$rodape = "";
				$rodape.= "<tr bgcolor=\"" . $this->cor_lin1 . "\">";
				$rodape.= "<td height=\"20\" colspan=\"";
				$rodape.= sizeof($nomes) + (($this->setar) ? 1 : 0);
				$rodape.= "\">";

				if ($this->setar && $this->bt_excluir)	{
					$rodape.= "<input type=\"button\" value=\":: excluir marcados ::\" onclick=\"cExcluir()\" style=\"font-family: verdana; font-size: 8pt; color: #0000CC; background: #F0F0F0; cursor: hand\">";
				}

				if ($this->setar && $this->bt_incluir)	{
					if ($this->show_forms) {
				      $rodape.= "<input type=\"button\" value=\":: incluir novo ::\" onclick=\"formulario.style.visibility = 'visible'\" style=\"font-family: verdana; font-size: 8pt; color: #0000CC; background: #F0F0F0; cursor: hand\">";			
					} else {
				      $rodape.= "<input type=\"button\" value=\":: incluir novo ::\" onclick=\"cIncluir()\" style=\"font-family: verdana; font-size: 8pt; color: #0000CC; background: #F0F0F0; cursor: hand\">";
					}
				}				
				$rodape.= "\n<P>Total de <font color=\"#FF0000\"><b>##TotRegs##</b></font> registro(s).";		
				$rodape.= "	</td>";
				$rodape.= " </tr>";
				$rodape.= "</TABLE>";

				//************************************************************//
				// Fim do rodap� da tabela                                    //
				//************************************************************//				
				
				echo $cabecalho;
				
				$busca = "SELECT * FROM $tabela";
				if (trim($this->filtros)!="")	{
					$busca.=" " . $this->filtros;
				}
				if (trim($ordem)!="")	{
					if (!isset($this->agrupar[0]))	{
						$busca.=" ORDER BY $ordem";
					} else {
						$busca.=" ORDER BY " . $this->agrupar[0] . ", " . $ordem;
					}
				} else {
					if (isset($this->agrupar[0]))
						$busca.=" ORDER BY " . $this->agrupar[0];
				}
						
				if (is_integer($limite) && is_integer($inicio))	{
					if (($limite > 0) && ($inicio >= 0))	{
						$busca.=" LIMIT $inicio,$limite";	
					}
				}
							
				if ($this->sql($busca))	{
					$linha    = 0;
					$anterior = "";
					
					// calcula as vari�veis necess�rias a pagina��o de registros.
					$total_regs = $this->qtd;
					if ($this->paginacao > 0)	{
						$resto   = bcmod($total_regs,$this->paginacao);
						$paginas = (int) ($total_regs / $this->paginacao);
						if ($resto > 0)
							$paginas++;
					} else {
						$paginas = 1;
					}
					$paginainicial = 1;
					
					if (sizeof($this->totais) > 0)	{
						for ($j = 0; $j < sizeof($this->totais); $j++)	{
							$this->vtotais[strtolower($this->totais[$j])]    = 0.00;
							$this->vsubtotais[strtolower($this->totais[$j])] = 0.00;
						}
					}
						
					while ($this->dados())	{	
						$linha++;
						if (isset($this->agrupar[0]))	{
							if (($linha > 1) && ($anterior!=$this->data[$this->agrupar[0]]))	{
								echo "\n  <tr bgcolor=\"" . $this->cor_lin1 . "\">";
								if ($this->setar)
									echo "\n    <td></td>";
								for ($x = 0; $x <= $cont; $x++)	{
									if (($this->mostrar($nomes[$x])) && (strtolower($this->agrupar[0])!=strtolower($nomes[$x])))		{
										echo "\n    <TD nowrap " . $this->alinhar($tipos[$x], $nomes[$x]) . ">";	
										if (isset($this->vsubtotais[strtolower($nomes[$x])]))	{
											echo "\n		<B>";
											echo $this->formatar($tipos[$x], $this->vsubtotais[strtolower($nomes[$x])], $nomes[$x]);
											$this->vsubtotais[strtolower($nomes[$x])] = 0.00;
										} else {
											echo "\n        &nbsp;";
										}
										echo "\n    </TD>";
									}
								}
								echo "\n  </tr>";													
							}
						
							if (($linha==1) || ($anterior!=$this->data[$grupo]))	{
								$anterior = $this->data[$grupo];
								?>
  <tr>
    <td colspan="<? echo sizeof($nomes) + (($this->setar) ? 1 : 0) ?>">
    	Grupo:<b> <? echo $this->data[$grupo] ?>
    </td>
  </tr>
						    	<?								
							}
						}
						if ($this->setar)	{						
							echo "\n  <TR id=\"LIN" . $linha . "\" bgcolor=\"" . $this->cor_lin1 . "\" onmouseover=\"this.bgColor=CorLin2\" onmouseout=\"if (FRMLISTA.CHECK" . $linha . ".checked) { this.bgColor=CorSel; } else { this.bgColor=CorLin1; }\">";
							?>
    <td>
	  <input type="checkbox" name="CHECK<? echo $linha ?>" value="<? echo ($col_id >= 0) ? $this->data[$col_id] : "" ?>">
    </td>
							<?											
						} else {
							echo "\n  <TR id=\"LIN" . $linha . "\" bgcolor=\"" . $this->cor_lin1 . "\" onmouseover=\"this.bgColor=CorLin2\" onmouseout=\"this.bgColor=CorLin1\">";						
						}					
						for ($x = 0; $x <= $cont; $x++)	{
							if (($this->mostrar($nomes[$x])) && (strtolower($grupo)!=strtolower($nomes[$x])))		{
								if (isset($this->campo_esp[strtolower($nomes[$x])]))	{
									// se � um campo especial...
									echo "\n    <TD>";	
									echo "\n		" . $this->retornaesp($nomes[$x], $this->data[$x]);
									echo "\n    </TD>";									
								} else { // se � um campo normal...							
									echo "\n    <TD " . $this->alinhar($tipos[$x], $nomes[$x]) . ">";	
									if ((strtolower($nomes[$x])=="email") || (strtolower($nomes[$x])=="e-mail"))
										echo "\n		<a href=\"mailto:" . $this->data[$x] . "\">";
									if ((strtolower($nomes[$x])=="site") || (strtolower($nomes[$x])=="homepage"))
										echo "\n		<a href=\"http://" . $this->data[$x] . "\">";
										
									echo "\n		" . $this->formatar($tipos[$x], $this->data[$x], $nomes[$x]);
									
									if ((strtolower($nomes[$x])=="email") || (strtolower($nomes[$x])=="e-mail") || (strtolower($nomes[$x])=="site") || (strtolower($nomes[$x])=="homepage"))
										echo "</a>";								
																	
									echo "\n    </TD>";
								}
	
								if (isset($this->vsubtotais[strtolower($nomes[$x])]) && (is_numeric($this->data[$x]) or is_double($this->data[$x])))	{
									$this->vsubtotais[strtolower($nomes[$x])] += $this->data[$x];
									$this->vtotais[strtolower($nomes[$x])] += $this->data[$x];
								}
							}
						}
						// Mostra as op��es, se existirem e se setar = true.
						$t = sizeof($this->opcoes_val);
						if (($t > 0) and ($t = sizeof($this->opcoes_eti)))	{
							for ($x = 0; $x < $t; $x++)	{
								// Vai escrevendo as op��es...
								echo "\n	<TD align=\"center\" nowrap>";
								echo "\n		<a href=\"";
								echo (isset($this->data[$col_id])) ? str_replace("#Id#", $this->data[$col_id], $this->opcoes_val[$x]) : $this->opcoes_val[$x];
								echo "\">" . $this->opcoes_eti[$x] . "</a>";
								echo "\n	</TD>";
							}
						}
						// Fim das op��es.
						echo "\n  </TR>";
					}
														
					if ((sizeof($this->totais) > 0) && ($linha > 0))	{
						// subtotais do �ltimo grupo...
						if (isset($this->agrupar[0]))	{
							echo "\n  <tr bgcolor=\"" . $this->cor_lin1 . "\">";
							if ($this->setar)
								echo "\n    <td></td>";
							for ($x = 0; $x <= $cont; $x++)	{
								if (($this->mostrar($nomes[$x])) && (strtolower($grupo)!=strtolower($nomes[$x])))		{
									echo "\n    <TD nowrap " . $this->alinhar($tipos[$x], $nomes[$x]) . ">";	
									if (isset($this->vsubtotais[strtolower($nomes[$x])]))	{
										echo "\n<B>\n";
										echo $this->formatar($tipos[$x], $this->vsubtotais[strtolower($nomes[$x])], $nomes[$x]);
										$this->vsubtotais[strtolower($nomes[$x])] = 0.00;
									} else {
										echo "        &nbsp;";
									}
									echo "\n    </TD>";
								}
							}
							echo "\n  </tr>";	
						}
																							
						// totais da sele��o.
						echo "<tr bgcolor=\"" . $this->cor_lin1 . "\">";
						if ($this->setar)
							echo "\n<td></td>";
						for ($x = 0; $x <= $cont; $x++)	{
							if (($this->mostrar($nomes[$x])) && (strtolower($grupo)!=strtolower($nomes[$x])))		{
								echo "\n    <TD nowrap " . $this->alinhar($tipos[$x], $nomes[$x]) . ">";	
								if (isset($this->vtotais[strtolower($nomes[$x])]))	{
									echo "\n<B>\n";
									echo $this->formatar($tipos[$x], $this->vtotais[strtolower($nomes[$x])], $nomes[$x]);
								} else {
									echo "        &nbsp;";
								}
								echo "\n    </TD>";
							}
						}
						echo "\n  </tr>";						
					}
				}					
				echo str_replace("##TotRegs##", $this->qtd, $rodape);
?>				
<input type="hidden" name="TotRegs" value="<? echo $this->qtd ?>">
<input type="hidden" name="conAcao" value="">
</form>
<?
	// Cria os formul�rios junto com a tabela de solicitado pelo usu�rio
	if (($this->show_forms)&&($this->setar))	{
		?>
<DIV id="formulario" style="Z-INDEX: 1; VISIBILITY: hidden; POSITION: absolute; top: 100; left: 100">
	<table border="0" bgcolor="#004566" cellpadding="2" cellspacing="2">
	<tr><td align="center">
		<?
			$this->criarform($tabela, "Formul�rio de Inclus�o");
		?>
		<input type="button" value=":: fechar ::" name="btnFechar" onclick="formulario.style.visibility = 'hidden'" style="font-face: verdana; font-size: 8 pt; font-weight: bold; color: #FFFFFF; background: #808080; cursor: hand">
	</td></tr>
	</table>
</DIV>		
		<?		
	}
?>
<script>
	TotalRegs = <? echo $linha ?>;
</script>				
				<?
			
				return TRUE;
			} else {
				$this->erro = NAO_LISTA_TABELA . $tabela;
				return FALSE;
			}
		} else {
			$this->erro = MSG_NAO_CONECTADO;
			return FALSE;		
		}
	}
	
	function criarform($tabela="", $titulo="", $editar="")	{
		/*
			Esta fun��o cria um formul�rio na p�gina HTML para uma determinada tabela
			do banco de dados.
		*/
		$registros = array();
		if ($this->conectado)	{
			if ($editar != "")	{
				// se for para buscar um registro para edi��o
				// busca o primeiro registro com as condi��es informadas.
				if ($this->sql("select * from $tabela where $editar limit 0,1"))	{
					if ($this->dados())
						$registros = $this->data;
				}
			}	
		
			if ($this->sql("show columns from $tabela"))	{
				?>
<style>
	TABLE {
		font-size: <? echo $this->tam_font ?>;
		font-family: <? echo $this->fonte ?>
	}
</style>
<script language="javascript">
	CorSel  = "<? echo $this->cor_sel ?>";
	CorLin1 = "<? echo $this->cor_lin1 ?>";
	CorLin2 = "<? echo $this->cor_lin2 ?>";		
	
	// **************************************************************** //
	//                                                                  //
	//    ABAIXO AS FUN��ES DE VALIDA��O UTILIZADAS NO FORMUL�RIO       //
	//                                                                  //
	// **************************************************************** //
	
	function Valida_Num()	{
		if ((window.event.keyCode > 47) && (window.event.keyCode < 58))	{
			return true;
		} else {
			alert("Apenas n�meros neste campo.");
			return false;
		}
	}
	
	function Valida_Num2(atual)	{
		if ((window.event.keyCode == 44) || ((window.event.keyCode > 47) && (window.event.keyCode < 58)))	{
			if ((atual.indexOf(',', 0) >= 0) && (window.event.keyCode == 44))	{
				return false;
			}
			return true;
		} else {
			alert("Apenas n�meros ou v�rgula neste campo.");
			return false;
		}
	}
	
	function ImpDt(campo)	{
		if ((window.event.keyCode > 47) && (window.event.keyCode < 58))	{
			if ((campo.value.length == 2) || (campo.value.length == 5))	{
				campo.value = campo.value + "/";
			}
			return true;
		} else {
			alert("Apenas n�meros neste campo (n�o s�o necess�rias as barras).");
			return false;
		}
	}
	
	function ImpDtTime(campo)	{
		if ((window.event.keyCode > 47) && (window.event.keyCode < 58))	{
			if ((campo.value.length == 2) || (campo.value.length == 5))	{
				campo.value = campo.value + "/";
			} else {
				if (campo.value.length == 10)	{
					campo.value = campo.value + " ";
				} else {
					if ((campo.value.length == 13) || (campo.value.length == 16))	{
						campo.value = campo.value + ":";
					}
				}
			}
			return true;
		} else {
			alert("Apenas n�meros neste campo (n�o � necess�rio '/' ou ':').");
			return false;
		}	
	}
	
	function ImpHora(campo)	{
		if ((window.event.keyCode > 47) && (window.event.keyCode < 58))	{
			if ((campo.value.length == 2) || (campo.value.length == 5))	{
				campo.value = campo.value + ":";
			}
			return true;
		} else {
			alert("Apenas n�meros neste campo (n�o � necess�rio ':').");
			return false;		
		}	
	}
</script>
<form name="FRMCAD" action="<? echo $this->action ?>" method="post" <? echo (sizeof($this->campo_file) > 0) ? "enctype=\"multipart/form-data\"" : "" ?> onsubmit="return Valid_Campos()">
				<?
				echo "\n<TABLE cellspacing='1' border='" . $this->borda . "'>\n";
				if (trim($titulo!=""))	{
					?>
	<tr bgcolor="<? echo $this->cor_tit ?>">
		<td background="bar_titulo.jpg" align="center" colspan="2" nowrap>
			<font color="<? echo $this->cor_titfont ?>">
			<b><? echo $titulo ?></b>
		</td>
	</tr>
					<?				
				}
					?>
	<tr bgcolor="<? echo $this->cor_lin1 ?>">
		<td nowrap colspan="2">
			Os campos com * s�o obrigat�rios.
		</td>
	</tr> 					
					<?
				$cont = -1;
				while ($this->dados())	{
					// constr�i o formul�rio com os dados da tabela.
					$cont++;
					if ($this->mostrar($this->data[0]))	{
						?>
	<tr bgcolor="<? echo $this->cor_lin1 ?>" onmouseover="this.bgColor='<? echo $this->cor_lin2 ?>'" onmouseout="this.bgColor='<? echo $this->cor_lin1 ?>'">
		<td align="left">		
						<? 
						// Se possuir um nome informado pelo usu�rio, escreve-o como label do form,
						// se n�o, escreve o nome da coluna da tabela do banco de dados.
						if ($this->data[2] != "YES")	{
							echo "* ";
							// se for dado obrigat�rio cria a valida��o no form.
							$this->dados_notnull($this->data[0], $cont);
						}
						if (isset($this->titcolun[$cont]))	{
							echo $this->titcolun[$cont];
						} else {
							echo $this->data[0];			
						}
					?>
		</td>
		<td>
			<?
			// Escreve o campo HTML do fomul�rio na p�gina:
			if (isset($registros[$this->data[0]]))	{
				echo $this->campo($this->data[0], $this->data[1], $this->data[2], $registros[$this->data[0]], $this->data[5]);			
			} else {
				echo $this->campo($this->data[0], $this->data[1], $this->data[2], $this->data[4], $this->data[5]);
			}
			?>
		</td>
	</tr>					
						<?
					}
				}
				if ($this->bt_form)	{
					?>
	<tr bgcolor="<? echo $this->cor_lin1 ?>">
		<td colspan="2" align="center">
			<input type="submit" value=":: enviar ::" style="font-family: verdana; font-size: 8 pt; color: #0000cc; background: #f0f0f0; cursor: hand">
			<input type="reset" value=":: limpar ::" style="font-family: verdana; font-size: 8 pt; color: #0000cc; background: #f0f0f0; cursor: hand">
		</td>
	</tr>				
					<?
				}
					?>
</TABLE>
</FORM>
<script language="javascript">
	function Valid_Campos()	{
		<?
		// Escreva na p�gina a fun��o de valida��o de campos obrigat�rios. //
		echo $this->val_campos . "\n";	
		?>
		return true;	
	}
</script>					
					<?						
			} else {
				$this->erro = NAO_CRIA_FORM;
				return FALSE;			
			}		
		} else {
			$this->erro = MSG_NAO_CONECTADO;
			return FALSE;				
		}
	}
	
	function sobre()	{
	// Mostra os cr�ditos e ensina a utilizar a classe.	
		?>
			<table width="700" border="1" cellpadding="1" cellspacing="0">
				<tr bgcolor="#2980BE">
					<td height="100" align="center" nowrap colspan="2">
						<font face="verdana" size="1" color="#FFFFFF"><b>
						Classe wConn para PHP + Banco de dados MySQL<P>
						</b>
						Vers�o 3 - Mar�o de 2005
					</td>
				</tr>
				<tr bgcolor="#D8D8D8">
					<td align="justify" colspan="2">
						<font face="verdana" size="1">
						Esta � uma classe desenvolvida para realizar conex�es de maneira
						f�cil e eficiente com bancos de dados MySQL atraves do PHP.<P>
						Al�m de manipular instru��es SQL no banco, ela possui fun��es
						para criar rapidamente tabelas de dados e formul�rios de inclus�o/altera��o
						no browser do usu�rio, sem a necessidade de conhecimento de HTML ou
						utiliza��o de outras ferramentas de design.
					</td>
				</tr>
				<tr bgcolor="#004080">
					<td height="50" align="center" nowrap colspan="2">
						<font face="verdana" size="1" color="#FFFFFF"><b>
						Atributos da classe wConn
					</td>				
				</tr>
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						id
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este � o identificador da conex�o, � utilizado apenas pelo sistema.
					</td>
				</tr>
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						res
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Armazena o resultado das consultas (sql), utilizado apenas pelo sistema.
					</td>
				</tr>
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						qtd
					</td>
					<td width="550">
						<font face="verdana" size="1">
						N�mero inteiro maior ou igual a zero. Representa a quantidade de linhas retornadas
						por uma consulta (sql).
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						data
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Vetor que armazena os dados de uma determinada linha de uma consulta, cada posi��o
						do vetor retorna o dado de uma coluna da consulta.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						erro
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Armazena a �ltima mensagem de erro gravada pelo MySQL ou pela classe.
					</td>
				</tr>										
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						conectado
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica se h� uma conex�o ativa, retornando verdadeiro ou falso.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						titcolun
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Um vetor que armezena os t�tulos das colunas de uma tabela ou os labels dos
						campos do formul�rio, se n�o for informado, a tabela ou o formul�rio utilizar�o
						os nomes dos campos no banco de dados.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						ocultar
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Um vetor que armazena os nomes dos campos que n�o podem ser exibidos na tabela ao
						listar ou no formul�rio.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						cor_lin1
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica a cor da linha da tabela ou formul�rio.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						cor_lin2
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica a cor da linha da tabela ou formul�rio ao mover do mouse sobre a mesma.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						cor_tit
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica a cor do t�tulo dos campos de uma tabela ou a cor do t�tulo do formul�rio.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						cor_font
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica a cor da fonte da tabela ou formul�rio.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						cor_titfont
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica a cor da fonte do t�tulo da tabela ou formul�rio.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						cor_sel
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica a cor das linhas selecionadas de uma tabela.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						tam_font
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica o tamanho da fonte utilizada na tabela ou formul�rio (ex. 8pt).
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						fonte
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica o nome da fonte utilizada na tabela.
					</td>
				</tr>
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						setar
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica se a tabela deve possuir op��es de sele��o e bot�es de inclus�o/exclus�o.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						borda
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Expecifica a espessura da borda da tabela.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						action
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica a p�gina para onde os dados do formul�rio ser�o enviados quando o usu�rio submeter
						uma a��o (cadastro/altera��o).
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						bt_excluir
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica se o bot�o "excluir" dever� ser vis�vel na tabela de dados (true) ou n�o (false).
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						bt_incluir
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica se o bot�o "incluir" dever� ser vis�vel na tabela de dados (true) ou n�o (false).
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						totais
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Vetor no qual o usu�rio informa quais campos devem possuir os totais e subtotais
						informados na tabela.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						vsubtotais
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Vetor com os valores dos subtotais de uma tabela.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						vtotais
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Vetor com os valores dos totais de uma tabela.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						agrupar
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica o nome do campo pelo qual a tabela deve ser agrupada, se n�o informado a tabela
						n�o possuir� nenhum agrupamento.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						max_texto
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica o n�mero m�ximo de caracteres para uma c�lula de texto em uma tabela.<br>
						Se o texto ultrapassar este limite, um link "mais" ser� disponibilizado para que
						o usu�rio possa ler todo o texto em uma janela pop-up.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						load_img
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica se os campos com path de imagens devem carregar as imagens (true) ou apenas o
						path (false).
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						larg_img
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica a largura padr�o das imagens em uma tabela.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						bt_form
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica se os bot�es "enviar" e "limpar" devem ser exibidos no formul�rio
						de cadastro ou altera��o.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						val_campos
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Armazena o script de valida��o dos campos de um formul�rio.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						filtros
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Armazena os filtros de uma tabela (condi��es WHERE da SQL).
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						campo_esp
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Vetor que indica os campos referenciados (manual ou via outra tabela).
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						opcoes_val
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Vetor que armazena os valores (urls) programados para as op��es cadastradas em uma tabela.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						opcoes_eti
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Vetor que armazena as etiquetas (labels) das op��es cadastradas em uma tabela.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						campo_file
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Vetor que indica quais campos ser�o do tipo "file" no formul�rio de cadastro ou 
						altera��o.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						show_forms
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Indica se os formul�rios de inclus�o/altera��o estar�o dispon�veis ou n�o
						na mesma p�gina da tabela.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						readonly
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Vetor que indica quais campos da tabela dever�o ser do tipo somente leitura
						no formul�rio de inclus�o/altera��o.
					</td>
				</tr>	
				<tr bgcolor="#004080">
					<td height="50" align="center" nowrap colspan="2">
						<font face="verdana" size="1" color="#FFFFFF"><b>
						M�todos da classe wConn
					</td>				
				</tr>
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						wConn
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Construtor da classe, realiza a conex�o com o banco 
						no momento da instancia��o.
						Caso a conex�o falhe, o carregamento � interrompido e o usu�rio
						� notificado.<P>
						Os par�metros necess�rios s�o:<br>
						servidor: endere�o do servidor onde est� rodando o MySQL.<br>
						usuario: login para acesso.<br>
						senha: a senha do usuario.<br>
						nomebd: o nome do banco de dados que ser� aberto.<br>
						No caso de omiss�o dos dados, o PHP utilizar� os valores default para
						este m�todo, s�o eles:<br>
						servidor = "127.0.0.1";<br>
						usuario = "root";
						senha = "";
						nomebd = "test";<P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						$objeto = new wConn("servidor", "usuario", "senha", "banco");
					</td>			
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						executa
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo executa uma consulta (uma SQL) no banco de dados, caso exista uma conex�o 
						ativa. Retorna verdadeiro se conseguir realizar a consulta ou falso se algo sair errado.<br>
						Se n�o existir nenhuma conex�o ativa no momento ele gravar� o erro <? echo MSG_NAO_CONECTADO ?>
						e retornar� falso. Se ocorrer um erro do MySQL, o erro ser� armazenado na vari�vel erro e
						o m�todo retornar� falso.<P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						boolean $objeto->executa("meu select...")
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						sql
					</td>
					<td width="550">
						<font face="verdana" size="1">
						O mesmo que o m�todo executa.<P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						boolean $objeto->sql("meu select...")
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						manipula
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo executa uma DML (Data Manipulation Language), ou seja, ele
						pode executar INSERT INTO... ou UPDATE SET... por exemplo.<br>
						Deve ser utilizado para a inclus�o e altera��o de dados do banco.<br>
						Retorna verdadeiro se a opera��o for realizada corretamente ou falso
						se algo sair errado, neste cado a mensagem de erro ser� armazenada no vari�vel erro.
						Se n�o existir nenhuma conex�o ativa no momento ele gravar� o erro <? echo MSG_NAO_CONECTADO ?>
						e retornar� falso.<P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						boolean $objeto->manipula("meu insert... ou update...")
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						dados
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo armazena na vari�vel data os dados da pr�xima linha de uma consulta.<br>
						Retorna verdadeiro se existir uma consulta ativa e n�o tiver chegado ao fim da consulta.
						Retorna falso se chegar ao fim da consulta ou n�o existir nenhuma consulta ativa.<P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						boolean $objeto->dados()
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						fecha
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo encerra a conex�o com o banco de dados.<br>
						� aconselh�vel utilizar este m�todo sempre que finalizar o uso da classe em
						suas p�ginas.<P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						$objeto->fecha();
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						ad_campo_esp
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo permite a inclus�o de campos especiais na tabela ou formul�rio.
						<P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						$objeto->formatar("nome", "valores", "etiquetas", "tabref", "camporef", "campomostra", "tipo");</font><P>
						Este m�todo pode ser utilizado de duas formas:<br>
						<b>1) Criando refer�ncias a partir de uma lista informada no pr�prio c�digo.</b><br>
						Ex.: Suponha que temos um campo que armazena a unidade de medida de um item, 
						para fazer com que a tabela informe a descri��o das unidades ou com que o formul�rio
						informe radiobuttons com a descri��o as unidades fazemos o seguinte:<BR>
						<font color="#0000BB" face="courier">
						$objeto->ad_campo_esp("UN", array("kg", "lt"), array("Kilo", "Litro"), "", "", "", "radio");</font><P>
						<b>2) Criando refer�ncias com outras tabelas.</b><br>
						Ex.: Suponha que temos novamente um campo que armazena a unidade de medida de um item,
						por�m queremos informar a descri��o da unidade que est� armazenada em outra tabela
						do banco, e se for um formul�rio queremos exibir uma lista com as descri��es, 
						fazemos o seguinte:<BR>
						<font color="#0000BB" face="courier">
						$objeto->ad_campo_esp("UN", array(), array(), "tab_unidades", "cod_un", "Descricao", "lista");
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						filtrar
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo permite ao usu�rio informar condi��es de filtro para
						a tabela.<P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						$objeto->filtrar("CAMPO=VALOR");<p>
						ou<P>
						$objeto->filtrar("CAMPO LIKE VALOR");
					</td>
				</tr>
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						ad_opcao
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo permite ao usu�rio informar op��es que podem ser efetuadas para
						cada registro da tabela.<br>
						No par�metro etiqueta informa-se o nome ou label da op��o e no par�metro
						valor informa-se o url ou fun��o a ser chamada pela op��o.<br>
						Aten��o: para informar o Id de cada registro da op��o precisa-se utilizar 
						o texto #Id#. <P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						$objeto->ad_opcao("etiqueta", "valor");</font><P>
						Exemplos:<br>
						<font color="#0000BB" face="courier">
						$objeto->ad_opcao("Alterar", "alterar.php?codigo=#Id#");<br>						
						$objeto->ad_opcao("campo", "javascript: Excluir('#Id#')");						
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						listartabela
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo lista os dados de uma tabela do banco em uma tabela do browser
						do usu�rio de acordo com todas as propriedades informadas.<P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						$objeto->listartabela("nometabela", "ordem", "id", "in�cio", "limite");
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						criarform
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo cria um formul�rio para altera��o ou inclus�o de dados em uma
						tabela do banco no browser do usu�rio de acordo com todas as propriedades informadas.<P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						$objeto->criarform("nometabela", "titulo", "editar");</font><P>
						No par�metro "editar" o usu�rio informa a condi��o para buscar um registro para edi��o
						pelo formul�rio, ex. "codigo=8" ou "nome='luis' and idade > 18".<br>
						Se n�o informado o par�metro "editar" o formul�rio ser� apenas de inclus�o.
					</td>
				</tr>						
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						insere
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo executa uma inser��o de dados em uma tabela utilizando as 
						vari�veis POST enviadas por um formul�rio de uma p�gina HTML.<P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						bool $objeto->insere("nometabela", array("campo1", "campo2", etc));</font><P>
						No caso de retornar False, a mensagem de erro estar� na propriedade "erro" da classe.
					</td>
				</tr>										
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						atualiza
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo executa uma atualiza��o de dados em uma tabela utilizando as 
						vari�veis POST enviadas por um formul�rio de uma p�gina HTML.<P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						bool $objeto->atualiza("nometabela", "condi��o", array("campo1", "campo2", etc));</font><P>
						No caso de retornar False, a mensagem de erro estar� na propriedade "erro" da classe.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						remove
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Esta fun��o remove os registros de uma tabela de acordo com os par�metros enviados, sendo:
						Se $id (nome do campo chave da tabela) for <> "" ent�o o sistema eliminar�
						todos os registros existentes e marcados na lista de dados.
						Se $id for ignorado a remo��o ser� feita apenas com base no par�metro $condicao.													

						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						bool $objeto->remove("nometabela", "id", "condicao");</font><P>
						No caso de retornar False, a mensagem de erro estar� na propriedade "erro" da classe.
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						sobre
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo apresenta estas informa��es no browser do usu�rio.<P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						$objeto->sobre();
					</td>
				</tr>					
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						retornaesp
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo recebe o nome e valor de um campo especial 
						e retorna a etiqueta do mesmo.<P>
						<font color="#EE0000"><b>M�todo utilizado apenas pelo sistema.</b></font><P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						$objeto->retornaesp("campo", "valor");
					</td>
				</tr>
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						alinhar
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo recebe o nome e tipo de um campo e retorna o seu alinhamento
						nas c�lulas da tabela.<P>
						<font color="#EE0000"><b>M�todo utilizado apenas pelo sistema.</b></font><P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						$objeto->alinhar("tipo", "nome");
					</td>
				</tr>
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						campo
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo faz a inser��o do HTML de um campo no formul�rio
						de inclus�o/altera��o.<P>
						<font color="#EE0000"><b>M�todo utilizado apenas pelo sistema.</b></font><P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						$objeto->campo("nome", "tipo", "nulo", "padrao", "extra");
					</td>
				</tr>
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						imagem
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo verifica se o valor do campo representa um path de imagem v�lido
						e retorna TRUE ou FALSE.<P>
						<font color="#EE0000"><b>M�todo utilizado apenas pelo sistema.</b></font><P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						$objeto->imagem("texto");
					</td>
				</tr>				
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						mostrar
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo retorna TRUE se o campo informado poder ser mostra na tabela ou
						formul�rio.<P>
						<font color="#EE0000"><b>M�todo utilizado apenas pelo sistema.</b></font><P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						$objeto->mostrar("campo");
					</td>
				</tr>
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						criar_campo_file
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo retorna TRUE se o campo informado for do tipo "file" para 
						a constru��o do formul�rio.<P>
						<font color="#EE0000"><b>M�todo utilizado apenas pelo sistema.</b></font><P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						$objeto->criar_campo_file("campo");
					</td>
				</tr>				
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						formatar
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo recebe os dados de um campo do banco, verifica seu tipo e as
						configura��es da classe retornando o texto como deve ser apresentado no browser.<P>
						<font color="#EE0000"><b>M�todo utilizado apenas pelo sistema.</b></font><P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						$objeto->formatar("tipo", "valor", "nome");
					</td>
				</tr>	
				<tr bgcolor="#D8D8D8">
					<td>
						<font face="verdana" size="1"><b>
						dados_notnull
					</td>
					<td width="550">
						<font face="verdana" size="1">
						Este m�todo cria a valida��o para os campos obrigat�rios do formul�rio
						atrav�s de javascript.<P>
						<font color="#EE0000"><b>M�todo utilizado apenas pelo sistema.</b></font><P>
						Sintaxe:<br>
						<font color="#0000BB" face="courier">
						$objeto->dados_notnull("campo", "id_nome");
					</td>
				</tr>	
				<tr bgcolor="#2980BE">
					<td height="100" align="center" nowrap colspan="2">
						<font face="verdana" size="1" color="#FFFFFF"><b>
						Autor: Willian Fernando Soares<br>
						Agradecimentos pelas dicas fornecidas por:<br>
						Alfred Reinold Baudisch<br>
						Leandro Fernandes
						</b>
					</td>
				</tr>																																																		
			</table>
		<?
	}
}
?>