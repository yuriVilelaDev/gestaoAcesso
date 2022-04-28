<?php
/*
*  CLASS PLATAFORMAS
*
*  manipula as funcoes relacionadas as Plataformas.
*
*  @type	class
*  @date	18/03/2022
*  @since	1.0.0
*
*/
include_once('class_Log.php');
class Plataforma{
	
	private static $instance;

	/** GET INSTANCE
     * 
     * @type	function
     * @date	22/03/2022
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
	 * @date	22/03/2022
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
	 * @date	22/03/22
	 * @since	1.0.0
	 * 
	 * @param	Array dados
	 * @return	HTML
	 */
	private static function getIDEmpresa(){
		session_start();
		$IDEmpresa = ( isset($_SESSION['IDEmpresa']) )? $_SESSION['IDEmpresa'] : NULL; 
		return $IDEmpresa;
	}
   
    static function listarPlataformas($idEmpresa = null){
		global $wpdb;
		$BASPlataforma = $wpdb->prefix.sigsaClass::get_prefix().'basplataforma';
		try{
			if(isset($idEmpresa))$IDEmpresa = $idEmpresa;
			else $IDEmpresa = SELF::getIDEmpresa();

			$SQL = "SELECT 
						IDPlataforma,
						NMPlataforma,
						DSPlataforma, 
						DSVersaoPlataforma, 
						DSReleasePlataforma, 
						CDTipoOperacaoPlataforma, 
						CDTipoLicencaPlataforma, 
						IMLogoPlataforma,
						STPlataforma	 
					FROM ".$BASPlataforma." where st_delete = 0  AND IDEmpresa = ".$IDEmpresa;
			$result =  $wpdb->get_results($SQL);
		}catch(\Throwable $th){
			$result = $th;
			Log::registraLogSystem($th);				
		}finally{
			return $result;
		}
    }
	
    public static function inserirPlataformas($dados){
		global $wpdb;

		 try{
			$data = array(
                 
                'NMPlataforma' => $dados['NMPlataforma'], 
                'DSPlataforma' => "test",  
                'DSVersaoPlataforma' => $dados['DSVersaoPlataforma'], 
                'DSReleasePlataforma' => $dados['DSReleasePlataforma'], 
                'CDTipoOperacaoPlataforma' => '0',  
                'CDTipoLicencaPlataforma' => '0', 
                'IMLogoPlataforma' => "test",  
                'STPlataforma' => '0', 
                'IDEmpresa' => '1'
			);
			
			$result =  $wpdb->insert('wp_sigsa_basplataforma', $data); 
			//  session_start();	
			//  $_SESSION['idPlataforma'] = $wpdb->insert_id;	
		}catch(\Throwable $th){
			$result = $th;
			Log::registraLogSystem($th);				
		}finally{
			return $result;
		}
	}
  
    static function deletePlataforma($dados){
        global $wpdb;
		try{
			
			$data['st_delete'] = 1;
			$where = array( 'IDPlataforma' => $dados['id']);
			$wpdb->update('wp_sigsa_basplataforma',$data,$where);

		}catch(\Throwable $th){
			$result = 0;
			Log::registraLogSystem(Throwable::getMessage());				
		}finally{
			return $result;
		}
    }
    
    static function alterarPlataforma($dados){
        global $wpdb;
		try{
			$data = array(
                'NMPlataforma' => $dados['IDUsuario'], 
                'DSPlataforma' => $dados['IDUsuario'],  
                'DSVersaoPlataforma' => $dados['IDUsuario'], 
                'DSReleasePlataforma' => $dados['IDUsuario'], 
                'CDTipoOperacaoPlataforma' => $dados['IDUsuario'],  
                'CDTipoLicencaPlataforma' => $dados['IDUsuario'], 
                'IMLogoPlataforma' => $dados['IDUsuario'],  
                'STPlataforma' => $dados['IDUsuario'], 
                'IDEmpresa' => $dados['IDUsuario']
			);
			
			$where = array('IDPlataforma' => $dados['IDPlataforma']);
			$wpdb->update('wp_sigsa_BASPlataforma',$data,$where);		
		}catch(\Throwable $th){
			$result = 0;
			Log::registraLogSystem(Throwable::getMessage());				
		}finally{
			return $result;
		}
    }

