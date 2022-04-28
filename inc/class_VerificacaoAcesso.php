<?php
/**
 * CLASS VerificacaoAcesso
 * 
 * manipula as funcoes relacionadas a aos acessos e perfir do usuário que  
 * 
 * @type	class
 * @date	19/11/2021
 * @since	1.0.0
 */

include_once('class_Log.php');

class VerificacaoAcesso
{
    private static $instance;
    
    static $permissoes = array(1,2,3,4);

	/** get_instance
     * 
     * @type	function
     * @date	19/11/21
     * @since	1.0.0
     * 
     * @param	N/A
     * @return	(Object) VerificacaoAcesso.
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
	 * @date	19/11/21
	 * @since	1.0.0
	 * @param	N/A
	 * @return	N/A
	 */
	private function __construct() {
        session_start();
    }//function __construct()	

    public static function printa($s){
        echo '<pre>';
        print_r($s);
        echo '</pre>';
    }

    /** SET SESSION PERMISSOES
     * 
     * Grava em sessao as permissôes do usuário logado 
     * 
     * @type	function
	 * @date	19/11/21
	 * @since	1.0.0
     * @param	N/A
	 * @return	N/A
     */
    public static function setSessionPermissoes(){
        // obter do banco as permissoes do current user
        $perfil = self::getPerfil();
        //self::printa($perfil);
        $_SESSION["permissoes"] = $perfil->permissoes;
        $_SESSION["IDEmpresa"] = $perfil->IDEmpresa;
        //echo 'Oque sendo gravado em _SESSION<pre>';
        //print_r($_SESSION);
        //echo '</pre>';
        
    }

    /** GET PERMISSOES
	 * 
	 * Obtem e retorna um array contento a lista de permissões que o usuário possui
     * 
	 * @type	function
	 * @date	19/11/21
	 * @since	1.0.0
	 * 
	 * @param	IDUser|NULL
	 * @return	OBJETIC
	 */
    public static function getPermissoes($iduser = null){
        /**esta funcao tem que ler o usuario e obter todas permissoes
         * a qual ele tem acesso.
         * Essas regras serão definidas posteriormente. Por enquanto daremos as permissões
         * genéricas de acesso
         */
        return (object) array('permissoes'=> self::$permissoes );
    }

    /** USER CAN
     * 
     * Verifica se o usuário possui a permissão solicitada
     * 
     * @type	function
	 * @date	19/11/21
	 * @since	1.0.0
     * 
     * @param	ID(permissao), ID(usuario)
	 * @return	BOOLEAN
     */
    public static function userCan($permissao, $IDuser = null){
        /** ao inves de eu perguntar ao banco
         * vou perguntar a sessao
         */
        $permissoes_do_usuario = array();
        if( isset($_SESSION['permissoes'] ) )
            $permissoes_do_usuario = $_SESSION['permissoes'];
        if (in_array($permissao, $permissoes_do_usuario)) { 
            return true;
        }
        else{
            return false;
        }
    }

    /** GET PERFIL
     * 
     * Obtem as permissoes do perfil atual ou passado via parametro
     * 
     * @type	function
	 * @date	08/12/21
	 * @since	1.0.0
     * 
     * @param	ID(usuario)
	 * @return	ARRAY
     */
    public static function getPerfil($IDuser = null){
        global $wpdb;
        $perfil = array();
		//$table_PAREmpresa = $wpdb->prefix.sigsaClass::get_prefix().'PAREmpresa';
        $table_acsperfilacessosigsa = $wpdb->prefix.sigsaClass::get_prefix().'acsperfilacessosigsa';
        $table_acsuserperfilsigsa = $wpdb->prefix.sigsaClass::get_prefix().'acsuserperfilsigsa';
        $table_acsperfilpermissaosigsa = $wpdb->prefix.sigsaClass::get_prefix().'acsperfilpermissaosigsa';
        if(isset($IDuser)){
            $SQL = 'SELECT perfil.*,userPerfil.IDEmpresa FROM '.$table_acsuserperfilsigsa.' as userPerfil 
            INNER JOIN '.$table_acsperfilacessosigsa.' as perfil on(perfil.IDPerfil = userPerfil.IDPerfil)
            WHERE userPerfil.IDUser = '.$IDuser.';';
        }else{
            $SQL = 'SELECT perfil.*,userPerfil.IDEmpresa FROM '.$table_acsuserperfilsigsa.' as userPerfil 
            INNER JOIN '.$table_acsperfilacessosigsa.' as perfil on(perfil.IDPerfil = userPerfil.IDPerfil)
            WHERE userPerfil.IDUser = '.get_current_user_id().';';
        }
        //$SQL = 'SELECT * FROM wp_sigsa_acsuserperfilsigsa;';
        $perfil = $wpdb->get_row($SQL);
        //$perfil = $wpdb->get_results('SELECT perfil.*,userPerfil.IDEmpresa FROM wp_sigsa_acsuserperfilsigsa');
        if( isset($perfil) ){ 
            $SQL = 'SELECT PerfilPermissao.* 
                FROM '.$table_acsperfilpermissaosigsa.' as PerfilPermissao 
                where PerfilPermissao.IDPerfil ='.$perfil->IDPerfil.' order by PerfilPermissao.IDPermissao';
            $permissoes = $wpdb->get_results( $SQL );
            $a = array();
            foreach($permissoes as $permissao){
                array_push($a,$permissao->IDPermissao);
            }
            $perfil->permissoes = $a;
        }
        return $perfil;
    }
    
