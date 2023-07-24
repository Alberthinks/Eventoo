<?php
session_start();

include '../default.php';

$myconn = mysqli_connect('localhost','root','mysql', 'accesses') or die (mysqli_error());
$timestamp = cripta(date('d/m/Y H:i:s', strtotime("now")), "encrypt");
$action = cripta("Disconnessione dell'account (logout)", "encrypt");
$ip = cripta($_SERVER['REMOTE_ADDR'], "encrypt");
$uname = cripta($_SESSION['session_user_lele_planner_0425'], "encrypt");
$name = cripta($_SESSION['session_nome_lele_planner_0425'], "encrypt");
$cog = cripta($_SESSION['session_cognome_lele_planner_0425'], "encrypt");
$societa = cripta($nome_societa, "encrypt");
$mysql = "INSERT INTO accesses (username,nome,cognome,nome_societa,ip,azione,timestamp,validity) VALUES ('$uname', '$name','$cog','$societa','$ip','$action','$timestamp','$dataValidity')";

if($rressultt = mysqli_query($myconn,$mysql) or die (mysqli_error($myconn))) {
    session_destroy();
    header('Location: index.php');
}

exit;
?>
