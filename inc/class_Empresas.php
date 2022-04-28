<?php
/** 
 *  CLASS Empresas
 *  manipula as funcoes relacionadas a configurações > Empresas 
 *
 *  @type	class
 *  @date	12/07/2021
 *  @since	1.0.0
 *
 */
include_once('class_Log.php');

class Empresas
{
	
	private static $instance;

	/** get_instance
	 * 
	 *  @type	function
	 *  @date	12/07/21
	 *  @since	1.0.0
	 *
	 *  @param	N/A
	 *  @return	(Object) Empresas.
	 */
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
	 * @date	12/07/21
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
	 * @date	13/07/21
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
    
    /** TESTE DE AJAX DA CLASS 
	 * 
	 * Retorna um testo para o arquivo Handler para teste do ajax .
	 * 
	 * @type	function
	 * @date	09/11/21
	 * @since	1.0.0
	 *
	 * @param	NULL
	 * @return	TEXT
	 */
    static function teste(){
        return "testando ok";
    }

	/** GET QUANTIDADE DE EMPRESAS 
	 * 
	 * Obtem o numero de Empresas cadastrados com os filtros selecionados
	 * 
	 * @type	function
	 * @date	22/12/21
	 * @since	1.0.0
	 * @Autor 	Moacyr Leandro Delboni loss
	 *
	 * @param	NULL
	 * @return	INT
	 */
    public static function getQuantidadeEmpresas(){
        global $wpdb;
        $table_PAREmpresa = $wpdb->prefix.sigsaClass::get_prefix().'parempresa';
		try{
			$result = $wpdb->get_var('SELECT COUNT(*) FROM '.$table_PAREmpresa);	
		}catch(\Throwable $th){
			$result = false;
		}finally{
			return $result;
		}
    }

	/** GET EMPRESAS 
	 * 
	 * Levanta a lista de empresas com nomes e ids
	 * 
	 * @type	function
	 * @date	05/10/21 - 25/11/21 
	 * @since	1.0.1
	 *
	 * @param	NULL
	 * @return	ARRAY
	 */
	public static function getEmpresas(){
		try{
			global $wpdb;
			$table_PAREmpresa = $wpdb->prefix.sigsaClass::get_prefix().'parempresa';
			$SQL = 'SELECT * FROM '.$table_PAREmpresa.' ORDER BY NMRazaoEmpresa ASC LIMIT 20;';
			$empresas = $wpdb->get_results($SQL);
		}catch(\Throwable $th){
			$empresas = null;
			Log::registraLogSystem($th);
		}finally{
			return $empresas;
		}
	}

