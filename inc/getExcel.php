<?php
	include_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
    global $wpdb;
	
	//$q_registros = $_REQUEST['q_registros_local'];
	//$pagina = $_REQUEST['pagina_local'];
	//$offset = ($pagina-1)*$q_registros;
    
    $tabela = $wpdb->prefix.solicitacaoAcesso::get_prefix().'local';
	
	$where_clausula = '';
	//if($_REQUEST['s'] and $_REQUEST['aba']=='locais')
	//	$where_clausula  = ' WHERE nome LIKE "%'.$_REQUEST['s'].'%"';	

	$order_by = ' ORDER BY nome ASC ';
    
	$SQL = 'SELECT * FROM '.$tabela;
	$SQL .= $where_clausula;
	$SQL .= $order_by;
	//$SQL .= ' LIMIT '.$q_registros.' OFFSET '.$offset;
	
    
	$results = $wpdb->get_results($SQL);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Planilha Excel</title>
	</head>
	<body>
		<?php
		$arquivo = 'nomeArquivo.xls';
		

		$results = $wpdb->get_results($SQL);    
    	if($results){
			ob_start();
			?>
			<table border="1">
				<thead>
					<tr>
						<th>#</th>
						<th>Local</th>
						<th>Atributo</th>
						<th>Projeto Associado</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($results as $linha){?>
						<tr>
							<td><?=$linha->local_id?></th>
							<td><?=$linha->nome?></td>
							<td><?=solicitacaoAcesso::get_nomeAtributoLocal($linha->atributo)?></td>
							<td>(em construção)</td>
						</tr>	
					<?php }?>
				</tbody>
			</table>
        <?php
    	}
		
		$html = ob_get_clean();
		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: application/x-msexcel");
		header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
		header ("Content-Description: PHP Generated Data" );
		// Envia o conteúdo do arquivo
		echo $html;
		exit; ?>
	</body>
</html>