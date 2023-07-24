<?php
session_start();
$nome = $_SESSION['session_user_lele_planner_0425'];
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Eventi della piattaforma</title>
        <link rel="stylesheet" href="../css/style.css" type="text/css">
        <link rel="stylesheet" href="../../../css/default.css" type="text/css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <style>
            body {overflow-x: auto !important; height: 50%;}
            th {background-color: rgba(255, 255, 255, 0.2);}
            tr:hover {background-color: rgba(255, 255, 255, 0.5); color: #000;}
        </style>
    </head>
    <body>
        <?php
        // Permette l'accesso solo all'amministratore
        if ($_SESSION['session_user_lele_planner_0425'] == "lele_administrator_admin") {
        ?>
        <!-- Header -->
        <header>
            <a class="material-icons" href="../../">home</a>
            Benvenuto, <?php echo $nome; ?>
            <a href="../../../login/logout.php" class="material-icons headerbutton">logout</a>
        </header>
        <!-- Titolo -->
        <h1>Eventi della piattaforma</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="submit" name="truncate" value="Svuota lista">
        </form>

        <select id="actionFiltering" onchange="filtraAttivita(6,'actionFiltering')">
            <option value=""></option>
            <option value="accesso">Accesso all'account</option>
            <option value="disconnessione">Disconnessione dall'account</option>
            <option value="creazione">Creazione evento</option>
            <option value="modifica dell'evento">Modifica evento</option>
            <option value="eliminazione">Eliminazione evento</option>
            <option value="Password modificata correttamente">Modifica password</option>
        </select>
        <input type="text" onkeyup="filtraAttivita(1,'unameFiltering')" id="unameFiltering" placeholder="Username">
        <input type="datetime-local" oninput="setTimestamp()" id="timestampFiltering2">
        <input type="hidden" id="timestampFiltering">
        <script>
            function setTimestamp() {
                var input = document.getElementById('timestampFiltering2').value;
                console.log(input);
                if (input == "") {
                    timestamp = "";
                } else {
                    var timestamp = input.substr(8,2) + "/" + input.substr(5,2) + "/" + input.substr(0,4) + " " + input.substr(11,2) + ":" + input.substr(14,2);
                    console.log(timestamp);
                }
                document.getElementById('timestampFiltering').value = timestamp;
                filtraAttivita(7,'timestampFiltering');
            }
        </script>
        <p>I dati saranno memorizzati per un anno dal momento della registrazione (in base al timestamp).</p>

        <table class="utenti" id="utenti">
            <!-- Intestazioni della tabella -->
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Nome societ&agrave;</th>
                <th>Indirizzo IP</th>
                <th>Azione</th>
                <th>Timestamp</th>
            </tr>
            <?php
            // Includo file PHP per il collegamento al database
            include '../../../config.php';
            // Includo file PHP per la gestione della piattaforma
            include "../../../default.php";

            // Nome del database a cui mi devo collegare
            $db = 'accesses';
            // Collegamento al database
            $conn = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());

            // Elimino record salvati da + di 1 anno
            $deleteSQL = mysqli_query($conn,"DELETE from accesses WHERE validity <= CURDATE() - 10000") or die (mysqli_error($conn));

            // Ricavo tutte le informazioni contenute nel database
            $result = mysqli_query($conn,"SELECT * FROM accesses ORDER BY id") or die (mysqli_error($conn));
            // Conto il numero dei record contenuti nel database
            $row_cnt = mysqli_num_rows($result);

            if ($row_cnt == 0) {
                echo "<tr>";
                echo "<td colspan='8' style='padding: 15px;'><i class='material-icons' style='font-size: 40px;'>person_off</i><br />Nessun evento registrato</td>";
                echo "</tr>";
            } else {
            while($row = mysqli_fetch_row($result)) {
            // Inizia la riga
            echo "<tr>";
            // Riempimento celle con i dati dell'ID e dello Username
            for ($i = 0; $i < 1; $i++) {
                $valore = $row[$i];
                echo "<td>".$valore."</td>";
            }
            // Riempimento celle con i dati dell'ID e dello Username
            for ($i = 1; $i < 8; $i++) {
                $valore = cripta($row[$i], "decrypt");
                echo "<td>".$valore."</td>";
            }
            echo "</tr>\n"; 
            }
            }
            ?>
        </table>
        <script>
            function filtraAttivita(n,id) {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById(id);
            filter = input.value.toUpperCase();
            table = document.getElementById("utenti");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[n];
                if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
                }       
            }
            }
        </script>
        <?php
            // Se clicco sul pulsante "Svuota lista"
            if (isset($_POST['truncate'])) {
                if (mysqli_query($conn,"TRUNCATE accesses")or die(mysqli_error($conn))) {
                    echo "<script>location.href = '';</script>";
                } else {
                    echo "<script>alert(\"C'è stato un errore\");</script>";
                }
            }
        } else {
            // Se non si è l'amministratore, si viene mandati alla pagina principale
            echo "<script type=\"text/javascript\">location.replace(\"../../../\");</script>";
        }
        ?>
    </body>
</html>