	/** GET LISTA EMPRESAS 
	 * 
	 * Levanta a lista de todos Empresas cadastradas
	 * @type	function
	 * @date	13/07/21
	 * @since	1.0.0
	 * 
	 * @param	NULL
	 * @return	HTML
	 */
    public static function getListaEmpresasHTML($filtro = null){
		global $wpdb;
		$table_empresa = $wpdb->prefix.sigsaClass::get_prefix().'parempresa';

		ob_start();
		?>
		<div class="listaEmpresas">
			<div class="actions" style="display:none;">
				<a href="javascript:void(0);" class="adicionar btn btn-default btn-sm">
					<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					Enpresa
				</a>
			</div>
		<?php
		if (isset($_REQUEST['p']))$filtro['pagina'] = $_REQUEST['p']; else $filtro['pagina']=1;
		if (isset($_REQUEST['q_registros'])) $filtro['q_registros'] = $_REQUEST['q_registros']; 
        $filtro['pagina'] = $filtro['pagina']-1;
        $limit = ' LIMIT '.($filtro['pagina']*$filtro['q_registros']).','.$filtro['q_registros'];
		
		if( isset($_REQUEST['s']) ){
			$where = ' WHERE ( NMRazaoEmpresa like "%'.$_REQUEST['s'].'%" OR NMFantasiaEmpresa like "%'.$_REQUEST['s'].'%" )';
		}else{
			$where = '';
		}
		
		$SQL = 'SELECT * FROM '.$table_empresa.$where.' ORDER BY NMRazaoEmpresa ASC '.$limit.';';
		//echo $SQL;
		$empresas = $wpdb->get_results($SQL);
		
		if( isset($_REQUEST['s']) ){
			$n_empresas = count($empresas);
			if($n_empresas == 0 )$str = 'nenhum registro';
			else if($n_empresas == 1 )$str = '1 registro';
				else $str = $n_empresas.' registros';
			echo '<p>Sua pesquisa por: "'.$_REQUEST['s'].'" encontrou '.$str.'.</p>';
			//echo $SQL;
		}

		if($empresas){
			foreach($empresas as $empresa){
				$logo_src = wp_get_attachment_image_url($empresa->IMLogoEmpresa,'thumbnail');
			?>
				<div class="empresaItem caixa" id="IDEempresa_<?=$empresa->IDEmpresa?>" data-id="<?=$empresa->IDEmpresa?>">
					<div class="thumb">
						<img 
							src="<?=$logo_src?>"
							data-id="<?=$empresa->IMLogoEmpresa?>"
						/>
					</div>
					<div class="NMRazaoEmpresa"><h3><?=$empresa->NMRazaoEmpresa?></h3></div>
					<div class="NUCnpjEmpresa"><?=$empresa->NUCnpjEmpresa?></div>
					<div class="info"> 
						<div class="NMFantasiaEmpresa"><?=$empresa->NMFantasiaEmpresa?></div>
						<div class="NMWebsiteEmpresa"><?=$empresa->NMWebsiteEmpresa?></div>
					</div>
					<div class="opcoes">
						<div class="status" data-status="<?=$empresa->STEmpresa?>">
							<div>Status</div>
							<i class="fa fa-toggle-<?=$empresa->STEmpresa?'on':'off';?>" aria-hidden="true"></i>
						</div>
						<div class="opcao" data-status="<?=$empresa->STEmpresa?>">
							<i class="fa fa-trash-o" aria-hidden="true"></i>
						</div>
					</div>

				</div>    

			<?php
			}
		}
        
        ?></div><?php
		$html = ob_get_clean();
		return $html;
	}

