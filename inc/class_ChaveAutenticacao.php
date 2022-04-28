<?php
/*
*  CLASS ChaveAutenticacao
*
*  manipula as funcoes relacionadas as chaves de autenticações
*
*  @type	class
*  @date	29/03/2022
*  @since	1.0.0
*
*/
include_once('class_Log.php');
class ChaveAutenticacao
{
	
	private static $instance;

	/** GET INSTANCE
     * 
     * @type	function
     * @date	29/03/2022
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
	 * @date	29/03/2022
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
	 * @date	29/03/22
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
	 * Levanta a lista de projetos e retorna em objeto array
	 * 
	 * @type	function
	 * @date	29/03/2022
	 * @since	1.0.0
	 *
	 * @param	VAR|ARRAY|OBJ
	 * @return	HTML
	 */
	public static function getListaRegistros($filtro = null){
		global $wpdb;
		$DEFChaveAutenticacao = $wpdb->prefix.sigsaClass::get_prefix().'defchaveautenticacao';
		try{

			if(isset($filtro['IDProjeto']) && $filtro['IDProjeto'] !='' ){
				$IDProjeto = $filtro['IDProjeto'];
				//$where = 
				$SQL = <<<SQL
					SELECT chave.*
					FROM $DEFChaveAutenticacao AS chave
					WHERE chave.IDProjeto = $IDProjeto
					LIMIT 0,1;
				SQL;
				$registros = $wpdb->get_row($SQL);
			}
			else{
				$registros = false;
			}
		}catch(\Throwable $th){
			$registros = false;
			//Log::registraLogSystem($th);
		}finally{
			return $registros;
		}
	}

	/** SET CHAVE DE AUTENTICAVAO
	 * 
	 * grava no DB os dados da chave de autenticacao
	 * 
	 * @type	function
	 * @date	30/03/2022
	 * @since	1.0.0
	 *
	 * @param	VAR|ARRAY|OBJ
	 * @return	JSON
	 */
	public static function setChaveAutenticacao($filtro = null){
		$retorno['erro'] = 'não conseguimos realizar esta operacao';
		$retorno['status'] = false;
		global $wpdb;
		$DEFChaveAutenticacao = $wpdb->prefix.sigsaClass::get_prefix().'defchaveautenticacao';
		try{
			$IDEmpresa = SELF::getIDEmpresa();
			if( !isset($IDEmpresa) ){$retorno['erro']='Empresa não encotrada';return json_encode($retorno);}

			if(isset($filtro['projetoid']) && $filtro['projetoid'] !='' ){
				$IDProjeto = $filtro['projetoid'];
				
				$procura = $wpdb->get_row($wpdb->prepare( "SELECT IDChaveAutenticacao FROM $DEFChaveAutenticacao WHERE NUChaveAutenticacao = '%s'", $filtro['codigo'] ) );
				if($procura){
					$retorno['erro'] = 'Esta chave já foi usada no sistema. Escolha outra!';
				}else{
					$data = array(
						'IDChaveAutenticacao'	=> null,
						'NUChaveAutenticacao'	=> $filtro['codigo'],
						'IDContrato'			=> null,
						'IDProjeto'				=> $IDProjeto,
						'IDEmpresa'				=> $IDEmpresa
					);
					$SQL = <<<SQL
						SELECT IDChaveAutenticacao FROM $DEFChaveAutenticacao WHERE IDProjeto = $IDProjeto
					SQL;
					$IDChaveAutenticacao = $wpdb->get_row($SQL);
					$IDChaveAutenticacao = $IDChaveAutenticacao->IDChaveAutenticacao;
					if($IDChaveAutenticacao){
						$data['IDChaveAutenticacao'] = $IDChaveAutenticacao;
						$where = array('IDChaveAutenticacao' => $IDChaveAutenticacao);
						$wpdb->update($DEFChaveAutenticacao,$data,$where);
						$retorno['status'] = true;
						//Log::registraLog($DEFLocal,'UPDATE',get_current_user_id(),$data,$IDEmpresa );
					}else{
						$wpdb->insert( $DEFChaveAutenticacao , $data);
						$retorno['status'] = true;
					}
				}
			}
			else{
				$retorno['erro'] = 'Não consegui salvar a chave devido a um erro no ID do projeto';
			}
		}catch(\Throwable $th){
			$retorno['erro'] = 'algo deu ruim';
			//Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}

	/** GET DADOS DA CHAVE DE AUTENTICAVAO
	 * 
	 * Obtem todos os dados da chave de autenticacao
	 * 
	 * @type	function
	 * @date	30/03/2022
	 * @since	1.0.0
	 *
	 * @param	VAR|ARRAY|OBJ
	 * @return	JSON
	 */
	public static function getDados($filtro = null){
		$retorno['status'] = false;
		$retorno['erro'] = 'não conseguimos realizar esta operacao';
		global $wpdb;
		$DEFChaveAutenticacao = $wpdb->prefix.sigsaClass::get_prefix().'defchaveautenticacao';
		try{
			$IDEmpresa = SELF::getIDEmpresa();
			if( !isset($IDEmpresa) ){$retorno['erro']='Empresa não encotrada';return json_encode($retorno);}

			if(isset($filtro['projetoid']) && $filtro['projetoid'] !='' ){
				$IDProjeto = $filtro['projetoid'];
			}
			if(isset($filtro['chave']) && $filtro['chave'] !='' ){
				$chave = $filtro['chave'];
			}
			if($chave){

				$SQL = <<<SQL
					call spValidaNUChaveAutenticacao($chave);
				SQL;
				$registros = $wpdb->get_results($SQL);
				if($registros){
					unset($retorno['erro']);
					$retorno['chaveAutenticacao'] = $registros;
				}
			}
			else{
				$retorno['erro'] = 'Não consegui salvar a chave devido a um erro no ID do projeto';
			}
		}catch(\Throwable $th){
			$retorno['erro'] = 'algo deu ruim';
			//Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}

}