    /** GET ALL PERFIS
     * 
     * Retorna um array com todos os perfis SIGSA do sistema
     * 
     * @type	function
	 * @update	09/12/21
	 * @since	1.0.0
     * 
     * @param	null
	 * @return	ARRAY
     */
    public static function getAllPerfis(){
        global $wpdb;
        $perfis = null;
        $table_acsperfilacessosigsa = $wpdb->prefix.sigsaClass::get_prefix().'acsperfilacessosigsa';
        //$table_ACSPermissaoacessoSIGSA = $wpdb->prefix.sigsaClass::get_prefix().'ACSPermissaoacessoSIGSA';
        $table_acsperfilpermissaosigsa = $wpdb->prefix.sigsaClass::get_prefix().'acsperfilpermissaosigsa';

        $SQL = 'SELECT * FROM '.$table_acsperfilacessosigsa.' order by NUNivel,DSPerfil ASC ;';
        //return $SQL;
        $perfis = $wpdb->get_results( $SQL, ARRAY_A  );

        if($perfis){
			for($i = 0; $i < count($perfis) ; $i++){
                $SQL = 'SELECT PerfilPermissao.* 
                    FROM '.$table_acsperfilpermissaosigsa.' as PerfilPermissao 
                    where PerfilPermissao.IDPerfil ='.$perfis[$i]['IDPerfil'];
                $permissoes = $wpdb->get_results( $SQL );
                
                $a = array();
                foreach($permissoes as $permissao){
                    array_push($a,$permissao->IDPermissao);
                }
                $perfis[$i]['permissoes'] = $a;
            }
        }
        return $perfis;
    }

    /** SET PERFIL
     * 
     * Função que altera dados ou permissões dos perfis
     * 
     * @type	function
	 * @date	09/12/21
	 * @since	1.0.0
     * 
     * @param	Array dados
	 * @return	ARRAY
     */
    public static function setPerfil($dados){
        global $wpdb;
        $retorno['status'] =false;
        $retorno['erro'] = 'Não foi possivel salvar o Perfil';
        $table_acsperfilpermissaosigsa = $wpdb->prefix.sigsaClass::get_prefix().'acsperfilpermissaosigsa';
        $table_acsperfilacessosigsa = $wpdb->prefix.sigsaClass::get_prefix().'acsperfilacessosigsa';
        
        // processo de alterações de permissôes.
        $perfilPermissoesAtuais = array();
        $SQL = 'SELECT PerfilPermissao.* 
            FROM '.$table_acsperfilpermissaosigsa.' as PerfilPermissao 
            where PerfilPermissao.IDPerfil ='.$dados['IDPerfil'];
        $permissoes = $wpdb->get_results( $SQL );
        foreach($permissoes as $permissao){
            array_push($perfilPermissoesAtuais,intval($permissao->IDPermissao));
        }
        $dados['arrPermissoes'] = array_map('intval',$dados['arrPermissoes']);
        $permissoesParaAcrescentar = array_diff($dados['arrPermissoes'],$perfilPermissoesAtuais);
        sort($permissoesParaAcrescentar);
        $permissoesParaRetirar = array_Values(array_diff($perfilPermissoesAtuais,$dados['arrPermissoes']));
        sort($permissoesParaRetirar);
        
        if( count($permissoesParaAcrescentar) > 0 ){
            for($i=0; $i< count($permissoesParaAcrescentar); $i++){
                $data = array(
                    'IDPerfilpermissao'			=> null,
                    'IDPerfil'					=> $dados['IDPerfil'],
                    'IDPermissao'		        => $permissoesParaAcrescentar[$i]
                );
                $wpdb->insert($table_acsperfilpermissaosigsa,$data);
            }
		}

        if( count($permissoesParaRetirar) > 0 ){
            $idListString = implode(",",$permissoesParaRetirar);
            $SQL = 'DELETE FROM '.$table_acsperfilpermissaosigsa.' WHERE
            IDPerfil = '.$dados['IDPerfil'].' 
            AND IDPermissao IN ('.$idListString.');';
            $wpdb->query($SQL);
			$retorno['erro'] = $SQL;
		}

        //alteração de nome do Perfil
        if( $dados['DSPerfilAlterado'] ){
			$data['DSPerfil'] = $dados['DSPerfilAlterado'];
			$where = array('IDPerfil' => $dados['IDPerfil']);
			$wpdb->update($table_acsperfilacessosigsa,$data,$where);
            Log::registraLog($table_acsperfilacessosigsa,'UPDATE',get_current_user_id(),$data,0);
        }

        $retorno['status'] = true;
        return json_encode($retorno);
    }
    
    /** SET NOVO PERFIL
     * 
     * Adiciona um novo perfil SIGSA
     * 
     * @type	function
	 * @date	09/12/21
	 * @since	1.0.0
     * 
     * @param	Array dados
	 * @return	ARRAY
     */
    public static function setNewPerfil($dados){
        global $wpdb;
        $retorno['status'] =false;
        $retorno['erro'] = 'Não foi possivel Adicionar o Perfil';
        $table_acsperfilacessosigsa = $wpdb->prefix.sigsaClass::get_prefix().'acsperfilacessosigsa';
        
        $data = array(
			'IDPerfil'  => null,
			'DSPerfil'  => $dados['DSPerfil'],
			'NUNivel'   => $dados['NUNivel'],
		);
        
        $wpdb->insert($table_acsperfilacessosigsa,$data);
        $my_id = $wpdb->insert_id;
        if($my_id){
            $retorno['status'] = true;
            $retorno['IDPerfil'] = $my_id;
            $data['IDPerfil'] =  $my_id;
            Log::registraLog($table_acsperfilacessosigsa,'INSERT',get_current_user_id(),$data,0);
        }
        return json_encode($retorno);
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
	public static function getIDEmpresa(){
		session_start();
		$empresa = ( isset($_SESSION['IDEmpresa']) )? $_SESSION['IDEmpresa'] : NULL; 
		return $empresa;
	}

}