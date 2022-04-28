<?php
/*
*  CLASS Contratos
*
*  manipula as funcoes relacionadas a configurações > Contratos 
*
*  @type	class
*  @date	17/01/2022
*  @since	1.0.0
*
*/
include_once('class_Log.php');
class Contratos
{
	
	private static $instance;

	/**
     * get_instance
     * 
     * @type	function
     * @date	17/01/22
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
	 * @date	17/01/22
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
	 * @date	17/01/22
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


	/** GET LISTA CONTRATOS 
	 * 
	 * Levanta a lista de contratos e retorna em objeto JSON
	 * 
	 * @type	function
	 * @date	17/01/22
	 * @since	1.0.0
	 *
	 * @param	VAR|ARRAY|OBJ
	 * @return	HTML
	 */
	public static function getListaContratos($filtro = null){
		global $wpdb;
		$BASContrato = $wpdb->prefix.sigsaClass::get_prefix().'bascontrato';
		$BASCliente = $wpdb->prefix.sigsaClass::get_prefix().'bascliente';
		try{
			if(isset($_REQUEST['IDEmpresa'])){
				$filtro['IDEmpresa'] = $_REQUEST['IDEmpresa'];
			}
			else{
				$filtro['IDEmpresa'] = "";
			}
			
			if($filtro['IDEmpresa']){
				$where = ' WHERE contrato.st_delete =0 AND cliente.IDEmpresa='.$filtro['IDEmpresa'];
			}
			else{
				$where = ' WHERE contrato.st_delete =0';
			}
			if (isset($_REQUEST['p']))$filtro['pagina'] = $_REQUEST['p']; else $filtro['pagina']=1;
			if (isset($_REQUEST['q_registros'])) $filtro['q_registros'] = $_REQUEST['q_registros']; 
			$filtro['pagina'] = $filtro['pagina']-1;
			$limit = ' LIMIT '.($filtro['pagina']*$filtro['q_registros']).','.$filtro['q_registros'];
			
			if( isset($_REQUEST['s']) ){
				$where .= ' AND ( contrato.CDContrato like "%'.$_REQUEST['s'].'%" OR contrato.DSContrato like "%'.$_REQUEST['s'].'%" )';
			}
			$SQL = <<<SQL
				SELECT contrato.*,cliente.NMRazaoCliente, cliente.NMFantasiaCliente
				FROM $BASContrato AS contrato 
					LEFT JOIN $BASCliente AS cliente ON (contrato.IDCliente = cliente.IDCliente) 
				$where
				ORDER BY contrato.IDContrato DESC $limit;
			SQL;
			$contratos = $wpdb->get_results($SQL);
			if( isset($_REQUEST['s']) ){
				$n_contratos = count($contratos);
				if($n_contratos == 0 )$str = 'nenhum registro';
				else if($n_contratos == 1 )$str = '1 registro';
					else $str = $n_contratos.' registros';
				echo '<p>Sua pesquisa por: "'.$_REQUEST['s'].'" encontrou '.$str.'.</p>';
			}
		}catch(\Throwable $th){
			$contratos = false;
			Log::registraLogSystem($th);
		}finally{
			return $contratos;
		}
	}

