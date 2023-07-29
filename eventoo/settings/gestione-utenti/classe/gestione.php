<?php
session_start();
include "../../../config.php";
include "../../../default.php";

$function = $_GET['function'];
$id = $_GET['id'];

$db = 'eventoo_users';
$conn = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());
?>
<?php
if ($_SESSION['session_permessi_eventoo'] == "maintenance" || $_SESSION['session_permessi_eventoo'] == "administration") {
    switch ($function) {
        case "delete":
            $sql = "DELETE FROM classi WHERE id = '$id'";
            break;
        default:
            echo "<script type=\"text/javascript\">history.back();</script>";
    }
    if (mysqli_query($conn,$sql)or die(mysqli_error($conn))) {
        echo "<script type=\"text/javascript\">location.replace(\"../classe\");</script>";
    }
} else {
    echo "<script type=\"text/javascript\">location.replace(\"../classe\");</script>";
}
?>