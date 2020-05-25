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

            $pk_detalle_pedido = (isset($_POST['pk_detallePedido'])) ? $_POST['pk_detallePedido'] : "";
            $fecha_hora_completado = date('Y-m-d H:i:s');
        
                $actualizar_detallePedido = $pdo->prepare("UPDATE DetallePedidos
                                                           SET Estado = 1,
                                                           FechaHoraCompletado = :FechaHoraCompletado
                                                           WHERE PK_DetallePedido = :PK_DetallePedido");
                $actualizar_detallePedido->bindParam(':FechaHoraCompletado', $fecha_hora_completado);
                $actualizar_detallePedido->bindParam(':PK_DetallePedido', $pk_detalle_pedido);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $actualizar_detallePedido->execute();
                header('location: ../Pedidos-Tienda?msj=pedido_confirmado');
            

        break;
        
    }


}
?>