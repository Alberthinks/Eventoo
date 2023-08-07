<?php

namespace Devtech\License;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['confirm']=="true") {
    include_once '../licenza/Generator.php';
    include_once '../licenza/License.php';
    $default_servername = "localhost";
    $default_username = "eventooRootUser";
    $default_password = "QnBWQzlN-vVko9Egryb5b4&k1b4hghb2bj1jkj4$";
    $default_dbname = "eventoo";
    // Create connection
    $default_conn = mysqli_connect($default_servername, $default_username, $default_password, $default_dbname);
    $default_query = mysqli_query($default_conn,"SELECT * FROM systems") or die (mysqli_error($default_conn));
    $default_fetch = mysqli_fetch_array($default_query) or die (mysqli_error());

    // Nome della piattaforma
    $nome_app = $default_fetch['appName'];

    // Versione della piattaforma
    $version = $default_fetch['version'];

    /**
     * Description of Test
     *
     * @author jpuchky
     */

    /*
    * Generate License KEY
    */

    $generator = new Generator();
    $license = new License("I.I.S. Primo Levi di Badia Polesine", $version, $nome_app, $_POST['license'], "192.168.1.7");
    $licenseKey = $generator->generate($license);
    //file_put_contents("license.log", $licenseKey);
    $sql = "UPDATE systems SET license='$licenseKey' WHERE id=1";

    if ($result = mysqli_query($default_conn,$sql) or die (mysqli_error($default_conn))) {
        echo "<p>La License Key &egrave; stata creata e attivata correttamente.</p>\n";
        echo "<a href=\"#\" onclick=\"window.close()\"><button>Fine</button></a>";
    }
} else {
?>
<h1>Attivazione della licenza</h1>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
    <label for="license">Inserire la chiave di attivazione della licenza:</label><br>
    <input type="number" id="license" name="license">
    <input type="hidden" name="confirm" value="true">
    <input type="submit" name="submit" value="Conferma">
</form>
<?php
}
?>