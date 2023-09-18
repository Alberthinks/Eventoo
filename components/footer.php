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

.copyright {
  font-size: 10px;
  margin-top: 25px;
  text-align: center;
}
</style>
<footer>
    <div class="footer-container">
      <div style="display: flex; justify-content: space-around;">
        <div style="float: left; margin-right: 60px;">
            Realizzato da Albertin Emanuele (5 B TI a.s. 2023/2024) per <a href="https://www.primolevi.edu.it/">I.I.S PRIMO LEVI di Badia Polesine</a>
        </div>
        <div style="float: right;">
          <a href="https://docs.google.com/forms/d/e/1FAIpQLScwcfgt9F1rB2XKPg8Nwj8yz1meMTF4CqX5KTJsMRVBs1QI6g/viewform?usp=sf_link" target="_blank" style="text-align: left; text-decoration: underline; color: white;">Feedback</a>
        </div>
      </div>
      <p class="copyright"><?php echo $nome_app." - ver. ".$version; ?></p>
    </div>
</footer>