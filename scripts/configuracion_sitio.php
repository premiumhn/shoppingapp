<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include ("../global/config.php");
include ("../global/conexion.php");
include ("../global/const.php");


    
        $id_cliente_paypal = (isset($_REQUEST['input_idClientePaypal'])) ? $_REQUEST['input_idClientePaypal'] : "";
       
        // boton
        $action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : "";


        switch($action){
            case "editar":
            
                $editar_configuracion = $pdo->prepare("UPDATE Configuracion
                                                       SET IDClientePaypal = :IDClientePaypal");

                $editar_configuracion->bindParam(':IDClientePaypal', $id_cliente_paypal);
                
                
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $editar_configuracion->execute();
               
                header('location: ../Configuracion-Sitio?msj=editado');

            break;
        }
    
       
 
?>