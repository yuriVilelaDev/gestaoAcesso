<?php 
$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$url = $_SERVER['REQUEST_URI'];
$my_url = explode('wp-content' , $url); 
$path = $_SERVER['DOCUMENT_ROOT']."/".$my_url[0];
	require_once '../../inc/class_Plataforma.php';
	include_once $path . '/wp-config.php';
	include_once $path . '/wp-load.php';
	include_once $path . '/wp-includes/wp-db.php';
	include_once $path . '/wp-includes/pluggable.php';
?>
<div class="modal-header">
    <h4 class="modal-title">Lista de plataforma</h4>
</div>
<table class="table table-bordered table-hover table-condensed listaClientes">
	<thead>
		<tr>
			<th>#</th>
			<th>Logo</th>
			<th>Nome </th>
			<th>Descrição</th>
			<th>Versão</th>
			<th>Release</th>
			<th>Status</th>
			<th>Ações</th>
		</tr>
	</thead>
	<tbody>
      <?php 
	  		session_start();  
			
			$listPlataformas = Plataforma::listarPlataformas($_SESSION['IDEmpresa']);
			if( empty($listPlataformas)){
				echo "tem nada";
			}else{
				// echo "<pre>";
				// print_r($listPlataformas);
				foreach($listPlataformas as $listPlataforma){
				?>
						<tr id='<?= $listPlataforma->IDPlataforma ?>'> 
					<td class='IDPlataforma'><?=$listPlataforma->IDPlataforma ?></td>
					<td class='IMLogoPlataforma'><?=$listPlataforma->IMLogoPlataforma ?></td>
					<td class='NMPlataforma'><?=$listPlataforma->NMPlataforma ?></td>
					<td class='DSPlataforma'><?=$listPlataforma->DSPlataforma ?></td>
					<td class='DSVersaoPlataforma'><?=$listPlataforma->DSVersaoPlataforma ?></td>
					<td class='DSReleasePlataforma'><?=$listPlataforma->DSReleasePlataforma ?></td>
					<td  class="situacao">
						<i class="fa fa-toggle-<?=$listPlataforma->STPlataforma?'on':'off';?>" aria-hidden="true"></i>
					</td>
					<td>
						<a 
						href="<?=$url_plugin.'&ID='.$listPlataforma->IDPlataforma?>"
						data-id="<?=$listPlataforma->IDPlataforma?>"
						id="editarPlataforma"
						>
							<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
						</a>
						<a 
						href="<?=$url_plugin.'&ID='.$listPlataforma->IDPlataforma?>"
						data-id="<?=$listPlataforma->IDPlataforma?>"
						data-nome="<?=$listPlataforma->NMPlataforma?>"

						id="excluirPlataforma"
						>
							<i class="fa fa-trash" aria-hidden="true"></i>
						</a>
               		</td>
					

				</tr>
				<?php
				
				}
				
				
			
				
			}
			
		?>
    <tbody>
</table>



