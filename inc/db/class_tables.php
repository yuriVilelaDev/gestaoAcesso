<?php
	class tables{
		static $prefix = '';

        static function setPrefix($str){
			self::$prefix = $str;
		}

		static function get_prefix(){
			return self::$prefix;
		}
		
		static function remove_all_data(){
       
        	global $wpdb;
			$tabelas = $wpdb->get_results( "SHOW TABLES LIKE '" . $wpdb->prefix . self::get_prefix() . "%'" );
			$wpdb->query("SET FOREIGN_KEY_CHECKS = 0");
			foreach($tabelas as $position1 => $tabela_obj){
				foreach($tabela_obj as $position2 => $tabela){
					// Verificando se o nome da tabela nao contém GERHistorico ou gerhistorico
					if( strpos( $tabela, "historico") === false){
						$sql = "DROP TABLE ".$tabela.";";
						$wpdb->query($sql);
					}
				}
			}
			$wpdb->query("SET FOREIGN_KEY_CHECKS = 1");
    	}
		
		
		static function construct_all_data(){
			global $wpdb;
			
			/*Nome da Tabela = wp_ticketPluss*/
			//$tableimagens = $wpdb->prefix. self::get_prefix(). '_imagens';
			//$tablehashtag = $wpdb->prefix. self::get_prefix(). '_hashtags';
			
            $sql = file_get_contents( SIGSA_PATH .'inc/db/base.sql' );
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
		}
		
	}


?>