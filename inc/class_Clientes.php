<?php
/*
*  CLASS Clientes
*
*  manipula as funcoes relacionadas a configurações > Clientes 
*
*  @type	class
*  @date	12/08/2021
*  @since	1.0.0
*
*/
include_once('class_Log.php');
class Clientes
{
	
	private static $instance;

	/**
     * get_instance
     * 
     * @type	function
     * @date	12/08/21
     * @since	1.0.0
     * @param	N/A
     * @return	(Object) Clientes.
     * */
	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self;	
		}
		return self::$instance;
	}
	
	/** CONSTRUTOR 
	 *
	 * Acionada assim que a classe ou objeto dessa instancia é criada.
	 *
	 * @type	function
	 * @date	12/08/21
	 * @since	1.0.0
	 *
	 * @param	N/A
	 * @return	N/A
	 */
	private function __construct() {
	}//function __construct()	
	
	/** PRINTA 
	 * 
	 * Cria na tela para fins de debug um espaco destinado a listar o conteúdo de uma variável, array ou obejto.
	 * 
	 * @type	function
	 * @date	12/08/21
	 * @since	1.0.0
	 *
	 * @param	VAR|ARRAY|OBJ
	 * @return	HTML
	 */
	public static function printa($t){
		echo '<pre>';
		print_r($t);
		echo '</pre>';	
	}

    /** GET QUANTIDADE DE CLIENTES 
	 * 
	 * Obtem o numero de clientes listados com os filtros selecionados
	 * 
	 * @type	function
	 * @date	19/10/21
	 * @since	1.0.0
	 *
	 * @param	NULL
	 * @return	INT
	 */
    public static function getQuantidadeClientes(){
        global $wpdb;
        $BASCliente = $wpdb->prefix.sigsaClass::get_prefix().'bascliente';
        
		if( isset($_REQUEST['IDEmpresa']) ) $id_empresa = $_REQUEST['IDEmpresa']; else $id_empresa = false;

        if($id_empresa){
            $filtro['IDEmpresa'] = $id_empresa;
            $where = ' WHERE IDEmpresa='.$filtro['IDEmpresa'];
        }
        else{
            $where = '';
        }

        $q = $wpdb->get_var('SELECT COUNT(*) FROM '.$BASCliente.$where);
        return $q;
    }

	/** GET LISTA CLIENTES 
	 * 
	 * Levanta a lista de clientes e retorna em html
	 * 
	 * @type	function
	 * @date	19/08/21
	 * @since	1.0.0
	 *
	 * @param	VAR|ARRAY|OBJ
	 * @return	HTML
	 */
	public static function getListaClientesHTML($filtro = null){
		global $wpdb;
		$url_plugin = admin_url( 'admin.php?page=actcon-sigsa/view/page-clientes.php' );
		$BASCliente = $wpdb->prefix.sigsaClass::get_prefix().'bascliente';
		try{
			ob_start();
			if(isset($_REQUEST['IDEmpresa'])){
				$filtro['IDEmpresa'] = $_REQUEST['IDEmpresa'];
			}
			else{
				$filtro['IDEmpresa'] = "";
			}
			
			if($filtro['IDEmpresa']){
				$where = ' WHERE st_delete =0 AND IDEmpresa='.$filtro['IDEmpresa'];
			}
			else{
				$where = ' WHERE st_delete =0';
			}
			if (isset($_REQUEST['p']))$filtro['pagina'] = $_REQUEST['p']; else $filtro['pagina']=1;
			if (isset($_REQUEST['q_registros'])) $filtro['q_registros'] = $_REQUEST['q_registros']; 
			$filtro['pagina'] = $filtro['pagina']-1;
			$limit = ' LIMIT '.($filtro['pagina']*$filtro['q_registros']).','.$filtro['q_registros'];
			
			if( isset($_REQUEST['s']) ){
				$where .= ' AND ( NMRazaoCliente like "%'.$_REQUEST['s'].'%" OR NMFantasiaCliente like "%'.$_REQUEST['s'].'%" )';
			}
			$SQL = 'SELECT * FROM '.$BASCliente.$where.' ORDER BY NMRazaoCliente ASC '.$limit.';';

			$clientes = $wpdb->get_results($SQL);
			if( isset($_REQUEST['s']) ){
				$n_clientes = count($clientes);
				if($n_clientes == 0 )$str = 'nenhum registro';
				else if($n_clientes == 1 )$str = '1 registro';
					else $str = $n_clientes.' registros';
				echo '<p>Sua pesquisa por: "'.$_REQUEST['s'].'" encontrou '.$str.'.</p>';
				//echo $SQL;
			}

			if($clientes){
				?>
				<table class="table table-bordered table-hover table-condensed listaClientes">
					<thead>
						<tr>
							<th>#</th>
							<th>Razão Social</th>
							<th>Nome Fantasia</th>
							<th>CNPJ</th>
							<th>Website</th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody>

						<?php
						foreach($clientes as $cliente){
							//$logo_src = wp_get_attachment_image_url($empresa->IMLogoEmpresa,'thumbnail');
							?>
							<tr id="IDCliente<?=$cliente->IDCliente?>"> 
								<td><?=$cliente->IDCliente?></td>
								<td class="NMRazaoCliente">
									<a
									href="<?=$url_plugin.'&IDCliente='.$cliente->IDCliente?>"
									data-id="<?=$cliente->IDCliente?>"
									class="editarCliente">
										<?=$cliente->NMRazaoCliente?>
									</a>
								</td>
								<td class="NMFantasiaCliente"><?=$cliente->NMFantasiaCliente?></td>
								<td class="NUCnpjCliente"><?=$cliente->NUCnpjCliente?></td>
								<td class="EDWebsiteCliente"><?=$cliente->EDWebsiteCliente?></td>
								<td>
									<a 
									href="<?=$url_plugin.'&IDCliente='.$cliente->IDCliente?>"
									data-id="<?=$cliente->IDCliente?>"
									class="editarCliente"
									>
										<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
									</a>
									<a 
									href="<?=$url_plugin.'&IDCliente='.$cliente->IDCliente?>"
									data-id="<?=$cliente->IDCliente?>"
									data-razao="<?=$cliente->NMRazaoCliente?>"
									class="excluirCliente"
									>
										<i class="fa fa-trash" aria-hidden="true"></i>
									</a>
								</td>
							</tr>
							<?php
						}
					?>
					</tbody>
				</table>
				<?php
			}
			$html = ob_get_clean();
		}catch(\Throwable $th){
			$html = '<p>Não conseguimos montar a lista de clientes!</p>';
			$retorno['erro'] = 'Erro de código ao excluir';
			Log::registraLogSystem($th);
		}finally{
			return $html;
		}
	}

	/** SALVAR CLIENTE
	 * 
	 * Recebe os parametros do cliente e faz o salvamento DB
	 * @type	function
	 * @date	19/08/21
	 * @since	1.0.0
	 * 
	 * @param	NULL
	 * @return	INT IDCliente
	 */
    public static function salvarCliente($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível Salvar o cliente';
		global $wpdb;
		$BASCliente = $wpdb->prefix.sigsaClass::get_prefix().'bascliente';
		
		$data = array(
			'IDCliente'			=> null, 
			'NMRazaoCliente' 	=> $dados['NMRazaoCliente'],
			'NUCnpjCliente'		=> str_replace(['.','/','-'],'', $dados['NUCnpjCliente']),
			'EDWebsiteCliente'	=> $dados['EDWebsiteCliente'],
			'NMFantasiaCliente' => $dados['NMFantasiaCliente'],
			'IMLogoCliente'		=> $dados['IMLogoCliente'],
			'STCliente' 		=> $dados['STCliente'],
			'IDEmpresa' 		=> $dados['IDEmpresa']
		);

		if($dados['IDCliente']){
			//ALTERACAO DE CLIENTE
			//$format = array('%s','%d','%s');
			$data['IDCliente'] = $dados['IDCliente'];
			$where = array('IDCliente' => $dados['IDCliente']);
			$wpdb->update($BASCliente,$data,$where);
			$retorno['status'] = true;
			$retorno['IDCliente'] = $dados['IDCliente'];
			Log::registraLog($BASCliente,'UPDATE',get_current_user_id(),$data,$dados['IDEmpresa'] );
		}
		else{
			//INCLUSAO DE NOVO CLIENTE
			//$format = array('%s','%d','%s');
			$wpdb->insert( $BASCliente , $data);
			$my_id = $wpdb->insert_id;
			if($my_id){
				$retorno['status'] = true;
				$retorno['IDCliente'] = $my_id;
			}
			$data['IDCliente'] =  $my_id;
			Log::registraLog($BASCliente,'INSERT',get_current_user_id(),$data,$dados['IDEmpresa'] );
		}
		
		return json_encode($retorno);
	}

	/** EXCLUIR CLIENTE
	 * 
	 * Recebe os parametros do cliente e faz a update do campo st_delete para 1
	 * @type	function
	 * @date	14/01/22
	 * @since	1.0.0
	 * 
	 * @param	ARRAY dados
	 * @return	JSON dados de response
	 */
	public static function excluirCliente($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível realizar a operacao!';
		global $wpdb;
		try{
			$BASCliente = $wpdb->prefix.sigsaClass::get_prefix().'bascliente';
			if($dados['cliente']){
				//ALTERACAO DE CLIENTE
				//$format = array('%s','%d','%s');
				$data['st_delete'] = 1;
				$where = array('IDCliente' => $dados['cliente']);
				$wpdb->update($BASCliente,$data,$where);
				$retorno['status'] = true;
				$retorno['mensagem'] = 'O cliente '.$dados['razao'].' foi excluido com sucesso!';
				Log::registraLog($BASCliente,'UPDATE',get_current_user_id(),$data,$dados['IDEmpresa'] );
			}
		}catch(\Throwable $th){
			$retorno['status'] = false;
			$retorno['erro'] = 'Erro de código ao excluir o cliente';
			Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}

	/** SALVA ENDERECO CLIENTE
	 * 
	 * Recebe os parametros do endereco e faz o salvamento DB
	 * @type	function
	 * @date	06/01/22
	 * @since	1.0.0
	 * 
	 * @param	ARRAY DADOS
	 * @return	INT IDEnderecoCliente
	 */
    public static function salvaEnderecoCliente($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível Salvar o endereco';
		global $wpdb;
		try{
			$BASEnderecoCliente = $wpdb->prefix.sigsaClass::get_prefix().'basenderecocliente';
			
			$CDTipoEnderecoClienteJSON = json_encode($dados['CDTipoEndCliente']);
			$DSTelefoneEndClienteJSON = json_encode($dados['DSTelefoneEndCliente']);
			$DSEnderecoCliente = array(
				'cep' 			=> $dados['CEP'],
				'logradouro' 	=> $dados['logradouro'],
				'bairro' 		=> $dados['bairro'],
				'localidade'	=> $dados['localidade'],
				'uf' 			=> $dados['uf']
			);
			$DSEnderecoClienteJSON = json_encode($DSEnderecoCliente);
			$data = array(
				'IDEnderecoCliente'			=> null,
				'IDCliente'					=> $dados['IDCliente'],
				'CDTipoEnderecoClienteJSON'	=> $CDTipoEnderecoClienteJSON,
				'DSEnderecoClienteJSON'		=> $DSEnderecoClienteJSON,
				'NULogradouroEndCliente'	=> $dados['numero'],
				'DSComplementoEndCliente'	=> $dados['complemento'],
				'DSTelefoneEndClienteJSON'	=> $DSTelefoneEndClienteJSON,
				'st_delete'				=> 0
			);
			
			if(!$dados['logradouro'])return json_encode($retorno);

			if($dados['IDEnderecoCliente']){
				//ALTERACAO DE ENDERECO
				//$format = array('%s','%d','%s');
				$data['IDEnderecoCliente'] = $dados['IDEnderecoCliente'];
				$where = array('IDEnderecoCliente' => $dados['IDEnderecoCliente']);
				$wpdb->update($BASEnderecoCliente,$data,$where);
				$retorno['status'] = true;
				$retorno['IDEnderecoCliente'] = $dados['IDEnderecoCliente'];
				Log::registraLog($BASEnderecoCliente,'UPDATE',get_current_user_id(),$data,$dados['IDCliente'] );
			}
			else{
				//INCLUSAO DE NOVO PROJETO
				//$format = array('%s','%d','%s');
				$data['IDEmpresa'] = $dados['IDEmpresa'];
				$wpdb->insert($BASEnderecoCliente,$data);
				$my_id = $wpdb->insert_id;
				if($my_id){
					$retorno['status'] = true;
					$retorno['IDEnderecoCliente'] = $my_id;
				}
				$data['IDEnderecoCliente'] =  $my_id;
				Log::registraLog($BASEnderecoCliente,'INSERT',get_current_user_id(),$data,$dados['IDCliente'] );
			}
		}catch(\Throwable $th){
			$retorno['status'] = false;
			$retorno['erro'] = 'Erro de código no salvamento do endereco do cliente';
			Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}

	/** SALVA CONTATO CLIENTE
	 * 
	 * Recebe os parametros do contato e faz o salvamento DB
	 * @type	function
	 * @date	12/01/22
	 * @since	1.0.0
	 * 
	 * @param	ARRAY DADOS
	 * @return	INT IDContatoCliente
	 */
    public static function salvaContatoCliente($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível salvar o contato';
		global $wpdb;
		try{
			$BASContatoCliente = $wpdb->prefix.sigsaClass::get_prefix().'bascontatocliente';
			
			$DSReferenciaClienteJSON = json_encode($dados['DSReferenciaClienteJSON']);
			$DSTelefoneContatoClienteJSON = json_encode($dados['DSTelefoneContatoCliente']);
			
			$data = array(
				'IDContatoCliente'			=> null,
				'IDCliente'					=> $dados['IDCliente'],
				'DSReferenciaClienteJSON'	=> $DSReferenciaClienteJSON,
				'NMContatoCliente'			=> $dados['nome'],
				'DSSetorCliente'			=> $dados['setor'],
				'DSCargoCliente'			=> $dados['cargo'],
				'EDEmailContatoCliente'		=> $dados['email'],
				'DSTelefoneContatoClienteJSON'	=> $DSTelefoneContatoClienteJSON,
				'st_delete'				=> 0
			);
			
			if($dados['IDContatoCliente']){
				//ALTERACAO DE CONTATO
				//$format = array('%s','%d','%s');
				$data['IDContatoCliente'] = $dados['IDContatoCliente'];
				$where = array('IDContatoCliente' => $dados['IDContatoCliente']);
				$wpdb->update($BASContatoCliente,$data,$where);
				$retorno['status'] = true;
				$retorno['IDContatoCliente'] = $dados['IDContatoCliente'];
				Log::registraLog($BASContatoCliente,'UPDATE',get_current_user_id(),$data,$dados['IDCliente'] );
			}
			else{
				//INCLUSAO DE NOVO CONTATO
				//$format = array('%s','%d','%s');
				$data['IDEmpresa'] = $dados['IDEmpresa'];
				$wpdb->insert($BASContatoCliente,$data);
				$my_id = $wpdb->insert_id;
				if($my_id){
					$retorno['status'] = true;
					$retorno['IDContatoCliente'] = $my_id;
				}
				$data['IDContatoCliente'] =  $my_id;
				Log::registraLog($BASContatoCliente,'INSERT',get_current_user_id(),$data,$dados['IDCliente'] );
			}
		}catch(\Throwable $th){
			$retorno['status'] = false;
			$retorno['erro'] = 'Erro de código no salvamento do contato do cliente';
			Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}

	/** EXCLUIR DADOS DO CLIENTE
	 * 
	 * Recebe os parametros dos dados do cliente e faz a update do campo st_delete para 1
	 * @type	function
	 * @date	14/01/22
	 * @since	1.0.0
	 * 
	 * @param	ARRAY dados
	 * @return	JSON dados de response
	 */
	public static function excluirDadosCliente($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível realizar a operacao!';
		global $wpdb;
		try{
			switch($dados['tipo']){
				case 'contato':
					$tableAlvo = $wpdb->prefix.sigsaClass::get_prefix().'bascontatocliente';
					$where = array('IDContatoCliente' => $dados['id']);
				break;
				case 'endereco':
					$tableAlvo = $wpdb->prefix.sigsaClass::get_prefix().'basenderecocliente';
					$where = array('IDEnderecoCliente' => $dados['id']);
				break;
				default:
					$tableAlvo = null;
			}
			if($dados['id']){
				//$format = array('%s','%d','%s');
				$data['st_delete'] = 1;
				$wpdb->update($tableAlvo,$data,$where);
				$retorno['status'] = true;
				$retorno['mensagem'] = 'O '.$dados['tipo'].' foi excluido com sucesso!';
				Log::registraLog($tableAlvo,'UPDATE',get_current_user_id(),$data,$dados['id'] );
			}
		}catch(\Throwable $th){
			$retorno['status'] = false;
			$retorno['erro'] = 'Erro de código ao excluir';
			Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}

    /** GET CLIENTE 
	 * 
	 * Retorna um array contento a variável cliente
	 * 
	 * @type	function
	 * @date	04/01/22
	 * @since	1.0.1
	 *
	 * @param	VAR|ARRAY|OBJ
	 * @return	JSON
	 */
	public static function getCliente($dados){
        $retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível obter o cliente';
		global $wpdb;
		
		try{
			$BASCliente = $wpdb->prefix.sigsaClass::get_prefix().'bascliente';
			$BASEnderecoCliente = $wpdb->prefix.sigsaClass::get_prefix().'basenderecocliente';
			$BASContatoCliente = $wpdb->prefix.sigsaClass::get_prefix().'bascontatocliente';

			$SQL = <<<SQL
				SELECT 
					$BASCliente.IDCliente AS id_cliente,
					$BASCliente.NUCnpjCliente AS cnpj,
					$BASCliente.NMRazaoCliente AS razao_social,
					$BASCliente.NMFantasiaCliente AS nome_fantasia,
					$BASCliente.EDWebsiteCliente AS site,
					$BASCliente.IMLogoCliente AS logo,
					$BASCliente.STCliente AS status_cliente,
					$BASCliente.IDEmpresa AS id_empresa,
					CONCAT( '[',
						GROUP_CONCAT(
							'{'
							,'"end_id":',$BASEnderecoCliente.IDEnderecoCliente
							,',','"end_end":',$BASEnderecoCliente.DSEnderecoClienteJSON
							,',','"end_logradouro":"',$BASEnderecoCliente.NULogradouroEndCliente,'"'
							,',','"end_complemento":"',COALESCE($BASEnderecoCliente.DSComplementoEndCliente,'0'),'"'
							,',','"end_telefone":',$BASEnderecoCliente.DSTelefoneEndClienteJSON
							,',','"end_tipo":',$BASEnderecoCliente.CDTipoEnderecoClienteJSON
							,'}'
						SEPARATOR ',') 
					,']') AS enderecos,
					CON.contatos
				FROM
					$BASCliente
				LEFT JOIN $BASEnderecoCliente
					ON ($BASCliente.IDCliente = $BASEnderecoCliente.IDCliente)
				LEFT JOIN(
					SELECT 
						$BASCliente.IDCliente,
						CONCAT( '[',
							GROUP_CONCAT(
								'{'
								,'"con_id":',$BASContatoCliente.IDContatoCliente
								,',"nome":"',$BASContatoCliente.NMContatoCliente,'"'
								,',"setor":"',COALESCE($BASContatoCliente.DSSetorCliente,''),'"'
								,',"cargo":"',COALESCE($BASContatoCliente.DSCargoCliente,''),'"'
								,',"tipo":',COALESCE($BASContatoCliente.DSReferenciaClienteJSON,'[]')
								,',"email":"',COALESCE($BASContatoCliente.EDEmailContatoCliente,''),'"'
								,',"telefone":',COALESCE($BASContatoCliente.DSTelefoneContatoClienteJSON,'[]')
								,',"con_end_id":"',COALESCE($BASContatoCliente.IDEnderecoCliente,''),'"'
								,',"con_user_id":"',COALESCE($BASContatoCliente.IDUsuario,''),'"'
								,'}'
							SEPARATOR ',')
						,']') AS contatos
					FROM $BASCliente
					LEFT JOIN $BASContatoCliente
							ON ($BASCliente.IDCliente = $BASContatoCliente.IDCliente)
					WHERE $BASCliente.IDCliente = :IDCliente
						AND $BASContatoCliente.st_delete != 1
				) AS CON 
					ON ($BASCliente.IDCliente = CON.IDCliente)
				WHERE $BASCliente.IDCliente = :IDCliente
					AND $BASEnderecoCliente.st_delete != 1;
			SQL;
			$SQL = str_replace(':IDCliente',$dados['IDCliente'],$SQL);

			$cliente = $wpdb->get_row($SQL,'ARRAY_A');

			if($cliente){
				$BASContrato = $wpdb->prefix.sigsaClass::get_prefix().'bascontrato';
				$SQL = "SELECT * FROM $BASContrato WHERE IDCliente = :IDCliente and st_delete=0;";
				$SQL = str_replace(':IDCliente',$dados['IDCliente'],$SQL);
				$contratos = $wpdb->get_results($SQL,'ARRAY_A');
				if($contratos)$cliente['contratos'] = $contratos;
			}

			if($cliente){
				$retorno['status'] = true;
				$retorno['erro'] = '';
				//$cliente['sql'] = $SQL;
				$imagem = wp_get_attachment_image_url($cliente['logo'],'thumbnail');
				if($imagem)
					$cliente['IMLogoCliente_src'] = $imagem;
				else
					$cliente['IMLogoCliente_src'] = '';
				$retorno['Cliente'] = $cliente;
			}

		}catch(\Throwable $th){
			$retorno['status'] = false;
			$retorno['erro'] = 'Não foi possível obter o cliente';
			Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}

	/** GET ENDERECO CLIENTE 
	 * 
	 * Retorna um array contento os dados do endereço do cliente solicidado
	 * 
	 * @type	function
	 * @date	06/01/22
	 * @since	1.0.1
	 *
	 * @param	VAR|ARRAY|OBJ
	 * @return	JSON
	 */
	public static function getEnderecoCliente($dados){
        $retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível obter o cliente';
		global $wpdb;
		$IDEnderecoCliente = $dados['IDEnderecoCliente'];
		if(!isset($IDEnderecoCliente)){
			$retorno['erro'] = 'Parametros incorretos!';
			return json_encode($retorno);	
		}
		try{
			$BASEnderecoCliente = $wpdb->prefix.sigsaClass::get_prefix().'basenderecocliente';
			$SQL = <<<SQL
				SELECT * FROM $BASEnderecoCliente WHERE IDEnderecoCliente = $IDEnderecoCliente limit 1;
			SQL;
			$dadosEndereco = $wpdb->get_row($SQL,'ARRAY_A');
			if($dadosEndereco){
				$retorno['status'] = true;
				unset($retorno['erro']);
				$retorno['endereco'] = $dadosEndereco;
			}
		}catch(\Throwable $th){
			$retorno['status'] = false;
			$retorno['erro'] = 'O código esta com erro';
			Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}

	/** GET CONTATO CLIENTE 
	 * 
	 * Retorna um array contento os dados do contato do cliente solicidado
	 * 
	 * @type	function
	 * @date	12/01/22
	 * @since	1.0.1
	 *
	 * @param	VAR|ARRAY|OBJ
	 * @return	JSON
	 */
	public static function getContatoCliente($dados){
        $retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível obter o contato do cliente';
		global $wpdb;
		$IDContatoCliente = $dados['IDContatoCliente'];
		if(!isset($IDContatoCliente)){
			$retorno['erro'] = 'Parametros incorretos!';
			return json_encode($retorno);	
		}
		try{
			$BASContatoCliente = $wpdb->prefix.sigsaClass::get_prefix().'bascontatocliente';
			$SQL = <<<SQL
				SELECT * FROM $BASContatoCliente WHERE IDContatoCliente = $IDContatoCliente limit 1;
			SQL;
			$dadosContato = $wpdb->get_row($SQL,'ARRAY_A');
			if($dadosContato){
				$retorno['status'] = true;
				unset($retorno['erro']);
				$retorno['contato'] = $dadosContato;
			}
		}catch(\Throwable $th){
			$retorno['status'] = false;
			$retorno['erro'] = 'O código esta com erro';
			Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}

	/** Get METADADOS OPCOES
	 * 
	 * Recebe o nome do metadado e realiza a busca das opcoes
	 * @type	function
	 * @date	06/01/22
	 * @since	1.0.0
	 * 
	 * @param	STRING
	 * @return	ARRAY
	 */
	public static function getGERMetadadoOpcoes($NMCampoMetadado){
		global $wpdb;
		$GERMetadado = $wpdb->prefix.sigsaClass::get_prefix().'germetadado';
		$SQL = 'SELECT * FROM '.$GERMetadado.' WHERE NMCampoMetadado="'. $NMCampoMetadado .'" ORDER BY DSOpcaoMetadado ASC LIMIT 20;';
		return $wpdb->get_results($SQL);
		//return $SQL;
	}

	/**	GET FORM ANEXAR CONTRATO
	 * 
	 * 
	 * @type	function
	 * @date	04/03/22
	 * @since	1.0.0
	 * 
	 * @param	Array dados
	 * @return	HTML
	 */
	public static function getFormContrato($dados){
		// Obtem as opcoes de situação contrato projeto em metadados
		/*
		$situacaoContratoProjeto = '';
		$CDSituacaoContratoProjeto_lista = SELF::getGERMetadadoOpcoes('CDSituacaoContratoProjeto');
		foreach($CDSituacaoContratoProjeto_lista as $item){
			$data_class = $item->DSOpcaoMetadado;
			$data_class = preg_replace(array("/(A|ã)/","/(E)/","/(I)/","/(O)/","/(U)/","/(C)/","/ /"),explode(" ","a e i o u c -"),$data_class);
			$situacaoContratoProjeto.= '<option data-class="'.$data_class.'" value="'. $item->IDMetadado .'">'. $item->DSOpcaoMetadado .'</option>';
		}
		*/
		$arquivo = file_get_contents(SIGSA_PATH.'view/forms/cadastro-cliente-adiciona-contrato.html');
		//$arquivo = str_replace('{:situacaoContratoProjeto}',$situacaoContratoProjeto,$arquivo);
		ob_start();
		echo $arquivo;
		$html = ob_get_clean();
		return $html;
	}

	/**	GET LISTA CONTRATOS AJAX - ANEXAR CONTRATO
	 * Obtem a lista de contratos possíveis a serem anexados ao cliente que está sendo editado.
	 * 
	 * @type	function
	 * @date	04/03/22
	 * @since	1.0.0
	 * 
	 * @param	Array dados
	 * @return	HTML
	 */
	public static function getListaContratos_ajax($dados){
		global $wpdb;
		$BASContrato = $wpdb->prefix.sigsaClass::get_prefix().'bascontrato';
		$BASCliente = $wpdb->prefix.sigsaClass::get_prefix().'bascliente';
		try{
			$IDEmpresa = VerificacaoAcesso::getIDEmpresa();
			ob_start();
			if( isset($dados['s']) ){
				$pesquisa = $dados['s'];
				$IDContrato = $dados['contratoid'];
				//$IDContrato = $dados['contratoid'];
				$SQL = <<<SQL
					SELECT 
						$BASContrato.*
					FROM 
						$BASContrato
						INNER JOIN $BASCliente 
							ON ($BASContrato.IDCliente = $BASCliente.IDCliente) 
					WHERE 
						$BASContrato.IDCliente = null
						AND $BASCliente.IDEmpresa = $IDEmpresa
						AND ( $BASContrato.CDContrato like "%$pesquisa%" OR $BASContrato.DSContrato like "%$pesquisa%" )
					LIMIT 0,20;
				SQL;
				$contratos = $wpdb->get_results($SQL);
				if ($contratos){
					foreach($contratos as $contrato){
						?><li>
							<a href="javascript:void(0)" 
							data-id="<?=$contrato->IDContrato?>"><?=$contrato->CDContrato?> - <?=$contrato->DSContrato?></a>
						</li><?php	
					}
				}else{
					echo '<li>Nenhum contrato encontrado</li>';
				}
			}
			$lista = ob_get_clean();
		}catch(\Throwable $th){
			$lista = '<li>Ocorreu um erro inesperado na pesquisa</li>';
			//Log::registraLogSystem($th);
		}finally{
			return $lista;
		}
	}
}