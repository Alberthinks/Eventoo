<?php
session_start();

include "../../../../default.php";
include "../../../../config.php";

$nome = $_SESSION['session_nome_eventoo']." ".$_SESSION['session_cognome_eventoo'];
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Nuova classe</title>
        <style>
            body {padding-left: 40px !important;}
            input {margin-bottom: 60px;}
        </style>
        <link rel="stylesheet" href="../../css/style.css" type="text/css">
        <link rel="stylesheet" href="../../../../css/default.css" type="text/css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    </head>
    <body>
        <?php
        if ($_SESSION['session_permessi_eventoo'] == "maintenance" || $_SESSION['session_permessi_eventoo'] == "administration") {
            if (isset($_POST['confirm']) && $_POST['confirm']=="Salva") {

                $id = cripta(addslashes($_POST['id']),'encrypt');
                $indirizzo = cripta(addslashes($_POST['indirizzo']),'encrypt');
                $sede = cripta(addslashes($_POST['sede']),'encrypt');

                $conn = mysqli_connect($host,$user,$pass, "eventoo_users") or die (mysqli_error());

                $sql = "INSERT INTO classi (id, indirizzo, sede) VALUES ('$id', '$indirizzo', '$sede')";

			    if($result = mysqli_query($conn,$sql) or die (mysqli_error($conn))) {
                    echo "<script>location.href = \"../\";</script>";
                }
            }
        ?>
        <!-- Header -->
        <header>
            <a class="material-icons" href="../../">home</a>
            Benvenuto, <?php echo $nome; ?>
            <a href="../../../login/logout.php" class="material-icons headerbutton">logout</a>
        </header>
        <!-- Titolo -->
        <h1>Aggiungi una nuova classe</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
            <label for="id">Classe:</label>
            <input type="text" name="id" id="id" placeholder="5 B TI">
            <br>
            <label for="indirizzo">Indirizzo:</label>
            <input type="text" name="indirizzo" id="indirizzo" placeholder="TECNICO INFORMATICO">
            <br>
            <select name="sede" style="font-family: 'Segoe UI'; font-size: 16px; height: 48px; width: 310px; border: 1px solid #c0c0c0; border-radius: 4px; box-sizing: border-box; padding-left: 16px; margin-bottom: 50px;" required>
                <option disabled selected>Sede</option>
                <option value="Balzan">Balzan</option>
                <option value="Einaudi">Einaudi</option>
                <option value="Medie">Medie (succursale)</option>
            </select>
            <br>
            <input type="submit" name="confirm" value="Salva" style="padding: 0; background: green; color: #fff; border: none; width: 140px; height: 40px;">
            <input type="reset" name="cancel" onclick="history.back()" style="padding: 0; background: red; color: #fff; border: none; width: 140px; height: 40px;" value="Annulla">
        </form>
        <?php
        } else {
            echo "<script type=\"text/javascript\">location.replace(\"../../\");</script>";
        }
        ?>
    </body>
</html>