<?php
/**
*  	HANDLER
*
* 	RESNPONSALVEL PELA CONXAO AJAX DAS CLASSES
*
*  @type	function
*  @date	09/04/21
*  @since	1.0.0
*
*  @param	N/A
*  @return	(HTML)
*/
function Handler_SA(){
	
	//recuperando o nome da classe requerida
	if($_REQUEST['class'])
		$nomeClass 		= $_REQUEST['class'];	
	else
		$nomeClass 		= 'SolicitacaoAcesso_basic';
	$nomeArquivo 	= 'class_'. $nomeClass .'.php';
	
	// indica necessidade do uso da classe de funções customizadas
	require_once plugin_dir_path( __FILE__ ).$nomeArquivo;
	
	echo call_user_func_array( array($nomeClass, $_REQUEST['funcao']) ,array($_REQUEST) );
	
	//comando responsável por ocultar o "0"(zero) padrão retornado pelo AJAX do Wordpress
	//echo 'retorno aqui '.plugin_dir_path( __FILE__ ).$nomeArquivo;
    die();
}

add_action('wp_ajax_nopriv_Handler_SA', 'Handler_SA');	
add_action('wp_ajax_Handler_SA', 'Handler_SA');

?>