	/** GET CONTEUDO INTERNO EMPRESA
	 * 
	 * Recebe os parametros da empresa e faz o salvamento DB
	 * @type	function
	 * @date	23/07/21
	 * @since	1.0.0
	 * 
	 * @param	ARRAY()
	 * @return	JSON HTML
	 */
	static  function getConteudoEmpresaModalHTML($dados){
		global $wpdb;
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível Obter os dados'.$dados['IDEmpresa'];
		$PARContratoEmpresa = $wpdb->prefix.sigsaClass::get_prefix().'parcontratoempresa';
		
        ob_start();
		if($dados['IDEmpresa']){
			//echo $this::getGERMetadadoValue('xx');
			$SQL = 'SELECT * FROM '.$PARContratoEmpresa.' WHERE IDEmpresa ='. $dados['IDEmpresa'] .' ORDER BY CDContratoEmpresa ASC LIMIT 20;';
			//echo $SQL;
			$contratos = $wpdb->get_results($SQL);
            foreach($contratos as $contrato){
				$tipo = Empresas::getGERMetadadoValue( $contrato->CDTipoContratoEmpresa );
				$tipo = preg_replace(array("/(A|ã)/","/(E)/","/(I)/","/(O)/","/(U)/","/(C)/","/ /"),explode(" ","a e i o u c -"),$tipo);
				?>
				<div class="contrato caixa <?=$tipo?>" id="contrato_<?=$contrato->IDContratoEmpresa?>" 
					data-id="<?=$contrato->IDContratoEmpresa?>"
					data-situacao="<?=$contrato->CDSituacaoContratoEmpresa?>"
					data-tipo="<?=$contrato->CDTipoContratoEmpresa?>"
				>
					<input type="hidden" name="NUAditivoContratoEmpresa" value="<?=$contrato->NUAditivoContratoEmpresa?>"/>
					<div class="icone">
						<i class="fa fa-file-text-o" aria-hidden="true"></i>
					</div>
					<div class="info">
						<div class="titulo"><?=$contrato->DSContratoEmpresa?></div>
						<div class="subtitulo">
							<span class="CDContratoEmpresa"><?=$contrato->CDContratoEmpresa?></span>
							<span class="DTInicioContratoEmpresa"><?=$contrato->DTInicioContratoEmpresa?></span>
							<span class="DTTerminoContratoEmpresa"><?=$contrato->DTTerminoContratoEmpresa?></span>
						</div>
					</div>
					<div class="content" style="display:none">
						conteudo do endereço quando abrir
					</div>
				</div>
				<?php
			}

		}
		?>
		
		<?php
		$contratos = ob_get_clean();
		$retorno['contratos'] = $contratos;
        
		$PAREnderecoEmpresa = $wpdb->prefix.sigsaClass::get_prefix().'parenderecoempresa';
		ob_start();
		if($dados['IDEmpresa']){
			//echo $this::getGERMetadadoValue('xx');
			$SQL = 'SELECT * FROM '.$PAREnderecoEmpresa.' WHERE IDEmpresa ='. $dados['IDEmpresa'] .' ORDER BY IDEnderecoEmpresa ASC LIMIT 20;';
			$enderecos = $wpdb->get_results($SQL);
			
            foreach($enderecos as $endereco){
				$CDTipoEndEmpresaJSON = json_decode($endereco->CDTipoEndEmpresaJSON,true);
				$DSEnderecoEmpresaJSON = json_decode($endereco->DSEnderecoEmpresaJSON);
				$DSTelefoneEndEmpresaJSON = json_decode($endereco->DSTelefoneEndEmpresaJSON);
                if( isset($DSTelefoneEndEmpresaJSON) ){$nTelefones = count($DSTelefoneEndEmpresaJSON);}
				$class = '';
				$iconesHTML = '';
				
                foreach($CDTipoEndEmpresaJSON as $CDtipo){
					$tipo = Empresas::getGERMetadadoValue( intval($CDtipo) );
					$tipo = preg_replace(array("/(A|ã)/","/(E|ê)/","/(F)/","/(G)/","/(I)/","/(O|ó)/","/(P)/","/(T)/","/(U)/","/(C)/","/ /"),explode(" ","a e f g i o p t u c -"),$tipo);
					//$class .= ' '.$tipo;
					$iconesHTML .= '<div class="icone '. $tipo .'"><i class="fa fa-university" aria-hidden="true"></i></div>';
				}
                
			    ?>
				<div class="endereco caixa <?=$class?>" 
					id="endereco_<?=$endereco->IDEnderecoEmpresa?>"
					data-id="<?=$endereco->IDEnderecoEmpresa?>"
				>
					<div class="info">
						<div class="titulo">
							<?=$DSEnderecoEmpresaJSON->logradouro?>,
							<?=$endereco->NULogradouroEndEmpresa?>, 
							<?=$DSEnderecoEmpresaJSON->localidade?>-
							<?=$DSEnderecoEmpresaJSON->uf?>
						</div>
						<div class="subtitulo">
							<?php 
								if($nTelefones == 0)echo 'Nenhum telefone cadastrado';
								else if($nTelefones == 1) echo '1 Telefone';
								else echo $nTelefones.' Telefones';
							?>
						</div>
					</div>
					<div class="icones">
						<?=$iconesHTML?>
					</div>
					<div class="content" style="display:none">
						<div class="CDTipoEndEmpresaJSON"><?=$endereco->CDTipoEndEmpresaJSON?></div>
						<div class="DSEnderecoEmpresaJSON"><?=$endereco->DSEnderecoEmpresaJSON?></div>
						<div class="DSTelefoneEndEmpresaJSON"><?=$endereco->DSTelefoneEndEmpresaJSON?></div>
						<input type="hidden" name="NULogradouroEndEmpresa" value="<?=$endereco->NULogradouroEndEmpresa?>"/>
						<input type="hidden" name="DSComplementoEndEmpresa" value="<?=$endereco->DSComplementoEndEmpresa?>"/>
					</div>
				</div>
			<?php
			}
		}//End if IDEmpresa
		?>

		<?php
		$enderecos = ob_get_clean();
		$retorno['enderecos'] = $enderecos;

		$PARContatoEmpresa = $wpdb->prefix.sigsaClass::get_prefix().'parcontatoempresa';
		ob_start();
		if($dados['IDEmpresa']){
			
			$SQL = 'SELECT * FROM '.$PARContatoEmpresa.' WHERE IDEmpresa ='. $dados['IDEmpresa'] .' ORDER BY IDContatoEmpresa ASC LIMIT 20;';
			$contatos = $wpdb->get_results($SQL);

			foreach($contatos as $contato){
				$DSReferenciaContatoEmpJSON = json_decode($contato->DSReferenciaContatoEmpJSON,true);
				$iconesHTML = '';
				foreach($DSReferenciaContatoEmpJSON as $referencia){
					$tipo = Empresas::getGERMetadadoValue( intval($referencia) );
					$tipo = preg_replace(array("/(A|ã)/","/(E|ê)/","/(F)/","/(G)/","/(I)/","/(O|ó)/","/(P)/","/(T)/","/(U)/","/(C)/","/ /"),explode(" ","a e f g i o p t u c -"),$tipo);
					$iconesHTML .= '<div class="icone '. $tipo .'"><i class="fa fa-university" aria-hidden="true"></i></div>';
				}
				?>

				<div class="contato caixa" 
					id="contato_<?=$contato->IDContatoEmpresa?>"
					data-id="<?=$contato->IDContatoEmpresa?>" 
				>
					<div class="icones">
						<?=$iconesHTML?>
					</div>
					<div class="info">
						<div class="titulo"><?=$contato->NMContatoEmpresa?></div>
						<div class="subtitulo"><?=$contato->EDEmailContatoEmpresa?></div>
					</div>
					<div class="content" style="display:none">
						<div class="DSTelefoneContatoEmpresaJSON"><?=$contato->DSTelefoneContatoEmpresaJSON?></div>
						<div class="DSReferenciaContatoEmpJSON"><?=$contato->DSReferenciaContatoEmpJSON?></div>
						<input type="hidden" name="NMContatoEmpresa" value="<?=$contato->NMContatoEmpresa?>"/>
						<input type="hidden" name="EDEmailContatoEmpresa" value="<?=$contato->EDEmailContatoEmpresa?>"/>
						<input type="hidden" name="DSSetorContatoEmpresa" value="<?=$contato->DSSetorContatoEmpresa?>"/>
						<input type="hidden" name="DSCargoContatoEmpresa" value="<?=$contato->DSCargoContatoEmpresa?>"/>
						<input type="hidden" name="IDEnderecoEmpresa" value="<?=$contato->IDEnderecoEmpresa?>"/>
					</div>
				</div>

				<?php
			}

		}// End if IDEmpresa
		$contatos = ob_get_clean();
		$retorno['contatos'] = $contatos;
		
		
		$retorno['status'] = true;
		return json_encode($retorno);
	}