	/** GET CONTRATO 
	 * 
	 * Retorna um array contento a variável contrato
	 * 
	 * @type	function
	 * @date	18/01/22
	 * @since	1.0.1
	 *
	 * @param	VAR|ARRAY|OBJ
	 * @return	OBJ|ARRAY
	 */
	public static function getContrato($dados){
		global $wpdb;
		try{
			$BASCliente = $wpdb->prefix.sigsaClass::get_prefix().'bascliente';
			$BASContrato = $wpdb->prefix.sigsaClass::get_prefix().'bascontrato';
			$BASProjeto = $wpdb->prefix.sigsaClass::get_prefix().'basprojeto';
			$DEFContratoProjeto = $wpdb->prefix.sigsaClass::get_prefix().'defcontratoprojeto';
			
			$SQL = <<<SQL
				SELECT 
					$BASContrato.*
					,$BASCliente.IDEmpresa
					,$BASCliente.NMRazaoCliente AS clientename
				FROM
					$BASContrato
					LEFT JOIN $BASCliente
					ON ( $BASContrato.IDCliente = $BASCliente.IDCliente )
				WHERE $BASContrato.IDContrato = :IDContrato
					AND $BASContrato.st_delete != 1;
			SQL;
			$SQL = str_replace(':IDContrato',$dados['ID'],$SQL);
			$contrato = $wpdb->get_row($SQL);
			
			// Inclusão de projetos do contrato
			$SQL = <<<SQL
				SELECT 
					$BASProjeto.IDProjeto,
					$BASProjeto.NMProjeto,
					$BASProjeto.DSProjeto,
					$BASProjeto.IMLogoProjeto,
					$BASProjeto.STProjeto,
					$DEFContratoProjeto.CDSituacaoContratoProjeto,
					$DEFContratoProjeto.DTInicioContratoProjeto,
					$DEFContratoProjeto.DTTerminoContratoProjeto,
					$DEFContratoProjeto.STUsarGestaoSolicitacao,
					$DEFContratoProjeto.STUsarPerfilAcessoPadrao,
					$DEFContratoProjeto.STUsarLocal
				FROM
					$DEFContratoProjeto
					INNER JOIN $BASProjeto
					ON ( $DEFContratoProjeto.IDProjeto = $BASProjeto.IDProjeto )
				WHERE $DEFContratoProjeto.IDContrato = :IDContrato;
			SQL;
			$SQL = str_replace(':IDContrato',$dados['ID'],$SQL);
			$contrato->projetos = $wpdb->get_results($SQL,'ARRAY_A');

			//$contrato->SQL = $SQL;
			if(!$contrato){
				$contrato = (object) array('IDContrato' => null);
			}
		}catch(\Throwable $th){
			$contrato = false;
			Log::registraLogSystem($th);
		}finally{
			return $contrato;
		}
	}

	/** GET QUANTIDADE DE CONTRATOS 
	 * 
	 * Obtem o numero de contratos listados com os filtros selecionados
	 * 
	 * @type	function
	 * @date	18/01/22
	 * @since	1.0.0
	 *
	 * @param	NULL
	 * @return	INT
	 */
    public static function getQuantidadeContratos(){
        global $wpdb;
        $BASContrato = $wpdb->prefix.sigsaClass::get_prefix().'bascontrato';
        
		if( isset($_REQUEST['IDEmpresa']) ) $id_empresa = $_REQUEST['IDEmpresa']; else $id_empresa = false;

        if($id_empresa){
            $filtro['IDEmpresa'] = $id_empresa;
            $where = ' WHERE st_delete =0 AND IDEmpresa='.$filtro['IDEmpresa'];
        }
        else{
            $where = ' WHERE st_delete =0';
        }
        $q = $wpdb->get_var('SELECT COUNT(*) FROM '.$BASContrato.$where);
        return $q;
    }

