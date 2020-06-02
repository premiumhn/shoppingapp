<?php

$select_id = $pdo->prepare('SELECT * FROM Configuracion');
$select_id->execute();
$id = $select_id->fetchAll(PDO::FETCH_ASSOC);

define('ProPayPal', 0);
if(ProPayPal){
	define("PayPalClientId", "*********************");
	define("PayPalSecret", "*********************");
	define("PayPalBaseUrl", "https://api.paypal.com/v1/");
	define("PayPalENV", "production");
} else {
	define("PayPalClientId", $id[0]['IDClientePaypal']);
	define("PayPalSecret", "EJnLPOqEbfYPP2Cblc-9XuFkcY4gi2qNFssJh6lvcwjm9-FbT3Mh4wiWzvD7JZJdeZDDPSEciVxP4iaY");
	define("PayPalBaseUrl", "https://api.sandbox.paypal.com/v1/");
	define("PayPalENV", "sandbox");
}
?>