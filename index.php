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
            content: '\25BC';
            font-size: 12px;
            color: #777;
            font-weight: bold;
            float: right;
            margin-left: 5px;
            }

            .active:after {
            content: "\25B2";
            }

            .panel {
            padding: 0 18px;
            background-color: var(--color-accordion-bg);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
            }

            .panel a {color: black; text-decoration: none; transition: 0.2s; padding: 5px;}
            .panel a:hover {background: rgba(255, 255, 255, 0.4);}
        </style>
    </head>
    <body>
        <?php
            $base_url = "";
            include "components/header.php";
        ?>

        <!-- Sottomenu -->
        <div class="second_menu">
            <div class="second_menu_btn" <?php if ($view != "classi") {echo "id=\"selected\"";} ?> onclick="location.href='?view=';">Eventi dell'Istituto</div>
            <div class="second_menu_btn" <?php if ($view == "classi") {echo "id=\"selected\"";} ?> onclick="location.href='?view=classi';">Eventi per classe</div>
        </div>

        <section class="content_default">
            
            <?php
            // Siamo nella sezione "Eventi per classe"
            if ($view == "classi") {
                echo "<h1 style='font-size: 32px; margin-bottom: 40px; float: left;'>Eventi per classe</h1>";
            ?>
                <div style="float: right;">
                    <select id='filtraSede' style='width: 210px; height: 30px; margin-right: 20px;' onchange='filtraSede()'>
                        <option <?php if(!isset($_GET['sede'])) {echo "selected";} ?>></option>
                        <option <?php if($_GET['sede'] == "Balzan") {echo "selected";} ?>>Balzan</option>
                        <option <?php if($_GET['sede'] == "Einaudi") {echo "selected";} ?>>Einaudi</option>
                        <option <?php if($_GET['sede'] == "Medie") {echo "selected";} ?>>Medie</option>
                    </select>
                </div>
            <?php
                echo "<script>function filtraSede() {var filtraSede = document.getElementById(\"filtraSede\"); location.href='?view=classi&sede=' + filtraSede.options[filtraSede.selectedIndex].text;}</script>";
                $db = 'eventoo_users';
                $conn = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());

                if (isset($_GET['sede']) && $_GET['sede'] != "") {
                    $result = mysqli_query($conn,"SELECT * FROM classi WHERE sede='".cripta($_GET['sede'], "encrypt")."' ORDER BY indirizzo") or die (mysqli_error($conn));
                } else {
                    $result = mysqli_query($conn,"SELECT * FROM classi ORDER BY indirizzo") or die (mysqli_error($conn));
                }
                
                $row_cnt = mysqli_num_rows($result);
                $conta = 0;

                // Se non ci sono classi registrate
                if ($row_cnt == 0) {
                    echo "<div style='margin-top: 120px; text-align: center; font-size: 30px;'><i class='material-icons' style='font-size: 60px;'>group_off</i><br />Nessuna classe registrata</div>";
                } else {
                    // Variabile che contiene il nome di tutte le classi per mostrare gli eventi in base all'indirizzo e non alla singola classe
                    $indirizzo = "";
                    while($row = mysqli_fetch_row($result)) {
                        // Contatore per sapere se sono arrivato all'ultimo elemento
                        $conta++;
                        // Se la classe attuale appartiene allo stesso indirizzo della classe precedente
                        if ($row[1] == $indirizzo_prec) {
                            // Metto un separatore e aggiungo la classe attuale nello stesso panel di quella precedente
                            echo "&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp;<a href='home?classe=".cripta($row[0], "decrypt")."'>".cripta($row[0], "decrypt")."</a>";
                            $indirizzo .= ",".cripta($row[0], "decrypt");
                            /* Altrimenti chiudo il panel della classe precedente e ne apro uno nuovo con
                            l'indirizzo della classe attuale */
                        } else {
                            // Risolve il problema che causava la comparsa del link "Tutte le classi di indirizzo" tra il titolo e il 1° pulsante dell'accordion
                            // Se la variabile $indirizzo e' vuota, allora non appartiene a nessun indirizzo
                            if ($indirizzo != "") {
                                echo "&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp;<a href='home?classe=".$indirizzo."'>Tutte le classi di indirizzo</a>";
                                $indirizzo = "";
                            }
                            echo "</div>";
                            echo "<button class=\"accordion\">".cripta($row[1], "decrypt")."</button>\n";
                            echo "<div class=\"panel\">\n";
                            echo "<a href='home?classe=".cripta($row[0], "decrypt")."'>".cripta($row[0], "decrypt")."</a>";
                            $indirizzo .= cripta($row[0], "decrypt");
                        }
                        // Se sono arrivato all'ultima classe chiudo il panel di questa classe
                        if ($conta == $row_cnt) {
                            // Risolve il problema che non mostra il link "Tutte le classi di indirizzo" nell'ultimo panel dell'accordion
                            echo "&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp;<a href='home?classe=".$indirizzo."'>Tutte le classi di indirizzo</a>";
                            echo "</div>";
                        }
                        $indirizzo_prec = $row[1];
                    }
                }
            } else {
            ?>

            <!-- Calendario di tutte le classi -->
            <h1 style='font-size: 32px; margin-bottom: 40px;'>Eventi dell&apos;Istituto</h1>
            <div class="tabel" style="margin-left: auto; margin-right: auto;">
                <?php
                function ShowCalendar($m,$y)
                {
                    if ((!isset($_GET['d']))||($_GET['d'] == ""))
                    {
                        $m = date('n');
                        $y = date('Y');
                    } else {
                        $m = (int)strftime( "%m" ,(int)$_GET['d']);
                        $y = (int)strftime( "%Y" ,(int)$_GET['d']);
                        $m = $m;
                        $y = $y;
                    }

                    $precedente = mktime(0, 0, 0, $m -1, 1, $y);
                    $successivo = mktime(0, 0, 0, $m +1, 1, $y);

                    $nomi_mesi = array(
                        "Gennaio",
                        "Febbraio",
                        "Marzo",
                        "Aprile",
                        "Maggio",
                        "Giugno", 
                        "Luglio",
                        "Agosto",
                        "Settembre",
                        "Ottobre",
                        "Novembre",
                        "Dicembre"
                    );
                    $nomi_giorni = array(
                        "Lunedì",
                        "Martedì",
                        "Mercoledì",
                        "Giovedì",
                        "Venerdì",
                        "Sabato",
                        "Domenica"
                    );

                    $cols = 7;
                    $days = date("t",mktime(0, 0, 0, $m, 1, $y)); 
                    $lunedi= date("w",mktime(0, 0, 0, $m, 1, $y));
                    if($lunedi==0) $lunedi = 7;
                    echo "<table style=\"margin-left: auto; margin-right: auto;\">\n"; 
                    echo "<tr>\n
                    <th class=\"mese\" colspan=\"2\">\n
                    <a class=\"cambia_mese material-icons\" title=\"Mese precedente\" style=\"padding-left: 10px; padding-right: 0;\" href=\"?d=".$precedente."\">arrow_back_ios</a>\n
                    </th>\n
                    <th class=\"mese\" colspan=\"3\">\n
                    " . $nomi_mesi[$m-1] . " " . $y . "
                    </th>\n
                    <th class=\"mese\" colspan=\"2\">
                    <a class=\"cambia_mese material-icons\" title=\"Mese successivo\" href=\"?d=".$successivo."\">arrow_forward_ios</a>\n
                    </th>\n
                    </tr>\n";
                    foreach($nomi_giorni as $v)
                    {
                        echo "<th>".$v."</th>\n";
                    }
                    echo "</tr>";

                    for($j = 1; $j<$days+$lunedi; $j++)
                    {
                        if($j%$cols+1==0)
                        {
                            echo "<tr>\n";
                        }
                        
                        // Se il mese non inizia il lunedi', si aggiungono altre celle per riempire i buchi
                        if($j<$lunedi)
                        {
                            echo "<td class=\"extra_day\"> </td>\n";
                        } else {
                            $day= $j-($lunedi-1);
                            $data = strtotime(date($y."-".$m."-".$day));
                            $oggi = strtotime(date("Y-m-d"));
                            $contenuto = "";
                            
                            include 'config.php';
                            $db = 'eventoo_planner';
                            $conn_calendar = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());
                            
                            $result = mysqli_query($conn_calendar,"SELECT data FROM planner") or die (mysqli_error($conn_calendar));

                            if(mysqli_num_rows($result) > 0)
                            {
                                while($fetch = mysqli_fetch_array($result))
                                {
                                    $str_data = $fetch['data'];
                                    if ($str_data == $data)
                                    {
                                        $sql = "SELECT * FROM planner WHERE data=$str_data";
                                        $result = mysqli_query($conn_calendar,$sql) or die (mysqli_error($conn_calendar));
                                        
                                        if(mysqli_num_rows($result) == 1)
                                        {
                                            while($fetch = mysqli_fetch_array($result))
                                            {
                                                $id = stripslashes($fetch['id']);
                                                $titolo = stripslashes($fetch['titolo']);
                                                $data_evento = date("d-m-Y", $fetch['data']);
                                                $ora = stripslashes($fetch['ora_inizio']);
                                                $ora2 = stripslashes($fetch['ora_fine']);
                                                $classe = stripslashes($fetch['classe']);
                                                $type = stripslashes($fetch['categoria']);
                                                $filenameDelete = stripslashes($fetch['link_locandina']);
                                                $validity = stripslashes($fetch['validity']);
                                            }

                                            // Elimino i file degli eventi salvati da 2 o piu' anni
                                            if ($validity <= date('Ymd') - 20000) {
                                                unlink("evento/files/$filenameDelete");
                                            }
                            
                                            // Elimino record salvati da + di 2 anni
                                            $deleteSQL = mysqli_query($conn_calendar,"DELETE from planner WHERE validity <= CURDATE() - 20000") or die (mysqli_error($conn_calendar));
                                        
                                            $contenuto = "<div class=\"nota\">
                                                            <a href=\"evento/?id=$id\">
                                                            <p class=\"title\" title=\"".$titolo."\">".$titolo."</p>
                                                            <p class=\"info_nota\">
                                                            <span title=\"".$ora." - ".$ora2."\"><i class=\"material-icons\">schedule</i>".$ora." - ".$ora2."</span><br>
                                                            <span title=\"".$classe."\"><i class=\"material-icons\">school</i>".$classe."</span>
                                                            </p>
                                                            </a>
                                                            </div>";
                                        }
                                        $num_rows = mysqli_num_rows($result);
                                        if($num_rows > 1)
                                            {
                                                while($fetch = mysqli_fetch_array($result))
                                                {
                                                    $id = stripslashes($fetch['id']);
                                                    $titolo = stripslashes($fetch['titolo']);
                                                    $data_evento = date("d-m-Y", $fetch['data']);
                                                }
                                            
                                                $contenuto = "<a href=\"evento/?day=$str_data&classe=".$_GET['classe']."\" title=\"Vedi tutti\"><div class=\"nota multipla\">Sono presenti ".$num_rows." eventi...</div></a>";
                                        }
                                    
                                    
                                    }
                                }
                            }

                            if($data == $oggi)
                            {
                                echo "<td class=\"oggi\" onclick=\"newEvent(".$data.")\"><span class=\"data\">".$day."</span>".$contenuto."</td>";
                            } else {
                                echo "<td onclick=\"newEvent(".$data.")\"><span class=\"data\">".$day."</span>".$contenuto."</td>";
                            }
                        }

                        if($j%$cols==0)
                        {
                        echo "</tr>";
                        }
                    }

                    // Se le celle dell'ultima riga sono meno di 7, se ne aggiungono altre
                    if ($j<42) {
                        // 36 = (7 colonne * 5 righe) + 1 perche' i e' minore, quindi deve arrivare fino a 35
                        // 36 - $day = dal numero massimo di giorni possibili (35 + 1 di prima) tolgo i giorni del mese in corso
                        // 36 - $day - $lunedi = dai giorni restanti tolgo quelli del mese prima e ottengo quelli del mese successivo
                        // (giorni totali - giorni mese attuale - giorni mese precedente = giorni mese successivo)
                        for($i=0; $i<36-$day-$lunedi;$i++) {
                            echo "<td class=\"extra_day\"></td>";
                        }
                    } elseif ($j>=42) {
                        echo "<tr>";
                        for($i=0; $i<43-$day-$lunedi;$i++) {
                            echo "<td class=\"extra_day\"></td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                }
                
                // Richiamo la funzione del calendario
                ShowCalendar(date("m"),date("Y"));
                ?>
                <script>
                    function newEvent(data) {
                        location.href = "nuovo?data=" + data;
                    }
                </script>
            </div>
            <?php
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