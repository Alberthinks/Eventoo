<?php
session_start();
include '../default.php';

$username = $_SESSION['session_user_eventoo'];
$nome = $_SESSION['session_nome_eventoo'];
$cognome = $_SESSION['session_cognome_eventoo'];
$ao = $_SESSION['session_ao_eventoo'];

$str_data = $_GET['day'];
$data = date("d/m/Y", $str_data);

$id_evento = $_GET['id'];

include '../config.php';
$db = 'eventoo_planner';
$conn = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());
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
        <link rel="manifest" href="../img/icon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="../img/icon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <!-- Titolo -->
        <title>Eventi | <?php echo $nome_app; ?></title>
        <!-- CSS -->
        <link rel="stylesheet" href="css/style.css" type="text/css">
        <link rel="stylesheet" href="../css/default.css" type="text/css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <?php
        
        if (isset($id_evento) && is_numeric($id_evento)) {
            $sql = "SELECT * FROM planner WHERE id=$id_evento";
                $result = mysqli_query($conn,$sql) or die (mysqli_error($conn));

                if(mysqli_num_rows($result) > 0) {
                    while($fetch = mysqli_fetch_array($result)) {
                        $titolo = stripslashes($fetch['titolo']);
                        $descrizione = stripslashes($fetch['descrizione']);
                    }
                }
        ?>
        <!-- Social meta tags -->        
        <meta name="robots" content="all" />
        <meta name="revisit-after" content="8" />
        <meta name="author" content="Albertin Emanuele, Paun Catalin-Adrian">
        <meta name="title" content="<?php echo $titolo; ?>">
        <meta name="description" content="<?php echo $descrizione; ?>">
        <meta name="keywords" content="planner, castelmassa, associazioni, eventi Castelmassa, calto, ceneselli, castelnovo bariano, università popolare">
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="<?php echo $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']; ?>">
        <meta property="og:title" content="<?php echo $titolo; ?>">
        <meta property="og:description" content="<?php echo $descrizione; ?>">
        <meta property="og:image" content="../img/icon/apple-touch-icon.png">
        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="<?php echo $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']; ?>">
        <meta property="twitter:title" content="<?php echo $titolo; ?>">
        <meta property="twitter:description" content="<?php echo $descrizione; ?>">
        <meta property="twitter:image" content="../img/icon/apple-touch-icon.png">
        <?php
        }
        ?>
    </head>
    <body>
        <!-- Header -->
        <?php
            $base_url = "../";
            include '../components/header.php';
        ?>
        <div class="container">
            <?php
            if (isset($id_evento) && is_numeric($id_evento)) {

                $sql = "SELECT * FROM planner WHERE id=$id_evento";
                $result = mysqli_query($conn,$sql) or die (mysqli_error($conn));

                if(mysqli_num_rows($result) > 0) {
                    while($fetch = mysqli_fetch_array($result)) {
                        $id = stripslashes($fetch['id']);
                        $titolo = stripslashes($fetch['titolo']);
                        $descrizione = stripslashes($fetch['descrizione']);
                        $data = stripslashes($fetch['data']);
                        $ora = stripslashes($fetch['ora_inizio']);
                        $durata = stripslashes($fetch['ora_fine']);
                        $organizzatore = stripslashes($fetch['organizzatore']);
                        $luogo = stripslashes($fetch['stanza']);
                        $classe = stripslashes($fetch['classe']);
                        $tipo = stripslashes($fetch['categoria']);
                        $link_prenotazione = stripslashes($fetch['link_videoconferenza']);
                        $link_foto_video = stripslashes($fetch['link_locandina']);
                        $data_modifica = stripslashes($fetch['data_modifica']);

                        // Se e' stata caricata un'immagine come locandina, si mostra l'immagine; altrimenti non si mostra niente

                        if ($link_foto_video == "") {
                            $link_foto_video = "locandina_default.png";
                        }

                        echo "<div class=\"informazioni\">";
                        echo "<h2 class=\"titolo\">".$titolo."</h2>";
                        echo "<span id=\"dots\" style=\"float: right; position: relative; top: 20px; right: 15px;\">...</span><p class=\"descrizione\" id=\"descrizione\">".$descrizione."</p><p><a id=\"descrizioneBtn\" href=\"#\">Espandi</a></p>\n";
                        ?>
                        <script type="text/javascript">  
                            $(document).ready(function(){
                                if ($("#descrizione").height() > 50) {
                                    $("#descrizioneBtn").show();
                                    $("#dots").show();
                                    $("#descrizione").css("height","35px");
                                } else {
                                    $("#descrizioneBtn").hide();
                                    $("#dots").hide();
                                }
                            });
                            $("#descrizioneBtn").click(function(){
                                if ($("#descrizione").height() > 35) {
                                    $("#descrizione").css("height","35px");
                                    $("#dots").show();
                                    $("#descrizione").css("text-overflow","ellipsis");
                                    $("#descrizioneBtn").text("Espandi");
                                } else {
                                    $("#descrizione").css("height","auto");
                                    $("#dots").hide();
                                    $("#descrizione").css("text-overflow","clip");
                                    $("#descrizioneBtn").text("Comprimi");
                                }
                            });
                        </script> 
                        <?php
                        echo "<i class=\"material-icons\">calendar_today</i> <b>Data:</b> ".date("d/m/Y", $data)."<br>\n";
                        echo "<i class=\"material-icons\">schedule</i> <b>Ora:</b> ".$ora." - ".$durata."<br>\n";
                        echo "<i class=\"material-icons\">place</i> <b>Luogo:</b> ".$luogo."<br>\n";
                        echo "<i class=\"material-icons\">event</i> <b>Categoria:</b> ".$tipo."<br>\n";
                        echo "<i class=\"material-icons\">school</i> <b>Classi interessate:</b> ".$classe."<br>\n";
                        
                        // Link per modificare/eliminare evento (visibili solo dall'amministratore e dall'organizzatore dell'evento)
                        if ($_SESSION['session_permessi_eventoo'] == "administration" || $_SESSION['session_permessi_eventoo'] == "maintenance" || $organizzatore == $_SESSION['session_nome_eventoo']." ".$_SESSION['session_cognome_eventoo']) {
                            echo "<p><a class=\"changeBtn\" href=\"../modifica/?id=$id\">Modifica</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a class=\"changeBtn\" href=\"../elimina/?id=$id&organizzatore=$organizzatore&data=$str_data\">Elimina</a></p>";
                            echo "<b>Ultima modifica:</b> ".$data_modifica;
                        }

                        echo "</div>";
                        echo "<div class=\"right_content\">";

                        // Locandina dell'evento
                        if ($link_foto_video != "locandina_default.png") {
                            $estensione = substr($link_foto_video, -4);
                            switch ($estensione) {
                                case ".pdf":
                                    $file_icon = "../img/pdf_file.png";
                                    break;
                                case ".doc":
                                    $file_icon = "../img/doc_file.png";
                                    break;
                                case "docx":
                                    $file_icon = "../img/doc_file.png";
                                    break;
                                default:
                                    $file_icon = "../img/txt_file.png";
                            }
                            echo "<img src='".$file_icon."' align='center' height='30' style='margin-right: 15px;'>".$link_foto_video." <a style='margin-left: 25px;' href='files/".$link_foto_video."' class='material-icons' download>download</a> <a style='margin-left: 25px;' href='files/".$link_foto_video."' class='material-icons' target='_blank'>visibility</a>";
                        }
                        
                        
                        // Pulsante per prenotare l'evento
                        if ($link_prenotazione != null || $link_prenotazione != "") {
                            echo "<a href=\"".$link_prenotazione."\" target=\"_blank\" title=\"Prenota evento\"><button class=\"prenotaBtn\">Accedi alla videoconferenza</button></a>";
                        }
                        echo "</div>";
                    }
                }
            } elseif (isset($str_data) && is_numeric($str_data)) {
                $sql = "SELECT * FROM planner WHERE data=$str_data";
                $result = mysqli_query($conn,$sql) or die (mysqli_error($conn));
            ?>
            <h1>Eventi del <?php echo $data; ?></h1>
            <?php
            if(mysqli_num_rows($result) > 0) {
                while($fetch = mysqli_fetch_array($result)) {
                    $id = stripslashes($fetch['id']);
                    $titolo = stripslashes($fetch['titolo']);
                    $descrizione = stripslashes($fetch['descrizione']);
                    $data = stripslashes($fetch['data']);
                    $ora_inizio = stripslashes($fetch['ora_inizio']);
                    $ora_fine = stripslashes($fetch['ora_fine']);
                    $organizzatore = stripslashes($fetch['organizzatore']);
                    $luogo = stripslashes($fetch['stanza']);
                    $classe = stripslashes($fetch['classe']);
                    $tipo = stripslashes($fetch['categoria']);
                    $link_videoconferenza = stripslashes($fetch['link_videoconferenza']);
                    $link_locandina = stripslashes($fetch['link_locandina']);
                    $data_modifica = stripslashes($fetch['data_modifica']);


                    echo "<div style='margin-top: 40px; overflow: hidden;'>";
                    echo "<div class=\"informazioni\">";
                    echo "<h2 class=\"titolo\">".$titolo."</h2>\n";
                    echo "<i class=\"material-icons\">schedule</i> <b>Ora:</b> ".$ora_inizio." - ".$ora_fine."<br>\n";
                    echo "<i class=\"material-icons\">place</i> <b>Luogo:</b> ".$luogo."<br>\n";
                    echo "<i class=\"material-icons\">school</i> <b>Classi interessate:</b> ".$classe."<br>\n";
                    
                    if ($descrizione != "") {
                        $descrizione = ": ".$descrizione;
                    }

                    // Descrizione dell'evento
                    //echo "<span id=\"descrizioneBtn".$id."\" style=\"float: right; position: relative; top: 20px; right: 15px;\">...</span><p class=\"descrizione\" id=\"descrizione".$id."\"><b>".$tipo."</b>".$descrizione."</p><p><a id=\"descrizioneBtn".$id."\" href=\"#\">Espandi</a></p>\n";
                    echo "<span id=\"dots".$id."\" style=\"float: right; position: relative; top: 20px; right: 15px;\">...</span><p class=\"descrizione\" id=\"descrizione".$id."\"><b>".$tipo."</b>".$descrizione."</p><p><a id=\"descrizioneBtn".$id."\" href=\"#\">Espandi</a></p>\n";
                    ?>
                    <script type="text/javascript">  
                        $(document).ready(function(){
                            if ($("#descrizione<?php echo $id; ?>").height() > 50) {
                                $("#descrizioneBtn<?php echo $id; ?>").show();
                                $("#dots<?php echo $id; ?>").show();
                                $("#descrizione<?php echo $id; ?>").css("height","35px");
                            } else {
                                $("#descrizioneBtn<?php echo $id; ?>").hide();
                                $("#dots<?php echo $id; ?>").hide();
                            }
                        });
                        $("#descrizioneBtn<?php echo $id; ?>").click(function(){
                            if ($("#descrizione<?php echo $id; ?>").height() > 35) {
                                $("#descrizione<?php echo $id; ?>").css("height","35px");
                                $("#dots<?php echo $id; ?>").show();
                                $("#descrizione<?php echo $id; ?>").css("text-overflow","ellipsis");
                                $("#descrizioneBtn<?php echo $id; ?>").text("Espandi");
                            } else {
                                $("#descrizione<?php echo $id; ?>").css("height","auto");
                                $("#dots<?php echo $id; ?>").hide();
                                $("#descrizione<?php echo $id; ?>").css("text-overflow","clip");
                                $("#descrizioneBtn<?php echo $id; ?>").text("Comprimi");
                            }
                        });
                    </script> 
                    <?php

                    if ($_SESSION['session_permessi_eventoo'] == "administration" || $_SESSION['session_permessi_eventoo'] == "maintenance" || $organizzatore == $_SESSION['session_nome_eventoo']." ".$_SESSION['session_cognome_eventoo']) {
                        echo "<p><a href=\"../modifica/?id=$id\" class=\"changeBtn\">Modifica</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href=\"../elimina/?id=$id&organizzatore=$organizzatore&data=$str_data\" class=\"changeBtn\">Elimina</a></p>";
                        echo "<b>Ultima modifica:</b> ".$data_modifica;
                    }

                    echo '</div>';
                    echo "<div class=\"right_content\">";

                
                    // Locandina dell'evento
                    if ($link_foto_video == "") {
                        $link_foto_video = "locandina_default.png";
                    }
                    if ($link_foto_video != "locandina_default.png") {
                        $onclick = " cursor: zoom-in;\" onclick=\"zoomLocandina('locandine/".$link_foto_video."')\"";
                    } else {
                        $onclick = "height: 100px;\"";
                    }
                    echo '<img src="locandine/'.$link_foto_video.'" style="margin-bottom: 30px;'.$onclick.' alt="locandina dell\'evento" id="locandina" class="locandina">';

                    // Pulsante per prenotare l'evento
                    if ($link_prenotazione != null || $link_prenotazione != "") {
                        echo "<a href=\"".$link_prenotazione."\" target=\"_blank\" title=\"Prenota evento\"><button class=\"prenotaBtn\">Iscriviti all&apos;evento</button></a>";
                    }
                    echo '</div>';
                    echo '</div>';
                }
            }
        }
            ?>
        </div>
        <br><br><br>
        <?php
            include '../components/footer.php';
        ?>
    </body>
</html>
