<?php
define('ProPayPal', 0);
if(ProPayPal){
	define("PayPalClientId", "*********************");
	define("PayPalSecret", "*********************");
	define("PayPalBaseUrl", "https://api.paypal.com/v1/");
	define("PayPalENV", "production");
} else {
	define("PayPalClientId", "AfD5UDBgvoCWjA2v1oEmxVJgBUqDo_bSB6ywQcs71MG6NTe64DTomwuf9Obw35BgjsmPsZQM_hUPMPk_");
	define("PayPalSecret", "EJnLPOqEbfYPP2Cblc-9XuFkcY4gi2qNFssJh6lvcwjm9-FbT3Mh4wiWzvD7JZJdeZDDPSEciVxP4iaY");
	define("PayPalBaseUrl", "https://api.sandbox.paypal.com/v1/");
	define("PayPalENV", "sandbox");
}
?>