	/** SALVA EMPRESA
	 * 
	 * Recebe os parametros da empresa e faz o salvamento DB
	 * @type	function
	 * @date	22/07/21
	 * @since	1.0.0
	 * 
	 * @param	NULL
	 * @return	INT IDEmpresa
	 */
    public static function salvaEmpresa($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível Salvar a empresa';
		
		global $wpdb;
		$table_empresa = $wpdb->prefix.sigsaClass::get_prefix().'parempresa';
		
		$data = array(
			'IDEmpresa'			=> null, 
			'NMRazaoEmpresa' 	=> $dados['NMRazaoEmpresa'],
			'NUCnpjEmpresa'		=> $dados['NUCnpjEmpresa'],
			'NMWebsiteEmpresa'	=> $dados['NMWebsiteEmpresa'],
			'NMFantasiaEmpresa' => $dados['NMFantasiaEmpresa'],
			'IMLogoEmpresa'		=> $dados['IMLogoEmpresa'],
			'STEmpresa' 		=> $dados['STEmpresa']
		);

		if($dados['IDEmpresa']){
			//ALTERACAO DE EMPRESA
			//$format = array('%s','%d','%s');
			$data['IDEmpresa'] = $dados['IDEmpresa'];
			$where = array('IDEmpresa' => $dados['IDEmpresa']);
			$wpdb->update($table_empresa,$data,$where);
			$retorno['status'] = true;
			$retorno['IDEmpresa'] = $dados['IDEmpresa'];
			Log::registraLog($table_empresa,'UPDATE',get_current_user_id(),$data,$dados['IDEmpresa'] );

		}
		else{
			//INCLUSAO DE NOVO PROJETO
			//$format = array('%s','%d','%s');
			$wpdb->insert($table_empresa,$data);
			$my_id = $wpdb->insert_id;
			if($my_id){
				$retorno['status'] = true;
				$retorno['IDEmpresa'] = $my_id;
			}
			$data['IDEmpresa'] =  $my_id;
			Log::registraLog($table_empresa,'INSERT',get_current_user_id(),$data,$dados['IDEmpresa'] );
		}

		return json_encode($retorno);
	}

