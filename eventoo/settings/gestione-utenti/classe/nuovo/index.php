<?php
session_start();

include "../../../../default.php";

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

                $conn = mysqli_connect("localhost","eventooRootUser","QnBWQzlN-vVko9Egryb5b4&k1b4hghb2bj1jkj4$", "eventoo_users") or die (mysqli_error());

                $sql = "INSERT INTO classi (id, indirizzo) VALUES ('$id', '$indirizzo')";

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