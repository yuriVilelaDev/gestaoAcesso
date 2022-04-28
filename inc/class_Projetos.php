<?php
/*
*  CLASS Projetos
*
*  manipula as funcoes relacionadas a Projetos 
*
*  @type	class
*  @date	01/02/2022
*  @since	1.0.0
*
*/
include_once('class_Log.php');
class Projetos
{
	
	private static $instance;

	/** GET INSTANCE
     * 
     * @type	function
     * @date	01/02/2022
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
	 * @date	01/02/2022
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
	 * @date	01/02/2022
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

	/**	GET EMPRESA SESSIOM
	 * 
	 * Retorna o IDEmpresa gravado em empresa
	 * @type	function
	 * @date	11/02/22
	 * @since	1.0.0
	 * 
	 * @param	Array dados
	 * @return	HTML
	 */
	private static function getIDEmpresa(){
		session_start();
		$empresa = ( isset($_SESSION['IDEmpresa']) )? $_SESSION['IDEmpresa'] : NULL; 
		return $empresa;
	}

	/** GET LISTA PROJETOS 
	 * 
	 * Levanta a lista de projetos e retorna em objeto JSON
	 * 
	 * @type	function
	 * @date	01/02/2022
	 * @since	1.0.0
	 *
	 * @param	VAR|ARRAY|OBJ
	 * @return	HTML
	 */
	public static function getListaProjetos($filtro = null){
		global $wpdb;
		$BASProjeto = $wpdb->prefix.sigsaClass::get_prefix().'basprojeto';
		try{
			if(isset($_REQUEST['IDEmpresa'])){
				$filtro['IDEmpresa'] = $_REQUEST['IDEmpresa'];
			}
			else{
				$filtro['IDEmpresa'] = "";
			}
			
			if($filtro['IDEmpresa']){
				$where = ' WHERE projeto.st_delete =0 AND projeto.IDEmpresa='.$filtro['IDEmpresa'];
			}
			else{
				$where = ' WHERE projeto.st_delete =0';
			}
			if (isset($_REQUEST['p']))$filtro['pagina'] = $_REQUEST['p']; else $filtro['pagina']=1;
			if (isset($_REQUEST['q_registros'])) $filtro['q_registros'] = $_REQUEST['q_registros']; 
			$filtro['pagina'] = $filtro['pagina']-1;
			$limit = ' LIMIT '.($filtro['pagina']*$filtro['q_registros']).','.$filtro['q_registros'];
			
			if( isset($_REQUEST['s']) ){
				$where .= ' AND ( projeto.NMProjeto like "%'.$_REQUEST['s'].'%" OR projeto.DSProjeto like "%'.$_REQUEST['s'].'%" )';
			}
			$SQL = <<<SQL
				SELECT projeto.*
				FROM $BASProjeto AS projeto  
				$where
				ORDER BY projeto.IDProjeto DESC $limit;
			SQL;
			$projetos = $wpdb->get_results($SQL);
			if( isset($_REQUEST['s']) ){
				$n_projetos = count($projetos);
				if($n_projetos == 0 )$str = 'nenhum registro';
				else if($n_projetos == 1 )$str = '1 registro';
					else $str = $n_projetos.' registros';
				echo '<p>Sua pesquisa por: "'.$_REQUEST['s'].'" encontrou '.$str.'.</p>';
			}
		}catch(\Throwable $th){
			$projetos = false;
			Log::registraLogSystem($th);
		}finally{
			return $projetos;
		}
	}

