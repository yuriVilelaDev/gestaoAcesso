<?php
/*
*  CLASS Local
*
*  manipula as funcoes relacionadas aos Locais
*
*  @type	class
*  @date	18/03/2022
*  @since	1.0.0
*
*/
include_once('class_Log.php');
class Local
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

	/** GET LISTA REGISTROS
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
		$DEFLocal = $wpdb->prefix.sigsaClass::get_prefix().'deflocal';
		$DEFContratoProjetoLocal = $wpdb->prefix.sigsaClass::get_prefix().'defcontratoprojetolocal';
		try{
			$inner = '';
			$pesquisa = null;
			if( isset($filtro['projetoid']) ){$filtro['IDProjeto'] = $filtro['projetoid'];}
			if( isset($filtro['s']) ){
				if ($filtro['s'] != '') 
					$pesquisa = $filtro['s'];
			}else{
				if( isset($_REQUEST['s']) )
					if ($_REQUEST['s'] != '') 
						$pesquisa = $_REQUEST['s'];
			}

			if(isset($filtro['IDEmpresa']))
				$IDEmpresa = $filtro['IDEmpresa'];
			else
				$IDEmpresa = SELF::getIDEmpresa();
			
			if($IDEmpresa)
				$where = ' WHERE l.st_delete =0 AND l.IDEmpresa='.$IDEmpresa;
			else
				$where = ' WHERE l.st_delete =0';

			if(isset($filtro['IDProjeto'])){
				$where .= ' AND ContratoProjetoLocal.IDProjeto='.$filtro['IDProjeto'];
				$inner .= ' INNER JOIN '.$DEFContratoProjetoLocal.' AS ContratoProjetoLocal 
				ON(ContratoProjetoLocal.IDLocal = l.IDLocal )';
			}
			
			if (isset($_REQUEST['p']))$filtro['pagina'] = $_REQUEST['p']; else $filtro['pagina']=1;
			if (isset($_REQUEST['q_registros']))
				$filtro['q_registros'] = $_REQUEST['q_registros']; 
			else 
				$filtro['q_registros'] = 50; 
			$filtro['pagina'] = $filtro['pagina']-1;
			$limit = ' LIMIT '.($filtro['pagina']*$filtro['q_registros']).','.$filtro['q_registros'];
			
			if( $pesquisa ){
				$where .= ' AND l.NMLocal like "%'.$_REQUEST['s'].'%"';
			}
			$campos = 'l.*';
			if( isset($filtro['campos'])){
				$campos = '';
				foreach($filtro['campos'] as $campo){
					$campos .= ',l.'.$campo;
				}
				$campos = substr($campos, 1);
			}
			$SQL = <<<SQL
				SELECT $campos
				FROM $DEFLocal AS l
				$inner
				$where
				ORDER BY l.NMLocal ASC $limit;
			SQL;
			$registros = $wpdb->get_results($SQL);
			//$registros = $SQL;
			// variavel html bloqueia impressões na tela. 
			$html = true;
			if( isset($filtro['output']) ){
				if( $filtro['output'] == 'JSON'){
					$registros = json_encode($registros);
					$html = false;
				}
				if( $filtro['output'] == 'ARRAY'){
					$html = false;
				}
			}

			if( $pesquisa && $html ){
				$n_registros = count($registros);
				if($n_registros == 0 )$str = 'nenhum registro';
				else if($n_registros == 1 )$str = '1 registro';
					else $str = $n_registros.' registros';
				echo '<p>Sua pesquisa por: "'.$_REQUEST['s'].'" encontrou '.$str.'.</p>';
			}
			//$composicoes = SELF::getIDEmpresa();
		}catch(\Throwable $th){
			$registros = false;
			Log::registraLogSystem($th);
		}finally{
			return $registros;
		}
	}

	/** GET REGISTRO
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
	public static function getRegistro($dados){
		global $wpdb;
		try{
			
			$BASComposicaoFuncional = $wpdb->prefix.sigsaClass::get_prefix().'bascomposicaofuncional';
			$DEFLocal = $wpdb->prefix.sigsaClass::get_prefix().'deflocal';
			
			$SQL = <<<SQL
				SELECT 
					$DEFLocal.*
				FROM
					$DEFLocal
				WHERE $DEFLocal.IDLocal = :IDLocal
					AND $DEFLocal.st_delete != 1;
			SQL;

			$SQL = str_replace(':IDLocal',$dados['ID'],$SQL);
			$registro = $wpdb->get_row($SQL);

			//$registro->SQL = $SQL;

			if(!$registro){
				$registro = (object) array('IDLocal' => null);
			}
		}catch(\Throwable $th){
			$projeto = false;
			Log::registraLogSystem($th);
		}finally{
			return $registro;
		}
	}

	/** GET QUANTIDADE DE LOCAIS 
	 * 
	 * Obtem o numero de projetos listados com os filtros selecionados
	 * 
	 * @type	function
	 * @date	21/03/2022
	 * @since	1.0.0
	 *
	 * @param	NULL
	 * @return	INT
	 */
    public static function getQuantidadeRegistros($filtro = null){
        global $wpdb;
        $DEFLocal = $wpdb->prefix.sigsaClass::get_prefix().'deflocal';
        
        if(isset($filtro['IDEmpresa']))
			$IDEmpresa = $filtro['IDEmpresa'];
		else
			$IDEmpresa = SELF::getIDEmpresa();
		
		if($IDEmpresa)
			$where = ' WHERE st_delete =0 AND local.IDEmpresa='.$IDEmpresa;
		else
			$where = ' WHERE local.st_delete =0';

        $q = $wpdb->get_var('SELECT COUNT(*) FROM '.$DEFLocal.' as local '.$where);
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
		$retorno['erro'] = 'Não foi possível Salvar o local';
		//return json_encode($retorno);
		global $wpdb;
		$DEFLocal = $wpdb->prefix.sigsaClass::get_prefix().'deflocal';
		try{
			$IDEmpresa = SELF::getIDEmpresa();
			if( !isset($IDEmpresa) ){$retorno['erro']='Empresa não encotrada';return json_encode($retorno);}
			$endereco = array(
				'logradouro' 	=> $dados['logradouro'],
				'numero' 		=> $dados['numero'],
				'complemento' 	=> $dados['complemento'],
				'bairro'		=> $dados['bairro'],
				'localidade' 	=> $dados['localidade'],
				'uf' 			=> $dados['uf'],
				'cep' 			=> $dados['cep'],
			);
			
			$data = array(
				'IDLocal'				=> null,
				'NMLocal'				=> $dados['nome'],
				'CDTipoLocal'			=> $dados['tipolocal'],
				'EDEnderecoLocalJSON'	=> json_encode($endereco),
				'CDRegional'			=> $dados['cdregioal'],
				'IDEmpresa'				=> $IDEmpresa,
				'st_delete'				=> 0
			);

			if($dados['local_id']){
				//ALTERACAO DE PROJETO
				//$format = array('%s','%d','%s');
				$data['IDLocal'] = $dados['local_id'];
				$where = array('IDLocal' => $dados['local_id']);
				$wpdb->update($DEFLocal,$data,$where);
				$retorno['status'] = true;
				$retorno['local_id'] = $dados['local_id'];
				Log::registraLog($DEFLocal,'UPDATE',get_current_user_id(),$data,$IDEmpresa );
			}
			else{
				//INCLUSAO DE NOVO PROJETO
				//$format = array('%s','%d','%s');
				$wpdb->insert( $DEFLocal , $data);
				$my_id = $wpdb->insert_id;
				if($my_id){
					$retorno['status'] = true;
					$retorno['local_id'] = $my_id;
				}
				$data['local_id'] =  $my_id;
				Log::registraLog($DEFLocal,'INSERT',get_current_user_id(),$data,$dados['empresaid'] );
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
			$DEFLocal = $wpdb->prefix.sigsaClass::get_prefix().'deflocal';
			if($dados['local_id']){
				//ALTERACAO DE PROJETO
				//$format = array('%s','%d','%s');
				$data['st_delete'] = 1;
				$where = array('IDLocal' => $dados['local_id']);
				$wpdb->update($DEFLocal,$data,$where);
				$retorno['status'] = true;
				$retorno['mensagem'] = 'O local '.$dados['descricao'].' foi excluido com sucesso!';
				Log::registraLog($DEFLocal,'UPDATE',get_current_user_id(),$data,$dados['local_id'] );
			}
		}catch(\Throwable $th){
			$retorno['status'] = false;
			$retorno['erro'] = 'Erro de código ao excluir o local';
			Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}

	/** Get METADADOS OPCOES
	 * 
	 * Recebe o nome do metadado e realiza a busca das opcoes
	 * @type	function
	 * @date	21/03/2022
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
	
	/** GET LISTA AJAX HTML LOCAL COM O PROJETO
	* 
	* Recebe os parametros de busca e faz um requerimento dos registros
	* tem a função de listar html dos registros em forma de lista <li></li>
	* @type	function
	* @date	18/03/2022
	* @since	1.0.0
	* 
	* @param	ARRAY | dados
	* @return	HTML lista de LI
	*/
	public static function getlistaLocais_ajax($filtro = null){
		try{
			$output = "Não encontrei nenhum local com a pesquisa acima.";
			$filtro['output'] = 'ARRAY';
			$data = $filtro;
			$_REQUEST['q_registros'] = 10;
			$registros = SELF::getListaRegistros($data);
			if($registros){
				ob_start();
				foreach($registros as $local){
					$enderecoArray = json_decode($local->EDEnderecoLocalJSON);
					$endereco = '';
					//Local::printa($enderecoArray);
					if($enderecoArray->logradouro)$endereco .= $enderecoArray->logradouro;
					if($enderecoArray->numero)$endereco .= ','.$enderecoArray->numero;
					if($enderecoArray->complemento)$endereco .= ' ('.$enderecoArray->complemento.')';
					if($enderecoArray->bairro)$endereco .= ', '.$enderecoArray->bairro;
					if($enderecoArray->localidade)$endereco .= ', '.$enderecoArray->localidade;
					if($enderecoArray->uf)$endereco .= '-'.$enderecoArray->uf;
					?>
					<li>
						<a href="#" data-id="<?=$local->IDLocal?>">
							<span class="nome"><?=$local->NMLocal;?></span> - 
							<span class="endereco"><?=$endereco?></span>
						</a>
					</li>
					<?php
				}
				$output = ob_get_clean();
			}
		}catch(\Throwable $th){
			$output = false;
		}finally{
			return $output;
		}
	}

	/** SALVAR VINCULO DO LOCAL COM O PROJETO
	 * 
	 * Recebe os parametros do cadastro e faz o salvamento DB
	 * tem a função de salvar ou criar um novo vinculo. 
	 * @type	function
	 * @date	18/03/2022
	 * @since	1.0.0
	 * 
	 * @param	ARRAY | dados
	 * @return	INT LOCAL
	 */
    public static function setVinculoContratoProjetoLocal($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'algo deu errado com o vinculo do local com o projeto';
		
		global $wpdb;
		$DEFContratoProjetoLocal = $wpdb->prefix.sigsaClass::get_prefix().'defcontratoprojetolocal';
		try{
			$IDEmpresa = SELF::getIDEmpresa();
			if( !isset($IDEmpresa) ){$retorno['erro']='Empresa não encotrada';return json_encode($retorno);}
			$IDContrato = $dados['contrato_id'];
			$IDProjeto = $dados['projeto_id'];
			$IDLocal = $dados['local_id'];
			
			if($dados['acao']=='vincular'){
				$SQL = <<<SQL
				SELECT *
				FROM $DEFContratoProjetoLocal
				WHERE IDProjeto=$IDProjeto AND IDLocal=$IDLocal
				SQL;
				$existeVinculo = $wpdb->get_results($SQL);
				if($existeVinculo){
					$retorno['status'] = false;
					$retorno['erro'] = 'Local já inserido no projeto';
					return json_encode($retorno);
				}else{
					$data = array(
						'IDContrato'		=> null,
						'IDProjeto'			=> $IDProjeto,
						'IDLocal'			=> $IDLocal
					);
					$wpdb->insert( $DEFContratoProjetoLocal , $data);
					$retorno['status'] = true;
				}
			}
			if($dados['acao']=='desvincular'){
				$SQL = <<<SQL
				SELECT *
				FROM $DEFContratoProjetoLocal
				WHERE IDProjeto=$IDProjeto AND IDLocal=$IDLocal
				SQL;
				$existeVinculo = $wpdb->get_results($SQL);
				if(!$existeVinculo){
					$retorno['status'] = false;
					$retorno['erro'] = 'Local não existe no projeto';
					return json_encode($retorno);
				}else{
					$data = array(
						'IDProjeto'	=> $IDProjeto,
						'IDLocal'	=> $IDLocal
					);
					$wpdb->delete( $DEFContratoProjetoLocal , $data);
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
}