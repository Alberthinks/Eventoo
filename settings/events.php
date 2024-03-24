<?php
session_start();

include "../../default.php";

$nome = $_SESSION['session_nome_eventoo']." ".$_SESSION['session_cognome_eventoo'];
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Controllo evento</title>
        <link rel="stylesheet" href="css/style.css" type="text/css">
        <link rel="stylesheet" href="../css/default.css" type="text/css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <style>
            /*table, td, th {border: 1px solid black;}*/
            table {width: 1000px; margin-left: auto; margin-right: auto;}
            td {font-size: 18px;}
            .shorter {width: 190px !important;}
            input[type=button] {
                cursor: pointer;
                border: none;
                transition: 0.4s;
                font-size: 18px;
                margin-top: 40px;
                margin-left: auto;
                margin-right: auto;
                padding: 5px;
                border-radius: 4px;
                box-sizing: border-box;
            }
        </style>
    </head>
    <body>
        <?php
        // Permette l'accesso solo all'amministratore e a chi fa manutenzione
        if ($_SESSION['session_permessi_eventoo'] == "maintenance" || $_SESSION['session_permessi_eventoo'] == "administration") {

            include '../config.php';
            $db = 'eventoo_planner';
            $conn = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());

            $result = mysqli_query($conn,"SELECT * FROM planner WHERE id='".$_GET['id']."'") or die (mysqli_error($conn));
            $row_cnt = mysqli_num_rows($result); 

            if ($row_cnt == 0) {
                echo "<h3 style='padding: 15px;'>Nessun evento trovato.</h3>";
                ?>
                    <form method="get">
                        <label for="id">Inserire l'ID dell'evento da verificare:</label><br>
                        <input type="number" name="id" id="id"><br><br>
                        <input type="submit" value="Cerca"><br><br>
                        <input type="reset" onclick="location.href='index.php'" value="Annulla">
                    </form>
                <?php
            } else {
                $data = mysqli_fetch_array($result);
        ?>
        <!-- Header -->
        <header>
            <a class="material-icons" href="index.php">home</a>
            Benvenuto, <?php echo $nome; ?>
            <a href="../login/logout.php" class="material-icons headerbutton">logout</a>
        </header>
        <!-- Titolo -->
        <h1 style="text-align: left;">Controllo evento</h1>
            <table>
                <tr>
                    <th colspan="4"><h1><?php echo $data['titolo']; ?></h1></th>
                </tr>
                <tr>
                    <td colspan="4"><p style="text-align: left; text-overflow: clip; white-space: pre-wrap; padding: 10px;"><?php echo $data['descrizione']; ?></p></td>
                </tr>
                <tr>
                    <td class="shorter">
                        <b>ID evento:</b>
                    </td>
                    <td colspan="3">
                        <?php
                            echo $data['id'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="shorter">
                        <b>Data:</b>
                    </td>  
                    <td>
                        <?php
                            echo date("d/m/Y",$data['data']);
                        ?>
                    </td>
                    <td class="shorter">
                        <b>Luogo:</b>
                    </td>
                    <td>
                        <?php
                            echo $data['stanza'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="shorter">
                        <b>Ora inizio:</b>
                    </td>
                    <td>
                        <?php
                            echo $data['ora_inizio'];
                        ?>
                    </td>
                    <td class="shorter">
                        <b>Ora fine:</b>
                    </td>
                    <td>
                        <?php
                            echo $data['ora_fine'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="shorter">
                        <b>Classe interessata:</b>
                    </td>
                    <td>
                        <?php
                            echo $data['classe'];
                        ?>
                    </td>
                    <td class="shorter">
                        <b>Categoria:</b>
                    </td>
                    <td>
                        <?php
                            echo $data['categoria'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="shorter">
                        <b>Data creazione:</b>
                    </td>
                    <td>
                        <?php
                            function getData($stringa) {
                                $stringaArray = str_split($stringa,2);
                                $dd = $stringaArray[3];
                                $mm = $stringaArray[2];
                                $yy1 = $stringaArray[1];
                                $yy2 = $stringaArray[0];
                                return $dd."/".$mm."/".$yy2.$yy1;
                            }
                            echo getData($data['validity']);
                        ?>
                    </td>
                    <td class="shorter">
                        <b>Ultima modifica:</b>
                    </td>
                    <td>
                        <?php
                            echo $data['data_modifica'];
                        ?>
                    </td>
                </tr>
                <tr>
                    
                    <td>
                        <b class="shorter">Creato da:</b>
                    </td>
                    <td colspan="3">
                        <?php
                            echo $data['organizzatore'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="button" style="background-color: var(--color-confirm-btn); color: #ffffff;" onclick="location.href='events.php'" value="Nuova ricerca">
                    </td>
                    <td colspan="2">
                        <input type="button" style="background-color: #ffffff; color: red; border: 1.8px solid red;" onclick="location.href='../elimina/index.php?id=<?php echo $data['id']; ?>'" value="Elimina">
                    </td>
                </tr>
            </table>
        <?php
            }
        } else {
            // Se non si è l'amministratore, si viene mandati alla pagina principale
            echo "<script type=\"text/javascript\">location.replace(\"../../\");</script>";
        }
        ?>
    </body>
</html>
