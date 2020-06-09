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
        
        case "confirmar_pedido":

            $pk_pedido = (isset($_POST['pk_pedido'])) ? $_POST['pk_pedido'] : "";
            $fecha_hora_completado = date('Y-m-d H:i:s');
        
                $actualizar_pedido = $pdo->prepare("UPDATE Pedidos
                                                    SET Estado = 1,
                                                    FechaHoraEntrega = :FechaHoraCompletado
                                                    WHERE PK_Pedido = :PK_Pedido");
                $actualizar_pedido->bindParam(':FechaHoraCompletado', $fecha_hora_completado);
                $actualizar_pedido->bindParam(':PK_Pedido', $pk_pedido);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo $actualizar_pedido->execute();
            
        break;
        
    }


}
?>