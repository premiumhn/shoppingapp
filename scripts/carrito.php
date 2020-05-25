<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include ("../global/config.php");
include ("../global/conexion.php");

session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    global $form;
    
    // accion del registro
    $action = (isset($_POST['action'])) ? $_POST['action'] : "";

    switch($action){
        
        case "eliminar_carrito":

            $pk_carrito = (isset($_POST['pk_carrito'])) ? $_POST['pk_carrito'] : "";
        
                $eliminar_carrito = $pdo->prepare("DELETE FROM Carrito
                                                   WHERE PK_Carrito = :PK_Carrito");
                $eliminar_carrito->bindParam(':PK_Carrito', $pk_carrito);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $eliminar_carrito->execute();
                header('location: ../Carrito?msj=eliminado');
            

        break;
        
    }


}
?>