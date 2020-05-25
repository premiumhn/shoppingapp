<?php

$server="mysql:dbname=".DB.";host=".SERVER;

try{

    $pdo = new PDO($server, USER, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8"));

    //echo "<script>alert('Conectado...')</script>"; 

}catch(PDOException $e){

    //echo "<script>alert('Error...')</script>";

}

?>