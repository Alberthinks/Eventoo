<?php
session_start();
include '../../../config.php';
include '../../../default.php';
$db = 'users';
$conn = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());

$del_id = $_GET['id'];

$query = mysqli_query($conn,"SELECT * FROM users WHERE id = $del_id") or die (mysqli_error($conn));
$fetch = mysqli_fetch_array($query) or die (mysqli_error());
$filenameDelete = cripta($fetch['logo'], "decrypt");

$nome = $_SESSION['session_user_lele_planner_0425'];

if (isset($_SESSION['session_id_lele_planner_0425']) && $_SESSION['session_user_lele_planner_0425'] == "lele_administrator_admin") {
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
