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

                        // Se e' stata caricata un'immagine come locandina, si mostra l'immagine; altrimenti non si mostra niente
                        if ($link_foto_video == "") {
                            $link_foto_video = "locandina_default.png";
                        }

                        echo "<div class=\"informazioni\">";
                        echo "<h2 class=\"titolo\">".$titolo."</h2>";
                        echo "<span id=\"dots\" style=\"float: right; position: relative; top: 40px; right: 15px;\">...</span><p class=\"descrizione\" id=\"descrizione\">".$descrizione."</p><p><a id=\"descrizioneBtn\" style=\"border: 1px solid black; border-radius: 5px; padding: 5px 10px;\" href=\"#\">Espandi</a></p>\n";
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
                        if (isset($tipo) && ($tipo != "")) {
                            echo "<i class=\"material-icons\">event</i> <b>Categoria:</b> ".$tipo."<br>\n";
                        }
                        
                        echo "<span id=\"dots2".$id."\" style=\"float: right; position: relative; top: 40px; right: 15px;\">...</span><p class=\"descrizione\" id=\"classe".$id."\"><i class=\"material-icons\">school</i> <b>Classe interessata:</b> ".$classe."</p><p><a style=\"border: 1px solid black; border-radius: 5px; padding: 5px 10px;\" id=\"classeBtn".$id."\" href=\"#\">Mostra tutte le classi</a></p>\n";
                        ?>
                        <script type="text/javascript">  
                            $(document).ready(function(){
                                if ($("#classe<?php echo $id; ?>").height() > 50) {
                                    $("#classeBtn<?php echo $id; ?>").show();
                                    $("#dots2<?php echo $id; ?>").show();
                                    $("#classe<?php echo $id; ?>").css("height","35px");
                                } else {
                                    $("#classeBtn<?php echo $id; ?>").hide();
                                    $("#dots2<?php echo $id; ?>").hide();
                                }
                            });
                            $("#classeBtn<?php echo $id; ?>").click(function(){
                                if ($("#classe<?php echo $id; ?>").height() > 35) {
                                    $("#classe<?php echo $id; ?>").css("height","35px");
                                    $("#dots2<?php echo $id; ?>").show();
                                    $("#classe<?php echo $id; ?>").css("text-overflow","ellipsis");
                                    $("#classeBtn<?php echo $id; ?>").text("Mostra tutte le classi");
                                } else {
                                    $("#classe<?php echo $id; ?>").css("height","auto");
                                    $("#dots2<?php echo $id; ?>").hide();
                                    $("#classe<?php echo $id; ?>").css("text-overflow","clip");
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
                            echo "<table class='file_viewer'>";
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


                    echo "<section style='margin-top: 40px; overflow: hidden;'>";
                    echo "<div class=\"informazioni\">";
                    echo "<h2 class=\"titolo\">".$titolo."</h2>\n";
                    // Descrizione dell'evento
                    echo "<span id=\"dots".$id."\" style=\"float: right; position: relative; top: 40px; right: 15px;\">...</span><p class=\"descrizione\" id=\"descrizione".$id."\">".$descrizione."</p><p><a style=\"border: 1px solid black; border-radius: 5px; padding: 5px 10px;\" id=\"descrizioneBtn".$id."\" href=\"#\">Espandi</a></p>\n";
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
                    echo "<i class=\"material-icons\">schedule</i> <b>Ora:</b> ".$ora_inizio." - ".$ora_fine."<br>\n";
                    echo "<i class=\"material-icons\">place</i> <b>Luogo:</b> ".$luogo."<br>\n";
                    if (isset($tipo) && ($tipo != "")) {
                        echo "<i class=\"material-icons\">event</i> <b>Categoria:</b> ".$tipo."<br>\n";
                    }
                    echo "<span id=\"dots2".$id."\" style=\"float: right; position: relative; top: 40px; right: 15px;\">...</span><p class=\"descrizione\" id=\"classe".$id."\"><i class=\"material-icons\">school</i> <b>Classe interessata:</b> ".$classe."</p><p><a style=\"border: 1px solid black; border-radius: 5px; padding: 5px 10px;\" id=\"classeBtn".$id."\" href=\"#\">Mostra tutte le classi</a></p>\n";
                    ?>
                    <script type="text/javascript">  
                        $(document).ready(function(){
                            if ($("#classe<?php echo $id; ?>").height() > 50) {
                                $("#classeBtn<?php echo $id; ?>").show();
                                $("#dots2<?php echo $id; ?>").show();
                                $("#classe<?php echo $id; ?>").css("height","35px");
                            } else {
                                $("#classeBtn<?php echo $id; ?>").hide();
                                $("#dots2<?php echo $id; ?>").hide();
                            }
                        });
                        $("#classeBtn<?php echo $id; ?>").click(function(){
                            if ($("#classe<?php echo $id; ?>").height() > 35) {
                                $("#classe<?php echo $id; ?>").css("height","35px");
                                $("#dots2<?php echo $id; ?>").show();
                                $("#classe<?php echo $id; ?>").css("text-overflow","ellipsis");
                                $("#classeBtn<?php echo $id; ?>").text("Mostra tutte le classi");
                            } else {
                                $("#classe<?php echo $id; ?>").css("height","auto");
                                $("#dots2<?php echo $id; ?>").hide();
                                $("#classe<?php echo $id; ?>").css("text-overflow","clip");
                                $("#classeBtn<?php echo $id; ?>").text("Mostra alcune classi");
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
                        
                        echo "<table class='file_viewer'>";
                        echo "<tr>";
                        echo "<td><img src='".$file_icon."' align='center' draggable='false' height='30' style='margin-right: 15px;'></td><td>".$link_locandina."</td><td><a style='margin-left: 25px;' href='files/".$link_locandina."' class='material-icons' download>download</a></td><td><a style='margin-right: 10px;' href='files/".$link_locandina."' class='material-icons' target='_blank'>visibility</a></td>";
                        echo "</tr>";
                        echo "</table>";
                    }
                    
                    
                    // Pulsante per prenotare l'evento
                    if ($link_videoconferenza != null || $link_videoconferenza != "") {
                        echo "<a href=\"".$link_videoconferenza."\" target=\"_blank\" title=\"Prenota evento\"><button class=\"prenotaBtn\">Accedi alla videoconferenza</button></a>";
                    }
                    echo "</div>";
                    echo '</section>';
                }
            }
        }
            ?>
        </div>
        <br><br><br>
        <?php
            include '../components/footer.php';
        ?>

        <?php
            $today = date("j m");
            if ($today == "25 01" && $_COOKIE['hihihiha'] != "false") {
        ?>
        <script>
            const d = new Date();
            let day = d.getDate();
            let month = d.getMonth();

            if (day == 25 && month == 0) {
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
                easterScreen.style.backgroundImage = "url('../img/sfondo_natale.png')";

                const day2 = new Date();
                day2.setTime(day2.getTime() + (1*24*60*60*1000));
                let expires = "expires=" + day2.toUTCString();
                document.cookie = "hihihiha=false;" + expires + ";path=/";

                easterScreen.innerHTML = "<button onclick='location.href=\"?classe=<?php echo $_GET['classe']; ?>&day=<?php echo $str_data; ?>\";' style='background: transparent; border: 2.5px solid white; font-weight: bold; color: #ffffff; border-radius: 12px; font-size: 20px; margin: auto; cursor: pointer; padding: 5px 30px;'>Vedi gli eventi&nbsp;&nbsp;&nbsp;&nbsp;&#10095;</button>";
                document.body.appendChild(easterScreen);
            }
        </script>
        <?php
            }
        ?>
    </body>
</html>
