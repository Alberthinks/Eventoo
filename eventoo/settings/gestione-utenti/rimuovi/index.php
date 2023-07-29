<?php
session_start();
include '../../../config.php';
include '../../../default.php';
$db = 'eventoo_users';
$conn = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());

$del_id = $_GET['id'];

$query = mysqli_query($conn,"SELECT * FROM users WHERE id = $del_id") or die (mysqli_error($conn));
$fetch = mysqli_fetch_array($query) or die (mysqli_error());
$filenameDelete = cripta($fetch['logo'], "decrypt");

$nome = $_SESSION['session_nome_eventoo']." ".$_SESSION['session_cognome_eventoo'];

if ($_SESSION['session_permessi_eventoo'] == "maintenance" || $_SESSION['session_permessi_eventoo'] == "administration") {
    if ($del_id != 1 && $del_id != 2) {
        if (mysqli_query($conn,"DELETE FROM users WHERE id = '$del_id'")or die(mysql_error($conn))) {
            unlink("../nuovo/$filenameDelete");
            echo "<script>location.replace(\"../\");</script>";
        }
    } else {
        echo "<script>\nalert(\"Non puoi eliminare l'admin o il tecnico di manutenzione!\");\nhistory.back();\n</script>";
    }
} else {
    echo "<script>\nalert(\"Utente non autorizzato all'eliminazione degli account!\");\nhistory.back();\n</script>";
}
?>
