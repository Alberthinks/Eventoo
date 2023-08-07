// Username

function focusFunction() {
    document.getElementById("placeholder").style.top = "18px";
    document.getElementById("placeholder").style.bottom = "0px";
    document.getElementById("placeholder").style.fontSize = "14.6px";
    document.getElementById("uname").style.border = "1.5px solid #333";
  }
  
  function blurFunction() {
    document.getElementById("placeholder").style.bottom = "36px";
    document.getElementById("placeholder").style.top = "40px";
    document.getElementById("placeholder").style.fontSize = "16px";
    document.getElementById("uname").style.border = "1px solid #ccc";
  }
  
  function compila() {
    setTimeout(function () {
    document.getElementById("placeholder").style.top = "18px";
    document.getElementById("placeholder").style.bottom = "0px";
    document.getElementById("placeholder").style.color = "#d60027";
    document.getElementById("placeholder").style.fontSize = "14.6px";
    document.getElementById("uname").style.border = "1.5px solid #d60027";
    }, 100);
  }
  
  function checkinput() {
  if (document.getElementById("uname").value=="" || document.getElementById("uname").value==null) {
    document.getElementById("uname").removeEventListener("blur", focusFunction);
    document.getElementById("placeholder").removeEventListener("blur", focusFunction);
    
    document.getElementById("uname").addEventListener("focus", focusFunction);
    document.getElementById("placeholder").addEventListener("focus", focusFunction);
    document.getElementById("uname").addEventListener("blur", blurFunction);
    document.getElementById("placeholder").addEventListener("blur", blurFunction);
  } else {
    document.getElementById("uname").removeEventListener("focus", focusFunction);
    document.getElementById("placeholder").removeEventListener("focus", focusFunction);
    document.getElementById("uname").removeEventListener("blur", blurFunction);
    document.getElementById("placeholder").removeEventListener("blur", blurFunction);
    
    document.getElementById("uname").addEventListener("blur", focusFunction);
    document.getElementById("placeholder").addEventListener("blur", focusFunction);
  }
  }
  
  // Password
  
  function focusFunctionPsw() {
    document.getElementById("placeholderpsw").style.top = "18px";
    document.getElementById("placeholderpsw").style.bottom = "0px";
    document.getElementById("placeholderpsw").style.fontSize = "14.6px";
    document.getElementById("password").style.border = "1.5px solid #333";
  }
  
  function blurFunctionPsw() {
    document.getElementById("placeholderpsw").style.bottom = "36px";
    document.getElementById("placeholderpsw").style.top = "40px";
    document.getElementById("placeholderpsw").style.fontSize = "16px";
    document.getElementById("password").style.border = "1px solid #ccc";
  }
  
  function compilaPsw() {
    setTimeout(function () {
    document.getElementById("placeholderpsw").style.top = "18px";
    document.getElementById("placeholderpsw").style.bottom = "0px";
    document.getElementById("placeholderpsw").style.color = "#d60027";
    document.getElementById("placeholderpsw").style.fontSize = "14.6px";
    document.getElementById("password").style.border = "1.5px solid #d60027";
    }, 100);
  }
  
  function checkinputPsw() {
    if (document.getElementById("password").value=="" || document.getElementById("password").value==null) {
        document.getElementById("password").removeEventListener("blur", focusFunctionPsw);
        document.getElementById("placeholderpsw").removeEventListener("blur", focusFunctionPsw);
        
        document.getElementById("password").addEventListener("focus", focusFunctionPsw);
        document.getElementById("placeholderpsw").addEventListener("focus", focusFunctionPsw);
        document.getElementById("password").addEventListener("blur", blurFunctionPsw);
        document.getElementById("placeholderpsw").addEventListener("blur", blurFunctionPsw);
    } else {
        document.getElementById("password").removeEventListener("focus", focusFunctionPsw);
        document.getElementById("placeholderpsw").removeEventListener("focus", focusFunctionPsw);
        document.getElementById("password").removeEventListener("blur", blurFunctionPsw);
        document.getElementById("placeholderpsw").removeEventListener("blur", blurFunctionPsw);
        
        document.getElementById("password").addEventListener("blur", focusFunctionPsw);
        document.getElementById("placeholderpsw").addEventListener("blur", focusFunctionPsw);
    }
  }

  // Mostra / Nascondi password

function showPsw() {
    var x = document.getElementById("password");
    var btn = document.getElementById("showBtn");
    if (x.type === "password") {
      x.type = "text";
      btn.innerHTML = "visibility_off";
      btn.title = "Nascondi password";
    } else {
      x.type = "password";
      btn.innerHTML = "visibility";
      btn.title = "Mostra password";
    }
  }

  // Verifica riempimento di tutti i campi

function confirmation() {
    if (document.getElementById("uname").value=="" && document.getElementById("password").value=="" || document.getElementById("uname").value==null && document.getElementById("password").value==null) {
       compilaPsw();
       document.getElementById("uname").focus();
       compila();
   } else if (document.getElementById("uname").value=="" || document.getElementById("uname").value==null) {
       document.getElementById("uname").focus();
       compila();
   } else if (document.getElementById("password").value=="" || document.getElementById("password").value==null) {
       document.getElementById("password").focus();
       compilaPsw();
   } else {
       document.enter.submit();
   }
}

// Inviare login

function inviaLogin(event){
	// ottengo il codice unicode del tasto premuto sfruttando la proprietà keyCode dell'oggetto event
	var codiceTasto = event.keyCode;
	// trasformo il codice unicode in carattere
	if (codiceTasto == 13) {
		document.getElementById("submit").click();
	}
}

// Password dimenticata

function forgottenPsw() {
  /*var forgottenForm = document.getElementById("forgottenForm");
  var forgottenDiv = document.getElementById("background_div");
  forgottenForm.style.display = "block";
  forgottenDiv.style.display = "block";
  document.getElementById("forgottenFrame").setAttribute("src", "https://docs.google.com/forms/d/e/1FAIpQLSeeNopa2iDeLLgzPpEdh2hK5dKud3X8xD7Z5n02bh_w9yirVA/viewform?embedded=true");*/
  alert("Per recuperare le credenziali contatta l'amministratore!\nIl tuo amministratore è: Amos Golinelli.");
}

function closeForgotten() {
  var forgottenForm = document.getElementById("forgottenForm");
  var forgottenDiv = document.getElementById("background_div");
  forgottenForm.style.display = "none";
  forgottenDiv.style.display = "none";
}
