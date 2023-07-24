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
        <link rel="apple-touch-icon" sizes="180x180" href="../img/icon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../img/icon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../img/icon/favicon-16x16.png">
        <link rel="manifest" href="../img/icon/site.webmanifest">
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
                        $ora = stripslashes($fetch['ora']);
                        $durata = stripslashes($fetch['durata']);
                        $organizzatore = stripslashes($fetch['organizzatore']);
                        $luogo = stripslashes($fetch['luogo']);
                        $tipo = stripslashes($fetch['tipo']);
                        $link_prenotazione = stripslashes($fetch['link_prenotazione']);
                        $link_foto_video = stripslashes($fetch['link_foto_video']);
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
                        echo "<i class=\"material-icons\">schedule</i> <b>Ora di inizio:</b> ".$ora."<br>\n";
                        echo "<i class=\"material-icons\">timelapse</i> <b>Durata:</b> ".$durata."<br>\n";

                        echo getGoogleMapsLink($luogo, false);
                        
                        echo "<i class=\"material-icons\">event</i> <b>Categoria:</b> ".$tipo."<br>\n";
                        
                        // Ottenere il logo dell'organizzatore
                        $db2 = 'users';
                        $conn2 = mysqli_connect($host,$user,$pass, $db2) or die (mysqli_error());
                        $organizzatoreCriptato = cripta($organizzatore, "encrypt");
                        $result2 = mysqli_query($conn2,"SELECT * FROM users WHERE nome_societa='$organizzatoreCriptato'") or die (mysqli_error($conn2));
                        $fetch2 = mysqli_fetch_array($result2);

                        echo "<img style='margin-top: 40px; width: 60px;' alt='logo organizzatore' src='../settings/gestione-utenti/nuovo/".cripta($fetch2['logo'],"decrypt")."'><br>\n";

                        // Link per modificare/eliminare evento (visibili solo dall'amministratore e dall'organizzatore dell'evento)
                        if ($_SESSION['session_user_lele_planner_0425'] == "lele_administrator_admin" || $_SESSION['session_nome-societa_lele_planner_0425'] == $organizzatore) {
                            echo "<p><a class=\"changeBtn\" href=\"../modifica/?id=$id\">Modifica</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a class=\"changeBtn\" href=\"../elimina/?id=$id&organizzatore=$organizzatore&data=$str_data\">Elimina</a></p>";
                            echo "<b>Ultima modifica:</b> ".$data_modifica;
                        }

                        echo "</div>";

                        echo "<div class=\"right_content\">";

                        // Area con i pulsanti di condivisione sui social network
                        echo "<div class=\"share\"><a title=\"Condividi su WhatsApp\" href=\"https://api.whatsapp.com/send/?text=".$titolo."+".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']."%3Fid%3D".$id."\"><img src=\"../img/icons8-whatsapp.svg\" style=\"margin-left: 0;\"></a>";
                        echo '<a target="_blank" title="Condividi su Facebook" href="https://www.facebook.com/sharer/sharer.php?u='.$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"].'%3Fid%3D'.$id.'"><img src="../img/icons8-facebook-nuovo.svg"></a>';
                        echo '<a target="_blank" title="Condividi su Twitter" href="https://twitter.com/intent/tweet?text='.$titolo.'&url='.$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"].'%3Fid%3D'.$id.'"><img src="../img/icons8-twitter-cerchiato.svg"></a>';
                        echo '<a target="_blank" title="Condividi tramite e-mail" href="mailto:?subject='.$titolo.'&body='.$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"].'%3Fid%3D'.$id.'"><img src="../img/icons8-circled-envelope-96.png"></a>';
                        echo '<a title="Copia link" href="javascript:copyUrl(\''.$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"].'%3Fid%3D'.$id.'\')"><img src="../img/icons8-link-48.png"></a></div>';

                        // Locandina dell'evento
                        if ($link_foto_video != "locandina_default.png") {
                            $onclick = " cursor: zoom-in;\" onclick=\"zoomLocandina('locandine/".$link_foto_video."')\"";
                        } else {
                            $onclick = "\"";
                        }
                        echo '<img src="locandine/'.$link_foto_video.'" style="margin-bottom: 30px;'.$onclick.' alt="locandina dell\'evento" id="locandina" class="locandina">';
                        
                        // Pulsante per prenotare l'evento
                        if ($link_prenotazione != null || $link_prenotazione != "") {
                            echo "<a href=\"".$link_prenotazione."\" target=\"_blank\" title=\"Prenota evento\"><button class=\"prenotaBtn\">Iscriviti all&apos;evento</button></a>";
                        }

                        echo "</div>";

                        // Copiare l'URL dell'evento attuale per condividerlo con altri
                        echo '<script>function copyUrl(url) {navigator.clipboard.writeText(url);alert("Link copiato negli appunti");}</script>';
                        // Mostrare la locandina in grande
                        echo "<script>var locandina=document.getElementById('locandina').src; function zoomLocandina() {window.open(locandina, '_blank', 'width=550,height=800');}</script>";
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
                    $ora = stripslashes($fetch['ora']);
                    $durata = stripslashes($fetch['durata']);
                    $organizzatore = stripslashes($fetch['organizzatore']);
                    $luogo = stripslashes($fetch['luogo']);
                    $tipo = stripslashes($fetch['tipo']);
                    $link_prenotazione = stripslashes($fetch['link_prenotazione']);
                    $link_foto_video = stripslashes($fetch['link_foto_video']);
                    $data_modifica = stripslashes($fetch['data_modifica']);


                    echo "<div style='margin-top: 40px; overflow: hidden;'>";
                    echo "<div class=\"informazioni\">";
                    echo "<h2 class=\"titolo\">".$titolo."</h2>\n";
                    echo "<i class=\"material-icons\">schedule</i> <b>Ora di inizio:</b> ".$ora."<br>\n";
                    echo "<i class=\"material-icons\">timelapse</i> <b>Durata:</b> ".$durata."<br>\n";

                    echo getGoogleMapsLink($luogo, false);
                    
                    // Ottenere il logo dell'organizzatore
                    $db2 = 'users';
                    $conn2 = mysqli_connect($host,$user,$pass, $db2) or die (mysqli_error());
                    $organizzatoreCriptato = cripta($organizzatore, "encrypt");
                    $result2 = mysqli_query($conn2,"SELECT * FROM users WHERE nome_societa='$organizzatoreCriptato'") or die (mysqli_error($conn2));
                    $fetch2 = mysqli_fetch_array($result2);

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

                    if ($_SESSION['session_user_lele_planner_0425'] == "lele_administrator_admin" || $_SESSION['session_nome-societa_lele_planner_0425'] == $organizzatore) {
                        echo "<p><a href=\"../modifica/?id=$id\" class=\"changeBtn\">Modifica</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href=\"../elimina/?id=$id&organizzatore=$organizzatore&data=$str_data\" class=\"changeBtn\">Elimina</a></p>";
                        echo "<b>Ultima modifica:</b> ".$data_modifica;
                    }

                    echo '</div>';
                    echo "<div class=\"right_content\">";

                    // Area con i pulsanti di condivisione sui social network
                    echo "<div class=\"share\"><a title=\"Condividi su WhatsApp\" href=\"https://api.whatsapp.com/send/?text=".$titolo."+".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']."%3Fid%3D".$id."\"><img src=\"../img/icons8-whatsapp.svg\" style=\"margin-left: 0;\"></a>";
                    echo '<a target="_blank" title="Condividi su Facebook" href="https://www.facebook.com/sharer/sharer.php?u='.$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"].'%3Fid%3D'.$id.'"><img src="../img/icons8-facebook-nuovo.svg"></a>';
                    echo '<a target="_blank" title="Condividi su Twitter" href="https://twitter.com/intent/tweet?text='.$titolo.'&url='.$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"].'%3Fid%3D'.$id.'"><img src="../img/icons8-twitter-cerchiato.svg"></a>';
                    echo '<a target="_blank" title="Condividi tramite e-mail" href="mailto:?subject='.$titolo.'&body='.$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"].'%3Fid%3D'.$id.'"><img src="../img/icons8-circled-envelope-96.png"></a>';
                    echo '<a title="Copia link" href="javascript:copyUrl(\''.$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"].'%3Fid%3D'.$id.'\')"><img src="../img/icons8-link-48.png"></a></div>';

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

                    // Mostrare la locandina in grande
                    echo "<script>function zoomLocandina(locandina) {window.open(locandina, '_blank', 'width=550,height=800');}</script>";   
                    // Copiare l'URL dell'evento attuale per condividerlo con altri
                    echo '<script>function copyUrl(url) {navigator.clipboard.writeText(url);alert("Link copiato negli appunti");}</script>';
                }
            }
        }
            ?>
        </div>

        <?php
        echo '<a class="material-icons info" onclick="toggleDisplay()">info</a>';
        echo '<div id="credits" style="display: none;">';
        echo '<a target="_blank" href="https://icons8.com/icon/16713/whatsapp">WhatsApp</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a><br>';
        echo '<a target="_blank" href="https://icons8.com/icon/114450/twitter-cerchiato">Twitter cerchiato</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a><br>';
        echo '<a target="_blank" href="https://icons8.com/icon/uLWV5A9vXIPu/facebook-nuovo">Facebook Nuovo</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a><br>';
        echo '<a target="_blank" href="https://icons8.com/icon/35084/circled-envelope">Circled Envelope</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a><br>';
        echo '<a target="_blank" href="https://icons8.com/icon/kktvCbkDLbNb/link">Link</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a>';
        echo '<!--Foto (sfondo) di <a href="https://unsplash.com/@antenna?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText">Antenna</a> su <a href="https://unsplash.com/it/s/foto/evento-sportivo?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText">Unsplash</a>-->';
        echo '</div>';
        echo '<script>function toggleDisplay() {if(document.getElementById("credits").style.display == "none"){document.getElementById("credits").style.display = "block"} else {document.getElementById("credits").style.display = "none"}}</script>';
        ?>

        <br><br><br>
        <?php
            include '../components/footer.php';
        ?>
    </body>
</html>
