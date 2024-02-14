<?php
session_start();
include "../default.php";

$get_classe = $_GET['classe'];
$str_data = $_GET['data'];
$data = date("Y-m-d", $str_data);

$username = $_SESSION['session_user_eventoo'];
$nome = $_SESSION['session_nome_eventoo'];
$cognome = $_SESSION['session_cognome_eventoo'];
$ao = $_SESSION['session_ao_eventoo'];
$permessi = $_SESSION['session_permessi_eventoo'];
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
        <title>Nuovo evento | <?php echo $nome_app; ?></title>
        <!-- CSS -->
        <link rel="stylesheet" href="css/style.css" type="text/css">
        <link rel="stylesheet" href="../css/default.css" type="text/css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    </head>
    <body>
        <!-- Header -->
        <?php
            $base_url = "../";
            include '../components/header.php';
            // Fine header

            if ($permessi == "write" || $permessi == "maintenance" || $permessi == "administration") {

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['submit']=="Crea evento") {
                $titolo = writeRecord($_POST['titolo']);
                $descrizione = writeRecord($_POST['descrizione']);
                $data = strtotime(writeRecord($_POST['data']));
                $ora_inizio = writeRecord($_POST['ora_inizio']);
                $ora_fine = writeRecord($_POST['ora_fine']);
                $organizzatore = writeRecord($_POST['organizzatore']);
                $luogo = writeRecord($_POST['luogo']);
                $classe = writeRecord($_POST['classe2']);

                $categoria = writeRecord($_POST['tipo']);
                $link_videoconferenza = writeRecord($_POST['link_prenotazione']);
                $data_modifica = writeRecord($_POST['data_modifica']);

                // Percorso della cartella dove mettere i file caricati dagli utenti
                $uploaddir = '../evento/files/';

                include '../config.php';

                $db = 'eventoo_planner';
                $conn = mysqli_connect($host,$user,$pass, $db) or die (mysqli_error());

                $myconn = mysqli_connect($host,$user,$pass, 'eventoo') or die (mysqli_error());
                $timestamp = cripta(date('d/m/Y H:i:s', strtotime("now")), "encrypt");
                $ip = cripta($_SERVER['REMOTE_ADDR'], "encrypt");
                $uname = cripta($username, "encrypt");
                $name = cripta($nome, "encrypt");
                $cog = cripta($cognome, "encrypt");
                $societa = cripta($nome_societa, "encrypt");


                $userfile_name = "locandina_default.png";       /* Inizializzo il nome del file caricato come stringa vuota, cosi' se
                                                                non si carica nessun file, non viene memorizzato il valore null */
                          
                // Controllo se e' stata caricata una locandina
                if (($_FILES['locandina']['name'] != null) && ($_FILES['locandina']['tmp_name'] != null)) {
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
                        echo "I file di estensione <b>.".$userfile_extension."</b> non sono ammessi.";
                        exit;
                    }
                }


                // Inserisco i dati dell'evento nel database "planner"
                $sql = "INSERT INTO planner (titolo,descrizione,data,ora_inizio,ora_fine,organizzatore,stanza,classe,categoria,link_videoconferenza,link_locandina,data_modifica,validity) VALUES ('$titolo', '$descrizione','$data','$ora_inizio','$ora_fine','$organizzatore','$luogo','$classe','$categoria','$link_videoconferenza','$userfile_name','$data_modifica','$dataValidity')";
                

                // Controllo se esiste gia' un file con lo stesso nome e se sono in grado di caricare il file corrente (o se il file si chiama "locandina_default.png", che e' gia' salvata di default)
                if ((!file_exists($uploaddir.$userfile_name) || $userfile_name == "locandina_default.png")) {
                    move_uploaded_file($userfile_tmp, $uploaddir.$userfile_name);
                    if ($result = mysqli_query($conn,$sql) or die (mysqli_error($conn))) {
                        // Prelevo dal database l'id da registrare nel registro delle attivita'
                        $equery = mysqli_query($conn,"SELECT * FROM planner WHERE titolo='$titolo' AND organizzatore='$organizzatore' AND data='$data'") or die (mysqli_error($conn));
                        $row = mysqli_fetch_array($equery);
                        $accessesID = $row['id'];
                        $action = cripta("Creazione dell'evento (id:".$accessesID.") '$titolo' del ".date('d/m/Y', $data)." alle $ora", "encrypt");
                        $mysql = "INSERT INTO accesses (username,nome,cognome,ip,azione,timestamp,validity) VALUES ('$uname', '$name','$cog','$ip','$action','$timestamp','$dataValidity')";

                        if ($rressultt = mysqli_query($myconn,$mysql) or die (mysqli_error($myconn))) {
                            echo "<script type=\"text/javascript\">location.replace(\"../?d=".$data."\");</script>";
                        }
                    }
                } elseif (file_exists($uploaddir.$userfile_name) && $userfile_name != "locandina_default.png") {
                    echo "Il file esiste gi&agrave;!";
                } else {
                    echo "Errore!";
                }

            }
            
            date_default_timezone_set('Europe/Rome');
            ?>
        <div class="container">
            <div class="main_container">
                <div class="left_content">
                    <!-- Titolo -->
                    <h1>Nuovo evento</h1>
                    <form name="crea" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
                        <input type="hidden" name="data_modifica" value="<?php echo(date("d/m/Y h:i:s A",time())); ?>">
                        <p>
                            <!-- Titolo evento -->
                            <div class="input-container">
                                <input
                                    type="text"
                                    id="titolo"
                                    class="long_input"
                                    name="titolo"
                                    value=""
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
                                <!--<input
                                    type="text"
                                    id="descrizione"
                                    name="descrizione"
                                    class="long_input"
                                    value=""
                                    aria-labelledby="label-descrizione"
                                    style="resize: both;"
                                    oninput="manageTextInputStyle('descrizione')"
                                />-->
                                
                                <textarea
                                    id="descrizione"
                                    name="descrizione"
                                    rows="14"
                                    cols="52"
                                    aria-labelledby="label-descrizione"
                                    style="resize: none;"
                                    oninput="manageTextInputStyle('descrizione')"
                                ></textarea>

                                <label class="label" for="descrizione" style="height: 50px;" id="label-descrizione">
                                    <div class="text">Descrizione (facoltativa)</div>
                                </label>
                            </div>
                        </p>
                        <p>
                            <div class="input-container">
                                <input type="date" name="data" class="long_input" id="data" aria-labelledby="label-data" value="<?php echo $data; ?>" required>
                                <label for="data" class="fixed_label" id="label-data">Data dell'evento</label>
                            </div>
                        </p>
                        <p>
                            <div class="input-container">
                                <input type="time" name="ora_inizio" id="ora_inizio" aria-labelledby="label-ora_inizio" required>
                                <label for="ora_inizio" class="fixed_label" id="label-ora_inizio">Ora di inizio</label>

                                <input type="time" name="ora_fine" id="ora_fine" aria-labelledby="label-ora_fine" required>
                                <label for="ora_fine" class="fixed_label" style="margin-left: 310px;" id="label-ora_fine">Ora di fine</label>
                            </div>
                        </p>
                        <p>
                            <input type="hidden" name="organizzatore" value="<?php echo $nome." ".$cognome; ?>" required>
                            <!-- Luogo evento -->
                            <div class="input-container">
                                <div class="autocomplete" style="width: 300px;">
                                    <input
                                        type="text"
                                        id="luogo"
                                        name="luogo"
                                        class="long_input"
                                        value=""
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
                                $query = mysqli_query($conn,"SELECT * FROM classi") or die (mysqli_error($conn));
                                $arrayClassi = null;
                                if(mysqli_num_rows($query) > 0) {
                                    $conta = 4;
                                    $arrayClassi[0] = " ";
                                    $arrayClassi[1] = " ";
                                    $arrayClassi[2] = " ";
                                    $arrayClassi[3] = " ";
                                    while($fetch = mysqli_fetch_array($query)) {
                                        $arrayClassi[$conta] = stripslashes(cripta($fetch['id'], "decrypt"));
                                        $conta++;
                                    }
                                    sort($arrayClassi);
                                    $arrayClassi[0] = "Docenti";
                                    $arrayClassi[1] = "Balzan";
                                    $arrayClassi[2] = "Einaudi";
                                    $arrayClassi[3] = "Medie";
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
                                            for ($x=0; $x<count($arrayClassi); $x++) {
                                                switch ($x) {
                                                    case 1:
                                                        echo '<label for="element'.$x.'"><input type="checkbox" id="element'.$x.'" onchange="selectBalzan()" value="'.$arrayClassi[$x].'" /> '.$arrayClassi[$x].'</label>';
                                                        break;
                                                    case 2:
                                                        echo '<label for="element'.$x.'"><input type="checkbox" id="element'.$x.'" onchange="selectEinaudi()" value="'.$arrayClassi[$x].'" /> '.$arrayClassi[$x].'</label>';
                                                        break;
                                                    case 3:
                                                        echo '<label for="element'.$x.'"><input type="checkbox" id="element'.$x.'" onchange="selectMedie()" value="'.$arrayClassi[$x].'" /> '.$arrayClassi[$x].'</label>';
                                                        break;
                                                    default:
                                                        echo '<label for="'.$arrayClassi[$x].'"><input type="checkbox" id="'.$arrayClassi[$x].'" onchange="checkboxStatusChange()" value="'.$arrayClassi[$x].'" /> '.$arrayClassi[$x].'</label>';
                                                }
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
                                    value=""
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
                                value=""
                                class="long_input"
                                aria-labelledby="label-link_prenotazione"
                                oninput="manageTextInputStyle('link_prenotazione')"
                                onchange="manageTextInputStyle('link_prenotazione')"
                            />
                            <label class="label" for="link_prenotazione" id="label-link_prenotazione">
                                <div class="text">Link alla videoconferenza (facoltativo)</div>
                            </label>
                        </div>
                        </p>
                    </div>
                    <div class="right_content">
                        <!-- Area di upload del file -->
                        <div class="drop-zone">
                            <span class="drop-zone__prompt">Trascina qui il file o clicca per caricarlo<br><br>
                            <a class="drop-zone__prompt__accepted_filetype"><b>File accettati:</b> .pdf, .doc, .docx, .txt, .pub</a></span>
                            <input name="locandina" id="selectfile" class="drop-zone__input" type="file" accept=".pdf, .doc, .docx, .txt, .pub">
                        </div>
                        <input style="margin-top: 60px; accent-color: #db0d0d;" type="checkbox" name="chiusura" id="chiusura" onchange="chiusuraScuola()"><label for="chiusura" style="color: #db0d0d; margin-left: 10px; font-weight: bold;">Sospensione delle lezioni/chiusura della scuola</label>
                        <script>
                            function chiusuraScuola() {
                                let checkbox = document.getElementById("chiusura");
                                let categoryText = document.getElementById("tipo");
                                if (checkbox.checked == true) {
                                    categoryText.value = "Chiusura";
                                    manageTextInputStyle("tipo");
                                } else {
                                    categoryText.value = "";
                                    manageTextInputStyle("tipo");
                                }
                            }
                        </script>
                    </div>
                </div>
                <p>
                    <input type="submit" name="submit" value="Crea evento" onmousedown="checkTiming()">
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
        // Funzione per autocompletamento
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
                    var autocompleteText = this.getElementsByTagName("input")[0].value;
                    var outputText = "";

                    /* Se si crea l'evento per tutta la sede, il db deve conoscere le classi alle quali si fa
                    riferimento, per mostrare l'evento nei rispettivi calendari */
                    switch (autocompleteText) {
                        case "Balzan":
                            outputText = "<?php echo $classiBalzan; ?>";
                            break;
                        case "Einaudi":
                            outputText = "<?php echo $classiEinaudi; ?>";
                            break;
                        case "Medie":
                            outputText = "<?php echo $classiMedie; ?>";
                            break;
                        default:
                            outputText = autocompleteText;
                    }
                    inp.value = outputText;

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
            } else if (e.keyCode == 38) {
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
        var luoghi = ["Aula Magna - sede Balzan", "Aula Magna - sede Einaudi", "Palestra - sede Balzan", "Palestra - sede Einaudi","Aula","Cortile","Tutte le sedi"];
        var tipi = ["Educazione alla salute","Social Time","Consiglio di classe","Ricevimento genitori","Lezione","Spettaccolo","Colloqui","Uscita didattica","P.C.T.O.","Assemblea di classe","Assemblea d'istituto","Collegio docenti","Riunione","Evento","Incontro informativo"];

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
            echo "<script type=\"text/javascript\">history.back();</script>";
        }
        ?>

        <br><br><br><br>
        <?php
        include '../components/footer.php';
        ?>
    </body>
</html>