	/** SALVA CONTRATO EMPRESA
	 * 
	 * Recebe os parametros do contrato e faz o salvamento DB
	 * @type	function
	 * @date	28/07/21
	 * @since	1.0.0
	 * 
	 * @param	NULL
	 * @return	INT IDContratoEmpresa
	 */
    public static function salvaContratoEmpresa($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível Salvar o contrato';
		global $wpdb;
		$table_contrato = $wpdb->prefix.sigsaClass::get_prefix().'parcontratoempresa';
		
		$data = array(
			'IDContratoEmpresa'			=> null,
			'IDEmpresa'					=> $dados['IDEmpresa'],
			'CDTipoContratoEmpresa'		=> $dados['CDTipoContratoEmpresa'],
			'CDContratoEmpresa'       	=> $dados['CDContratoEmpresa'],
			'DSContratoEmpresa'			=> $dados['DSContratoEmpresa'],
			'DTInicioContratoEmpresa'	=> $dados['DTInicioContratoEmpresa'],
			'DTTerminoContratoEmpresa'	=> $dados['DTTerminoContratoEmpresa'],
			'CDSituacaoContratoEmpresa'	=> $dados['CDSituacaoContratoEmpresa'],
			'NUAditivoContratoEmpresa'	=> $dados['NUAditivoContratoEmpresa']
		);

		if($dados['IDContratoEmpresa']){
			//ALTERACAO DE CONTRATO
			//$format = array('%s','%d','%s');
			$data['IDContratoEmpresa'] = $dados['IDContratoEmpresa'];
			$where = array('IDContratoEmpresa' => $dados['IDContratoEmpresa']);
			$wpdb->update($table_contrato,$data,$where);
			$retorno['status'] = true;
			$retorno['IDContratoEmpresa'] = $dados['IDContratoEmpresa'];
			Log::registraLog($table_contrato,'UPDATE',get_current_user_id(),$data,$dados['IDEmpresa'] );

		}
		else{
			//INCLUSAO DE NOVO PROJETO
			//$format = array('%s','%d','%s');
			$wpdb->insert($table_contrato,$data);
			$my_id = $wpdb->insert_id;
			if($my_id){
				$retorno['status'] = true;
				$retorno['IDContratoEmpresa'] = $my_id;
			}
			$data['IDContratoEmpresa'] =  $my_id;
			Log::registraLog($table_empresa,'INSERT',get_current_user_id(),$data,$dados['IDEmpresa'] );
		}

		return json_encode($retorno);
	}
	
	/** EXCLUIR CONTRATO EMPRESA
	 * 
	 * Recebe os parametros do contrato e faz a exclusao DB
	 * @type	function
	 * @date	29/07/21
	 * @since	1.0.0
	 * 
	 * @param	NULL
	 * @return	INT IDContratoEmpresa
	 */
    public static function excluirContratoEmpresa($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível excluir o registro do contrato';
		global $wpdb;
		$table_contrato = $wpdb->prefix.sigsaClass::get_prefix().'parcontratoempresa';

		if($dados['IDContratoEmpresa']){
			$data = array(
				'IDContratoEmpresa'		=> $dados['IDContratoEmpresa'] 
			);
			$wpdb->delete($table_contrato,$data);
			$retorno['status'] = true;
			$retorno['IDContratoEmpresa'] = $dados['IDContratoEmpresa'];
		}
		return json_encode($retorno);
	}
	