	/** SALVAR CONTRATO
	 * 
	 * Recebe os parametros do contrato e faz o salvamento DB
	 * @type	function
	 * @date	28/01/22
	 * @since	1.0.0
	 * 
	 * @param	ARRAY | dados
	 * @return	INT idcontrato
	 */
    public static function salvarContrato($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível Salvar o contrato';
		
		global $wpdb;
		$BASContrato = $wpdb->prefix.sigsaClass::get_prefix().'bascontrato';
		try{
			$data = array(
				'IDContrato'		=> null, 
				'CDTipoContrato' 	=> $dados['tipocontrato'],
				'CDContrato'		=> $dados['codigo'],
				'DSContrato'		=> $dados['descricao'],
				'DTInicioContrato' 	=> $dados['datainicio'],
				'DTTerminoContrato'=> $dados['datatermino'],
				'CDSituacaoContrato'=> $dados['situacaocontrato'],
				'DSMotivoMudancaStatus'=> $dados['motivomudancastatus'],
				'NUAditivoContrato' => $dados['codigoaditivo'],
				'IDCliente' 		=> $dados['cliente'],
				'st_delete'	=> 0
			);
			if($dados['contratoid']){
				//ALTERACAO DE CONTRATO
				//$format = array('%s','%d','%s');
				$data['IDContrato'] = $dados['contratoid'];
				$where = array('IDContrato' => $dados['contratoid']);
				$wpdb->update($BASContrato,$data,$where);
				$retorno['status'] = true;
				$retorno['contratoid'] = $dados['contratoid'];
				//Log::registraLog($BASContrato,'UPDATE',get_current_user_id(),$data,$dados['IDEmpresa'] );
			}
			else{
				//INCLUSAO DE NOVO CONTRATO
				//$format = array('%s','%d','%s');
				$wpdb->insert( $BASContrato , $data);
				$my_id = $wpdb->insert_id;
				if($my_id){
					$retorno['status'] = true;
					$retorno['contratoid'] = $my_id;
				}
				$data['contratoid'] =  $my_id;
				Log::registraLog($BASContrato,'INSERT',get_current_user_id(),$data,$dados['IDEmpresa'] );
			}
			$contatosAtivos = $dados['contatosAtivos'];
			if($contatosAtivos){
				$retorno['status'] = false;
				//Executa a limpeza de todos os contatos cadastrados no contrato.
				$BASContatoContrato = $wpdb->prefix.sigsaClass::get_prefix().'bascontatocontrato';
				$SQL = <<<SQL
					DELETE FROM $BASContatoContrato 
					WHERE $BASContatoContrato.IDContrato = :IDContrato;
				SQL;
				$SQL = str_replace(':IDContrato',$dados['contratoid'],$SQL);
				$wpdb->query($SQL);
				//insere os contatos selecionados
				foreach($contatosAtivos as $contato){
					$data = array(
						//'id'		=> null, 
						'IDContatoCliente' 	=> $contato,
						'IDContrato' 	=> $dados['contratoid']
					);
					$wpdb->insert( $BASContatoContrato , $data);
				}
				$retorno['status'] = true;
				//ob_start();
				//print_r($contatosAtivos);
				//$retorno['erro'] = ob_get_clean();
			}
		}catch(\Throwable $th){
			$retorno['erro'] = 'Houve um erro ao salvar o contrato!';
			Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}

	/** EXCLUIR CONTRATO
	 * 
	 * Recebe os parametros do contrato e faz a update do campo st_delete para 1
	 * @type	function
	 * @date	31/01/22
	 * @since	1.0.0
	 * 
	 * @param	ARRAY dados
	 * @return	JSON dados de response
	 */
	public static function excluirContrato($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível realizar a operacao!';
		global $wpdb;
		try{
			$BASContrato = $wpdb->prefix.sigsaClass::get_prefix().'bascontrato';
			if($dados['contratoid']){
				//ALTERACAO DE CONTRATO
				//$format = array('%s','%d','%s');
				$data['st_delete'] = 1;
				$where = array('IDContrato' => $dados['contratoid']);
				$wpdb->update($BASContrato,$data,$where);
				$retorno['status'] = true;
				$retorno['mensagem'] = 'O contrato '.$dados['descricao'].' foi excluido com sucesso!';
				Log::registraLog($BASContrato,'UPDATE',get_current_user_id(),$data,$dados['contratoid'] );
			}
		}catch(\Throwable $th){
			$retorno['status'] = false;
			$retorno['erro'] = 'Erro de código ao excluir o contrato';
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
	
	/** Get METADADOS VALOR
	 * 
	 * Recebe id do metadado e e retorna a o valor
	 * @type	function
	 * @date	13/07/21
	 * @since	1.0.0
	 * 
	 * @param	INT IDMetadado
	 * @return	VARCHAR DSOpcaoMetadado
	 */
	public static function getGERMetadadoValue($IDMetadado){
		global $wpdb;
		$GERMetadado = $wpdb->prefix.sigsaClass::get_prefix().'germetadado';
		$SQL = 'SELECT * FROM '.$GERMetadado.' WHERE IDMetadado="'. $IDMetadado .'" LIMIT 1;';
		$r = $wpdb->get_row($SQL);
		return $r->DSOpcaoMetadado;
		//return $SQL;
	}

	/**	getlistaClientes_ajax
	 * 
	 * Recebe parametros de busca e retorna uma lista com os itens encontrados
	 * @type	function
	 * @date	24/01/22
	 * @since	1.0.0
	 * 
	 * @param	Array dados
	 * @return	HTML
	 */
	public static function getlistaClientes_ajax($dados){ 
		global $wpdb;
		$BASCliente = $wpdb->prefix.sigsaClass::get_prefix().'bascliente';
		try{
			if(isset($dados['empresa'])){
				$empresa = $dados['empresa'];
				if( isset($dados['s']) ){
					$pesquisa = $dados['s'];
					$SQL = <<<SQL
						SELECT
							$BASCliente.IDCliente,
							$BASCliente.NMRazaoCliente,
							$BASCliente.NMFantasiaCliente
						FROM
							$BASCliente
						WHERE 
							$BASCliente.st_delete = '0'
							AND $BASCliente.IDEmpresa = $empresa 
							AND ( $BASCliente.NMRazaoCliente like "%$pesquisa%" 
								OR $BASCliente.NMFantasiaCliente like "%$pesquisa%" )
						LIMIT 0,20;	
					SQL;
				}
				$clientes = $wpdb->get_results($SQL);
				ob_start();
				if ($clientes){
					foreach($clientes as $cliente){
						?><li>
							<a href="javascript:void(0)" 
							data-id="<?=$cliente->IDCliente?>"><?=$cliente->NMRazaoCliente?></a>
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

	public static function getListaContatosCliente($dados){
		global $wpdb;
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível obter a lista de contatos!';
		try{
			$BASContatoCliente = $wpdb->prefix.sigsaClass::get_prefix().'bascontatocliente';
			$BASContatoContrato = $wpdb->prefix.sigsaClass::get_prefix().'bascontatocontrato';
			
			// Contatos cadastrados no contrato. 
			$SQL = <<<SQL
				SELECT $BASContatoContrato.IDContatoCliente 
				FROM $BASContatoContrato
				INNER JOIN $BASContatoCliente ON($BASContatoCliente.IDContatoCliente = $BASContatoContrato.IDContatoCliente)
				WHERE $BASContatoContrato.IDContrato = :IDContrato
				AND $BASContatoCliente.IDCliente = :IDCliente ;
			SQL;
			$SQL = str_replace(':IDContrato',$dados['contratoid'],$SQL);
			$SQL = str_replace(':IDCliente',$dados['clienteid'],$SQL);
			ob_start();
			//printf('Aqui esta sql ativos<pre>%s</pre> ',print_r($SQL,true));
			$contatosAtivos = $wpdb->get_results($SQL,ARRAY_A);
			$contatosAtivos = array_column($contatosAtivos, 'IDContatoCliente');
			$retorno['contatosAtivos'] = $contatosAtivos;

			// Contatos em geral.
			
			$SQL = <<<SQL
				SELECT * FROM $BASContatoCliente
				WHERE $BASContatoCliente.IDCliente = :IDCliente ;
			SQL;
			$SQL = str_replace(':IDCliente',$dados['clienteid'],$SQL);
			$contatos = $wpdb->get_results($SQL);
			
			//printf('Aqui esta contatos<pre>%s</pre> ',print_r($contatos,true));
			foreach($contatos as $contato){
				$iconesHTML = '';
				$ref = json_decode($contato->DSReferenciaClienteJSON);
				foreach($ref as $CDtipo){
					$tipo = SELF::getGERMetadadoValue( intval($CDtipo) );
					$tipoI = preg_replace(array("/(A|ã)/","/(E|ê)/","/(F)/","/(G)/","/(I)/","/(O|ó)/","/(P)/","/(T)/","/(U)/","/(C)/","/ /"),explode(" ","a e f g i o p t u c -"),$tipo);
					//$class .= ' '.$tipo;
					$iconesHTML .= '<div class="icone '. $tipoI .'"><i class="fa fa-map-o" aria-hidden="true"></i> </div>';
				}
				if( in_array($contato->IDContatoCliente,$contatosAtivos)  )$class = ' ativo';
				else $class = '';
				?>
				<div class="contato caixa<?=$class?>" id="contato_<?=$contato->IDContatoCliente?>" data-id="<?=$contato->IDContatoCliente?>">
					<div class="icones">
						<?=$iconesHTML;?>
					</div>
					<div class="info">
						<div class="titulo"><?=$contato->NMContatoCliente?></div>
						<div class="subtitulo"><?=$contato->EDEmailContatoCliente?></div>
					</div>
				</div>
			<?php
			}
			$retorno['status'] = true;
			$retorno['html'] = ob_get_clean();
		}catch(\Throwable $th){
			$retorno['html'] = 'Ocorreu um erro inesperado na busca de contatos do cliente';
			Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}

	/**	GET FORM ANEXAR PROJETO
	 * 
	 * 
	 * @type	function
	 * @date	15/02/22
	 * @since	1.0.0
	 * 
	 * @param	Array dados
	 * @return	HTML
	 */
	public static function getFormProjeto($dados){
		// Obtem as opcoes de situação contrato projeto em metadados
		$situacaoContratoProjeto = '';
		$CDSituacaoContratoProjeto_lista = SELF::getGERMetadadoOpcoes('CDSituacaoContratoProjeto');
		foreach($CDSituacaoContratoProjeto_lista as $item){
			$data_class = $item->DSOpcaoMetadado;
			$data_class = preg_replace(array("/(A|ã)/","/(E)/","/(I)/","/(O)/","/(U)/","/(C)/","/ /"),explode(" ","a e i o u c -"),$data_class);
			$situacaoContratoProjeto.= '<option data-class="'.$data_class.'" value="'. $item->IDMetadado .'">'. $item->DSOpcaoMetadado .'</option>';
		}
		$arquivo = file_get_contents(SIGSA_PATH.'view/forms/cadastro-contrato-adiciona-projeto.html');
		$arquivo = str_replace('{:situacaoContratoProjeto}',$situacaoContratoProjeto,$arquivo);
		ob_start();
		echo $arquivo;
		$html = ob_get_clean();
		return $html;
	}

	/**	GET LISTA PROJETOS AJAX - ANEXAR PROJETO
	 * Obtem a lista de projetos possíveis a serem anexados ao contrato que está sendo editado.
	 * 
	 * @type	function
	 * @date	17/02/22
	 * @since	1.0.0
	 * 
	 * @param	Array dados
	 * @return	HTML
	 */
	public static function getListaProjetos_ajax($dados){
		global $wpdb;
		$BASProjeto = $wpdb->prefix.sigsaClass::get_prefix().'basprojeto';
		$DEFContratoProjeto = $wpdb->prefix.sigsaClass::get_prefix().'defcontratoprojeto';
		try{
			$IDEmpresa = VerificacaoAcesso::getIDEmpresa();
			ob_start();
			if( isset($dados['s']) ){
				$pesquisa = $dados['s'];
				$IDContrato = $dados['contratoid'];
				$SQL = <<<SQL
					SELECT
						$BASProjeto.*
					FROM
						$BASProjeto
						LEFT JOIN $DEFContratoProjeto
							ON ($DEFContratoProjeto.IDProjeto = $BASProjeto.IDProjeto)
					WHERE
						$BASProjeto.IDEmpresa = $IDEmpresa
						AND $BASProjeto.st_delete = 0
						AND $BASProjeto.IDProjeto NOT IN(
							SELECT IDProjeto FROM $DEFContratoProjeto WHERE IDContrato = $IDContrato
						)
						AND ( $BASProjeto.NMProjeto like "%$pesquisa%" OR $BASProjeto.DSProjeto like "%$pesquisa%" ) 
					LIMIT 0,20;
				SQL;
				$projetos = $wpdb->get_results($SQL);
				if ($projetos){
					foreach($projetos as $projeto){
						?><li>
							<a href="javascript:void(0)" 
							data-id="<?=$projeto->IDProjeto?>"><?=$projeto->NMProjeto?></a>
						</li><?php	
					}
				}else{
					echo '<li>Nenhum registro encontrado</li>';
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

	/**	SET CONTRATO PROJETO - ANEXAR PROJETO EM CONTRATO
	 * Estabelece o vínculo entre contrato e projeto atribuindo uma linha em
	 * DEFContratoProjeto
	 * 
	 * @type	function
	 * @date	22/02/22
	 * @since	1.0.0
	 * 
	 * @param	Array dados
	 * @return	JSON
	 */
	public static function setContratoProjeto($dados){
		global $wpdb;
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível realizar a operacao!';
		$DEFContratoProjeto = $wpdb->prefix.sigsaClass::get_prefix().'defcontratoprojeto';
		try{
			$IDEmpresa = VerificacaoAcesso::getIDEmpresa();
			$IDContrato = $dados['contratoid'];
			$IDProjeto =  $dados['projetoid'];
			$data = array(
				'IDContrato'				=> $IDContrato,
				'IDProjeto' 				=> $IDProjeto,
				'CDSituacaoContratoProjeto'	=> $dados['situacaoContratoProjeto'],
				'DTInicioContratoProjeto'	=> $dados['inicioProjeto'],
				'DTTerminoContratoProjeto'	=> $dados['terminoProjeto'],
				'STUsarGestaoSolicitacao'	=> $dados['gestaoSolicitacaoAcesso'],
				'STUsarPerfilAcessoPadrao'	=> $dados['usoPerfilPadrao'],
				'STUsarLocal'				=> $dados['usoLocalPadrao'],
				'STCadastrarEstudante'		=> $dados['cadastroEstudante'],
				'IDEmpresa'					=> $IDEmpresa
			);
			$wpdb->insert( $DEFContratoProjeto,$data );
			$retorno['status'] = true;
			//ob_start();
			//print_r($data);
			//$retorno['erro'] = ob_get_clean();
		}catch(\Throwable $th){
			$retorno['status'] = false;
			$retorno['erro'] = 'Erro de código ao inserir o projeto no contrato';
			Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}

	/**	ORDENA ARRAY
	 * Tem a funcão de ordear um array bidimensional pelo field escolhido
	 * @type	function
	 * @date	23/02/22
	 * @since	1.0.0
	 * @param	Array,String,boolean
	 * @return	ARRAY
	 */
	private static function ordena_array($records, $field, $reverse=false){
		$hash = array();
		foreach($records as $record){
			$hash[$record[$field]] = $record;
		}
		($reverse)? krsort($hash) : ksort($hash);
		$records = array();
		foreach($hash as $record){
			$records[]= $record;
		}  
		return $records;
	}

	/**	GET LISTA DE ANEXOS
	 * Retorna a lista de anexos em formato html com tabelas
	 * @type	function
	 * @date	24/02/22
	 * @since	1.0.0
	 * @param	Array id dos anexos
	 * @return	HTML
	 */
	public static function getListaAnexos($anexos){
		$erro = "<div>Ocorreu um erro do sistema ao procurar por anexos neste contrato</div>";
		$html = $erro;
		try{
			$lista_anexos = str_replace('"','',$anexos);
			$lista_anexos_array = json_decode($lista_anexos);
			$anexos_arr = array();
			if($lista_anexos_array){
				foreach($lista_anexos_array as $anexoid){
					$obanexo = get_post( $anexoid );
					$anexo = array(
						'ID' => $anexoid,
						'post_title' => $obanexo->post_title,
						'guid' => $obanexo->guid,
						'post_mime_type'=>$obanexo->post_mime_type
					);
					$anexos_arr[] = $anexo;
				}
				$anexos_arr = SELF::ordena_array($anexos_arr, 'post_title');
			}
			//extencoes
			$extencoes = array(
				'image/jpeg' 		=> 'jpg',
				'application/pdf'	=> 'pdf'
			);
			ob_start();
			?>
				<input id="lista_anexos" type="text" value="<?=$lista_anexos?>" style="display:none;"/>
				<table class="table table-bordered table-hover table-condensed">
					<thead>	
						<tr>
							<th>#</th>
							<th>Arquivo</th>
							<th>Ações</th>
						</tr>
					</thead>
					<tboby>
						<?php
						foreach($anexos_arr as $anexo){
							
							?>
							<tr>
							<td><?=$anexo['ID'];?></td>
							<td><?=$anexo['post_title'];?>.<?=$extencoes[$anexo['post_mime_type']]?></td>
							<td><a href="#" class="excluiAnexo" data-id="<?=$anexo['ID'];?>"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
							</tr>
						<?php } ?>
					</tboby>
				</table>
			<?php
			$html = ob_get_clean();
		}catch(\Throwable $th){
			Log::registraLogSystem($th);
			return "<div>Ocorreu um erro do sistema ao procurar por anexos neste contrato</div>";
		}finally{
			return $html;
		}
			
	}

	/**	SET ANEXOS EM CONTRATO
	 * Grava no banco na tabela de contratos os anexos selecionados.
	 * Além disso retorna em html a lista de anexos do contrato atualizada.
	 * @type	function
	 * @date	22/02/22
	 * @since	1.0.0
	 * @param	Array dados
	 * @return	JSON
	 */
	public static function setAnexosContrato($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível Anexar arquivos ao contrato';
		global $wpdb;
		$BASContrato = $wpdb->prefix.sigsaClass::get_prefix().'bascontrato';
		try{
			$IDEmpresa = VerificacaoAcesso::getIDEmpresa();
			$data = array(
				'DSAnexosJSON' => $dados['lista']
			);
			$where = array('IDContrato' => $dados['contratoid']);
			$wpdb->update($BASContrato,$data,$where);
			$retorno['html'] = SELF::getListaAnexos($dados['lista']);
			$retorno['status'] = true;
		}catch(\Throwable $th){
			$retorno['status'] = false;
			$retorno['erro'] = 'Erro de código ao anexar arquivos no contrato';
			Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}
}