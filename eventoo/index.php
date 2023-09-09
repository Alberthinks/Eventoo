<?php
session_start();

//$view = $_GET['view'];

include 'default.php';
?>
<!DOCTYPE html>
    <html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home | Eventoo</title>
        <link rel="apple-touch-icon" sizes="57x57" href="img/icon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="img/icon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="img/icon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="img/icon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="img/icon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="img/icon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="img/icon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="img/icon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="img/icon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="img/icon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="img/icon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="img/icon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="img/icon/favicon-16x16.png">
        <link rel="manifest" href="manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="img/icon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <!-- CSS -->
        <link rel="stylesheet" href="css/default.css" type="text/css">
        <link rel="stylesheet" href="css/style.css" type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <style>
            /* Contenuto della pagina */
            .content_default {padding: 163px 30px 40px 30px; font-size: 18px;}

            .accordion {
            background-color: var(--color-accordion-btn);
            color: #444;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
            transition: 0.4s;
            }

            .active, .accordion:hover {
            background-color: var(--color-accordion-btn-active);
            }

            .accordion:after {
            content: '\2228';
            color: #777;
            font-weight: bold;
            float: right;
            margin-left: 5px;
            }

            .active:after {
            content: "\2227";
            }

            .panel {
            padding: 0 18px;
            background-color: var(--color-accordion-bg);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
            }

            .panel a {color: black; text-decoration: none; transition: 0.2s; padding: 5px;}
            .panel a:hover {background: #fff;}
        </style>
    </head>
    <body>
        <?php
            $base_url = "";
            include "components/header.php";
        ?>

        <section class="content_default">
        <?php

        // Siamo nella sezione "Classi"
        //if ($view != "stanze") {

            echo "<h1 style='font-size: 32px; margin-bottom: 40px;'>Eventi per classe</h1>";

            include 'config.php';
            $db = 'eventoo_users';
            $conn = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());

            $result = mysqli_query($conn,"SELECT * FROM classi ORDER BY indirizzo") or die (mysqli_error($conn));
            $row_cnt = mysqli_num_rows($result);
            $conta = 0;

            // Se non ci sono classi registrate
            if ($row_cnt == 0) {
                echo "<i class='material-icons' style='font-size: 40px;'>group_off</i><br />Nessuna classe registrata";
            } else {
                while($row = mysqli_fetch_row($result)) {
                    // Contatore per sapere se sono arrivato all'ultimo elemento
                    $conta++;
                    // Se la classe attuale appartiene allo stesso indirizzo della classe precedente
                    if ($row[1] == $indirizzo_prec) {
                        // Metto un separatore e aggiungo la classe attuale nello stesso panel di quella precedente
                        echo "&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp;<a href='home?classe=".cripta($row[0], "decrypt")."'>".cripta($row[0], "decrypt")."</a>";
                        /* Altrimenti chiudo il panel della classe precedente e ne apro uno nuovo con
                        l'indirizzo della classe attuale */
                    } else {
                        echo "</div>";
                        echo "<button class=\"accordion\">".cripta($row[1], "decrypt")."</button>\n";
                        echo "<div class=\"panel\">\n";
                        echo "<a href='home?classe=".cripta($row[0], "decrypt")."'>".cripta($row[0], "decrypt")."</a>";
                    }
                    // Se sono arrivato all'ultima classe chiudo il panel di questa classe
                    if ($conta == $row_cnt) {
                        echo "</div>";
                    }
                    $indirizzo_prec = $row[1];
                }
            }
            ?>
        </section>
        
        <script>
        var acc = document.getElementsByClassName("accordion");
        var i;

        for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight) {
            panel.style.maxHeight = null;
            } else {
            panel.style.maxHeight = "40px";   //panel.scrollHeight + "px";
            } 
        });
        }
        </script>
        <?php
        /*} else {
            echo "<h1 style='margin-bottom: 20px;'><i class='material-icons'>engineering</i> Sezione in realizzazione</h1>
                    <p>Questa sezione &egrave; ancora in fase di realizzazione. Quando sar&agrave; pronta e attivata la Prenotazione laboratori, la potrai trovare qui.<br>
                    Per saperne di pi&ugrave; contatta l'amministratore della piattaforma.</p>";
            echo "</section>";
        }*/
        include 'components/footer.php';
        ?>
    </body>
</html>
