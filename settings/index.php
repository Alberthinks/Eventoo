<?php
session_start();
include "../default.php";

$nome = $_SESSION['session_nome_eventoo']." ".$_SESSION['session_cognome_eventoo'];
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Icone -->
        <link rel="apple-touch-icon" sizes="180x180" href="../img/icon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../img/icon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../img/icon/favicon-16x16.png">
        <link rel="manifest" href="img/icon/site.webmanifest">
        <title>Settings | <?php echo $nome_app; ?></title>
        <link rel="stylesheet" href="css/style.css" type="text/css">
        <link rel="stylesheet" href="../css/default.css" type="text/css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    </head>
    <body>
        <?php
        // Permette l'accesso solo all'amministratore e a chi fa manutenzione
        if ($_SESSION['session_permessi_eventoo'] == "maintenance" || $_SESSION['session_permessi_eventoo'] == "administration") {
        ?>
        <!-- Header -->
        <header>
            <a class="material-icons" href="../">home</a>
            Benvenuto, <?php echo $nome; ?>
            <a href="../login/logout.php" class="material-icons headerbutton">logout</a>
        </header>
        <!-- Titolo -->
        <h1>Settings</h1>
        <!-- Elenco delle funzioni per l'amministratore -->
        <ul>
            <li><a href="gestione-utenti">Utenti</a></li>
            <li><a href="gestione-utenti/classe">Gestisci classi</a></li>
            <li><a href="gestione-utenti/nuovo">Registra nuovo utente</a></li>
            <li><a href="gestione-utenti/accessi">Controllo accessi</a></li>
            <li><a href="events.php">Controllo e verifica eventi</a></li>
            <?php
            if ($_SESSION['session_permessi_eventoo'] == "maintenance") {
            ?>
            <li><a href="cmd/index.php">Prompt comandi - <?php echo $nome_app; ?></a></li>
            <?php
            }
            ?>
        </ul>
        <?php
        } else {
            // Se non si è l'amministratore, si viene mandati alla pagina principale
            echo "<script type=\"text/javascript\">location.replace(\"../\");</script>";
        }
        ?>
    
    </body>
</html>
