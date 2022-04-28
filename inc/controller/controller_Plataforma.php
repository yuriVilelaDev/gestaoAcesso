<?php
 require_once '../../inc/class_Plataforma.php';
 require_once '../../inc/database/database.php';

// echo '<pre>';
// var_dump($_POST);
    print_r(  Database::connectar());

//  echo Plataforma::inserirPlataformas($_POST);