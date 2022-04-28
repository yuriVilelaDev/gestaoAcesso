<?php
/*
*  CLASS DadosAdicionais
*
*  manipula as funcoes relacionadas a configurações > Dados Adicionais 
*
*  @type	class
*  @date	06/07/2021
*  @since	1.0.0
*
*/
include_once('class_Log.php');

class DadosAdicionais
{
	
	private static $instance;

	/*
	*  get_instance
	*
	*  @type	function
	*  @date	06/07/21
	*  @since	1.0.0
	*
	*  @param	N/A
	*  @return	(Object) DadosAdicionais.
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
	 * @date	06/07/21
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
	 * @date	06/07/21
	 * @since	1.0.0
	 *
	 * @param	VAR|ARRAY|OBJ
	 * @return	HTML
	 */
	public function printa($t){
		echo '<pre>';
		print_r($t);
		echo '</pre>';	
	}

	/** GET LISTA SELECTS 
	 * 
	 * Levanta a lista de todos os selects da tabela de metadados
	 * @type	function
	 * @date	06/07/21
	 * @since	1.0.0
	 * 
	 * @param	NULL
	 * @return	HTML
	 */
    public  function getListaSelectsHTML(){
		global $wpdb;
		ob_start();

		$listaSelect =array();
		$listaSelect[] = array('id'=>'CDTipoContratoEmpresa','titulo'=>'Tipo Contrato Empresa') ;
		$listaSelect[] = array('id'=>'CDSituacaoContratoEmpresa','titulo'=>'Situação Contrato Empresa') ;
		$listaSelect[] = array('id'=>'CDTipoEndEmpresa','titulo'=>'Tipos Endereço Empresa') ;
		$listaSelect[] = array('id'=>'DSReferenciaContatoEmpJSON','titulo'=>'Referencia Contato Empresa') ;
		$listaSelect[] = array('id'=>'CDTipoEndCliente','titulo'=>'Tipos de endereço Cliente') ;
		$listaSelect[] = array('id'=>'DSReferenciaContatoClienteJSON','titulo'=>'Referencia Contato Cliente') ;
		$listaSelect[] = array('id'=>'CDTipoContrato','titulo'=>'Tipo Contrato Geral');
		$listaSelect[] = array('id'=>'CDSituacaoContrato','titulo'=>'Situação Contrato Geral');
		$listaSelect[] = array('id'=>'CDTipooperacaoPlataforma','titulo'=>'Tipo Opecao Plataforma');
		$listaSelect[] = array('id'=>'CDTipoLocal','titulo'=>'Tipo de classificação Local');

		echo '<div class="listaSelectsOpcoes">';
		foreach($listaSelect as $select){
			echo $this->getSelectOpcoes($select);
		}
		echo '</div>';
		$html = ob_get_clean();
		return $html;
	}

	/** GET SELECT OPCOES 
	 * 
	 * Monta na tela as a lista select que está em metadados
	 * 
	 * @type	function
	 * @date	07/07/21
	 * @since	1.0.0
	 *
	 * @param	ARRAY (id, titulo)
	 * @return	HTML
	 */
	public static function getSelectOpcoes($select){
		global $wpdb;
		ob_start();
		
		$tabela = $wpdb->prefix.sigsaClass::get_prefix().'germetadado';
		
		$where_clausula = '';
		if($select['id'])
			$where_clausula  = ' WHERE NMCampoMetadado = "'.$select['id'].'"';	
	
		$order_by = ' ORDER BY DSOpcaoMetadado ASC ';
		
		$SQL = 'SELECT IDMetadado,NMCampoMetadado as metaKey,DSOpcaoMetadado as metaValue FROM '.$tabela;
		$SQL .= $where_clausula;
		$SQL .= $order_by;
		
		$results = $wpdb->get_results($SQL);
		
		?>
		<div class="caixa selectOpcoes" id="<?=$select['id']?>">
			<div class="header">
				<span class="titulo"><?=$select['titulo']?></span>
				
				<button 
					type="button" 
					class="btn btn-default bt_editar" 
					aria-label="Left Align" 
					data-id="" 
					data-action="">
					<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
				</button>
			</div>
			<div class="content">
				<ul>
					<?php
						if($results):
							foreach($results as $linha){
								?>
									<li id="<?=$linha->IDMetadado?>" class="opcao" >
										<input 
											type=text 
											value="<?=$linha->metaValue?>" 
											name="<?=$linha->metaKey.'_'.$linha->IDMetadado?>" 
											data-oldValue="<?=$linha->metaValue?>"
											data-id="<?=$linha->IDMetadado?>"
											disabled />
										<a href="#" class="excluir"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
									</li>
								<?php
							}
						endif;
					?>
					<li>
						<a href="#" class="add"> <span>+</span> </a>
					</li>
				</ul>
				<div class="atualizando" style="display:none"><i class="fa fa-cog fa-spin fa-3x fa-fw" aria-hidden="true"></i></div>
			</div>
		</div>
		<?php
		$html = ob_get_clean();
		return $html;
	}

