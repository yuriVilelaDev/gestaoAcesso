<?php
/*
Plugin Name: SIGSA Empresa Actcon
Plugin URI: www.actcon.com.br
Description: Plugin para gerenciamento de solicitações de acesso de usuários ao portal do cliente, da empresa ACTCON
Version: 1.1
Author: Yuri de Souza Vilela
Author URI: yuri.svilela@outlook.com.br
License: GPLv2
*/
/*
 *      Copyright 2021 Yuri vilela
 * 		<moaloss@gmail.com>
 *
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 3 of the License, or
 *      (at your option) any later version.
 *
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */
?>
<?php

	define( 'NOMEPLUGIN' , 'SIGSA' );
	define( 'SIGSA_MINIMUM_WP_VERSION', '3.2' );
	define( 'SIGSA_PATH', plugin_dir_path(__FILE__) );
	define( 'SIGSA_URL', plugins_url( '/', __FILE__ ));
	
	class sigsaClass{
		//static $prefix = 'actcon_sa_';
		//static $prefix = 'SIGSA_';
		static $prefix = 'sigsa_';
		
		static function get_prefix(){
			return self::$prefix;
		}
		
		static $SIGSA_tabelas_criadas;
		
		private static $instance;
		
		static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				$class_name = __CLASS__;
				self::$instance = new $class_name;
			}
			return self::$instance;
		}
		
		
		function __construct(){
			require_once(SIGSA_PATH .'inc/handler.php');
			require_once(SIGSA_PATH .'inc/shortecodes/shortecodes.php');
			require_once(SIGSA_PATH .'inc/class_VerificacaoAcesso.php');

			add_action( 'wp_enqueue_scripts', array($this,'sigsaClass_wp_scripts' ));

			if(is_admin()){
				add_action( 'admin_enqueue_scripts', array($this,'sigsaClass_admin_scripts' ));
				add_action( 'admin_menu', array( $this, 'sigsaClass_construct_menu' ) );
				add_action( 'admin_head', array( $this,'hide_update_notice_to_all_but_admin_users'), 1 );
			};
		}
		public function sigsaClass_wp_scripts(){
			wp_register_style('bootstrap' , SIGSA_URL . 'lib/bootstrap/css/bootstrap.css');
			wp_register_script('bootstrapjs' , SIGSA_URL . 'lib/bootstrap/js/bootstrap.js');
			wp_register_style('font-awesome', SIGSA_URL . 'lib/font-awesome-4.4.0/css/font-awesome.min.css');
			wp_register_style(self::get_prefix().'_style', SIGSA_URL . 'style.css?op='.rand(1, 1000),array('bootstrap','font-awesome'));
			wp_register_script(self::get_prefix().'_script', SIGSA_URL . 'js/scripts.js?op='.rand(1, 1000),array('jquery','bootstrapjs'));
			wp_register_script(self::get_prefix().'_Shortecode', SIGSA_URL . 'js/Shortecode.js?op='.rand(1, 1000));
		}
		public function sigsaClass_admin_scripts(){
			//datapicker
			wp_register_script('jquery-ui-js' , SIGSA_URL . 'lib/jquery-ui/jquery-ui.js');
			wp_register_style('jquery-ui-css', SIGSA_URL . 'lib/jquery-ui/jquery-ui.css');
			
			wp_register_style('font-awesome', SIGSA_URL . 'lib/font-awesome-4.4.0/css/font-awesome.min.css');
			wp_register_style('bootstrap' , SIGSA_URL . 'lib/bootstrap/css/bootstrap.css');
			wp_register_script('bootstrapjs' , SIGSA_URL . 'lib/bootstrap/js/bootstrap.js');

			wp_register_style(self::get_prefix().'_style', SIGSA_URL . 'style.css?op='.rand(1, 1000),array('bootstrap','font-awesome'));

			wp_register_script(self::get_prefix().'_script', SIGSA_URL . 'js/scripts.js?op='.rand(1, 1000),array('jquery','bootstrapjs','jquery-ui-js'));
			wp_register_script(self::get_prefix().'_DadosAdicionais', SIGSA_URL . 'js/DadosAdicionais.js?op='.rand(1, 1000),array(self::get_prefix().'_script'));
			wp_register_script(self::get_prefix().'_Empresas', SIGSA_URL . 'js/Empresas.js?op='.rand(1, 1000),array(self::get_prefix().'_script'));
			wp_register_script(self::get_prefix().'_Clientes', SIGSA_URL . 'js/Clientes.js?op='.rand(1, 1000),array(self::get_prefix().'_script'));
			wp_register_script(self::get_prefix().'_Usuario', SIGSA_URL . 'js/Usuario.js?op='.rand(1, 1000),array(self::get_prefix().'_script'));
			wp_register_script(self::get_prefix().'_Plataforma', SIGSA_URL . 'js/Plataforma.js?op='.rand(1, 1000),array(self::get_prefix().'_script'));
			wp_register_script(self::get_prefix().'_Contratos', SIGSA_URL . 'js/Contratos.js?op='.rand(1, 1000),array(self::get_prefix().'_script'));
			wp_register_script(self::get_prefix().'_Projetos', SIGSA_URL . 'js/Projetos.js?op='.rand(1, 1000),array(self::get_prefix().'_script'));
			wp_register_script(self::get_prefix().'_ComposicaoFuncional', SIGSA_URL . 'js/ComposicaoFuncional.js?op='.rand(1, 1000),array(self::get_prefix().'_script'));
			wp_register_script(self::get_prefix().'_Locais', SIGSA_URL . 'js/Locais.js?op='.rand(1, 1000),array(self::get_prefix().'_script'));
			wp_register_script(self::get_prefix().'_Shortecode', SIGSA_URL . 'js/Shortecode.js?op='.rand(1, 1000));
			wp_register_script(self::get_prefix().'_SolicitacaoAcesso', SIGSA_URL . 'js/SolicitacaoAcesso.js?op='.rand(1, 1000));
		}

		public function hide_update_notice_to_all_but_admin_users()
		{
			//if (!current_user_can('update_core')) {
				remove_action( 'admin_notices', 'update_nag', 3 );
			//}
		}
		
		public function sigsaClass_construct_menu(){
			VerificacaoAcesso::get_instance();
			//$vari =  "user_id= ".wp_get_current_user()." *";
			//VerificacaoAcesso::setSessionPermissoes();
			
			add_object_page(
				'sigsaClass', //titulo_name
				'SIGSA',//menu_name
				'read',//privilegio de acesso
				basename(SIGSA_URL).'/view/page-sigsa.php', //link para pagina a ser exibida (funciona como id do Menu Pai)
				'',//função a ser utilizada pelo menu pai (vazia pois o menu pai aponta p/ um arquivo)
				'dashicons-welcome-view-site'//icone
			);
			// sub menu Usuários
			//if(VerificacaoAcesso::userCan(10))
			add_submenu_page(
				basename(SIGSA_URL).'/view/page-sigsa.php',//Menu pai
				'Usuários',//titulo_name
				'Usuários', //submenu_name
				'read',//privilegio de acesso
				basename(SIGSA_URL).'/view/page-usuarios.php' //link para pagina a ser exibida
			);
			//if(get_current_user_id()==1 ||VerificacaoAcesso::userCan(5))
			add_submenu_page(
				basename(SIGSA_URL).'/view/page-sigsa.php',//Menu pai
				'Plataformas',//titulo_name
				'Plataformas', //submenu_name
				'read',//privilegio de acesso
				basename(SIGSA_URL).'/view/page-plataformas.php' //link para pagina a ser exibida
			);
			add_submenu_page(
				basename(SIGSA_URL).'/view/page-sigsa.php',//Menu pai
				'Locais',//titulo_name
				'Locais', //submenu_name
				'read',//privilegio de acesso
				basename(SIGSA_URL).'/view/page-locais.php' //link para pagina a ser exibida
			);
			add_submenu_page(
				basename(SIGSA_URL).'/view/page-sigsa.php',//Menu pai
				'Composição funcional',//titulo_name
				'Composição funcional', //submenu_name
				'read',//privilegio de acesso
				basename(SIGSA_URL).'/view/page-composicao-funcional.php' //link para pagina a ser exibida
			);
			add_submenu_page(
				basename(SIGSA_URL).'/view/page-sigsa.php',//Menu pai
				'Serviços',//titulo_name
				'Serviços', //submenu_name
				'read',//privilegio de acesso
				basename(SIGSA_URL).'/view/page-servicos.php' //link para pagina a ser exibida
			);
			//if( VerificacaoAcesso::userCan(30) )
			add_submenu_page(
				basename(SIGSA_URL).'/view/page-sigsa.php',//Menu pai
				'Projetos',//titulo_name
				'Projetos', //submenu_name
				'read',//privilegio de acesso
				basename(SIGSA_URL).'/view/page-projetos.php' //link para pagina a ser exibida
			);
			add_submenu_page(
				basename(SIGSA_URL).'/view/page-sigsa.php',//Menu pai
				'Contratos',//titulo_name
				'Contratos', //submenu_name
				'read',//privilegio de acesso
				basename(SIGSA_URL).'/view/page-contratos.php' //link para pagina a ser exibida
			);
			// sub menu clientes
			add_submenu_page(
				basename(SIGSA_URL).'/view/page-sigsa.php',//Menu pai
				'Clientes',//titulo_name
				'Clientes', //submenu_name
				'read',//privilegio de acesso
				basename(SIGSA_URL).'/view/page-clientes.php' //link para pagina a ser exibida
			);
			//if(get_current_user_id()==1 ||VerificacaoAcesso::userCan(5))
			add_submenu_page(
				basename(SIGSA_URL).'/view/page-sigsa.php',//Menu pai
				'Empresas',//titulo_name
				'Empresas', //submenu_name
				'read',//privilegio de acesso
				basename(SIGSA_URL).'/view/page-empresas.php' //link para pagina a ser exibida
			);
			//if(get_current_user_id()==1 ||VerificacaoAcesso::userCan(5))
			add_submenu_page(
				basename(SIGSA_URL).'/view/page-sigsa.php',//Menu pai
				'Perfil de acesso',//titulo_name
				'Perfil de acesso', //submenu_name
				'read',//privilegio de acesso
				basename(SIGSA_URL).'/view/page-perfil-acesso.php' //link para pagina a ser exibida
			);
			//if(get_current_user_id()==1 ||VerificacaoAcesso::userCan(1))
			add_submenu_page(
				basename(SIGSA_URL).'/view/page-sigsa.php',//Menu pai
				'Configurações',//titulo_name
				'Configurações', //submenu_name
				'read',//privilegio de acesso
				basename(SIGSA_URL).'/view/page-configuracoes.php' //link para pagina a ser exibida
			);
		}
		
		/**
		 * plugin_activation
		 * 
		 * @access public
		 *
		 * @return mixed Value.
		 */
		static function plugin_activation(){
            if ( version_compare( $GLOBALS['wp_version'], SIGSA_MINIMUM_WP_VERSION, '<' ) ) {
				deactivate_plugins( __FILE__ );
				wp_die( sprintf( __('sigsaClass requer uma versao mais atual do WordPress %s .', 'sigsaClass'), SIGSA_MINIMUM_WP_VERSION) );
			} else {
				require_once(SIGSA_PATH .'inc/db/class_tables.php');
                tables::setPrefix( self::get_prefix() );
				tables::construct_all_data();
			}
		}
		
		/**
		*
		*	funcao chamada no momento de desativação do plugin
		*	@static
		*/
		static function plugin_deactivation(){
			//flush_rewrite_rules( );
			require_once(SIGSA_PATH . 'inc/db/class_tables.php');
            tables::setPrefix( self::get_prefix() );
			tables::remove_all_data();
		}

		static function get_nomeAtributoLocal($tipo){
			$atributo = 'Não especificado';
			if($tipo == 1){$atributo = 'Administrativo';}
			if($tipo == 2){$atributo = 'Unidade escolar';}
			return $atributo;
		}

	}

// Initialize o objeto sigsa!
sigsaClass::get_instance();

// Registro de hooks de ativacao e desativação do plugin
register_activation_hook( __FILE__, array( 'sigsaClass', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'sigsaClass', 'plugin_deactivation' ) );

// Registro de hook acionada logo após o login do usuário
add_action( 'wp_login', 'sigsa_login_function' );
function sigsa_login_function($user_login){
	VerificacaoAcesso::setSessionPermissoes();
}

// Registro de hook acionada logo após o login do usuário para redirecionamento
add_filter( "login_redirect", "sigsa_login_redirect", 10, 3 );
function sigsa_login_redirect( $redirect_to, $request, $user )
{
	return admin_url().'admin.php?page='.basename(SIGSA_URL).'/view/page-sigsa.php';
}

?>