    static function criarPerfilPlataforma($dados){
        global $wpdb;
		try{
			$data = array(
                'IDPlataforma' => $dados['IDUsuario'], 
                'NMPlataforma' => $dados['IDUsuario'], 
                'DSPlataforma' => $dados['IDUsuario'],  
                'DSVersaoPlataforma' => $dados['IDUsuario'], 
                'DSReleasePlataforma' => $dados['IDUsuario'], 
                'CDTipoOperacaoPlataforma' => $dados['IDUsuario'],  
                'CDTipoLicencaPlataforma' => $dados['IDUsuario'], 
                'IMLogoPlataforma' => $dados['IDUsuario'],  
                'STPlataforma' => $dados['IDUsuario'], 
                'IDEmpresa' => $dados['IDUsuario']
			);
			$result = $wpdb->insert( 'wp_sigsa_BASPlataforma' , $data); 		
		}catch(\Throwable $th){
			$result = 0;
			Log::registraLogSystem(Throwable::getMessage());				
		}finally{
			return $result;
		}
    }

    static  function deletarPerfilPlataforma(){
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

    static function   editarPerfilPlataforma($dados){
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

	static function listarPerfilPlataforma($idPlataforma){
		global $wpdb;
		
		$SQL = "SELECT 
					*
				 
				FROM acsperfilacessoplataforma where acsperfilacessoplataforma = ".$idPlataforma;
		
		$mysqli = mysqli_connect("179.188.16.80", "sigsa1", "sigsa2021", "sigsa1");
		 
		$result = mysqli_query($mysqli,$SQL);
		$plataformaList = mysqli_fetch_assoc($result);
		mysqli_close($mysqli);
		return $plataformaList;
    }

	/** GET LISTA REGISTROS ATRAVES DE FILTRO
	 * 
	 * Levanta a lista de plataformas conforme um filtro estabelecido e retorna em objeto JSON
	 * 
	 * @type	function
	 * @date	25/03/2022
	 * @since	1.0.0
	 *
	 * @param	VAR|ARRAY|OBJ
	 * @return	HTML
	 */
	public static function getListaRegistros($filtro = null){
		global $wpdb;
		$BASPlataforma = $wpdb->prefix.sigsaClass::get_prefix().'basplataforma';
		$DEFContratoProjetoPlataforma = $wpdb->prefix.sigsaClass::get_prefix().'defcontratoprojetoplataforma';
		try{
			$inner = '';
			if( isset($filtro['projetoid']) ){$filtro['IDProjeto'] = $filtro['projetoid'];}
			if( isset($filtro['s']) ) $_REQUEST['s'] = $filtro['s'];

			if(isset($filtro['IDEmpresa']))
				$IDEmpresa = $filtro['IDEmpresa'];
			else
				$IDEmpresa = SELF::getIDEmpresa();
			
			if($IDEmpresa)
				$where = ' WHERE plataforma.st_delete =0 AND plataforma.IDEmpresa='.$IDEmpresa;
			else
				$where = ' WHERE plataforma.st_delete =0';
			
			if(isset($filtro['IDProjeto'])){
				$where .= ' AND ContratoProjetoPlataforma.IDProjeto='.$filtro['IDProjeto'];
				$inner .= ' INNER JOIN '.$DEFContratoProjetoPlataforma.' AS ContratoProjetoPlataforma 
				ON(ContratoProjetoPlataforma.IDPlataforma = plataforma.IDPlataforma )';
			}
			
			if (isset($_REQUEST['p']))$filtro['pagina'] = $_REQUEST['p']; else $filtro['pagina']=1;
			if (isset($_REQUEST['q_registros'])) $filtro['q_registros'] = $_REQUEST['q_registros']; else $filtro['q_registros'] = 50; 
			$filtro['pagina'] = $filtro['pagina']-1;
			$limit = ' LIMIT '.($filtro['pagina']*$filtro['q_registros']).','.$filtro['q_registros'];
			
			if( isset($_REQUEST['s']) && $_REQUEST['s'] !='' ){
				$where .= ' AND  plataforma.NMPlataforma like "%'.$_REQUEST['s'].'%" ';
			}
			$campos = 'plataforma.*';
			if( isset($filtro['campos'])){
				$campos = '';
				foreach($filtro['campos'] as $campo){
					$campos .= ',plataforma.'.$campo;
				}
				$campos = substr($campos, 1);
			}
			$SQL = <<<SQL
				SELECT $campos
				FROM $BASPlataforma AS plataforma
				$inner  
				$where
				ORDER BY plataforma.NMPlataforma ASC $limit;
			SQL;
			$registros = $wpdb->get_results($SQL);
			// variavel html bloqueia impressões na tela. 
			$html = true;
			if( isset($filtro['output']) ){
				if( $filtro['output'] == 'JSON'){
					$registros = json_encode($registros);
					$html = false;
				}
			}
			if( isset($_REQUEST['s']) && $html ){
				$n_registros = count($registros);
				if($n_registros == 0 )$str = 'nenhum registro';
				else if($n_registros == 1 )$str = '1 registro';
					else $str = $n_registross.' registros';
				echo '<p>Sua pesquisa por: "'.$_REQUEST['s'].'" encontrou '.$str.'.</p>';
			}
		}catch(\Throwable $th){
			$registros = false;
			Log::registraLogSystem($th);
		}finally{
			return $registros;
		}
	}

	/** SALVAR VINCULO DA PLATAFORMA COM O PROJETO
	 * 
	 * Recebe os parametros do cadastro e faz o salvamento DB
	 * tem a função de salvar ou criar um novo vinculo. 
	 * @type	function
	 * @date	18/03/2022
	 * @since	1.0.0
	 * 
	 * @param	ARRAY | dados
	 * @return	INT plataforma nome
	 */
    public static function setVinculoContratoProjetoPlataforma($dados){
		$retorno['status'] = false;
		$retorno['erro'] = 'algo deu errado com o vinculo da plataforma com o projeto';
		
		global $wpdb;
		$DEFContratoProjetoPlataforma = $wpdb->prefix.sigsaClass::get_prefix().'defcontratoprojetoplataforma';
		try{
			$IDEmpresa = SELF::getIDEmpresa();
			if( !isset($IDEmpresa) ){$retorno['erro']='Empresa não encotrada';return json_encode($retorno);}
			$IDContrato = $dados['contrato_id'];
			$IDProjeto = $dados['projeto_id'];
			$IDPlataforma = $dados['plataforma_id'];
			
			if($dados['acao']=='vincular'){
				$SQL = <<<SQL
				SELECT *
				FROM $DEFContratoProjetoPlataforma
				WHERE IDProjeto=$IDProjeto AND IDPlataforma=$IDPlataforma
				SQL;
				$existeVinculo = $wpdb->get_results($SQL);
				if($existeVinculo){
					$retorno['status'] = false;
					$retorno['erro'] = 'Plataforma já inserida no projeto';
					return json_encode($retorno);
				}else{
					$data = array(
						'IDContrato'		=> null,
						'IDProjeto'			=> $IDProjeto,
						'IDPlataforma'	=> $IDPlataforma
					);
					$wpdb->insert( $DEFContratoProjetoPlataforma , $data);
					$retorno['status'] = true;
				}
			}
			if($dados['acao']=='desvincular'){
				$SQL = <<<SQL
				SELECT *
				FROM $DEFContratoProjetoPlataforma
				WHERE IDProjeto=$IDProjeto AND IDPlataforma=$IDPlataforma
				SQL;
				$existeVinculo = $wpdb->get_results($SQL);
				if(!$existeVinculo){
					$retorno['status'] = false;
					$retorno['erro'] = 'Plataforma não existe no projeto';
					return json_encode($retorno);
				}else{
					$data = array(
						'IDProjeto'			=> $IDProjeto,
						'IDPlataforma'	=> $IDPlataforma
					);
					$wpdb->delete( $DEFContratoProjetoPlataforma , $data);
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

