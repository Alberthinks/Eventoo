<?php
session_start();
include "../default.php";

$str_data = $_GET['data'];
$data = date("d/m/Y", $str_data);

$id = $_GET['id'];
$organizzatore = $_GET['organizzatore'];

$username = $_SESSION['session_user_eventoo'];
$nome = $_SESSION['session_nome_eventoo'];
$cognome = $_SESSION['session_cognome_eventoo'];
$ao = $_SESSION['session_ao_eventoo'];
$nome_societa = $_SESSION['session_permessi_eventoo'];
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Icone -->
        <link rel="apple-touch-icon" sizes="57x57" href="../img/icon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="../img/icon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="../img/icon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="../img/icon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="../img/icon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="../img/icon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="../img/icon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="../img/icon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="../img/icon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="../img/icon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../img/icon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="../img/icon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../img/icon/favicon-16x16.png">
        <link rel="manifest" href="../manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="../img/icon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <!-- Titolo -->
        <title>Elimina evento | <?php echo $nome_app; ?></title>
        <!-- CSS -->
        <link rel="stylesheet" href="../css/default.css" type="text/css">
        <link rel="stylesheet" href="css/style.css" type="text/css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    </head>
    <body>
        <!-- Header -->
        <?php
        $base_url = "../";
        include '../components/header.php';

        if (isset($_SESSION['session_id_eventoo'])) {
        ?>
        <!-- Container -->
        <div class="container">
            <?php
            if ($_SESSION['session_permessi_eventoo'] == "maintenance" || $_SESSION['session_permessi_eventoo'] == "administration" || $organizzatore == $nome." ".$cognome) {
                
                include '../config.php';
                $db = 'eventoo_planner';
                $conn = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());

                if ($_POST['si'] = "Sì" && isset($_POST['del_id2'])) {
                    
                    $del_id2 = $_POST[del_id2];
                    $filenameDelete = $_POST['filenameDelete'];
                    
                    $myconn = mysqli_connect('localhost','root','mysql', 'eventoo') or die (mysqli_error());
                    $timestamp = cripta(date('d/m/Y H:i:s', strtotime("now")), "encrypt");
                    $action = cripta("Eliminazione dell'evento (id: $del_id2)", "encrypt");
                    $ip = cripta($_SERVER['REMOTE_ADDR'], "encrypt");
                    $uname = cripta($username, "encrypt");
                    $name = cripta($nome, "encrypt");
                    $cog = cripta($cognome, "encrypt");
                    $mysql = "INSERT INTO accesses (username,nome,cognome,ip,azione,timestamp,validity) VALUES ('$uname', '$name','$cog','$ip','$action','$timestamp','$dataValidity')";

                    if (mysqli_query($conn,"DELETE FROM planner WHERE id = '$del_id2'")or die(mysqli_error($conn))) {
                        if($rressultt = mysqli_query($myconn,$mysql) or die (mysqli_error($myconn))) {
                            if ($filenameDelete != "locandina_default.png") {
                                unlink("../evento/files/$filenameDelete");
                            }
                            echo "<script type=\"text/javascript\">location.replace(\"../?d=".$_POST['data']."\");</script>";
                        }
                    }
                }

                $query = mysqli_query($conn,"SELECT * FROM planner WHERE id = $id") or die (mysqli_error($conn));
                $fetch = mysqli_fetch_array($query) or die (mysqli_error());
                
                $titolo = stripslashes($fetch['titolo']);
                $data = date("d/m/Y", stripslashes($fetch['data']));
                $ora = stripslashes($fetch['ora_inizio']);
                $durata = stripslashes($fetch['ora_fine']);
                $luogo = stripslashes($fetch['stanza']);
                $classe = stripslashes($fetch['classe']);
                $tipo = stripslashes($fetch['categoria']);
                $fileName = stripslashes($fetch['link_locandina']);
                
                date_default_timezone_set('Europe/Rome');
            ?>
            <h1>Eliminazione dell'evento</h1>
            <p>L'eliminazione di questo evento è <b>definitiva</b>, perciò non potrà più essere annullata. Continuare?</p>
            <div>
                <h3>Dettagli dell'evento:</h3>
                <p>
                    <b>Titolo evento:</b> <?php echo $titolo; ?><br>
                    <b>Data evento:</b> <?php echo $data; ?><br>
                    <b>Ora di inizio:</b> <?php echo $ora; ?><br>
                    <b>Ora di fine:</b> <?php echo $durata; ?><br>
                    <b>Luogo evento:</b> <?php echo $luogo; ?><br>
                    <b>Tipo evento:</b> <?php echo $tipo; ?>
                </p>
            </div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?organizzatore=".$organizzatore; ?>">
                <input type="hidden" value="<?php echo $id; ?>" name="del_id2">
                <input type="hidden" value="<?php echo $classe; ?>" name="classe">
                <input type="hidden" value="<?php echo stripslashes($fetch['data']); ?>" name="data">
                <input type="hidden" value="<?php echo $fileName; ?>" name="filenameDelete">
                <input type="submit" value="Sì" name="si">
                <input type="reset" value="No" name="no" onclick="history.back();">
            </form>
            <?php
            }

        } else {
            echo "<script type=\"text/javascript\">location.replace(\"../\");</script>";
        }
        ?>
        </div>
        <br><br><br><br><br><br><br><br>
        <?php
        include '../components/footer.php';
        ?>
    </body>
</html>
