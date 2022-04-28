<?php
class Log{
    static function registraLog($tabela,$operacao,$idusuario,$dados,$idEmpresa ){
        global $wpdb;
        
        $dados['tabela'] = $tabela;
        $dtz = new DateTimeZone("America/Sao_Paulo");
        $dt = new DateTime("now", $dtz);
 
        $data = array(
			'DSTipoAcaoHistorico' 	=> $operacao,
			'IDUsuarioHistorico'	=> $idusuario,
			'DSHistoricoJSON'	    => json_encode($dados),
			'IDEmpresa'             => $idEmpresa,
			'DTHistorico'		    =>  $dt->format("Y-m-d") . "T" . $dt->format("H:i:s")
		);
        // Obtendo o nome da tabela
        $GERHistorico = $wpdb->prefix.sigsaClass::get_prefix().'GERHistorico';
        $wpdb->insert( $GERHistorico , $data);

    }
    static function registraLogSystem($erro){
        global $wpdb;
        
        if(isset($tabela))$dados['tabela'] = $tabela;
        $dtz = new DateTimeZone("America/Sao_Paulo");
        $dt = new DateTime("now", $dtz);
 
        $data = array(
			'DSErroJSON'	    => json_encode($erro),
			'DTErro'		    =>  $dt->format("Y-m-d") . "T" . $dt->format("H:i:s")
		);
        // Obtendo o nome da tabela
        $GERLogSistema = 'wp_sigsa_logSystem';
        $wpdb->insert( $GERLogSistema , $data);

    }
}
