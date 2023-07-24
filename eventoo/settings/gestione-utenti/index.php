<?php
session_start();

include "../../default.php";

$nome = $_SESSION['session_user_lele_planner_0425'];
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestione utenti</title>
        <link rel="stylesheet" href="css/style.css" type="text/css">
        <link rel="stylesheet" href="../../css/default.css" type="text/css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <script type="text/javascript" src="js/script.js"></script>
    </head>
    <body>
        <?php
        // Permette l'accesso solo all'amministratore e a chi fa manutenzione
        if ($_SESSION['session_permessi_eventoo'] == "maintenance" || $_SESSION['session_user_lele_planner_0425'] == "lele_administrator_admin") {
        ?>
        <!-- Header -->
        <header>
            <a class="material-icons" href="../">home</a>
            Benvenuto, <?php echo $nome; ?>
            <a href="../../login/logout.php" class="material-icons headerbutton">logout</a>
        </header>
        <!-- Titolo -->
        <h1>Gestione utenti</h1>
        <!-- Tabella degli utenti -->
        <table class="utenti">
            <!-- Intestazioni della tabella -->
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Sesso</th>
                <th>Username</th>
                <th>email</th>
                <th>Nome societ&agrave;</th>
                <th>Logo societ&agrave;</th>
                <th>Ultimo accesso</th>
                <th>Cambia password</th>
                <th>Rimuovi</th>
            </tr>
            <?php
            include '../../config.php';
            $db = 'users';
            $conn = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());

            $result = mysqli_query($conn,"SELECT * FROM users ORDER BY id") or die (mysqli_error($conn));
            $row_cnt = mysqli_num_rows($result); 

            if ($row_cnt == 0) {
                echo "<tr>";
                echo "<td colspan='6' style='padding: 15px;'><i class='material-icons' style='font-size: 40px;'>person_off</i><br />Nessun utente registrato</td>";
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
            for ($i = 1; $i < 5; $i++) {
                $valore = cripta($row[$i], "decrypt");
                echo "<td>".$valore."</td>";
            }
            // Riempimento celle con i dati dell'ID e dello Username
            for ($i = 6; $i < 9; $i++) {
                $valore2 = cripta($row[$i], "decrypt");
                echo "<td>".$valore2."</td>";
            }
            // Riempimento celle con i dati di email, Nome societa', Ultimo accesso
            for ($i = 9; $i < 10; $i++) {
                $valore3 = $row[$i];
                if ($valore3 == "" || $valore3 == null) {
                    $valore3 = "Mai";
                }
                echo "<td>".$valore3."</td>";
            }
            for ($i = 0; $i < 1; $i++) {
                $id = $row[$i];
                // Cella per modificare la password dell'account selezionato
                echo "<td><a class='material-icons bottone' href='#' onclick='changePassword($id)' role='button'>password</a></td>";
                // Ultima cella per eliminare l'account. La funzione deleteConfirm() chiede se si e' sicuri di eliminare l'account
                echo "<td><a class='material-icons bottone' href='#' onclick='deleteConfirm($id)' role='button'>delete</a></td>";
            }
            echo "</tr>\n"; 
            }
            }
            ?>
        </table>
        <!-- Sezione per cambiare la password -->
        <section id="psw-container" style="width: 500px; height: 300px; background: white; border: 1px solid red; color: black; margin: auto; padding-left: 80px; display: none;">
        <?php
            if (isset($_POST['submit']) && $_POST['submit']=="Conferma") {
                $psw = addslashes($_POST['psw']);
                $psw2 = addslashes($_POST['psw2']);
                $id_post = addslashes($_POST['id_post']);

                include '../../config.php';
                $db = 'users';
                $conn = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());

                if ($psw == $psw2) {
                    $password = password_hash($psw, PASSWORD_BCRYPT);
                    $sql = "UPDATE users SET password='$password' WHERE id = '$id_post'";
                    if (mysqli_query($conn,$sql) or die (mysqli_error($conn))) {
                        echo "<p>La password &egrave; stata modificata correttamente.</p>";
                    }
                } else if ($password1 != $password2) {
                    echo "Le due nuove password non corrispondono!";
                } else {
                    echo "Errore nella procedura";
                }
            } else {
        ?>
            <h3>Cambia la password dell'utente n° <span id="nome-utente"></span></h3>
            <form name="cambiaPassword" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <!--input type="text" name="psw" placeholder="Password" required-->
                    <div class="input-container">
                        <input
                            type="text"
                            id="psw"
                            name="psw"
                            value=""
                            aria-labelledby="label-psw"
                            oninput="manageTextInputStyle('psw')"
                            required
                        />
                        <label class="label" for="psw" id="label-psw">
                            <div class="text">Nuova password</div>
                        </label>
                    </div>
                    <div class="input-container">
                        <input
                            type="text"
                            id="psw2"
                            name="psw2"
                            value=""
                            aria-labelledby="label-psw2"
                            oninput="manageTextInputStyle('psw2')"
                            required
                        />
                        <label class="label" for="psw2" id="label-psw2">
                            <div class="text">Ripeti password</div>
                        </label>
                    </div>
                    <script>
                    function manageTextInputStyle(id) {
                        const input = document.getElementById(id);
                        input.setAttribute('value', input.value);
                    }
                    </script>
                <!--input type="text" name="psw2" placeholder="Ripeti password" required-->
                <input type="hidden" name="id_post" id="id_post" value="0">
                <input type="submit" name="submit" value="Conferma">
                <input type="reset" value="Chiudi" onclick="document.getElementById('psw-container').style.display='none'">
            </form>
        </section>
        <?php
            }
        } else {
            // Se non si è l'amministratore, si viene mandati alla pagina principale
            echo "<script type=\"text/javascript\">location.replace(\"../../\");</script>";
        }
        ?>
    </body>
</html>
