<?php
session_start();

if ($_SESSION['session_foto_eventoo'] == "") {
  $fotoProfilo = "logos/baseline_account_circle_black_24dp.png";
} else {
  $fotoProfilo = $_SESSION['session_foto_eventoo'];
}

$logoPlatform = "logo.png";
$today = date("j m");

if ($today == "20 12") {
  $logoPlatform = "logo_natale.png";
}
if ($today == "21 12") {
  $logoPlatform = "logo_natale.png";
}
if ($today == "22 12") {
  $logoPlatform = "logo_natale.png";
}
if ($today == "23 12") {
  $logoPlatform = "logo_natale.png";
}
if ($today == "24 12") {
  $logoPlatform = "logo_natale.png";
}
if ($today == "25 12") {
  $logoPlatform = "logo_natale.png";
}
if ($today == "26 12") {
  $logoPlatform = "logo_natale.png";
}
if ($today == "31 10") {
  $logoPlatform = "logo_halloween.png";
}
if ($today == "25 11") {
  $logoPlatform = "logo_25novembre.png";
}
if ($today == "26 11") {
  $logoPlatform = "logo_chiuso.png\" style=\"height: 72.5px !important;";
}
if ($today == "16 01") {
  // Anniversario di Eventoo
  $logoPlatform = "logo_festa.png";
  echo "<style>#anni {display: block !important; visibility: visible !important;}</style>";
}
/*if ($today == "13 02") {
  $logoPlatform = "logo_chiuso.png\" style=\"height: 72.5px !important;";
}*/

// Per anniversario di Eventoo: conto quanti anni sono passati dal 16 gennaio 2024
$oggi = date("Y");
$anniversatio = $oggi - 2024;
?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400&display=swap');
    @import url('../css/default.css');
    * {margin: 0; padding: 0; font-family: 'Noto Sans', sans-serif;}
    header {width: 100%; /*background-color: #6611ac;*/ background-color: var(--color-primary); padding: 4px 16px 4px 16px; position: fixed; top: 0; left: 0; right: 0; z-index: 100;}
    #top_menu {width: 98%; height: 85px; align-content: center;}
    .logo {float: left;}
    #account {border-radius: 50%; cursor: pointer; width: 45px; height: 45px; margin-top: 20px;}
    #account:hover {background: rgba(255, 255, 255, 0.4);}
    .logo img {height: 60px; width: auto; margin-top: 12px;}
    
    .dropdown {
        display: block;
        float: right;
    }
    .dropdown-content {
        display: none;
        position: absolute;
        right: 30px;
        background-color: #f1f1f1;
        width: 260px;
        overflow: auto;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        color: #000000;
    }

    .dropdown-content p {padding: 12px 16px; font-size: 14px;}

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        border-top: 1px solid #333333;
        display: block;
    }

    .dropdown-content a.first {border-top: none;}

    .dropdown a:hover {background-color: #ddd;}

    .show {display: block;}

    #anni {
      color: red;
      font-weight: bold;
      font-size: 40px;
      transform: rotateZ(16deg);
      display: none;
      visibility: hidden;
      position: absolute;
      top: 20px;
      left: 170px;
      font-family: Arial Black, sans-serif;
    }

    .easterBtn {
      width: 60px;
      height: 40px;
      margin-left: 80px;
      margin-top: 25px;
      font-size: 24px;
      text-align: center;
      cursor: pointer;
      outline: none;
      color: #fff;
      background-color: white;
      background-image: url(<?php echo $base_url."components/"; ?>img_btn.gif);
      background-size: cover;
      border: none;
      border-radius: 50%;
      box-shadow: 0 9px #666666;
    }

    .easterBtn:hover {background-color: #3e8e41}

    .easterBtn:active {
      background-color: #3e8e41;
      box-shadow: 0 5px #666666;
      transform: translateY(4px);
    }

    #easteregg iframe {border: none; margin: auto; width: 80%; height: 100%; overflow: hidden; position: absolute; left: 2%; right: 2%;}

    @media only screen and (max-width: 1030px) {
      #easteregg iframe {width: 100% !important; left: 0;}
      .easterBtn {margin-left: 25px;}
    }
</style>
<header id="header">
    <div id="top_menu">
        <div class="logo">
          <a href="<?php echo $base_url; ?>" title="Home">
            <img alt="Eventoo" src="<?php echo $base_url.$logoPlatform; ?>" draggable="false" id="logo">
            <!-- Anniversario di Eventoo -->
            <span id="anni">+<?php echo $anniversatio; ?></span>
          </a>
        </div>
        
        <?php
          // Easter egg per il Pesce d'Aprile
          if (date("d m") == "01 04" && !isset($_COOKIE['hahahahahaha'])) {
        ?>
          <button class="easterBtn" onclick="easterEgg()"> </button>
          <script>
            function easterEgg() {
              if (document.getElementById('easteregg').style.display == 'block') {
                document.getElementById('easteregg').style.display = 'none';
              } else {
                document.getElementById('easteregg').style.display = 'block';
              }
            }
          </script>
        <?php
          }
        ?>

        <div class="dropdown">
            <?php
            if (isset($_SESSION['session_id_eventoo'])) {
            ?>
            <img id="account" title="<?php echo $_SESSION['session_nome_eventoo']." ".$_SESSION['session_cognome_eventoo']; ?>" onclick="openAccountOptions()" src="<?php echo $base_url; ?>settings/gestione-utenti/nuovo/<?php echo $fotoProfilo; ?>" alt="" draggable="false">
            <div id="accountOptions" class="dropdown-content">
                <p><?php echo "Benvenut".$_SESSION['session_ao_eventoo'].", ".$_SESSION['session_nome_eventoo']." ".$_SESSION['session_cognome_eventoo']; ?></p>
                <a href="<?php echo $base_url; ?>account" class="first">Gestione account</a>
                <?php
                if ($_SESSION['session_permessi_eventoo'] == "maintenance" || $_SESSION['session_permessi_eventoo'] == "administration") {
                ?>
                <a href="<?php echo $base_url; ?>settings">Impostazioni piattaforma</a>
                <?php
                }
                ?>
                <a href="<?php echo $base_url; ?>login/logout.php">Logout</a>
                <a href="<?php echo $base_url; ?>easter"><span class="material-icons" style="transform: translateY(5px);">play_arrow</span> Gioca</a>
            </div>
            <?php
            } else {
            ?>
            <img id="account" title="Accedi" onclick="openAccountOptions()" src="<?php echo $base_url; ?>settings/gestione-utenti/nuovo/logos/baseline_account_circle_black_24dp.png" alt="" draggable="false">
            <div id="accountOptions" class="dropdown-content">
                <p>Accedi per inserire e modificare gli eventi.</p>
                <a href="<?php echo $base_url; ?>login">Accedi</a>
                <!--<a href="<?php echo $base_url; ?>easter"><span class="material-icons" style="transform: translateY(5px);">play_arrow</span> Gioca</a>-->
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</header>

<script>
/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function openAccountOptions() {
  document.getElementById("accountOptions").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('#account')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>

<?php
  // Easter egg per il Pesce d'Aprile
  if (date("d m") == "01 04" && !isset($_COOKIE['hahahahahaha'])) {
?>
  <div id="easteregg" onclick="document.getElementById('easteregg').style.display = 'none';" style="display: none; position: fixed; top: 143px; bottom: 0; left: 0; width: 100%; height: auto; background: rgba(0, 0, 0, 0.6); z-index: 9989889;">
    <iframe src="<?php echo $base_url; ?>easter/index.html"></iframe>
  </div>
<?php
  }
?>