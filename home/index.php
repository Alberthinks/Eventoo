<?php
session_start();
include "../default.php";
$username = $_SESSION['session_user_eventoo'];
$nome = $_SESSION['session_nome_eventoo'];
$cognome = $_SESSION['session_cognome_eventoo'];
$ao = $_SESSION['session_ao_eventoo'];

// Visualizzo gli eventi della classe richiesta
$classe = $_GET['classe'];
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="all" />
        <meta name="revisit-after" content="8" />
        <meta name="author" content="Albertin Emanuele">
        <meta name="title" content="Eventoo - Planner per la scuola">
        <meta name="description" content="Planner degli eventi proposti dall'I.I.S. 'Primo Levi' di Badia Polesine (RO).">
        <meta name="keywords" content="planner, scuola, primo levi, eventi, agenda">
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="<?php echo $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']; ?>">
        <meta property="og:title" content="Eventoo - Planner per la scuola">
        <meta property="og:description" content="Planner degli eventi proposti dall'I.I.S. 'Primo Levi' di Badia Polesine (RO).">
        <meta property="og:image" content="../img/icon/apple-touch-icon.png">
        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="<?php echo $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']; ?>">
        <meta property="twitter:title" content="Eventoo - Planner per la scuola">
        <meta property="twitter:description" content="Planner degli eventi proposti dall'I.I.S. 'Primo Levi' di Badia Polesine (RO).">
        <meta property="twitter:image" content="../img/icon/apple-touch-icon.png">
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
        <title>Planner | <?php echo $nome_app; ?></title>
        <!-- CSS -->
        <link rel="stylesheet" href="../css/default.css" type="text/css">
        <link rel="stylesheet" href="../css/style.css" type="text/css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <!-- Scorciatoie da tastiera -->
        <script>
            document.addEventListener("keydown", function(event){
                if (event.key == "ArrowLeft"){
                    document.getElementById("mesePrec").click();
                }
                if (event.key == "ArrowRight"){
                    document.getElementById("meseSucc").click();
                }
            });
        </script>
    </head>
    <body>
        <!-- Header -->
        <?php
            $base_url = "../";
            include '../components/header.php';
        ?>
        <center>
            <section class="container">
            <h1>Calendario <?php echo $classe; ?></h1>
            <div class="tabel">
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
                    echo "<table>\n"; 
                    echo "<tr>\n
                    <th class=\"mese\" colspan=\"2\">\n
                    <a class=\"cambia_mese material-icons\" title=\"Mese precedente\" id=\"mesePrec\" style=\"padding-left: 10px; padding-right: 0;\" href=\"?d=" . $precedente . "&classe=".$_GET['classe']."\">arrow_back_ios</a>\n
                    </th>\n
                    <th class=\"mese\" colspan=\"3\">\n
                    " . $nomi_mesi[$m-1] . " " . $y . "
                    </th>\n
                    <th class=\"mese\" colspan=\"2\">
                    <a class=\"cambia_mese material-icons\" title=\"Mese successivo\" id=\"meseSucc\" href=\"?d=" . $successivo . "&classe=".$_GET['classe']."\">arrow_forward_ios</a>\n
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
                            $contenuto = " ";
                            include '../config.php';
                            
                            $db = 'eventoo_planner';
                            $conn = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());
                            
                            $result = mysqli_query($conn,"SELECT data FROM planner WHERE classe LIKE '%".$_GET['classe']."%'") or die (mysqli_error($conn));

                            if(mysqli_num_rows($result) > 0)
                            {
                                while($fetch = mysqli_fetch_array($result))
                                {
                                    $str_data = $fetch['data'];
                                    if ($str_data == $data)
                                    {
                                        $sql = "SELECT * FROM planner WHERE data=$str_data AND classe LIKE '%".$_GET['classe']."%'";
                                        $result = mysqli_query($conn,$sql) or die (mysqli_error($conn));
                                        
                                        if(mysqli_num_rows($result) == 1)
                                        {
                                            while($fetch = mysqli_fetch_array($result))
                                            {
                                                $id = stripslashes($fetch['id']);
                                                $titolo = stripslashes($fetch['titolo']);
                                                $data_evento = date("d-m-Y", $fetch['data']);
                                                $ora = stripslashes($fetch['ora_inizio']);
                                                $ora2 = stripslashes($fetch['ora_fine']);
                                                $luogo = stripslashes($fetch['stanza']);
                                                $type = stripslashes($fetch['categoria']);
                                                $filenameDelete = stripslashes($fetch['link_locandina']);
                                                $validity = stripslashes($fetch['validity']);
                                            }

                                            // Elimino i file degli eventi salvati da 2 o piu' anni
                                            if ($validity <= date('Ymd') - 20000) {
                                                unlink("evento/files/$filenameDelete");
                                            }
                            
                                            // Elimino record salvati da + di 2 anni
                                            $deleteSQL = mysqli_query($conn,"DELETE from planner WHERE validity <= CURDATE() - 20000") or die (mysqli_error($conn));
                                        
                                            $contenuto = "<div class=\"nota\">
                                                            <a href=\"../evento/?id=$id\">
                                                            <p class=\"title\" title=\"".$titolo."\">".$titolo."</p>
                                                            <p class=\"info_nota\">
                                                            <span title=\"".$ora." - ".$ora2."\"><i class=\"material-icons\">schedule</i>".$ora." - ".$ora2."</span><br>
                                                            <span title=\"".$luogo."\"><i class=\"material-icons\">place</i>".$luogo."</span>
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
                                            
                                                $contenuto = "<a href=\"../evento/?day=$str_data&classe=".$_GET['classe']."\" title=\"Vedi tutti\"><div class=\"nota multipla\">Sono presenti ".$num_rows." eventi...</div></a>";
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
                    }
                    echo "</table>";
                }
                
                // Richiamo la funzione del calendario
                ShowCalendar(date("m"),date("Y"));
                ?>
            </div>
            </section>
        </center>

        <script>
            function newEvent(data) {
                location.href = "../nuovo?data=" + data + "&classe=<?php echo $classe; ?>";
            }
        </script>
        
        <br><br><br>
        <?php
        include '../components/footer.php';
        ?>
    </body>
</html>
