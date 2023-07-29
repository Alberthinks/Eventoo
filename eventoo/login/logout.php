<?php
session_start();

include '../default.php';
include '../config.php';

$myconn = mysqli_connect($host,$user,$pass, 'eventoo') or die (mysqli_error());
$timestamp = cripta(date('d/m/Y H:i:s', strtotime("now")), "encrypt");
$action = cripta("Disconnessione dell'account (logout)", "encrypt");
$ip = cripta($_SERVER['REMOTE_ADDR'], "encrypt");
$uname = cripta($_SESSION['session_user_eventoo'], "encrypt");
$name = cripta($_SESSION['session_nome_eventoo'], "encrypt");
$cog = cripta($_SESSION['session_cognome_eventoo'], "encrypt");
$mysql = "INSERT INTO accesses (username,nome,cognome,ip,azione,timestamp,validity) VALUES ('$uname', '$name','$cog','$ip','$action','$timestamp','$dataValidity')";

if($rressultt = mysqli_query($myconn,$mysql) or die (mysqli_error($myconn))) {
    session_destroy();
    header('Location: index.php');
}

exit;
?>
