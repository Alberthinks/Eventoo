<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400&display=swap');
* {margin: 0; padding: 0; font-family: 'Noto Sans', sans-serif;}

footer {
	margin-top: auto !IMPORTANT;
	padding-top: 50px !IMPORTANT;
    background-color: #333;
    color: #ffffff;
    padding: 20px 0 20px 0;
    text-align: center;
    transition: opacity 0.5s ease-in-out;
    margin-top: 30px;
    box-shadow: 0 -6px 6px 5px rgba(0,0,0,0.5);
}

.footer-container {
  max-width: 1200px;
  margin: 0 auto;
}

.footer-row {
  display: flex;
  justify-content: center;
}

.footer-col {
  text-align: center;
  margin: 0 10px;
}

.copyright {
  font-size: 12px;
  margin-top: 25px;
}
</style>
<footer>
    <div class="footer-container">
        <div class="footer-row">
        <div class="footer-col">
            Realizzato da Albertin Emanuele per conto di<br>
            <img alt="Logo I.I.S PRIMO LEVI di Badia Polesine" title="I.I.S PRIMO LEVI di Badia Polesine" src="<?php echo $base_url; ?>img/logo_primo_levi.png" height="80">
        </div>
        </div>
        <p class="copyright">A. P. Planner - ver. <?php echo $version; ?></p>
        <a href="https://forms.gle/kHUfzDRse281sreo7" target="_blank" style="text-align: left; text-decoration: underline; color: white;">Consigliaci un miglioramento</a>
    </div>
</footer>