	/** SALVA ENDERECO EMPRESA
	 * 
	 * Recebe os parametros do endereco e faz o salvamento DB
	 * @type	function
	 * @date	30/07/21
	 * @since	1.0.0
	 * 
	 * @param	NULL
	 * @return	INT IDEnderecoEmpresa
	 */
    public static function salvaEnderecoEmpresa($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível Salvar o endereco';
		global $wpdb;
		$table_endereco = $wpdb->prefix.sigsaClass::get_prefix().'parenderecoempresa';
		
		$CDTipoEndEmpresaJSON = json_encode($dados['CDTipoEndEmpresa']);
		$DSTelefoneEndEmpresaJSON = json_encode($dados['DSTelefoneEndEmpresa']);
		$DSEnderecoEmpresa = array(
			'cep' 			=> $dados['CEP'],
			'logradouro' 	=> $dados['logradouro'],
			'bairro' 		=> $dados['bairro'],
			'localidade'	=> $dados['localidade'],
			'uf' 			=> $dados['uf']
		);
		$DSEnderecoEmpresaJSON = json_encode($DSEnderecoEmpresa);
		$data = array(
			'IDEnderecoEmpresa'			=> null,
			'IDEmpresa'					=> $dados['IDEmpresa'],
			'CDTipoEndEmpresaJSON'		=> $CDTipoEndEmpresaJSON,
			'DSEnderecoEmpresaJSON'		=> $DSEnderecoEmpresaJSON,
			'NULogradouroEndEmpresa'	=> $dados['NULogradouroEndEmpresa'],
			'DSComplementoEndEmpresa'	=> $dados['DSComplementoEndEmpresa'],
			'DSTelefoneEndEmpresaJSON'		=> $DSTelefoneEndEmpresaJSON
		);
		

		if(!$dados['logradouro'])return json_encode($retorno);

		if($dados['IDEnderecoEmpresa']){
			//ALTERACAO DE ENDERECO
			//$format = array('%s','%d','%s');
			$data['IDEnderecoEmpresa'] = $dados['IDEnderecoEmpresa'];
			$where = array('IDEnderecoEmpresa' => $dados['IDEnderecoEmpresa']);
			$wpdb->update($table_endereco,$data,$where);
			$retorno['status'] = true;
			$retorno['IDEnderecoEmpresa'] = $dados['IDEnderecoEmpresa'];
			Log::registraLog($table_endereco,'UPDATE',get_current_user_id(),$data,$dados['IDEmpresa'] );
		}
		else{
			//INCLUSAO DE NOVO PROJETO
			//$format = array('%s','%d','%s');
			$wpdb->insert($table_endereco,$data);
			$my_id = $wpdb->insert_id;
			if($my_id){
				$retorno['status'] = true;
				$retorno['IDEnderecoEmpresa'] = $my_id;
			}
			$data['IDEnderecoEmpresa'] =  $my_id;
			Log::registraLog($table_endereco,'INSERT',get_current_user_id(),$data,$dados['IDEmpresa'] );
		}

		return json_encode($retorno);
	}
	
	/** EXCLUIR ENDERECO EMPRESA
	 * 
	 * Recebe os parametros do contrato e faz a exclusao DB
	 * @type	function
	 * @date	04/08/21
	 * @since	1.0.0
	 * 
	 * @param	NULL
	 * @return	JSON IDEnderecoEmpresa
	 */
    public static function excluirEnderecoEmpresa($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível excluir o registro do Endereco';
		global $wpdb;
		$table_contato = $wpdb->prefix.sigsaClass::get_prefix().'parcontatoempresa';
		$table_endereco = $wpdb->prefix.sigsaClass::get_prefix().'parenderecoempresa';
		//return json_encode($retorno);
		if($dados['IDEnderecoEmpresa']){
			// verificando a existencia de contatos vinculados a este endereço
			// se houver retorna os dados do(s) contato(s). e não exclui
			$query = 'SELECT NMContatoEmpresa FROM '.$table_contato.' ';
			$query .= ' WHERE IDEnderecoEmpresa="'. $dados['IDEnderecoEmpresa'] .'" ORDER BY IDContatoEmpresa ASC;';
			$contatos = $wpdb->get_results($query);
			if($contatos){
				$retorno['erro'] = 'Existe Contatos vinculados a este endereço que está tentando apagar';
				$retorno['contatos'] = $contatos;
				return json_encode($retorno);
			}
			// se não houver contatos vinculados realiza a operação de exclusao do endereço
			$data = array(
				'IDEnderecoEmpresa'		=> $dados['IDEnderecoEmpresa'] 
			);
			$wpdb->delete($table_endereco,$data);
			$retorno['status'] = true;
			$retorno['IDEnderecoEmpresa'] = $dados['IDEnderecoEmpresa'];
		}
		return json_encode($retorno);
	}

