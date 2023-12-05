<?php
session_start();
include "../default.php";

$id = $_GET['id'];
$changeImage = $_GET['changeImage'];

$username = $_SESSION['session_user_eventoo'];
$nome = $_SESSION['session_nome_eventoo'];
$cognome = $_SESSION['session_cognome_eventoo'];
$ao = $_SESSION['session_ao_eventoo'];
$permessi = $_SESSION['session_permessi_eventoo'];


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
        <title>Modifica evento | <?php echo $nome_app; ?></title>
        <!-- CSS -->
        <link rel="stylesheet" href="../nuovo/css/style.css" type="text/css">
        <link rel="stylesheet" href="../css/default.css" type="text/css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    </head>
    <body>
        <!-- Header -->
            <?php
            $base_url = "../";
            include '../components/header.php';

            if (isset($_SESSION['session_id_eventoo'])) {
                // Eliminare la locandina attuale per poterne caricare un'altra
                if ($changeImage == "yes") {
                    $sql_deleteImage = "UPDATE planner SET link_locandina='' WHERE id = '$id'";
                    $filenameDelete = $_GET['filenameDelete'];
                    if ($result = mysqli_query($conn,$sql_deleteImage) or die (mysqli_error($conn))) {
                        unlink("../evento/files/$filenameDelete");
                        echo "<script>location.href = \"?id=$id\";</script>";
                    }
                }

            if (isset($_POST['submit']) && $_POST['submit']=="Modifica") {

                // Prelevo dal form di modifica i dati (sia modificati che non)

                $post_id = $_POST['post_id'];

                $titolo = writeRecord($_POST['titolo']);
                $descrizione = writeRecord($_POST['descrizione']);
                $data = strtotime(writeRecord($_POST['data']));
                $ora_inizio = writeRecord($_POST['ora_inizio']);
                $ora_fine = writeRecord($_POST['ora_fine']);
                $classe2 = writeRecord($_POST['classe2']);
                $luogo = writeRecord($_POST['luogo']);
                $tipo = writeRecord($_POST['tipo']);
                $link_prenotazione = writeRecord($_POST['link_prenotazione']);
                $data_modifica = writeRecord($_POST['data_modifica']);

                // Creo un nuovo record nella tabella "accesses" (database "eventoo") per registrare la modifica dell'evento
                
                $myconn = mysqli_connect('localhost','eventooRootUser','QnBWQzlN-vVko9Egryb5b4&k1b4hghb2bj1jkj4$', 'eventoo') or die (mysqli_error());
                $timestamp = cripta(date('d/m/Y H:i:s', strtotime("now")), "encrypt");
                $action = cripta("Modifica dell'evento (id: $post_id) '$titolo' del ".date('d/m/Y', $data)." alle $ora", "encrypt");
                $ip = cripta($_SERVER['REMOTE_ADDR'], "encrypt");
                $uname = cripta($username, "encrypt");
                $name = cripta($nome, "encrypt");
                $cog = cripta($cognome, "encrypt");
                $mysql = "INSERT INTO accesses (username,nome,cognome,ip,azione,timestamp,validity) VALUES ('$uname', '$name','$cog','$ip','$action','$timestamp','$dataValidity')";

                // Se e' stata modificata la locandina (caricata una nuova immagine)
                if ($_FILES['locandina']['tmp_name'] != "") {
                    // Percorso della cartella dove mettere i file caricati dagli utenti
                    $uploaddir = '../evento/files/';
                    // Cartella temporanea del file da caricare
                    $userfile_tmp = $_FILES['locandina']['tmp_name'];
                    // Nome del file da caricare
                    $userfile_name = $_FILES['locandina']['name'];
                    // Dimensione del file da caricare
                    $userfile_size = $_FILES['locandina']['size'];
                    // Estensione del file da caricare
                    $userfile_extension = strtolower(pathinfo($userfile_name,PATHINFO_EXTENSION));

                    // Verifico se il file ha il formato corretto in base all'estensione
                    $filetypes = array("pdf", "doc", "docx", "txt", "pub");
                    if (!in_array($userfile_extension, $filetypes))
                    {
                        echo "I file di estensione <b>.".$userfile_extension."</b> non sono ammessi.<br>";
                        exit;
                    }

                    $sql_changeEvent = "UPDATE planner SET titolo='$titolo', descrizione='$descrizione', data='$data', ora_inizio='$ora_inizio', ora_fine='$ora_fine', classe='$classe2', stanza='$luogo', categoria='$tipo', link_videoconferenza='$link_prenotazione', link_locandina='$userfile_name', data_modifica='$data_modifica' WHERE id = '$post_id'";
                    
                    if (!file_exists($uploaddir.$userfile_name) && move_uploaded_file($userfile_tmp, $uploaddir.$userfile_name)) {  // Carico il file nella cartella delle locandine
                        if ($result = mysqli_query($conn,$sql_changeEvent) or die (mysqli_error($conn))) {
                            if ($rressultt = mysqli_query($myconn,$mysql) or die (mysqli_error($myconn))) {         // Salvo la modifica dell'evento nella console di amministrazione
                                echo "<script type=\"text/javascript\">location.replace(\"../\");</script>";
                            }
                        }
                        exit;
                    } else {
                        if (file_exists($uploaddir.$userfile_name)) {
                            echo "Errore: esiste gi&agrave; un file con lo stesso nome!";
                        } else {
                            echo "Errore nel caricamento!";
                        }
                        exit;
                    }
                }

                // Se NON e' stata modificata la locandina
                $sql_changeEvent = "UPDATE planner SET titolo='$titolo', descrizione='$descrizione', data='$data', ora_inizio='$ora_inizio', ora_fine='$ora_fine', stanza='$luogo', classe='$classe2', categoria='$tipo', link_videoconferenza='$link_prenotazione', data_modifica='$data_modifica' WHERE id = '$post_id'";

                if($result = mysqli_query($conn,$sql_changeEvent) or die (mysqli_error($conn))) {
                    if($rressultt = mysqli_query($myconn,$mysql) or die (mysqli_error($myconn))) {
                        echo "<script type=\"text/javascript\">location.replace(\"../\");</script>";
                    }
                }

            }

            // Prelevo dal database le informazioni sull'evento da modificare
            $query = mysqli_query($conn,"SELECT * FROM planner WHERE id = $id") or die (mysqli_error($conn));
            $fetch = mysqli_fetch_array($query) or die (mysqli_error());
            
            $titolo = stripslashes($fetch['titolo']);
            $descrizione = stripslashes($fetch['descrizione']);
            $data = date("Y-m-d", stripslashes($fetch['data']));
            $ora_inizio = stripslashes($fetch['ora_inizio']);
            $ora_fine = stripslashes($fetch['ora_fine']);
            $luogo = stripslashes($fetch['stanza']);
            $classe = stripslashes($fetch['classe']);
            $tipo = stripslashes($fetch['categoria']);
            $link_prenotazione = stripslashes($fetch['link_videoconferenza']);
            $link_foto_video = stripslashes($fetch['link_locandina']);

            date_default_timezone_set('Europe/Rome');
            ?>
        <div class="container">
            <!-- Titolo -->
            <h1>Modifica evento</h1>
            <form name="modifica" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
                <input type="hidden" name="data_modifica" value="<?php echo(date("d/m/Y h:i:s A",time())); ?>">
                <div class="main_container">
                    <div class="left_content">
                        <p>
                            <!-- Titolo evento -->
                            <div class="input-container">
                                <input
                                    type="text"
                                    id="titolo"
                                    name="titolo"
                                    class="long_input"
                                    value="<?php echo $titolo; ?>"
                                    aria-labelledby="label-titolo"
                                    oninput="manageTextInputStyle('titolo')"
                                    required
                                />
                                <label class="label" for="titolo" id="label-titolo">
                                    <div class="text">Titolo</div>
                                </label>
                            </div>
                        </p>
                        <p>
                            <!-- Descrizione evento -->
                            <div class="input-container">
                                <input
                                    type="text"
                                    id="descrizione"
                                    name="descrizione"
                                    class="long_input"
                                    value="<?php echo $descrizione; ?>"
                                    aria-labelledby="label-descrizione"
                                    style="resize: both;"
                                    oninput="manageTextInputStyle('descrizione')"
                                />
                                <label class="label" for="descrizione" id="label-descrizione">
                                    <div class="text">Descrizione (facoltativa)</div>
                                </label>
                            </div>
                        </p>
                        <!-- Data evento -->
                        <p>
                            <div class="input-container">
                                <input type="date" name="data" class="long_input" id="data" aria-labelledby="label-data" value="<?php echo $data; ?>" required>
                                <label for="data" id="label-data" class="fixed_label">Data dell'evento</label>
                            </div>
                        </p>
                        <p>
                            <!-- ID dell'evento da modificare -->
                            <input type="hidden" name="post_id" value="<?php echo $id; ?>" required>
                            <!-- Ora evento -->
                            <div class="input-container">
                                <input type="time" name="ora_inizio" id="ora_inizio" aria-labelledby="label-ora_inizio" value="<?php echo $ora_inizio; ?>" required>
                                <label for="ora_inizio" id="label-ora_inizio" class="fixed_label">Ora di inizio</label>
                                
                                <input type="time" name="ora_fine" id="ora_fine" aria-labelledby="label-ora_fine" value="<?php echo $ora_fine; ?>" required>
                                <label for="ora_fine" id="label-ora_fine" style="margin-left: 310px;" class="fixed_label">Ora di fine</label>
                            </div>
                        </p>
                        <p>
                            <!-- Luogo evento -->
                            <div class="input-container">
                                <div class="autocomplete" style="width: 300px;">
                                    <input
                                        type="text"
                                        id="luogo"
                                        name="luogo"
                                        class="long_input"
                                        value="<?php echo $luogo; ?>"
                                        aria-labelledby="label-luogo"
                                        oninput="manageTextInputStyle('luogo')"
                                        required
                                    />
                                    <label class="label" for="luogo" id="label-luogo">
                                        <div class="text">Luogo</div>
                                    </label>
                                </div>
                            </div>
                        </p>
                        <p>
                            <!-- Classi interessate -->
                            <?php
                                $db = 'eventoo_users';
                                $conn = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());
                                $myquery = mysqli_query($conn,"SELECT * FROM classi") or die (mysqli_error($conn));
                                $arrayClassi = null;
                                if(mysqli_num_rows($myquery) > 0) {
                                    $conta = 3;
                                    $arrayClassi[0] = " ";
                                    $arrayClassi[1] = " ";
                                    $arrayClassi[2] = " ";
                                    while($fetch = mysqli_fetch_array($myquery)) {
                                        $arrayClassi[$conta] = stripslashes(cripta($fetch['id'], "decrypt"));
                                        $conta++;
                                    }
                                    sort($arrayClassi);
                                    $arrayClassi[0] = "Balzan";
                                    $arrayClassi[1] = "Einaudi";
                                    $arrayClassi[2] = "Medie";
                                }
                                ?>
                            <div class="input-container">
                                <div id="myMultiselect" class="multiselect">
                                    <div id="mySelectLabel" class="selectBox" onclick="toggleCheckboxArea()">
                                        <select class="form-select long_input" name="classe2" id="classe2" aria-labelledby="label-classi">
                                            <option selected>Caricamento...</option>
                                        </select>
                                        <label for="classe2" class="fixed_label" id="label-classi">Classi interessate</label>
                                        <div class="overSelect"></div>
                                    </div>
                                    <div id="mySelectOptions">
                                        <?php
                                            $classeArray = explode(", ",$classe);

                                            for ($x=0; $x<count($arrayClassi); $x++) {
                                                // Creo l'array $arrayClassi2, che contiene gli elementi del <select>
                                                /* Inizialmente considero che l'evento non appartenga a nessuna classe (nessun
                                                checkbox selezionato), per semplificare il popolamento dell'array */
                                                if ($x == 0) {          // Applico la funzione specifica per selezionare tutte le classi della sede Balzan
                                                    $arrayClassi2[$x] = '<label for="element'.$x.'"><input type="checkbox" id="element'.$x.'" onchange="selectBalzan()" value="'.$arrayClassi[$x].'" /> '.$arrayClassi[$x].'</label>';
                                                } else if ($x == 1) {   // Applico la funzione specifica per selezionare tutte le classi della sede Einaudi
                                                    $arrayClassi2[$x] = '<label for="element'.$x.'"><input type="checkbox" id="element'.$x.'" onchange="selectEinaudi()" value="'.$arrayClassi[$x].'" /> '.$arrayClassi[$x].'</label>';
                                                } else if ($x == 2) {   // Applico la funzione specifica per selezionare tutte le classi della sede Medie
                                                    $arrayClassi2[$x] = '<label for="element'.$x.'"><input type="checkbox" id="element'.$x.'" onchange="selectMedie()" value="'.$arrayClassi[$x].'" /> '.$arrayClassi[$x].'</label>';
                                                } else {                // Checkbox per le singole classi
                                                    $arrayClassi2[$x] = '<label for="'.$arrayClassi[$x].'"><input type="checkbox" id="'.$arrayClassi[$x].'" onchange="checkboxStatusChange()" value="'.$arrayClassi[$x].'" /> '.$arrayClassi[$x].'</label>';
                                                }
                                            }

                                            /* Cerco le classi a cui e' stato assegnato l'evento, e sostituisco il contenuto
                                            precedente della loro cella (checkbox deselezionato) con un checkbox selezionato */
                                            foreach ($classeArray as $value) {
                                                if (in_array($value, $arrayClassi)) {
                                                    $i = array_search($value, $arrayClassi);
                                                    
                                                    if ($x == 0) {          // Applico la funzione specifica per selezionare tutte le classi della sede Balzan
                                                        $arrayClassi2[$i] = '<label for="element'.$i.'"><input type="checkbox" id="element'.$i.'" onchange="selectBalzan()" value="'.$arrayClassi[$i].'" checked /> '.$arrayClassi[$i].'</label>';
                                                    } else if ($x == 1) {   // Applico la funzione specifica per selezionare tutte le classi della sede Einaudi
                                                        $arrayClassi2[$i] = '<label for="element'.$i.'"><input type="checkbox" id="element'.$i.'" onchange="selectEinaudi()" value="'.$arrayClassi[$i].'" checked /> '.$arrayClassi[$i].'</label>';
                                                    } else if ($x == 2) {   // Applico la funzione specifica per selezionare tutte le classi della sede Medie
                                                        $arrayClassi2[$i] = '<label for="element'.$i.'"><input type="checkbox" id="element'.$i.'" onchange="selectMedie()" value="'.$arrayClassi[$i].'" checked /> '.$arrayClassi[$i].'</label>';
                                                    } else {                // Checkbox per le singole classi
                                                        $arrayClassi2[$i] = '<label for="'.$arrayClassi[$i].'"><input type="checkbox" id="'.$arrayClassi[$i].'" onchange="checkboxStatusChange()" value="'.$arrayClassi[$i].'" checked /> '.$arrayClassi[$i].'</label>';
                                                    }
                                                }
                                            }

                                            // Scrivo il tutto nel <select>
                                            for ($x=0; $x<count($arrayClassi2); $x++) {
                                                echo $arrayClassi2[$x];
                                                //echo "<script>console.log('arrayClassi[x]: ".$arrayClassi2[$x]."   x: ".$x."');</script>";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </p>
                        <script>
                            window.onload = (event) => {
                                initMultiselect();
                            };

                            function initMultiselect() {
                                checkboxStatusChange();

                                document.addEventListener("click", function(evt) {
                                    var flyoutElement = document.getElementById('myMultiselect'),
                                    targetElement = evt.target; // clicked element

                                    do {
                                        if (targetElement == flyoutElement) {
                                            // This is a click inside. Do nothing, just return.
                                            //console.log('click inside');
                                            return;
                                        }

                                        // Go up the DOM
                                        targetElement = targetElement.parentNode;
                                    } while (targetElement);

                                    // This is a click outside.
                                    toggleCheckboxArea(true);
                                    //console.log('click outside');
                                });
                            }

                            function checkboxStatusChange() {
                                var multiselect = document.getElementById("mySelectLabel");
                                var multiselectOption = multiselect.getElementsByTagName('option')[0];

                                var values = [];
                                var checkboxes = document.getElementById("mySelectOptions");
                                var checkedCheckboxes = checkboxes.querySelectorAll('input[type=checkbox]:checked');

                                for (const item of checkedCheckboxes) {
                                    var checkboxValue = item.getAttribute('value');
                                    values.push(checkboxValue);
                                }

                                var dropdownValue = "Nessuna classe selezionata";
                                if (values.length > 0) {
                                    dropdownValue = values.join(', ');
                                }

                                multiselectOption.innerText = dropdownValue;
                            }

                            function toggleCheckboxArea(onlyHide = false) {
                                var checkboxes = document.getElementById("mySelectOptions");
                                var displayValue = checkboxes.style.display;

                                if (displayValue != "block") {
                                    if (onlyHide == false) {
                                    checkboxes.style.display = "block";
                                    }
                                } else {
                                    checkboxes.style.display = "none";
                                }
                            }

                            justCheckedBalzan = false;
                            justCheckedEinaudi = false;
                            justCheckedMedie = false;

                            function selectBalzan() {
                                if (!justCheckedBalzan) {
                                <?php
                                    $query = mysqli_query($conn,"SELECT * FROM classi WHERE sede='".cripta("Balzan", "encrypt")."'") or die (mysqli_error($conn));
                                    if(mysqli_num_rows($query) > 0) {
                                        while($fetch = mysqli_fetch_array($query)) {
                                            echo "document.getElementById('".cripta($fetch['id'], "decrypt")."').checked = true;";
                                        }
                                    }
                                ?>
                                justCheckedBalzan = true;
                                } else {
                                    <?php
                                    $query = mysqli_query($conn,"SELECT * FROM classi WHERE sede='".cripta("Balzan", "encrypt")."'") or die (mysqli_error($conn));
                                    if(mysqli_num_rows($query) > 0) {
                                        while($fetch = mysqli_fetch_array($query)) {
                                            echo "document.getElementById('".cripta($fetch['id'], "decrypt")."').checked = false;";
                                        }
                                    }
                                ?>
                                justCheckedBalzan = false;
                                }
                                checkboxStatusChange();
                            }

                            function selectEinaudi() {
                                if (!justCheckedEinaudi) {
                                <?php
                                    $query = mysqli_query($conn,"SELECT * FROM classi WHERE sede='".cripta("Einaudi", "encrypt")."'") or die (mysqli_error($conn));
                                    if(mysqli_num_rows($query) > 0) {
                                        while($fetch = mysqli_fetch_array($query)) {
                                            echo "document.getElementById('".cripta($fetch['id'], "decrypt")."').checked = true;";
                                        }
                                    }
                                ?>
                                justCheckedEinaudi = true;
                                } else {
                                    <?php
                                    $query = mysqli_query($conn,"SELECT * FROM classi WHERE sede='".cripta("Einaudi", "encrypt")."'") or die (mysqli_error($conn));
                                    if(mysqli_num_rows($query) > 0) {
                                        while($fetch = mysqli_fetch_array($query)) {
                                            echo "document.getElementById('".cripta($fetch['id'], "decrypt")."').checked = false;";
                                        }
                                    }
                                ?>
                                justCheckedEinaudi = false;
                                }
                                checkboxStatusChange();
                            }

                            function selectMedie() {
                                if (!justCheckedMedie) {
                                <?php
                                    $query = mysqli_query($conn,"SELECT * FROM classi WHERE sede='".cripta("Medie", "encrypt")."'") or die (mysqli_error($conn));
                                    if(mysqli_num_rows($query) > 0) {
                                        while($fetch = mysqli_fetch_array($query)) {
                                            echo "document.getElementById('".cripta($fetch['id'], "decrypt")."').checked = true;";
                                        }
                                    }
                                ?>
                                justCheckedMedie = true;
                                } else {
                                    <?php
                                    $query = mysqli_query($conn,"SELECT * FROM classi WHERE sede='".cripta("Medie", "encrypt")."'") or die (mysqli_error($conn));
                                    if(mysqli_num_rows($query) > 0) {
                                        while($fetch = mysqli_fetch_array($query)) {
                                            echo "document.getElementById('".cripta($fetch['id'], "decrypt")."').checked = false;";
                                        }
                                    }
                                ?>
                                justCheckedMedie = false;
                                }
                                checkboxStatusChange();
                            }
                        </script>
                        <p>
                        <!-- Tipo evento -->
                        <div class="input-container">
                            <div class="autocomplete" style="width: 300px;">
                                <input
                                    type="text"
                                    id="tipo"
                                    name="tipo"
                                    class="long_input"
                                    value="<?php echo $tipo; ?>"
                                    aria-labelledby="label-tipo"
                                    oninput="manageTextInputStyle('tipo')"
                                />
                                <label class="label" for="tipo" id="label-tipo">
                                    <div class="text">Categoria (facoltativo)</div>
                                </label>
                            </div>
                        </div>
                        </p>
                        <p>
                        <!-- Link prenotazioni evento -->
                        <div class="input-container">
                            <input
                                type="url"
                                id="link_prenotazione"
                                name="link_prenotazione"
                                class="long_input"
                                value="<?php echo $link_prenotazione; ?>"
                                aria-labelledby="label-link_prenotazione"
                                oninput="manageTextInputStyle('link_prenotazione')"
                            />
                            <label class="label" for="link_prenotazione" id="label-link_prenotazione">
                                <div class="text">Link per prenotarsi (facoltativo)</div>
                            </label>
                        </div>
                    </div>
                    <div class="right_content">
                        <!-- Sezione per modificare la locandina -->
                        <div>
                            <?php
                            // Se e' presente la locandina
                            if ($link_foto_video != "" && $link_foto_video != "locandina_default.png") {
                                echo "&Egrave; presente 1 file: ".$link_foto_video."<a style='margin-left: 15px;' href='../evento/files/".$link_foto_video."' class='material-icons' download>download</a> <a style='margin-left: 15px;' href='../evento/files/".$link_foto_video."' class='material-icons' target='_blank'>visibility</a>";
                            ?>
                            <p><a href="?changeImage=yes&id=<?php echo $id; ?>&filenameDelete=<?php echo $link_foto_video; ?>" style="text-decoration: underline;">Elimina file</a></p>
                            <?php
                            // Se non e' presente la locandina
                            } else {
                            ?>
                            <!-- Area di upload della locandina -->
                            <div class="drop-zone">
                                <span class="drop-zone__prompt">Trascina qui il file o clicca per caricarlo<br><br>
                                <a class="drop-zone__prompt__accepted_filetype"><b>File accettati:</b> .pdf, .doc, .docx, .txt, .pub</a></span>
                                <input name="locandina" id="selectfile" class="drop-zone__input" type="file" accept=".pdf, .doc, .docx, .txt, .pub">
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <p>
                    <input type="submit" name="submit" value="Modifica" onmousedown="checkTiming()">
                    <input type="reset" value="Annulla" onclick="history.back();">
                </p>
            </form>
        </div>

        <script>
        function manageTextInputStyle(id) {
            const input = document.getElementById(id);
            input.setAttribute('value', input.value);
        }

        function checkTiming() {
            if (document.getElementById("ora_fine").value <= document.getElementById("ora_inizio").value) {
                alert("L'ora di inizio deve essere inferiore dell'ora di fine!");
                document.getElementById("ora_inizio").classList.add("input_error");
                document.getElementById("ora_fine").classList.add("input_error");
                document.getElementById("label-ora_inizio").classList.add("input_error_label");
                document.getElementById("label-ora_fine").classList.add("input_error_label");
            } else {
                document.getElementById("label-ora_inizio").classList.remove("input_error_label");
                document.getElementById("label-ora_fine").classList.remove("input_error_label");
                document.getElementById("ora_inizio").classList.add("input_error");
                document.getElementById("ora_fine").classList.add("input_error");
            }
        }
        </script>

        <script>
        function autocomplete(inp, arr) {
        /*the autocomplete function takes two arguments,
        the text field element and an array of possible autocompleted values:*/
        var currentFocus;
        /*execute a function when someone writes in the text field:*/
        inp.addEventListener("input", function(e) {
            var a, b, i, val = this.value;
            /*close any already open lists of autocompleted values*/
            closeAllLists();
            if (!val) { return false;}
            currentFocus = -1;
            /*create a DIV element that will contain the items (values):*/
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            /*append the DIV element as a child of the autocomplete container:*/
            this.parentNode.appendChild(a);
            /*for each item in the array...*/
            for (i = 0; i < arr.length; i++) {
                /*check if the item starts with the same letters as the text field value:*/
                if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                /*create a DIV element for each matching element:*/
                b = document.createElement("DIV");
                /*make the matching letters bold:*/
                b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                b.innerHTML += arr[i].substr(val.length);
                /*insert a input field that will hold the current array item's value:*/
                b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function(e) {
                    /*insert the value for the autocomplete text field:*/
                    inp.value = this.getElementsByTagName("input")[0].value;
                    /*close the list of autocompleted values,
                    (or any other open lists of autocompleted values:*/
                    closeAllLists();
                });
                a.appendChild(b);
                }
            }
        });
        /*execute a function presses a key on the keyboard:*/
        inp.addEventListener("keydown", function(e) {
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {
                /*If the arrow DOWN key is pressed,
                increase the currentFocus variable:*/
                currentFocus++;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 38) { //up
                /*If the arrow UP key is pressed,
                decrease the currentFocus variable:*/
                currentFocus--;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 13) {
                /*If the ENTER key is pressed, prevent the form from being submitted,*/
                e.preventDefault();
                if (currentFocus > -1) {
                /*and simulate a click on the "active" item:*/
                if (x) x[currentFocus].click();
                }
            }
        });
        function addActive(x) {
            /*a function to classify an item as "active":*/
            if (!x) return false;
            /*start by removing the "active" class on all items:*/
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            /*add class "autocomplete-active":*/
            x[currentFocus].classList.add("autocomplete-active");
        }
        function removeActive(x) {
            /*a function to remove the "active" class from all autocomplete items:*/
            for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
            }
        }
        function closeAllLists(elmnt) {
            /*close all autocomplete lists in the document,
            except the one passed as an argument:*/
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
            }
        }
        /*execute a function when someone clicks in the document:*/
        document.addEventListener("click", function (e) {
            closeAllLists(e.target);
        });
        }

        /*An array containing all the country names in the world:*/
        var luoghi = ["Aula Magna - sede Balzan", "Aula Magna - sede Einaudi", "Palestra - sede Balzan", "Palestra - sede Einaudi","Aula"];
        var tipi = ["Lezione","Spettaccolo","Colloqui","Uscita didattica","P.C.T.O.","Assemblea di classe","Assemblea d'istituto","Collegio docenti","Riunione","Evento","Incontro informativo"];

        /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
        autocomplete(document.getElementById("luogo"), luoghi);
        autocomplete(document.getElementById("tipo"), tipi);
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script>
        document.querySelectorAll(".drop-zone__input").forEach((inputElement) => {
        const dropZoneElement = inputElement.closest(".drop-zone");

        dropZoneElement.addEventListener("click", (e) => {
            inputElement.click();
        });

        inputElement.addEventListener("change", (e) => {
            if (inputElement.files.length) {
            updateThumbnail(dropZoneElement, inputElement.files[0]);
            }
        });

        dropZoneElement.addEventListener("dragover", (e) => {
            e.preventDefault();
            dropZoneElement.classList.add("drop-zone--over");
        });

        ["dragleave", "dragend"].forEach((type) => {
            dropZoneElement.addEventListener(type, (e) => {
            dropZoneElement.classList.remove("drop-zone--over");
            });
        });

        dropZoneElement.addEventListener("drop", (e) => {
            e.preventDefault();

            if (e.dataTransfer.files.length) {
            inputElement.files = e.dataTransfer.files;
            updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
            }

            dropZoneElement.classList.remove("drop-zone--over");
        });
        });

        /**
         * Updates the thumbnail on a drop zone element.
         *
         * @param {HTMLElement} dropZoneElement
         * @param {File} file
         */

        function updateThumbnail(dropZoneElement, file) {
        let thumbnailElement = dropZoneElement.querySelector(".drop-zone__thumb");

        // First time - remove the prompt
        if (dropZoneElement.querySelector(".drop-zone__prompt")) {
            dropZoneElement.querySelector(".drop-zone__prompt").remove();
        }

        // First time - there is no thumbnail element, so lets create it
        if (!thumbnailElement) {
            thumbnailElement = document.createElement("div");
            thumbnailElement.classList.add("drop-zone__thumb");
            dropZoneElement.appendChild(thumbnailElement);
        }

        thumbnailElement.dataset.label = file.name;

        // Show thumbnail for image files
        if (file.type.startsWith("image/")) {
            const reader = new FileReader();

            reader.readAsDataURL(file);
            reader.onload = () => {
            thumbnailElement.style.backgroundImage = 'url('+reader.result+')';
            thumbnailElement.style.backgroundColor = "transparent";
            };
        }  else {
            thumbnailElement.style.backgroundImage = null;
            thumbnailElement.style.backgroundColor = "#cccccc";
        }
        console.log(file.type);
        }
        </script>
        <?php
        } else {
            echo "<script type=\"text/javascript\">location.replace(\"../\");</script>";
        }
        ?>
        <br><br><br><br>
        <?php
        include '../components/footer.php';
        ?>
    </body>
</html>