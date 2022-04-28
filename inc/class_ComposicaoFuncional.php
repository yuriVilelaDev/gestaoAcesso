<?php
/*
*  CLASS ComposicaoFuncional
*
*  manipula as funcoes relacionadas a ComposicaoFuncional 
*
*  @type	class
*  @date	18/03/2022
*  @since	1.0.0
*
*/
include_once('class_Log.php');
class ComposicaoFuncional
{
	
	private static $instance;

	/** GET INSTANCE
     * 
     * @type	function
     * @date	18/03/2022
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
	 * @date	18/03/2022
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
	 * @date	18/03/2022
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
	 * @date	18/03/22
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

	/** GET LISTA COMPOSICOES FUNCIONAIS 
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
	public static function getListaRegistros($filtro = null){
		global $wpdb;
		$BASProjeto = $wpdb->prefix.sigsaClass::get_prefix().'basprojeto';
		$BASComposicaoFuncional = $wpdb->prefix.sigsaClass::get_prefix().'bascomposicaofuncional';
		$DEFContratoProjetoComposicao = $wpdb->prefix.sigsaClass::get_prefix().'defcontratoprojetocomposicao';
		try{
			$inner = '';
			if(isset($filtro['IDEmpresa']))
				$IDEmpresa = $filtro['IDEmpresa'];
			else
				$IDEmpresa = SELF::getIDEmpresa();
			
			if($IDEmpresa)
				$where = ' WHERE composicaof.st_delete =0 AND composicaof.IDEmpresa='.$IDEmpresa;
			else
				$where = ' WHERE composicaof.st_delete =0';
			
			if(isset($filtro['IDProjeto'])){
				$where .= ' AND contratoProjetoComposicao.IDProjeto='.$filtro['IDProjeto'];
				$inner .= ' INNER JOIN '.$DEFContratoProjetoComposicao.' AS contratoProjetoComposicao 
				ON(contratoProjetoComposicao.IDCompFuncional = composicaof.IDCompFuncional )';
			}
			
			if (isset($_REQUEST['p']))$filtro['pagina'] = $_REQUEST['p']; else $filtro['pagina']=1;
			if (isset($_REQUEST['q_registros'])) $filtro['q_registros'] = $_REQUEST['q_registros']; else $filtro['q_registros'] = 50; 
			$filtro['pagina'] = $filtro['pagina']-1;
			$limit = ' LIMIT '.($filtro['pagina']*$filtro['q_registros']).','.$filtro['q_registros'];
			
			if( isset($_REQUEST['s']) ){
				$where .= ' AND ( composicaof.NMSiglaCompFuncional like "%'.$_REQUEST['s'].'%" OR composicaof.NMCompFuncional like "%'.$_REQUEST['s'].'%" )';
			}
			$SQL = <<<SQL
				SELECT composicaof.*
				FROM $BASComposicaoFuncional AS composicaof
				$inner  
				$where
				ORDER BY composicaof.NMSiglaCompFuncional ASC $limit;
			SQL;
			$composicoes = $wpdb->get_results($SQL);
			//$composicoes = $SQL;
			if( isset($_REQUEST['s']) ){
				$n_composicoes = count($composicoes);
				if($n_composicoes == 0 )$str = 'nenhum registro';
				else if($n_composicoes == 1 )$str = '1 registro';
					else $str = $n_composicoes.' registros';
				echo '<p>Sua pesquisa por: "'.$_REQUEST['s'].'" encontrou '.$str.'.</p>';
			}
			//$composicoes = SELF::getIDEmpresa();
		}catch(\Throwable $th){
			$composicoes = false;
			Log::registraLogSystem($th);
		}finally{
			return $composicoes;
		}
	}

	/** GET COMPOSICAO FUNCIONAL 
	 * 
	 * Retorna um array contento a variável ComposicaoFuncional
	 * 
	 * @type	function
	 * @date	18/03/2022
	 * @since	1.0.1
	 *
	 * @param	VAR|ARRAY|OBJ
	 * @return	OBJ|ARRAY
	 */
	public static function getComposicaoFuncional($dados){
		global $wpdb;
		try{
			
			$BASComposicaoFuncional = $wpdb->prefix.sigsaClass::get_prefix().'bascomposicaofuncional';
			$DEFContratoProjetoComposicao = $wpdb->prefix.sigsaClass::get_prefix().'defcontratoprojetocomposicao';
			
			$SQL = <<<SQL
				SELECT 
					$BASComposicaoFuncional.*
				FROM
					$BASComposicaoFuncional
				WHERE $BASComposicaoFuncional.IDCompFuncional = :IDCompFuncional
					AND $BASComposicaoFuncional.st_delete != 1;
			SQL;

			$SQL = str_replace(':IDCompFuncional',$dados['ID'],$SQL);
			$registro = $wpdb->get_row($SQL);

			//$registro->SQL = $SQL;

			if(!$registro){
				$registro = (object) array('IDCompFuncional' => null);
			}
		}catch(\Throwable $th){
			$projeto = false;
			Log::registraLogSystem($th);
		}finally{
			return $registro;
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
    public static function getQuantidadeComposicaoFuncional($filtro = null){
        global $wpdb;
        $BASComposicaoFuncional = $wpdb->prefix.sigsaClass::get_prefix().'bascomposicaofuncional';
        
        if(isset($filtro['IDEmpresa']))
			$IDEmpresa = $filtro['IDEmpresa'];
		else
			$IDEmpresa = SELF::getIDEmpresa();
		
		if($IDEmpresa)
			$where = ' WHERE st_delete =0 AND composicaof.IDEmpresa='.$IDEmpresa;
		else
			$where = ' WHERE composicaof.st_delete =0';

        $q = $wpdb->get_var('SELECT COUNT(*) FROM '.$BASComposicaoFuncional.' as composicaof '.$where);
        return $q;
    }

	/** SALVAR CASDASTRO COMPOSICAO FUNCIONAL
	 * 
	 * Recebe os parametros do cadastro e faz o salvamento DB
	 * tem a função de salvar ou criar um novo vinculo. 
	 * @type	function
	 * @date	18/03/2022
	 * @since	1.0.0
	 * 
	 * @param	ARRAY | dados
	 * @return	INT composicaof_id
	 */
    public static function salvarCadastro($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível Salvar a Composicao funcional';
		//return json_encode($retorno);
		global $wpdb;
		$BASComposicaoFuncional = $wpdb->prefix.sigsaClass::get_prefix().'bascomposicaofuncional';
		try{
			$IDEmpresa = SELF::getIDEmpresa();
			if( !isset($IDEmpresa) ){$retorno['erro']='Empresa não encotrada';return json_encode($retorno);}
			$data = array(
				'IDCompFuncional'		=> null,
				'NMCompFuncional'		=> $dados['nome'],
				'NMSiglaCompFuncional'	=> $dados['sigla'],
				'IDEmpresa'				=> $IDEmpresa,
				'st_delete'				=> 0
			);
			if($dados['composicaof_id']){
				//ALTERACAO DE PROJETO
				//$format = array('%s','%d','%s');
				$data['IDCompFuncional'] = $dados['composicaof_id'];
				$where = array('IDCompFuncional' => $dados['composicaof_id']);
				$wpdb->update($BASComposicaoFuncional,$data,$where);
				$retorno['status'] = true;
				$retorno['composicaof_id'] = $dados['composicaof_id'];
				Log::registraLog($BASComposicaoFuncional,'UPDATE',get_current_user_id(),$data,$IDEmpresa );
			}
			else{
				//INCLUSAO DE NOVO PROJETO
				//$format = array('%s','%d','%s');
				$wpdb->insert( $BASComposicaoFuncional , $data);
				$my_id = $wpdb->insert_id;
				if($my_id){
					$retorno['status'] = true;
					$retorno['composicaof_id'] = $my_id;
				}
				$data['composicaof_id'] =  $my_id;
				Log::registraLog($BASComposicaoFuncional,'INSERT',get_current_user_id(),$data,$dados['empresaid'] );
			}
			
		}catch(\Throwable $th){
			$retorno['erro'] = 'Houve um erro ao salvar o projeto!';
			Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}

	/** EXCLUIR CADASTRO COMPOSICAO FUNCIONAL
	 * 
	 * Recebe os parametros da composicao funcional e faz a update do campo st_delete para 1
	 * @type	function
	 * @date	18/03/2022
	 * @since	1.0.0
	 * 
	 * @param	ARRAY dados
	 * @return	JSON dados de response
	 */
	public static function excluirCadastro($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível realizar a operacao!';
		global $wpdb;
		try{
			$BASComposicaoFuncional = $wpdb->prefix.sigsaClass::get_prefix().'bascomposicaofuncional';
			if($dados['composicaof_id']){
				//ALTERACAO DE PROJETO
				//$format = array('%s','%d','%s');
				$data['st_delete'] = 1;
				$where = array('IDCompFuncional' => $dados['composicaof_id']);
				$wpdb->update($BASComposicaoFuncional,$data,$where);
				$retorno['status'] = true;
				$retorno['mensagem'] = 'A composicao funcional '.$dados['descricao'].' foi excluida com sucesso!';
				Log::registraLog($BASComposicaoFuncional,'UPDATE',get_current_user_id(),$data,$dados['composicaof_id'] );
			}
		}catch(\Throwable $th){
			$retorno['status'] = false;
			$retorno['erro'] = 'Erro de código ao excluir a composicao funcional';
			Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}

	
	/** SALVAR VINCULO DA COMPOSICAO FUNCIONAL COM O PROJETO
	 * 
	 * Recebe os parametros do cadastro e faz o salvamento DB
	 * tem a função de salvar ou criar um novo vinculo. 
	 * @type	function
	 * @date	18/03/2022
	 * @since	1.0.0
	 * 
	 * @param	ARRAY | dados
	 * @return	INT composicaof_id
	 */
    public static function setVinculoContratoProjetoComposicao($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível Salvar a Composicao funcional';
		global $wpdb;
		$DEFContratoProjetoComposicao = $wpdb->prefix.sigsaClass::get_prefix().'defcontratoprojetocomposicao';
		try{
			$IDEmpresa = SELF::getIDEmpresa();
			if( !isset($IDEmpresa) ){$retorno['erro']='Empresa não encotrada';return json_encode($retorno);}
			$IDContrato = $dados['contrato_id'];
			$IDProjeto = $dados['projeto_id'];
			$IDCompFuncional = $dados['composicaof_id'];
			
			if($dados['acao']=='vincular'){
				$SQL = <<<SQL
				SELECT *
				FROM $DEFContratoProjetoComposicao
				WHERE IDProjeto=$IDProjeto AND IDCompFuncional=$IDCompFuncional
				SQL;
				$existeVinculo = $wpdb->get_results($SQL);
				if($existeVinculo){
					$retorno['status'] = false;
					$retorno['erro'] = 'Composicao funcional já inserida no projeto';
					return json_encode($retorno);
				}else{
					$data = array(
						'IDContrato'		=> null,
						'IDProjeto'			=> $IDProjeto,
						'IDCompFuncional'	=> $IDCompFuncional
					);
					$wpdb->insert( $DEFContratoProjetoComposicao , $data);
					$retorno['status'] = true;
				}
			}
			if($dados['acao']=='desvincular'){
				$SQL = <<<SQL
				SELECT *
				FROM $DEFContratoProjetoComposicao
				WHERE IDProjeto=$IDProjeto AND IDCompFuncional=$IDCompFuncional
				SQL;
				$existeVinculo = $wpdb->get_results($SQL);
				if(!$existeVinculo){
					$retorno['status'] = false;
					$retorno['erro'] = 'Composicao funcional não existe no projeto';
					return json_encode($retorno);
				}else{
					$data = array(
						'IDProjeto'			=> $IDProjeto,
						'IDCompFuncional'	=> $IDCompFuncional
					);
					$wpdb->delete( $DEFContratoProjetoComposicao , $data);
					$retorno['status'] = true;
				}
			}
		}catch(\Throwable $th){
			$retorno['erro'] = 'Houve um erro ao salvar o projeto!';
			Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}

	/** Get METADADOS OPCOES
	 * 
	 * Recebe o nome do metadado e realiza a busca das opcoes
	 * @type	function
	 * @date	18/03/2022
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

}