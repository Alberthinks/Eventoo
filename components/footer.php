<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400&display=swap');
* {margin: 0; padding: 0; font-family: 'Noto Sans', sans-serif;}

footer {
	margin-top: auto !IMPORTANT;
    background-color: #333;
    font-size: 15px;
    color: #ffffff;
    padding: 20px 0 20px 0;
}

footer a {color: #ffffff;}

.footer-container {
  max-width: 1200px;
  margin: 0 auto;
}

#container {display: flex; justify-content: space-around; flex-direction: row;}

#contatti {list-style-type: none; margin-top: 10px; margin-bottom: 20px;}
#contatti li {margin-bottom: 3px;}
#contatti li a {text-decoration: underline;}
#contatti li i {user-select: none; font-size: 20px; transform: translateY(4px);}

@media only screen and (max-width: 600px) {
  #container {
    flex-direction: column;
  }
}

.copyright {
  font-size: 10px;
  margin-top: 25px;
  text-align: center;
}
</style>
<footer>
    <div class="footer-container">
      <div id="container">
        <div style="float: left; margin-right: 40px; text-align: center; padding: 30px;">
            <b>Credits:</b>
            <p>
              Realizzato da Albertin Emanuele<br>
              con la partecipazione di Paun Catalin-Adrian<br>
              per <a href="https://www.primolevi.edu.it/">I.I.S. PRIMO LEVI di Badia Polesine</a>
            </p>
            <br><br><br>
            <p><b>Istituto Istruzione Superiore PRIMO LEVI</b></p>
            <br><br><br>
            <a href="https://docs.google.com/forms/d/e/1FAIpQLScwcfgt9F1rB2XKPg8Nwj8yz1meMTF4CqX5KTJsMRVBs1QI6g/viewform?usp=sf_link" target="_blank" style="text-align: left; text-decoration: underline; color: white;">Feedback</a>
        </div>
        <div style="float: right; margin-top: auto; margin-bottom: auto; padding: 30px;">
          <h3 style="margin-bottom: 15px;">CONTATTI</h3>
          <ul id="contatti">
            <li><i class="material-icons">place</i> Via Manzoni, 191 - 45021 Badia Polesine (RO)</li>
            <li><i class="material-icons">phone</i> Tel: <a href="tel:+39042553433">0425 53433</a></li>
            <li><i class="material-icons" style="font-size: 18px;">mail</i> Email: <a href="mailto:rois00700d@istruzione.it">rois00700d@istruzione.it</a></li>
            <li><i class="material-icons" style="font-size: 18px;">mail_lock</i> PEC: <a href="mailto:rois00700d@pec.istruzione.it">rois00700d@pec.istruzione.it</a></li>
            <li><i class="material-icons" style="font-size: 19px;">web</i> Sito web: <a href="https://www.primolevi.edu.it/">www.primolevi.edu.it</a></li>
            <li><i class="material-icons" style="font-size: 18px;">contact_mail</i> C.F.: 91005190292</li>
          </ul>
          <p>Codice Ministeriale ROIS00700D</p>
        </div>
      </div>
      <p class="copyright"><?php echo $nome_app." - ver. ".$version; ?></p>
    </div>
</footer>