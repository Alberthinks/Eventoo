<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400&display=swap');
    * {margin: 0; padding: 0;}
    header {width: 100%; background-color: dodgerblue; padding: 4px 16px 4px 16px; position: fixed; top: 0; left: 0; right: 0;}
    #top_menu {width: 98%; height: 85px; align-content: center;}
    .logo {float: left;}
    #account {float: right; border-radius: 50%; cursor: pointer;}
    #account:hover {background: rgba(255, 255, 255, 0.4);}
    .logo img {height: 60px; width: auto; margin-top: 12px; transition: height 0.3s;}
    #menu_elements {display: flex; transition: opacity 0.3s; position: relative; top: 0; z-index: 1; background-color: dodgerblue;}
    .element {padding: 10px 16px 10px 16px; margin-top: 12px; font-size: 18px; color: white; font-family: 'Noto Sans', sans-serif; letter-spacing: 0.4px;}
    .element {
        width: 100px;
        text-transform: uppercase;
        border-radius: 5px;
        text-align: center;
        cursor: pointer;
        transition: background 400ms;
        -webkit-transition-duration: background 400ms;
        transition-duration: background 400ms;
        text-decoration: none;
        user-select: none;
        overflow: hidden;
        position: relative;
    }
    

    .dropdown {
        position: relative;
        display: inline-block;
    }
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        overflow: auto;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown a:hover {background-color: #ddd;}

    .show {display: block;}

    .ripple {
        position: absolute;
        border-radius: 50%;
        transform: scale(0);
        animation: ripple 600ms linear;
        background-color: rgba(255, 255, 255, 0.3);
    }

    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
</style>

<?php
$_SESSION['session_foto_eventoo'] = "logos/baseline_account_circle_black_24dp.png";
?>

<header id="header">
    <div id="top_menu">
        <div class="logo"><img alt="Eventoo" src="logo.png" draggable="false" id="logo"></div>
        <div class="dropdown">
            <div id="account" onclick="openAccountOptions()">
                <img id="account_img" src="settings/gestione-utenti/nuovo/<?php echo $_SESSION['session_foto_eventoo']; ?>" alt="" draggable="false">
            </div>
            <div id="accountOptions" class="dropdown-content">
                <a href="#home">Home</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
            </div>
        </div>
    </div>
    <div id="menu_elements">
        <div class="element" onclick="setInterval(function () {location.href = '?view=classi';}, 400)">Classi</div>
        <div class="element" onclick="setInterval(function () {location.href = '?view=stanze';}, 400)">Stanze</div>
    </div>
</header>

<script>
    var buttons = document.getElementsByClassName('element');

    Array.prototype.forEach.call(buttons, function(b){
        b.addEventListener('click', createRipple);
    })

    function createRipple(e)
    {
        if(this.getElementsByClassName('ripple').length > 0)
        {
            this.removeChild(this.childNodes[1]);
        }
    
        var circle = document.createElement('div');
        this.appendChild(circle);
        
        var d = Math.max(this.clientWidth, this.clientHeight);
        circle.style.width = circle.style.height = d + 'px';
        
        circle.style.left = e.clientX - this.offsetLeft - d / 2 + 'px';
        circle.style.top = e.clientY - this.offsetTop - d / 2 + 'px';
        
        circle.classList.add('ripple');
    }

</script>
<script>
    // When the user scrolls down 80px from the top of the document, resize the navbar's padding and the logo's font size

    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
    if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {
        document.getElementById("menu_elements").style.display = "none";
        document.getElementById("menu_elements").style.opacity = "0";
        document.getElementById("logo").style.height = "50px";
    } else {
        document.getElementById("menu_elements").style.display = "flex";
        document.getElementById("menu_elements").style.opacity = "1";
        document.getElementById("logo").style.height = "60px";
    }
    }
</script>


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