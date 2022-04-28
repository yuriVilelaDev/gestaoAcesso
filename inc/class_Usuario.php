<?php 
	include_once('class_Log.php');
	
	class Usuario{

		public static function listarUsuario($nomeUsuario){
			global $wpdb;
			try{
				$result = '
					<table class="table table-bordered table-hover table-condensed listaClientes">
					<thead>
						<tr>
							<th>#</th>
							<th>Nome</th>
							<th>E-MAIL</th>
							<th>Perfil</th>
							<th>Empresa</th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody>
				';
				if(!$nomeUsuario){
					$SQL = 'SELECT * FROM vw_userPerfilPermisao group by  ID_user;';
		 			$usuarios = $wpdb->get_results($SQL);
					if(!empty($usuarios)){
						foreach($usuarios as $usuario){
							$result .= '<tr id="'.$usuario->ID_user.'"> 
											<td class="user_id">'.$usuario->ID_user.'</td>
											<td class="user_nome">'.$usuario->user_nicename.'</td>
											<td class="user_email">'.$usuario->user_email.'</td>
											<td class="user_perfil">'.$usuario->DSPerfil.'</td>
											<td class="user_empresa">'.$usuario->NMFantasiaEmpresa.'</td>
											<td> <a  href="javascript:void(0)"data-id="'.$usuario->ID_user.'"class="editarUsuario" id = "modal_editar_usuario"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
										</tr>';
						}
						$result .= '</tbody>';
					}
				}
			}catch(\Throwable $th){
				$result = false;
				Log::registraLogSystem($th);
			}finally{
				return $result;
			}
			
		}

		public static function selectUsuarios(){
			global $wpdb;
			try{
				$SQL = 'SELECT * FROM wp_users ;';
                $usuarios = $wpdb->get_results($SQL);
				$result = '<select class="form-control select" name="IDUsuario" id="IDUsuario">
								<option value="" >Todas</option> ';
				foreach($usuarios as $usuario){
					$result .= '<option value="'.$usuario->ID.'" >'.$usuario->user_login.'</option>';          
				}
				$result .= '</select> ';
			
			}catch(\Throwable $th){
				$result = false;
				Log::registraLogSystem($th);
			}finally{
				return $result;
			}
		}

		public static function selectEmpresas(){
			global $wpdb;
			try{
				$SQL = 'SELECT * FROM wp_sigsa_parempresa ;';
                $usuarios = $wpdb->get_results($SQL);
				$result = '<select class="form-control select" name="IDEmpresa" id="IDEmpresa">
								<option value="" >Selecione uma empresa</option> ';
				foreach($usuarios as $usuario){
					$result .= '<option value="'.$usuario->IDEmpresa.'" >'.$usuario->NMRazaoEmpresa.'</option>';          
				}
				$result .= '</select> ';
			
			}catch(\Throwable $th){
				$result = false;
				Log::registraLogSystem($th);
			}finally{
				return $result;
			}
		}
	
		public static function selectPerfil(){
			global $wpdb;
			try{
				$SQL = 'SELECT * FROM wp_sigsa_acsperfilacessosigsa where NUNivel != 0 ;';
                $usuarios = $wpdb->get_results($SQL);
				$result = '<select class="form-control select" name="IDPerfil" id="IDPerfil">
								<option value="" >Selecione uma empresa</option> ';
				foreach($usuarios as $usuario){
					$result .= '<option value="'.$usuario->IDPerfil.'" >'.$usuario->DSPerfil.'</option>';          
				}
				$result .= '</select> ';
			
			}catch(\Throwable $th){
				$result = false;
				Log::registraLogSystem($th);
			}finally{
				return $result;
			}
		}

		public static function atribuiPerfil($dados){
			global $wpdb;
			try{
				$data = array(
					'ID'		=> $dados['IDUsuario'], 
					'IDPerfil' 	=> $dados['IDPerfil'],
					'IDEmpresa' => $dados['IDEmpresa']
				);
				$result = $wpdb->insert( 'wp_sigsa_acsuserperfilsigsa' , $data); 		
			}catch(\Throwable $th){
				$result = 0;
				Log::registraLogSystem(Throwable::getMessage());				
			}finally{
				return $result;
			}
		}

		public static function salvaUsuario($dados){
			global $wpdb;
			try{
				$data = array(
					'ID'		    => null, 
					'user_login' 	=> $dados['user_login'],
					'user_pass'		=> MD5($dados['user_pass']),
					'user_nicename'	=> $dados['user_nicename'],
					'user_email'    => $dados['user_email'],
					'user_url'		=> "null",
					'display_name' 	=> $dados['display_name']
				);
				$wpdb->insert( 'wp_users' , $data);
				$my_id = $wpdb->insert_id;
				if($my_id){
					
					$result['status'] = true;	
				}
			}catch(\Throwable $th){
				$result = 0;
				Log::registraLogSystem(Throwable::getMessage());
			}finally{
				return $result;
			}
		}

		public static function editarUsuario($dados){
			try{
				$data = array(
					'ID'		=> $dados['id'], 
					'user_login' 	=> $dados['user_login'],
					'user_pass'		=> MD5($dados['user_pass']),
					'user_nicename'	=> $dados['user_nicename'],
					'user_email'    => $dados['user_email'],
					'user_url'		=> $dados['user_url'],
					'display_name' 	=> $dados['display_name']
				);
				$wpdb->update( 'wp_users' , $data);
				$my_id = $wpdb->insert_id;
				if($my_id){
					
					$result['status'] = true;	
				}
			}catch(\Throwable $th){
				$result = false;
				Log::registraLogSystem($th);
			}finally{
				return $result;
			}
		}
	
	}