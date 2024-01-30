<?php
session_start();

include "../../../default.php";

$nome = $_SESSION['session_nome_eventoo']." ".$_SESSION['session_cognome_eventoo'];
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../../css/default.css" type="text/css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <title>Aggiungi un nuovo utente</title>
        <style>
            body {padding-left: 40px !important;}
            input {margin-bottom: 30px; margin-top: 30px;}
            input[type=radio] {width: 14px; height: 14px; margin-bottom: 5px; margin-top: 5px;}
            input[type=file] {border: none;}
            .label2 {font-weight: bold;}
        </style>
    </head>
    <body>
        <?php
        // Permette l'accesso solo all'amministratore e al tecnico di manutenzione
        if ($_SESSION['session_permessi_eventoo'] == "maintenance" || $_SESSION['session_permessi_eventoo'] == "administration") {
            if (isset($_POST['submit']) && $_POST['submit']=="Registra utente") {
                $nome = cripta(addslashes($_POST['nome']),'encrypt');
                $cognome = cripta(addslashes($_POST['cognome']),'encrypt');
                $password = addslashes($_POST['password']);
                $permessi = cripta(addslashes($_POST['permessi']),'encrypt');
                $last_access = "";
                $ao = cripta(addslashes($_POST['ao']),'encrypt');
                $username = cripta(addslashes($_POST['username']),'encrypt');
                $passuord = password_hash($password, PASSWORD_BCRYPT);

                include '../../../config.php';
                $db = 'eventoo_users';
                $conn = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());

                $sql = "INSERT INTO users (nome,cognome,ao,username,password,foto,permessi,last_access) VALUES ('$nome', '$cognome','$ao','$username','$passuord','','$permessi','$last_access')";
			    if($result = mysqli_query($conn,$sql) or die (mysqli_error($conn))) {
                    echo "<script>location.href = \"../../\";</script>";
                } else {
                    echo "Errore nella registrazione dei dati";
                }
            } else {
        ?>
        <!-- Header -->
        <header>
            <a class="material-icons" href="../../">home</a>
            Benvenuto, <?php echo $nome; ?>
            <a href="../../../login/logout.php" class="material-icons headerbutton">logout</a>
        </header>
        <!-- Titolo -->
        <h1>Aggiungi un nuovo utente</h1>
        <!-- Form per inserire il nuovo utente -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
            <p>Non usare n&eacute; apici singoli n&eacute; apici doppi (' o ")</p>
            <label for="nome" class="label2">Nome:</label>
            <input type="text" name="nome" id="nome"><br>

            <label for="cognome" class="label2">Cognome:</label>
            <input type="text" name="cognome" id="cognome"><br>

            <label for="ao" class="label2">Sesso:</label>
            <input type="radio" name="ao" id="ao" value="o"> Maschio
            <input type="radio" name="ao" id="ao" value="a"> Femmina<br>

            <label for="username" class="label2">Username:</label>
            <input type="text" name="username" id="username"><br>

            <label for="password" class="label2">Password:</label>
            <input type="text" name="password" id="password"><br>

            <label class="label2">Permessi:</label><br>
            <input type="radio" name="permessi" id="read_permesso" value="read"> <label for="read_permesso">Lettura (leggere gli eventi dell'agenda)</label><br>
            <input type="radio" name="permessi" id="write_permesso" value="write"> <label for="write_permesso">Lettura e scrittura (leggere, aggiungere, modificare ed eliminare gli eventi dell'agenda)</label><br>
            <input type="radio" name="permessi" id="admin_permesso" value="administration"> <label for="admin_permesso">Amministrazione (accesso completo e senza limitazioni alla piattaforma)</label><br>

            <input type="submit" name="submit" value="Registra utente" style="padding: 0; background: green; color: #fff; border: none; width: 140px; height: 40px;">
        </form>
        <?php
            }
        } else {
            // Se non si è l'amministratore, si viene mandati alla pagina principale
            echo "<script type=\"text/javascript\">location.replace(\"../../\");</script>";
        }
        ?>
    </body>
</html>