	/** GET PROJETO 
	 * 
	 * Retorna um array contento a variável projeto
	 * 
	 * @type	function
	 * @date	01/02/2022
	 * @since	1.0.1
	 *
	 * @param	VAR|ARRAY|OBJ
	 * @return	OBJ|ARRAY
	 */
	public static function getProjeto($dados){
		global $wpdb;
		try{
			$BASProjeto = $wpdb->prefix.sigsaClass::get_prefix().'basprojeto';
			$BASContrato = $wpdb->prefix.sigsaClass::get_prefix().'bascontrato';
			$DEFContratoProjeto = $wpdb->prefix.sigsaClass::get_prefix().'defcontratoprojeto';
			$SQL = <<<SQL
				SELECT 
					$BASProjeto.*,
					$DEFContratoProjeto.*,
					$BASContrato.*
				FROM
					$BASProjeto
					LEFT JOIN $DEFContratoProjeto ON($DEFContratoProjeto.IDProjeto=$BASProjeto.IDProjeto)
					LEFT JOIN $BASContrato ON($DEFContratoProjeto.IDContrato=$BASContrato.IDContrato)
				WHERE $BASProjeto.IDProjeto = :IDProjeto
					AND $BASProjeto.st_delete != 1;
			SQL;

			$SQL = str_replace(':IDProjeto',$dados['ID'],$SQL);
			$projeto = $wpdb->get_row($SQL);


			//$projeto->SQL = $SQL;

			if(!$projeto){
				$projeto = (object) array('IDProjeto' => null);
			}
		}catch(\Throwable $th){
			$projeto = false;
			Log::registraLogSystem($th);
		}finally{
			return $projeto;
		}
	}

	/** GET QUANTIDADE DE PROJETOS 
	 * 
	 * Obtem o numero de projetos listados com os filtros selecionados
	 * 
	 * @type	function
	 * @date	01/02/2022
	 * @since	1.0.0
	 *
	 * @param	NULL
	 * @return	INT
	 */
    public static function getQuantidadeProjetos(){
        global $wpdb;
        $BASProjeto = $wpdb->prefix.sigsaClass::get_prefix().'basprojeto';
        
		if( isset($_REQUEST['IDEmpresa']) ) $id_empresa = $_REQUEST['IDEmpresa']; else $id_empresa = false;

        if($id_empresa){
            $filtro['IDEmpresa'] = $id_empresa;
            $where = ' WHERE st_delete =0 AND IDEmpresa='.$filtro['IDEmpresa'];
        }
        else{
            $where = ' WHERE st_delete =0';
        }
        $q = $wpdb->get_var('SELECT COUNT(*) FROM '.$BASProjeto.$where);
        return $q;
    }

