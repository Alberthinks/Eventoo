<?php
session_start();
include "../../../default.php";

$nome = $_SESSION['session_nome_eventoo']." ".$_SESSION['session_cognome_eventoo'];
?>
<!DOCTYPE html>
    <html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Classi</title>
        <link rel="stylesheet" href="../css/style.css" type="text/css">
        <link rel="stylesheet" href="../../../css/default.css" type="text/css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    </head>
    <body>
        <?php
        // Permette l'accesso solo all'amministratore e a chi fa manutenzione
        if ($_SESSION['session_permessi_eventoo'] == "maintenance" || $_SESSION['session_permessi_eventoo'] == "administration") {
        ?>
         <!-- Header -->
         <header>
            <a class="material-icons" href="../../">home</a>
            Benvenuto, <?php echo $nome; ?>
            <a href="../../../login/logout.php" class="material-icons headerbutton">logout</a>
        </header>
        <!-- Titolo -->
        <h1>Gestione classi</h1>
        <a href="nuovo" title="Aggiungi una classe" style="height: 0;"><button style="margin-left: 100px; margin-top: 60px;" class="material-icons">group_add</button></a>
        <!-- Tabella delle classi -->
        <table class="utenti">
            <!-- Intestazioni della tabella -->
            <tr>
                <th>Classe</th>
                <th>Indirizzo</th>
                <th>Elimina</th>
            </tr>
            <?php
            include '../../../config.php';
            $db = 'eventoo_users';
            $conn = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());

            $result = mysqli_query($conn,"SELECT * FROM classi ORDER BY id") or die (mysqli_error($conn));
            $row_cnt = mysqli_num_rows($result); 

            if ($row_cnt == 0) {
                echo "<tr>";
                echo "<td colspan='6' style='padding: 15px;'><i class='material-icons' style='font-size: 40px;'>group_off</i><br />Nessuna classe registrata</td>";
                echo "</tr>";
            } else {
            while($row = mysqli_fetch_row($result)) {
            // Inizia la riga
            echo "<tr>";
            // Riempimento celle con i dati dell'ID e dello Username
            for ($i = 0; $i < 2; $i++) {
                $valore = $row[$i];
                echo "<td>".cripta($valore, "decrypt")."</td>";
            }
            for ($i = 0; $i < 1; $i++) {
                $id = $row[$i];
                // Ultima cella per eliminare la classe. La funzione deleteConfirm() chiede se si e' sicuri di eliminarla
                echo "<td><a class='material-icons bottone' href='#' onclick='deleteConfirm(\"$id\")' role='button'>delete</a></td>";
            }
            echo "</tr>\n"; 
            }
            }
            ?>
        </table>
        <script>
            function deleteConfirm(id) {
                if (confirm("Sei sicuro di voler eliminare questa classe?\nL'operazione sarà definitiva.")) {
                    location.replace("gestione.php?function=delete&id=" + id);
                }
            }

            function changeClasse(id) {
                location.replace("gestione.php?function=change&id=" + id);
            }
        </script>
        <?php
        } else {
            echo "<script type=\"text/javascript\">location.replace(\"../\");</script>";
        }
        ?>
    </body>
</html>