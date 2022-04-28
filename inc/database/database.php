<?php
    class DataBase{
       

        

        public static function connectar(){
            try{
                
                $db = "sigsa1";
                $user = "sigsa1";
                $password = "sigsa2021";
                $host = "179.188.16.80";
                
                $conexao = mysqli_connect($host, $user, $password, $db); 
             
            }catch(\Throwable $th){
                $conexao = null;
            }finally{
                return $conexao;
            }
            
        }

        public static function insert($conexao, $dados, $tabela){
            try{
                 $query = "";                  
                $result = mysqli_query($conexao, $query); 
             
            }catch(\Throwable $th){
                $result = null;
            }finally{
                return $result;
            }
        }
    }