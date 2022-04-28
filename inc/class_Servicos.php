<?php
/*
*  CLASS Servicos
*
*  manipula as funcoes relacionadas a Servicos 
*
*  @type	class
*  @date	07/03/22
*  @since	1.0.0
*
*/
include_once('class_Log.php');
class Servicos
{
	
	private static $instance;

	/** GET INSTANCE
     * 
     * @type	function
     * @date	07/03/22
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
	 * @date	07/03/22
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
		printf('Conteúdo:<pre>%s</pre>',print_r($t,true));
	}

	/**	GET EMPRESA SESSIOM
	 * 
	 * Retorna o IDEmpresa gravado em empresa
	 * @type	function
	 * @date	07/03/22
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

	/** SALVAR SERVICO
	 * 
	 * Recebe os parametros do servico e faz o salvamento DB
	 * tem a função de verificar se ja existe um. 
	 * @type	function
	 * @date	07/03/22
	 * @since	1.0.0
	 * 
	 * @param	ARRAY | dados
	 * @return	INT idprojeto
	 */
    public static function salvarServico($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'Não foi possível Salvar o Servico';
		//return json_encode($retorno);
		global $wpdb;
		$DEFServico = $wpdb->prefix.sigsaClass::get_prefix().'defservico';
		try{
			$IDEmpresa = SELF::getIDEmpresa();
			if( !isset($IDEmpresa) ){$retorno['erro']='Empresa não encotrada';return json_encode($retorno);}
			$retorno['erro'] = 'Estou funcionando';
		}catch(\Throwable $th){
			$retorno['erro'] = 'Houve um erro ao salvar o projeto!';
			Log::registraLogSystem($th);
		}finally{
			return json_encode($retorno);
		}
	}



}