<?php require_once '../../inc/class_Plataforma.php';?>
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
			$listPerfilPlataforma = Plataforma::listarPerfilPlataforma($_SESSION['idPlataforma']);
            echo '<pre>';
            var_dump($listPerfilPlataforma);
			if( empty($listPerfilPlataforma)){
				echo "tem nada";
			}else{
				
				
				echo	"<tr id='".$listPerfilPlataforma["IDPlataforma"]."'> 
						<td class='IDPlataforma'>".$listPerfilPlataforma["IDPlataforma"]."</td>
						<td class='IMLogoPlataforma'>".$listPerfilPlataforma["IMLogoPlataforma"]."</td>
						<td class='NMPlataforma'>".$listPerfilPlataforma["NMPlataforma"]."</td>
						<td class='DSPlataforma'>".$listPlalistPerfilPlataformataforma["DSPlataforma"]."</td>
						<td class='DSReleasePlataforma'>".$listPerfilPlataforma["DSReleasePlataforma"]."</td>
						<td class='DSVersaoPlataforma'>".$listPerfilPlataforma["DSVersaoPlataforma"]."</td>
						<td class='situacao'>".$listPerfilPlataforma["situacao"]."</td>
						<td >
							 <a  href='javascript:void(0)'data-id='".$listPerfilPlataforma["IDPlataforma"]."'class='editarUsuario' id = 'modal_editar_usuario'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>
							 <a id='deletarPlataformas' href='javascript:void(0)'data-id='".$listPerfilPlataforma["IDPlataforma"]."'class='editarUsuario' ><i class='fa fa-trash' aria-hidden='true'></i></a>
							 <a  href='javascript:void(0)'data-id='".$listPerfilPlataforma["IDPlataforma"]."id = 'modal_infro_plataforma'><i class='fa fa-eye' aria-hidden='true'></i></a>
						</td>
					</tr>";
				
			}
			
		?>
    <tbody>
</table>



