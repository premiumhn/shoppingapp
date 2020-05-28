<?php
session_start();
if (isset($_GET["language"])){
$_SESSION["language"]=$_GET["language"];
header ('Location:'.$_SERVER['HTTP_REFERER']);
}

