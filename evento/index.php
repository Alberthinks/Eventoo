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
        <link rel="manifest" href="../manifest.json">
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
        <meta name="author" content="Albertin Emanuele">
        <meta name="title" content="<?php echo $titolo; ?>">
        <meta name="description" content="<?php echo $descrizione; ?>">
        <meta name="keywords" content="planner, iis primo levi, scuola">
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

                        // Metto il logo "Chiuso" nell'header se l'evento di oggi dice che la scuola e' chiusa
                        if ($tipo == "Chiusura" && date("d/m/Y", $data) == date("d/m/Y", time())) {
                            oggiChiuso();
                            $changing = "";
                            if ($_SESSION['session_permessi_eventoo'] == "administration" || $_SESSION['session_permessi_eventoo'] == "maintenance" || $organizzatore == $_SESSION['session_nome_eventoo']." ".$_SESSION['session_cognome_eventoo']) {
                                $changing = "<p><a href=\"../modifica/?id=$id\" class=\"changeBtn\">Modifica</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href=\"../elimina/?id=$id&organizzatore=$organizzatore&data=$str_data\" class=\"changeBtn\">Elimina</a></p>";
                            }
                            echo "<div style='width: 80%; border: 3.5px solid #CC0001; border-radius: 5px; padding: 35px 50px; background-color: #ffdddd; background-image: url(files/alert.png); background-repeat: no-repeat; background-size: 30px 30px; background-position: 50px 35px;'><h2 style='margin-left: 40px;'>Oggi la scuola &egrave; chiusa</h2><p style='font-size: 18px; margin-top: 30px;'>".$descrizione."</p><p style='margin-top: 15px;'><b>Data:</b> ".date("d/m/Y", $data)."</p>".$changing."</div>";
                        } else {

                            // Se e' stata caricata un'immagine come locandina, si mostra l'immagine; altrimenti non si mostra niente
                            if ($link_foto_video == "") {
                                $link_foto_video = "locandina_default.png";
                            }

                            echo "<div class=\"informazioni\">";
                            echo "<h2 class=\"titolo\">".$titolo."</h2>";
                            
                            
                            // Descrizione dell'evento
                            echo "<p class=\"descrizione\" id=\"descrizione".$id."\" style=\"text-align: left;\">".$descrizione."</p><p><a style=\"border: 1px solid black; border-radius: 5px; padding: 5px 10px; cursor:pointer; font-size: 18px; user-select: none;\" id=\"descrizioneBtn".$id."\">Espandi</a></p>\n";
                            ?>
                            <script type="text/javascript">  
                                $(document).ready(function(){
                                    if ($("#descrizione<?php echo $id; ?>").height() > 50) {
                                        $("#descrizioneBtn<?php echo $id; ?>").show();
                                        $("#descrizione<?php echo $id; ?>").css("text-overflow","ellipsis");
                                        $("#descrizione<?php echo $id; ?>").css("white-space","nowrap");
                                    } else {
                                        $("#descrizioneBtn<?php echo $id; ?>").hide();
                                    }
                                });
                                $("#descrizioneBtn<?php echo $id; ?>").click(function(){
                                    if (document.getElementById("descrizione<?php echo $id; ?>").style.textOverflow == "clip") {
                                        $("#descrizione<?php echo $id; ?>").css("text-overflow","ellipsis");
                                        $("#descrizione<?php echo $id; ?>").css("white-space","nowrap");
                                        $("#descrizioneBtn<?php echo $id; ?>").text("Espandi");
                                    } else {
                                        $("#descrizione<?php echo $id; ?>").css("text-overflow","clip");
                                        $("#descrizione<?php echo $id; ?>").css("white-space","pre-wrap");
                                        $("#descrizioneBtn<?php echo $id; ?>").text("Comprimi");
                                    }
                                });
                            </script>

                            <?php
                            echo "<i class=\"material-icons\">calendar_today</i> <b>Data:</b> ".date("d/m/Y", $data)."<br>\n";
                            echo "<i class=\"material-icons\">schedule</i> <b>Ora:</b> ".$ora." - ".$durata."<br>\n";
                            echo "<i class=\"material-icons\">place</i> <b>Luogo:</b> ".$luogo."<br>\n";
                            if (isset($tipo) && ($tipo != "")) {
                                echo "<i class=\"material-icons\">event</i> <b>Categoria:</b> ".$tipo."<br>\n";
                            }
                            
                            
                            if ($classe != "Nessuna classe selezionata") {
                                echo "<p class=\"descrizione\" style=\"margin-top:0; text-overflow: ellipsis; text-align: left;\" id=\"classe".$id."\"><i class=\"material-icons\">school</i> <b>Classe interessata:</b> ".$classe."</p><p><a style=\"border: 1px solid black; border-radius: 5px; padding: 5px 10px; cursor: pointer; font-size: 18px; user-select: none;\" id=\"classeBtn".$id."\">Mostra tutte le classi</a></p>\n";
                            }
                            ?>
                            <script type="text/javascript">  
                                $(document).ready(function(){
                                    if ($("#classe<?php echo $id; ?>").height() > 50) {
                                        $("#classeBtn<?php echo $id; ?>").show();
                                        $("#classe<?php echo $id; ?>").css("white-space","nowrap");
                                    } else {
                                        $("#classeBtn<?php echo $id; ?>").hide();
                                    }
                                });
                                $("#classeBtn<?php echo $id; ?>").click(function(){
                                    if (document.getElementById("classe<?php echo $id; ?>").style.whiteSpace == "normal") {
                                        $("#classe<?php echo $id; ?>").css("white-space","nowrap");
                                        $("#classeBtn<?php echo $id; ?>").text("Mostra tutte le classi");
                                    } else {
                                        $("#classe<?php echo $id; ?>").css("white-space","normal");
                                        $("#classeBtn<?php echo $id; ?>").text("Mostra alcune classi");
                                    }
                                });
                            </script>
                            
                            <?php
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
                                echo "<table class='file_viewer_single'>";
                                echo "<tr>";
                                echo "<td><img src='".$file_icon."' align='center' draggable='false' height='30' style='margin-right: 15px;'></td><td>".$link_foto_video."</td><td><a style='margin-left: 25px;' href='files/".$link_foto_video."' class='material-icons' download>download</a></td><td><a style='margin-right: 10px;' href='files/".$link_foto_video."' class='material-icons' target='_blank'>visibility</a></td>";
                                echo "</tr>";
                                echo "</table>";
                            }
                            
                            
                            // Pulsante per prenotare l'evento
                            if ($link_prenotazione != null || $link_prenotazione != "") {
                                echo "<a href=\"".$link_prenotazione."\" target=\"_blank\" title=\"Prenota evento\"><button class=\"prenotaBtn\">Accedi alla videoconferenza</button></a>";
                            }
                            echo "</div>";
                        }
                    }
                }
            } elseif (isset($str_data) && is_numeric($str_data)) {
                if ($_GET['classe'] == "" || !isset($_GET['classe'])) {
                    $sql = "SELECT * FROM planner WHERE data=$str_data ORDER BY ora_inizio";
                } else {
                    $sql = "SELECT * FROM planner WHERE data=$str_data AND classe LIKE '%".$_GET['classe']."%' ORDER BY ora_inizio";
                }
                $result = mysqli_query($conn,$sql) or die (mysqli_error($conn));
            ?>
            <h1><?php echo "Eventi del ".$data; ?></h1>
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

                    // Metto il logo "Chiuso" nell'header se l'evento di oggi dice che la scuola e' chiusa
                    if ($tipo == "Chiusura" && date("d/m/Y", $data) == date("d/m/Y", time())) {
                        oggiChiuso();
                        $changing = "";
                        if ($_SESSION['session_permessi_eventoo'] == "administration" || $_SESSION['session_permessi_eventoo'] == "maintenance" || $organizzatore == $_SESSION['session_nome_eventoo']." ".$_SESSION['session_cognome_eventoo']) {
                            $changing = "<p><a href=\"../modifica/?id=$id\" class=\"changeBtn\">Modifica</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href=\"../elimina/?id=$id&organizzatore=$organizzatore&data=$str_data\" class=\"changeBtn\">Elimina</a></p>";
                        }
                        echo "<div style='width: 80%; border: 3.5px solid #CC0001; border-radius: 5px; padding: 35px 50px; background-color: #ffdddd; background-image: url(files/alert.png); background-repeat: no-repeat; background-size: 30px 30px; background-position: 50px 35px;'><h2 style='margin-left: 40px;'>Oggi la scuola &egrave; chiusa</h2><p style='font-size: 18px; margin-top: 30px;'>".$descrizione."</p><p style='margin-top: 15px;'><b>Data:</b> ".date("d/m/Y", $data)."</p>".$changing."</div>";
                    } else {

                        //echo "<section style='margin-top: 40px; overflow: hidden;'>";
                        echo "<div class=\"informazioni\">";
                        echo "<h2 class=\"titolo\">".$titolo."</h2>\n";
                        
                        // Descrizione dell'evento
                        echo "<p class=\"descrizione\" id=\"descrizione".$id."\" style=\"text-overflow: clip; text-align: left;\">".$descrizione."</p><p><a style=\"border: 1px solid black; border-radius: 5px; padding: 5px 10px; cursor: pointer; font-size: 18px; user-select: none;\" id=\"descrizioneBtn".$id."\">Espandi</a></p>\n";
                        ?>
                        <script type="text/javascript">  
                            $(document).ready(function(){
                                if ($("#descrizione<?php echo $id; ?>").height() > 50) {
                                    $("#descrizioneBtn<?php echo $id; ?>").show();
                                    $("#descrizione<?php echo $id; ?>").css("text-overflow","ellipsis");
                                    $("#descrizione<?php echo $id; ?>").css("white-space","nowrap");
                                } else {
                                    $("#descrizioneBtn<?php echo $id; ?>").hide();
                                }
                            });
                            $("#descrizioneBtn<?php echo $id; ?>").click(function(){
                                if (document.getElementById("descrizione<?php echo $id; ?>").style.textOverflow == "clip") {
                                    $("#descrizione<?php echo $id; ?>").css("text-overflow","ellipsis");
                                    $("#descrizione<?php echo $id; ?>").css("white-space","nowrap");
                                    $("#descrizioneBtn<?php echo $id; ?>").text("Espandi");
                                } else {
                                    $("#descrizione<?php echo $id; ?>").css("text-overflow","clip");
                                    $("#descrizione<?php echo $id; ?>").css("white-space","pre-wrap");
                                    $("#descrizioneBtn<?php echo $id; ?>").text("Comprimi");
                                }
                            });
                        </script>

                        <?php
                        echo "<i class=\"material-icons\">schedule</i> <b>Ora:</b> ".$ora_inizio." - ".$ora_fine."<br>\n";
                        echo "<i class=\"material-icons\">place</i> <b>Luogo:</b> ".$luogo."<br>\n";
                        if (isset($tipo) && ($tipo != "")) {
                            echo "<i class=\"material-icons\">event</i> <b>Categoria:</b> ".$tipo."<br>\n";
                        }
                        if ($classe != "Nessuna classe selezionata") {
                            echo "<p class=\"descrizione\" style=\"margin-top:0; text-overflow: ellipsis; text-align: left;\" id=\"classe".$id."\"><i class=\"material-icons\">school</i> <b>Classe interessata:</b> ".$classe."</p><p><a style=\"border: 1px solid black; border-radius: 5px; padding: 5px 10px; cursor: pointer; font-size: 18px; user-select: none;\" id=\"classeBtn".$id."\">Mostra tutte le classi</a></p>\n";
                        }
                        ?>
                        <script type="text/javascript">  
                            $(document).ready(function(){
                                if ($("#classe<?php echo $id; ?>").height() > 50) {
                                    $("#classeBtn<?php echo $id; ?>").show();
                                    $("#classe<?php echo $id; ?>").css("white-space","nowrap");
                                } else {
                                    $("#classeBtn<?php echo $id; ?>").hide();
                                }
                            });
                            $("#classeBtn<?php echo $id; ?>").click(function(){
                                if (document.getElementById("classe<?php echo $id; ?>").style.whiteSpace == "normal") {
                                    $("#classe<?php echo $id; ?>").css("white-space","nowrap");
                                    $("#classeBtn<?php echo $id; ?>").text("Mostra tutte le classi");
                                } else {
                                    $("#classe<?php echo $id; ?>").css("white-space","normal");
                                    $("#classeBtn<?php echo $id; ?>").text("Mostra alcune classi");
                                }
                            });
                        </script>
                        
                        <?php
                        // Se e' stata caricata un'immagine come locandina, si mostra l'immagine; altrimenti non si mostra niente
                        if ($link_locandina == "") {
                            $link_locandina = "locandina_default.png";
                        }

                        // Locandina dell'evento
                        if ($link_locandina != "locandina_default.png") {
                            $estensione = substr($link_locandina, -4);
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

                            echo "<p style='margin-bottom: 5px;'><i class=\"material-icons\">attachment</i><b>Allegati</b></p>";
                            echo "<table class='file_viewer' style='margin-bottom: 30px; width: 100%;'>";
                            echo "<tr>";
                            echo "<td><img src='".$file_icon."' align='center' draggable='false' height='30' style='margin-right: 15px;'></td><td style='font-size: 16px; letter-spacing: normal; width: 90%;'>".$link_locandina."</td><td style='width: 50px; text-align: center;'><a style='margin-left: 25px; line-height: 1 !important; font-family: Material Icons;' href='files/".$link_locandina."' class='material-icons' download>download</a></td><td style='width: 50px; text-align: center;'><a style='margin-right: 10px; line-height: 1 !important; font-family: Material Icons;' href='files/".$link_locandina."' class='material-icons' target='_blank'>visibility</a></td>";
                            echo "</tr>";
                            echo "</table>";
                        }

                        
                        
                        // Pulsante per prenotare l'evento
                        if ($link_videoconferenza != null || $link_videoconferenza != "") {
                            echo "<a href=\"".$link_videoconferenza."\" target=\"_blank\"><button class=\"prenotaBtn\">Accedi alla videoconferenza</button></a>";
                        }

                        if ($_SESSION['session_permessi_eventoo'] == "administration" || $_SESSION['session_permessi_eventoo'] == "maintenance" || $organizzatore == $_SESSION['session_nome_eventoo']." ".$_SESSION['session_cognome_eventoo']) {
                            echo "<p><a href=\"../modifica/?id=$id\" class=\"changeBtn\">Modifica</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href=\"../elimina/?id=$id&organizzatore=$organizzatore&data=$str_data\" class=\"changeBtn\">Elimina</a></p>";
                            echo "<b>Ultima modifica:</b> ".$data_modifica;
                        }

                        echo '</div>';
                        //echo '</section>';
                    }
                }
            }
        }
            ?>
        </div>
        <br><br><br>
        <?php
            include '../components/footer.php';

            function oggiChiuso() {
                echo "<script>document.getElementById('logo').src = '../logo_chiuso.png'; document.getElementById('logo').style.height = '72.5px';</script>";
            }
        ?>

        <?php
            /*$today = date("j m");
            if ($today == "28 01" && $_COOKIE['hihihiha'] != "false") {
        ?>
        <script>
            var easterScreen = document.createElement("div");
            easterScreen.style.width = "100%";
            easterScreen.style.height = "100%";
            easterScreen.style.position = "fixed";
            easterScreen.style.top = "0";
            easterScreen.style.left = "0";
            easterScreen.style.right = "0";
            easterScreen.style.bottom = "0";
            easterScreen.style.zIndex = "500";
            easterScreen.style.backgroundColor = "#410810";

            const day2 = new Date();
            day2.setTime(day2.getTime() + (1*24*60*60*1000));
            let expires = "expires=" + day2.toUTCString();
            document.cookie = "hihihiha=false;" + expires + ";path=/";

            easterScreen.innerHTML = "<button onclick='location.href=\"?classe=<?php echo $_GET['classe']; ?>&day=<?php echo $str_data; ?>\";' style='background: transparent; border: 2.5px solid white; font-weight: bold; color: #ffffff; border-radius: 12px; font-size: 20px; margin: auto; cursor: pointer; padding: 5px 30px;'>Vedi gli eventi&nbsp;&nbsp;&nbsp;&nbsp;&#10095;</button>";
            document.body.appendChild(easterScreen);
        </script>
        <?php
            }*/
        ?>
    </body>
</html>
