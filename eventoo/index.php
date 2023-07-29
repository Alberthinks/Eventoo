<?php
session_start();

$view = $_GET['view'];

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
        <link rel="manifest" href="img/icon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="img/icon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <style>
            /* Contenuto della pagina */
            .content_default {padding: 163px 30px 40px 30px; font-size: 18px;}

            .accordion {
            background-color: #ddd;
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
            background-color: #ccc;
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
            background-color: #f0f0f0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
            }

            .panel a {color: black; text-decoration: none; transition: 0.2s; padding: 5px;}
            .panel a:hover {background: #ddd;}
        </style>
    </head>
    <body>
        <?php
            include "components/header_home.php";
        ?>

        <section class="content_default">
        <?php
        echo $view; ?>
        <?php

        // Siamo nella sezione "Classi"
        if ($view == "classi" || $view == null) {

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
        <br><br><br><br><br><br><br><br><br>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

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
        }
        include 'components/footer.php';
        ?>
    </body>
</html>