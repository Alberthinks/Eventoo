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
    box-shadow: 0px -8px 16px 0px rgba(0,0,0,0.5);
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
            Realizzato da Albertin Emanuele (5 B TI a.s. 2023/2024) per conto di<br>
            <img alt="Logo I.I.S PRIMO LEVI di Badia Polesine" title="I.I.S PRIMO LEVI di Badia Polesine" src="<?php echo $base_url; ?>img/logo_primo_levi.png" height="80">
        </div>
        </div>
        <p class="copyright"><?php echo $nome_app." - ver. ".$version; ?></p>
        <a href="https://docs.google.com/forms/d/e/1FAIpQLScwcfgt9F1rB2XKPg8Nwj8yz1meMTF4CqX5KTJsMRVBs1QI6g/viewform?usp=sf_link" target="_blank" style="text-align: left; text-decoration: underline; color: white;">Consigliami un miglioramento</a>
    </div>
</footer>