	/** SET OPCAO 
	 * 
	 * salva os dados de metadados fazendo uso do ID quando passado por parametro dentro de $dados
	 * se houver $dados[ID] ele faz update senao ele faz inserção de um novo campo
	 * 
	 * @type	function
	 * @date	12/07/21
	 * @since	1.0.0
	 *
	 * @param	ARRAY ($dados[ ID | metaKey | metaValue ])
	 * @return	BOOLEAN
	 */
	public static function setOpcao($dados){
		global $wpdb;
		$tabela = $wpdb->prefix.sigsaClass::get_prefix().'germetadado';
		
		if($dados['ID'] == 0 || $dados['ID']== null){
			// insersao de um novo campo
			$insert = array(
				'IDMetadado'		=> null,
				'NMCampoMetadado'	=> $dados['metaKey'],
				'DSOpcaoMetadado'	=> $dados['metaValue']
			);
			$wpdb->insert($tabela,$insert);
			$my_id = $wpdb->insert_id;
			if($my_id){
				return $my_id;
			}
		}else{
			// alteracao de um campo
			$alteracao = array(
				'DSOpcaoMetadado'	=> $dados['metaValue']
			);
			$where = array('IDMetadado' => $dados['ID']);
			$wpdb->update($tabela,$alteracao,$where);
			return $where['IDMetadado'];
		}
		return false;
	}

	/** REMOVE OPCAO 
	 * 
	 * Apaga os dados recebendo o paremetro ID dentro de dados
	 * 
	 * @type	function
	 * @date	12/07/21
	 * @since	1.0.0
	 *
	 * @param	ARRAY ($dados[ ID ])
	 * @return	BOOLEAN | ID
	 */
	public static function removeOpcao($dados){
		global $wpdb;
		$tabela = $wpdb->prefix.sigsaClass::get_prefix().'germetadado';
		
		if($dados['ID']){
			$where = array('IDMetadado' => $dados['ID']);
			$wpdb->delete($tabela,$where);
			return $where['IDMetadado'];
		}
		return false;
	}

	/** SALVA SELECT 
	 * 
	 * Faz o rastreio de todo select de opcoes identificando todas as operações relacionadas
	 * Alteracao | exclusao | inclusao
	 * 
	 * @type	function
	 * @date	12/07/21
	 * @since	1.0.0
	 *
	 * @param	ARRAY ($dados[ ID | metaKey | metaValue ])
	 * @return	JSON para AJAX
	 */
	public static function salvaSelect($dados){
		$retorno['status'] = false;
		$retorno['erro'] = "Não conseguimos processar!";
		global $wpdb;
		$tabela = $wpdb->prefix.sigsaClass::get_prefix().'germetadado';

		$inputs = $dados['dado'];
		foreach($inputs as $input){
			if($input['control']=='apagar'){
				$data = array(
					'ID'			=> $input['id']
				);
				$retorno['erro'] = 'aqui';
				$retorno['alterados'] .= DadosAdicionais::removeOpcao($data);
			}else{
				$data = array(
					'ID'			=> $input['id'],
					'metaKey'		=> $dados['metaKey'],
					'metaValue'		=> $input['value']
				);
				$retorno['alterados'] .= DadosAdicionais::setOpcao($data);
			}	
		}
		
		// retornando o conteudo dos selects
		$where_clausula  = ' WHERE NMCampoMetadado = "'.$dados['metaKey'].'"';	
		$order_by = ' ORDER BY DSOpcaoMetadado ASC ';
		$SQL = 'SELECT IDMetadado,NMCampoMetadado as metaKey,DSOpcaoMetadado as metaValue FROM '.$tabela;
		$SQL .= $where_clausula;
		$SQL .= $order_by;
		
		$results = $wpdb->get_results($SQL);
		ob_start();
			?>
			<ul>
				<?php
					if($results):
						foreach($results as $linha){
							?>
								<li id="<?=$linha->IDMetadado?>" class="opcao" >
									<input 
										type=text 
										value="<?=$linha->metaValue?>" 
										name="<?=$linha->metaKey.'_'.$linha->IDMetadado?>" 
										data-oldValue="<?=$linha->metaValue?>"
										data-id="<?=$linha->IDMetadado?>"
										disabled />
									<a href="#" class="excluir"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
								</li>
							<?php
						}
					endif;
				?>
				<li>
					<a href="#" class="add"> <span>+</span> </a>
				</li>
			</ul>
			<div class="atualizando" style="display:none"><i class="fa fa-cog fa-spin fa-3x fa-fw" aria-hidden="true"></i></div>
			<?php
		$retorno['html'] = ob_get_clean();
		$retorno['status'] = true;
		return json_encode($retorno);
	}
}