	/** SALVAR PROJETO
	 * 
	 * Recebe os parametros do projeto e faz o salvamento DB
	 * tem a função de verificar se ja existe um vinculo com contrato e salvar ou criar um novo vinculo. 
	 * @type	function
	 * @date	01/02/2022
	 * @since	1.0.0
	 * 
	 * @param	ARRAY | dados
	 * @return	INT idprojeto
	 */
    public static function salvarProjeto($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível Salvar o projeto';
		//return json_encode($retorno);
		global $wpdb;
		$BASProjeto = $wpdb->prefix.sigsaClass::get_prefix().'basprojeto';
		$DEFContratoProjeto = $wpdb->prefix.sigsaClass::get_prefix().'defcontratoprojeto';
		try{
			$IDEmpresa = SELF::getIDEmpresa();
			if( !isset($IDEmpresa) ){$retorno['erro']='Empresa não encotrada';return json_encode($retorno);}
			$data = array(
				'IDProjeto'		=> null,
				'NMProjeto'		=> $dados['nome'],
				'DSProjeto'		=> $dados['descricao'],
				'IMLogoProjeto'	=> $dados['logo_projeto'],
				'STProjeto'		=> $dados['status'],
				'IDEmpresa'		=> $IDEmpresa,
				'st_delete'	=> 0
			);
			if($dados['projetoid']){
				//ALTERACAO DE PROJETO
				//$format = array('%s','%d','%s');
				$data['IDProjeto'] = $dados['projetoid'];
				$where = array('IDProjeto' => $dados['projetoid']);
				$wpdb->update($BASProjeto,$data,$where);
				$retorno['status'] = true;
				$retorno['projetoid'] = $dados['projetoid'];
				Log::registraLog($BASProjeto,'UPDATE',get_current_user_id(),$data,$IDEmpresa );
			}
			else{
				//INCLUSAO DE NOVO PROJETO
				//$format = array('%s','%d','%s');
				$wpdb->insert( $BASProjeto , $data);
				$my_id = $wpdb->insert_id;
				if($my_id){
					$retorno['status'] = true;
					$retorno['projetoid'] = $my_id;
				}
				$data['IDProjeto'] =  $my_id;
				Log::registraLog($BASProjeto,'INSERT',get_current_user_id(),$data,$dados['empresaid'] );
			}
			// VINCULO ENTRE PROJETO E CONTRATO
			if( $dados['contratoid'] != '' ){
				$IDContrato = $dados['contratoid'];
				$IDProjeto =  $data['IDProjeto'];
				$data = array(
					'CDSituacaoContratoProjeto'	=> $dados['situacaoContratoProjeto'],
					'DTInicioContratoProjeto'	=> $dados['inicioProjeto'],
					'DTTerminoContratoProjeto'	=> $dados['terminoProjeto'],
					'STUsarGestaoSolicitacao'	=> $dados['gestaoSolicitacaoAcesso'],
					'STUsarPerfilAcessoPadrao'	=> $dados['usoPerfilPadrao'],
					'STUsarLocal'				=> $dados['usoLocalPadrao'],
					'STCadastrarEstudante'		=> $dados['cadastroEstudante'],
					'IDEmpresa'					=> $IDEmpresa
				);
				$SQL = "SELECT * FROM $DEFContratoProjeto WHERE IDContrato=$IDContrato AND IDProjeto=$IDProjeto limit 1; ";
				$vinculo = $wpdb->get_row($SQL);
				if($vinculo){
					$where = array('IDContrato' => $IDContrato,'IDProjeto' => $IDProjeto);
					$wpdb->update($DEFContratoProjeto,$data,$where);
				}
				else{
					$where = array('IDProjeto' => $IDProjeto);
					$wpdb->delete( $DEFContratoProjeto,$where );
					$data['IDContrato'] = $IDContrato;
					$data['IDProjeto'] = $IDProjeto;
					$wpdb->insert( $DEFContratoProjeto,$data );
				}
			}
			
		}catch(\Throwable $th){
			$retorno['erro'] = 'Houve um erro ao salvar o projeto!';
			Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}

	/** EXCLUIR PROJETO
	 * 
	 * Recebe os parametros do projeto e faz a update do campo st_delete para 1
	 * @type	function
	 * @date	01/02/2022
	 * @since	1.0.0
	 * 
	 * @param	ARRAY dados
	 * @return	JSON dados de response
	 */
	public static function excluirProjeto($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível realizar a operacao!';
		global $wpdb;
		try{
			$BASProjeto = $wpdb->prefix.sigsaClass::get_prefix().'basprojeto';
			if($dados['projetoid']){
				//ALTERACAO DE PROJETO
				//$format = array('%s','%d','%s');
				$data['st_delete'] = 1;
				$where = array('IDProjeto' => $dados['projetoid']);
				$wpdb->update($BASProjeto,$data,$where);
				$retorno['status'] = true;
				$retorno['mensagem'] = 'O projeto '.$dados['descricao'].' foi excluido com sucesso!';
				Log::registraLog($BASProjeto,'UPDATE',get_current_user_id(),$data,$dados['projetoid'] );
			}
		}catch(\Throwable $th){
			$retorno['status'] = false;
			$retorno['erro'] = 'Erro de código ao excluir o projeto';
			Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}

	/** Get METADADOS OPCOES
	 * 
	 * Recebe o nome do metadado e realiza a busca das opcoes
	 * @type	function
	 * @date	01/02/2022
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
	
	/**	getlistaContratos_ajax
	 * 
	 * Recebe parametros de busca e retorna uma lista com os itens encontrados
	 * @type	function
	 * @date	11/02/22
	 * @since	1.0.0
	 * 
	 * @param	Array dados
	 * @return	HTML
	 */
	public static function getlistaContratos_ajax($dados){
		global $wpdb;
		$BASContrato = $wpdb->prefix.sigsaClass::get_prefix().'bascontrato';
		$BASCliente = $wpdb->prefix.sigsaClass::get_prefix().'bascliente';
		try{
			$empresa = SELF::getIDEmpresa();
			if($empresa){
				if( isset($dados['s']) ){
					$pesquisa = $dados['s'];
					$contratoAtual = ($dados['contratoAtual'])?$dados['contratoAtual']:"''";
					$SQL = <<<SQL
						SELECT
							$BASContrato.IDContrato,
							$BASContrato.CDContrato,
							$BASContrato.DSContrato
						FROM
							$BASContrato
							INNER JOIN $BASCliente on ($BASCliente.IDCliente = $BASContrato.IDCliente )
						WHERE 
							$BASCliente.IDEmpresa = $empresa
							AND $BASContrato.st_delete = '0'
							AND $BASContrato.IDContrato != $contratoAtual
							AND ( $BASContrato.CDContrato like "%$pesquisa%" 
								OR $BASContrato.DSContrato like "%$pesquisa%" )
						LIMIT 0,20;	
					SQL;
				}
				$contratos = $wpdb->get_results($SQL);
				ob_start();
				if ($contratos){
					foreach($contratos as $contrato){
						?><li>
							<a href="javascript:void(0)" 
								data-id="<?=$contrato->IDContrato?>"
								data-codigo="<?=$contrato->CDContrato?>"
								data-descricao="<?=$contrato->DSContrato?>"
								>
								<?=$contrato->CDContrato?> - <?=$contrato->DSContrato?>
							</a>
						</li><?php	
					}
					$lista = ob_get_clean();
				}else{
					$lista = '<li>Nenhum registro encontrado</li>';
				}
			}
			else{
				$lista = '<li>Erro! empresa nao identificada</li>';
			}
		}catch(\Throwable $th){
			$lista = '<li>Ocorreu um erro inesperado na pesquisa</li>';
			Log::registraLogSystem($th);
		}finally{
			return $lista;
		}
	}

	public static function getLocaisCadastradosHTML($dados){
		
		
		ob_start();
		?>
		<tr id="LOCAL_"> 
			<td class="nome"></td>
			<td>
				<a 
				href="#" data-id=""
				class="desvincularLocal"
				>
					<i class="fa fa-trash" aria-hidden="true"></i>
				</a>
			</td>
		</tr>
		<?php
		$html = ob_get_clean();
		


		global $wpdb;
		$BASPlataforma = $wpdb->prefix.sigsaClass::get_prefix().'basplataforma';
		$DEFContratoProjetoPlataforma = $wpdb->prefix.sigsaClass::get_prefix().'defcontratoprojetoplataforma';
		try{
			$empresa = SELF::getIDEmpresa();
			if($empresa){
				if( isset($dados['s']) ){
					$pesquisa = $dados['s'];
					$contratoAtual = ($dados['contratoAtual'])?$dados['contratoAtual']:"''";
					$SQL = <<<SQL
						SELECT
							$BASContrato.IDContrato,
							$BASContrato.CDContrato,
							$BASContrato.DSContrato
						FROM
							$BASContrato
							INNER JOIN $BASCliente on ($BASCliente.IDCliente = $BASContrato.IDCliente )
						WHERE 
							$BASCliente.IDEmpresa = $empresa
							AND $BASContrato.st_delete = '0'
							AND $BASContrato.IDContrato != $contratoAtual
							AND ( $BASContrato.CDContrato like "%$pesquisa%" 
								OR $BASContrato.DSContrato like "%$pesquisa%" )
						LIMIT 0,20;	
					SQL;
				}
				$contratos = $wpdb->get_results($SQL);
				ob_start();
				if ($contratos){
					foreach($contratos as $contrato){
						?><li>
							<a href="javascript:void(0)" 
								data-id="<?=$contrato->IDContrato?>"
								data-codigo="<?=$contrato->CDContrato?>"
								data-descricao="<?=$contrato->DSContrato?>"
								>
								<?=$contrato->CDContrato?> - <?=$contrato->DSContrato?>
							</a>
						</li><?php	
					}
					$lista = ob_get_clean();
				}else{
					$lista = '<li>Nenhum registro encontrado</li>';
				}
			}
			else{
				$lista = '<li>Erro! empresa nao identificada</li>';
			}
		}catch(\Throwable $th){
			$lista = '<li>Ocorreu um erro inesperado na pesquisa</li>';
			Log::registraLogSystem($th);
		}finally{
			return $lista;
		}

	}

}