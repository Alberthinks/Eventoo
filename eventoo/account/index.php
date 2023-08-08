<?php
session_start();
include "../default.php";
include '../config.php';

$username = $_SESSION['session_user_eventoo'];
$nome = $_SESSION['session_nome_eventoo'];
$cognome = $_SESSION['session_cognome_eventoo'];
$ao = $_SESSION['session_ao_eventoo'];
$logo = $_SESSION['session_foto_eventoo'];
$nome_societa = $_SESSION['session_permessi_eventoo'];

if ($logo == " " || $logo == "") {
    $logo = "logos/baseline_account_circle_black_24dp.png";
}

if ($ao == "a") {
    $genere = "femmina";
} elseif ($ao == "o") {
    $genere = "maschio";
} else {
    $genere = "altro";
}

if (isset($_POST['submitPicture']) && $_POST['submitPicture']=="Cambia foto profilo") {
    if (($_FILES['picture']['name'] != null) && ($_FILES['picture']['tmp_name'] != null)) {
        // Eliminazione foto vecchia
        $username = cripta($username, "encrypt");
        $conn = mysqli_connect($host,$user,$pass, 'eventoo_users') or die (mysqli_error());
        $query = mysqli_query($conn,"SELECT foto FROM users WHERE username = '$username'") or die (mysqli_error($conn));
        $fetch = mysqli_fetch_array($query);
        $foto = cripta($fetch['foto'], "decrypt");
        unlink("../settings/gestione-utenti/nuovo/".$foto);

        // Salvataggio foto nuova
        $uploaddir = "../settings/gestione-utenti/nuovo/logos/";
        // Cartella temporanea del file da caricare
        $userfile_tmp = $_FILES['picture']['tmp_name'];
        // Nome del file da caricare
        $userfile_name = $_FILES['picture']['name'];
        // Dimensione del file da caricare
        $userfile_size = $_FILES['picture']['size'];
        // Estensione del file da caricare
        $userfile_extension = strtolower(pathinfo($userfile_name,PATHINFO_EXTENSION));

        // Verifico se il file ha il formato corretto in base all'estensione
        $filetypes = array("png", "jpeg", "jpg", "gif", "svg");
        if (!in_array($userfile_extension, $filetypes))
        {
            echo "I file di estensione <b>.".$userfile_extension."</b> non sono ammessi.";
            exit;
        }
        $userfile_name = "fotoprofilo_".date("hisdmY", time()).".".$userfile_extension;
        $picture = cripta("logos/".$userfile_name, "encrypt");
        $pictureSQL = "UPDATE users SET foto='$picture' WHERE username='$username'";
        if (!file_exists($uploaddir.$userfile_name)) {
            move_uploaded_file($userfile_tmp, $uploaddir.$userfile_name);
            if ($result = mysqli_query($conn,$pictureSQL) or die (mysqli_error($conn))) {
                $myconn = mysqli_connect($host,$user,$pass, 'eventoo') or die (mysqli_error());
                $timestamp = cripta(date('d/m/Y H:i:s', strtotime("now")), "encrypt");
                $action = cripta("Foto profilo modificata", "encrypt");
                $ip = cripta($_SERVER['REMOTE_ADDR'], "encrypt");
                $name = cripta($nome, "encrypt");
                $cog = cripta($cognome, "encrypt");
                $mysql = "INSERT INTO accesses (username,nome,cognome,ip,azione,timestamp,validity) VALUES ('$username', '$name','$cog','$ip','$action','$timestamp','$dataValidity')";
                if($rressultt = mysqli_query($myconn,$mysql) or die (mysqli_error($myconn))) {
                    echo "<script>alert('La foto profilo è stata modificata correttamente.\nAl prossimo accesso vedrai la tua nuova foto profilo');</script>";
                }
            }
        } elseif (file_exists($uploaddir.$userfile_name)) {
            echo "Il file esiste gi&agrave;!";
        } else {
            echo "Errore!";
        }
    }
}
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
        <link rel="manifest" href="../manifest.json">
        <!-- Titolo -->
        <title>Il tuo account | <?php echo $nome_app; ?></title>
        <!-- CSS -->
        <link rel="stylesheet" href="../css/default.css" type="text/css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <style>
            #message {
                padding: 20px;
                margin-top: 10px;
            }

            #message p {
                padding: 1px 35px;
                font-size: 16px;
            }

            .valid {
                color: #1acc00;
            }

            .valid:before {
                position: relative;
                left: -35px;
                content: "✔";
            }
            
            .invalid {
                color: #ff6554;
            }

            .invalid:before {
                position: relative;
                left: -35px;
                content: "✖";
            }

            /* Pulsanti di invio e reset */

            input[type=submit] {
                cursor: pointer;
                border: none;
                transition: 0.4s;
                font-size: 18px;
                width: 160px;
                margin-top: 30px;
                padding: 5px;
                background-color: var(--color-confirm-btn);
                color: #ffffff;
            }

            .changePswBtn {
                cursor: pointer;
                width: 110px;
                height: 35px;
                border: none;
                transition: 0.4s;
                font-size: 14px;
                margin-top: 0;
                padding: 0;
                background-color: var(--color-primary);
                border-radius: 4px;
                color: #ffffff;
            }

            .changePswBtn:hover {
                background: #006dd6;
            }

            body {
                min-height: 100vh !IMPORTANT;
                display: flex !IMPORTANT;
                flex-direction: column !IMPORTANT;
            }
            
            input[type=submit]:disabled, input[type=submit]:disabled:hover {
                opacity: 0.7;
                cursor: not-allowed;
            }

            input[type=submit]:focus, input[type=submit]:active, input[type=submit]:hover {
            border: none;
            background-color: var(--color-confirm-btn-hover);
            }

            td {height: 40px;}

            .img_container {
            position: relative;
            height: 60px;
            width: 60px;
            cursor: pointer;
            }

            .image {
            display: block;
            height: 100%;
            width: 100%;
            }

            .overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100%;
            width: 100%;
            opacity: 0;
            transition: .5s ease;
            background-color: rgba(255, 255, 255, 0.7);
            }

            .img_container:hover .overlay {
            opacity: 1;
            }

            .text_img {
            color: rgba(0, 0, 0, 0.7);
            font-size: 20px;
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            text-align: center;
            }

            #changeImg {padding: 20px; border-radius: 5px; margin-left: auto; margin-right: auto; margin-top: -20%; border: none; box-shadow: 0 0 50px #bbb;}

            /* Drag & Drop */
            .drop-zone {
            width: 500px;
            height: 200px;
            margin-top: 50px;
            padding: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-family: "Quicksand", sans-serif;
            font-weight: 500;
            font-size: 22px;
            cursor: pointer;
            color: #333333;
            border: 4px dashed #c5c5c5;
            border-radius: 10px;
            transition: 0.2s;
            min-width:250px;
            }
            
            .drop-zone:hover {
                background-color: rgba(0,0,0,0.1);
            }

            .drop-zone--over {
            border-style: solid;
            }

            .drop-zone__input {
            display: none;
            }

            .drop-zone__thumb {
            width: 200px;
            height: 100%;
            border-radius: 10px;
            overflow: hidden;
            background-color: transparent;
            background-size: auto 80%;
            background-repeat: no-repeat;
            background-position-x: 50%;
            position: relative;
            }

            .drop-zone__thumb::after {
            content: attr(data-label);
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 5px 0;
            color: #cccccc;
            background: rgba(0, 0, 0, 0.75);
            font-size: 14px;
            text-align: center;
            }
            
            .drop-zone__prompt__accepted_filetype {
                font-size: 16px;
                color: #777777;
            }
        </style>
    </head>
    <body>
        <!-- Header -->
        <?php
        $base_url = "../";
        include '../components/header.php';
        
        if (isset($_SESSION['session_id_eventoo'])) {
        ?>
        <div class="container">
            <h1 style="margin-bottom: 30px;">Il tuo account</h1>
            <table>
                <tr><td><b>Nome</b></td><td><?php echo $nome; ?></td></tr>
                <tr><td><b>Cognome</b></td><td><?php echo $cognome; ?></td></tr>
                <tr><td><b>Genere</b></td><td><?php echo $genere; ?></td></tr>
                <tr><td><b>Username</b></td><td><?php echo $username; ?></td></tr>
                <tr><td><b>Permessi</b></td><td><?php echo $nome_societa; ?></td></tr>
                <tr><td><b>Foto profilo</b></td><td>
                <div class="img_container" title="Cambia foto profilo">
                    <img src="../settings/gestione-utenti/nuovo/<?php echo $logo; ?>" alt="Foto profilo" height="40" class="image">
                    <div class="overlay" onclick="cambiaFotoProfilo()">
                        <div class="text_img material-icons">edit</div>
                    </div>
                </div></td></tr>
                <tr><td><b>Cambia password</b></td><td><button onclick="cambia()" class="changePswBtn" id="cambia">Cambia</button></td></tr>
            </table>
            <script>
            function cambia() {
                var cambia = document.getElementById("cambia-psw");
                if (cambia.style.display == "block") {
                    cambia.style.opacity = "0";
                    cambia.style.display = "none";
                } else {
                    cambia.style.opacity = "1";
                    cambia.style.display = "block";
                    document.getElementById("check").focus();
                }
            }
            </script>
            <?php
            if (isset($_POST['submit']) && $_POST['submit']=="Cambia password")
            {
                $username = cripta($username, "encrypt");
                $conn = mysqli_connect($host,$user,$pass, 'eventoo_users') or die (mysqli_error());
                $query = mysqli_query($conn,"SELECT password FROM users WHERE username = '$username'") or die (mysqli_error($conn));
                $fetch = mysqli_fetch_array($query);
                // Password vecchia salvata nel database
                $password = $fetch['password'];
                // Password nuova
                $password1 = addslashes($_POST['password1']);
                // Password nuova ripetuta
                $password2 = addslashes($_POST['password2']);
                // Password vecchia
                $check = $_POST['check'];
                $utilizzatore_content = stripslashes($_POST['utilizzatore_content']);
                
                if ($user == "admin") {
                    $utilizzatore = $utilizzatore_content;
                } else {
                    $utilizzatore = $user;
                }
                
                if (password_verify($check, $password) && $password1 == $password2) {
                    $new_password = password_hash($password1, PASSWORD_BCRYPT);
                    $sql = "UPDATE users SET password='$new_password' WHERE username = '$username'";

                    $myconn = mysqli_connect($host,$user,$pass, 'eventoo') or die (mysqli_error());
                    $timestamp = cripta(date('d/m/Y H:i:s', strtotime("now")), "encrypt");
                    $action = cripta("Password modificata correttamente", "encrypt");
                    $ip = cripta($_SERVER['REMOTE_ADDR'], "encrypt");
                    $name = cripta($nome, "encrypt");
                    $cog = cripta($cognome, "encrypt");
                    $mysql = "INSERT INTO accesses (username,nome,cognome,ip,azione,timestamp,validity) VALUES ('$username', '$name','$cog','$ip','$action','$timestamp','$dataValidity')";

                    if (mysqli_query($conn,$sql) or die (mysqli_error($conn))) {
                        if($rressultt = mysqli_query($myconn,$mysql) or die (mysqli_error($myconn))) {
                            echo "<p>La password &egrave; stata modificata correttamente.</p>";
                        }
                    }
                } else if ($password1 != $password2) {
                    echo "Le due nuove password non corrispondono!";
                } else if (!password_verify($check, $password)) {
                    echo "La password vecchia &egrave; errata!";
                } else {
                    echo "Errore nella procedura";
                }
            } else {
            ?>
            <!-- Sezione per cambiare password -->
            <section id="cambia-psw" style="display: none;">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"><br><br>
                    <!-- Password vecchia -->
                    <div class="input-container">
                        <input
                            type="password"
                            id="check"
                            name="check"
                            autocomplete="current-password"
                            value=""
                            aria-labelledby="label-check"
                            oninput="manageTextInputStyle('check')"
                            required
                        />
                        <label class="label" for="check" id="label-check">
                            <div class="text">Inserisci la password vecchia</div>
                        </label>
                    </div>
                    <br>
                    <!-- Password nuova -->
                    <div class="input-container">
                        <input
                            type="password"
                            id="password1"
                            name="password1"
                            value=""
                            aria-labelledby="label-password1"
                            oninput="manageTextInputStyle('password1')"
                            required
                        />
                        <label class="label" for="password1" id="label-password1">
                            <div class="text">Inserisci la password nuova</div>
                        </label>
                    </div>
                    <br>
                    <!-- Ripeti password nuova -->
                    <div class="input-container">
                        <input
                            type="password"
                            id="password2"
                            name="password2"
                            value=""
                            aria-labelledby="label-password2"
                            oninput="manageTextInputStyle('password2')"
                            required
                        />
                        <label class="label" for="password2" id="label-password2">
                            <div class="text">Ripeti la password nuova</div>
                        </label>
                    </div>
                    <input type="submit" name="submit" id="submit" value="Cambia password" disabled>
                </form>
                <div id="message">
                    <h3>La password deve contenere:</h3>
                    <p id="letter" class="invalid">Una lettera <b>minuscola</b></p>
                    <p id="capital" class="invalid">Una lettera <b>maiuscola</b></p>
                    <p id="number" class="invalid">Un <b>numero</b></p>
                    <p id="length" class="invalid">Almeno <b>8 caratteri</b></p>
                </div>
            </section>

            <dialog id="changeImg">
                <h3 style="float: left;">Aggiorna l'immagine del tuo profilo</h3>
                <a title="Chiudi" onclick="cambiaFotoProfilo()" style="float: right; cursor: pointer; font-size: 28px;">&times;</a>
                <form name="cambiaProfilo" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <!-- Area di upload del file -->
                    <div class="drop-zone">
                        <span class="drop-zone__prompt">Trascina qui il file o clicca per caricarlo<br><br>
                        <a class="drop-zone__prompt__accepted_filetype"><b>File accettati:</b> .png, .jpg, .jpeg, .svg, .gif</a></span>
                        <input name="picture" id="selectfile" class="drop-zone__input" type="file" accept=".png, .jpg, .jpeg, .svg, .gif">
                    </div>
                    <input type="submit" name="submitPicture" value="Cambia">
                </form>
            </dialog>

            <!-- Far funzionare i campi di input -->
            <script>
            function manageTextInputStyle(id) {
                const input = document.getElementById(id);
                input.setAttribute('value', input.value);
            }
            </script>

            <!-- Far comparire dialog per cambiare foto profilo -->
            <script>
            function cambiaFotoProfilo() {
                if (document.getElementById("changeImg").style.display == "block") {
                    document.getElementById("changeImg").style.display = "none";
                } else {
                    document.getElementById("changeImg").style.display = "block";
                }
            }
            </script>

            <script>
            var myInput = document.getElementById("password1");
            var letter = document.getElementById("letter");
            var capital = document.getElementById("capital");
            var number = document.getElementById("number");
            var length = document.getElementById("length");

            // When the user starts to type something inside the password field
            myInput.onkeyup = function() {
            // Validate lowercase letters
            var lowerCaseLetters = /[a-z]/g;
            if(myInput.value.match(lowerCaseLetters)) {  
                letter.classList.remove("invalid");
                letter.classList.add("valid");
            } else {
                letter.classList.remove("valid");
                letter.classList.add("invalid");
            }
            
            // Validate capital letters
            var upperCaseLetters = /[A-Z]/g;
            if(myInput.value.match(upperCaseLetters)) {  
                capital.classList.remove("invalid");
                capital.classList.add("valid");
            } else {
                capital.classList.remove("valid");
                capital.classList.add("invalid");
            }

            // Validate numbers
            var numbers = /[0-9]/g;
            if(myInput.value.match(numbers)) {  
                number.classList.remove("invalid");
                number.classList.add("valid");
            } else {
                number.classList.remove("valid");
                number.classList.add("invalid");
            }
            
            // Validate length
            if(myInput.value.length >= 8) {
                length.classList.remove("invalid");
                length.classList.add("valid");
            } else {
                length.classList.remove("valid");
                length.classList.add("invalid");
            }

            if (letter.className == "valid" && capital.className == "valid") {
                if (number.className == "valid" && length.className == "valid") {
                    document.getElementById("submit").removeAttribute("disabled");
                } else {
                    document.getElementById("submit").setAttribute("disabled", "");
                }
            } else {
                document.getElementById("submit").setAttribute("disabled", "");
            }
            }
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
            }
            ?>
            <?php
            } else {
                echo "<script type=\"text/javascript\">location.replace(\"../login\");</script>";
            }
            ?>
        </div>
        
        <br><br><br><br><br><br><br><br>
            
        <?php
        include '../components/footer.php';
        ?>
    </body>
</html>
