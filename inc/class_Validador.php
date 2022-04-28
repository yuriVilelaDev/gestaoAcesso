<?php
    /*
*  CLASS Validador
*
*  manipula as funcoes relacionadas a configurações > Clientes 
*
*  @type	class
*  @date	10/11/2021
*  @since	1.0.0
*
*/
    class Validador{

        public static function validaRequest($request){
            if(!$request){
                return "";
            }else{
                return $request;
            }
            
        }

    }