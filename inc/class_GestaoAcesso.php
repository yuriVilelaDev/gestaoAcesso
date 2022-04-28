<?php
    include_once('class_Log.php');
    class GestaoAcesso 
    {
        public function __construct(){
            include_once $path . '/wp-config.php';
            include_once $path . '/wp-load.php';
            include_once $path . '/wp-includes/wp-db.php';
            include_once $path . '/wp-includes/pluggable.php';
        }
        public static function validaAcesso($dados){
            $global $wpdb;
            try {
                if($email == ""){
                    $result = "informe um usuario e senha";
                }
                else{

                    $result = $wpdb->get_var("SELECT COUNT(ID) FROM wp_users WHERE user_email =" $dados['user_email'] ."AND user_pass = MD5(".$dados['user_pass']."))";
                }
            } catch (\Throwable $th) {
                $result = false;
				Log::registraLogSystem($th);
            }
            finally{
                return $result;
            }
        }

        public static function cadastraAcesso($dados){
            $global $wpdb;
            
            try {
                if(empty($dados)){
                    $result = "informe um usuario e senha";
                }
                else{
                    $dadosJson = json_encode($dados);
                    $data = array(
                        'id'		            => null, 
                        'dado_solicitacao' 	    => $dadosJson,
                        'chave_autenticacao'	=> $dados['chave_autenticacao']   
                    );   
                      
                    $wpdb->insert( 'wp_sigsa_acssolicitacaoacesso' , $data);
                }
            } catch (\Throwable $th) {
                $result = false;
				Log::registraLogSystem($th);
            }
        }

        public static function validaChaveAutenticacao($dados){
            $global $wpdb;
            try {

                $result = $wpdb->get_var(" SELECT COUNT(IDChaveAutenticacao) FROM wp_sigsa_defchaveautenticacao WHERE NUChaveAutenticacao = ".$dados['chave_autenticacao']);

            } catch (\Throwable $th) {

                $result = false;
				Log::registraLogSystem($th);
            }
            finally{
               
                return $result;
            }
        }
    }
    

    