	/** SALVA CONTATO EMPRESA
	 * 
	 * Recebe os parametros do contato e faz o salvamento DB
	 * @type	function
	 * @date	04/08/21
	 * @since	1.0.0
	 * 
	 * @param	NULL
	 * @return	JSON retorno array
	 */
    public static function salvaContatoEmpresa($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível Salvar o contato';
		global $wpdb;
		$table_contato = $wpdb->prefix.sigsaClass::get_prefix().'parcontatoempresa';
		
		$DSReferenciaContatoEmpJSON = json_encode($dados['DSReferenciaContatoEmpJSON']);
		$DSTelefoneContatoEmpresaJSON = json_encode($dados['DSTelefoneContatoEmpresaJSON']);
		
		$data = array(
			'IDContatoEmpresa'				=> null,
			'IDEmpresa'						=> $dados['IDEmpresa'],
			'DSReferenciaContatoEmpJSON'	=> $DSReferenciaContatoEmpJSON,
			'NMContatoEmpresa'				=> $dados['NMContatoEmpresa'],
			'EDEmailContatoEmpresa'			=> $dados['EDEmailContatoEmpresa'],
			'DSSetorContatoEmpresa'			=> $dados['DSSetorContatoEmpresa'],
			'DSCargoContatoEmpresa'			=> $dados['DSCargoContatoEmpresa'],
			'DSTelefoneContatoEmpresaJSON'	=> $DSTelefoneContatoEmpresaJSON,
			'IDEnderecoEmpresa'				=> $dados['IDEnderecoEmpresa']
		);
		if(!$dados['IDEmpresa'] || !$dados['NMContatoEmpresa'] || !$dados['EDEmailContatoEmpresa'] )return json_encode($retorno);
		
		if($dados['IDContatoEmpresa']){
			//ALTERACAO DE ENDERECO
			//$format = array('%s','%d','%s');
			$data['IDContatoEmpresa'] = $dados['IDContatoEmpresa'];
			$where = array('IDContatoEmpresa' => $dados['IDContatoEmpresa']);
			$wpdb->update($table_contato,$data,$where);
			$retorno['status'] = true;
			$retorno['IDContatoEmpresa'] = $dados['IDContatoEmpresa'];
			Log::registraLog($BASCliente,'UPDATE',get_current_user_id(),$data,$dados['IDEmpresa'] );
		}
		else{
			//INCLUSAO DE NOVO PROJETO
			//$format = array('%s','%d','%s');
			$wpdb->insert($table_contato,$data);
			$my_id = $wpdb->insert_id;
			if($my_id){
				$retorno['status'] = true;
				$retorno['IDContatoEmpresa'] = $my_id;
			}
			Log::registraLog($BASCliente,'INSERT',get_current_user_id(),$data,$my_id );
		}
		
		return json_encode($retorno);
	}
	
	/** EXCLUIR CONTATO EMPRESA
	 * 
	 * Recebe os parametros do contrato e faz a exclusao DB
	 * @type	function
	 * @date	04/08/21
	 * @since	1.0.0
	 * 
	 * @param	NULL
	 * @return	INT IDEnderecoEmpresa
	 */
	public static function excluirContatoEmpresa($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível excluir o registro do Contato';
		global $wpdb;
		$table_contato = $wpdb->prefix.sigsaClass::get_prefix().'parcontatoempresa';
		
		if($dados['IDContatoEmpresa']){
			$data = array(
				'IDContatoEmpresa'		=> $dados['IDContatoEmpresa'] 
			);
			$wpdb->delete($table_contato,$data);
			$retorno['status'] = true;
			$retorno['IDContatoEmpresa'] = $dados['IDContatoEmpresa'];
		}
		return json_encode($retorno);
	}

	/** Get METADADOS OPCOES
	 * 
	 * Recebe o nome do metadado e realiza a busca das opcoes
	 * @type	function
	 * @date	13/07/21
	 * @since	1.0.0
	 * 
	 * @param	NULL
	 * @return	INT IDEnderecoEmpresa
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
}