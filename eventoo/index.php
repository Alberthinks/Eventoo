<?php
$view = $_GET['view'];
?>
<!DOCTYPE html>
    <html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home | Eventoo</title>
        <style>
            /* Contenuto della pagina */
            .content {padding: 163px 30px 40px 30px; font-size: 18px;}

            /* Accordion */
            .accordion {
            background-color: #d6d6d6;
            color: #444;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
            transition: 0.4s;
            }

            .active, .accordion:hover {
            background-color: #ccc;
            }

            .accordion:after {
            content: '\002B';
            color: #777;
            font-weight: bold;
            float: right;
            margin-left: 5px;
            }

            .active:after {
            content: "\2212";
            }

            .panel {
            /*padding: 18px 20px 18px 20px;*/
            background-color: #f0f0f0;
            height: 0;
            overflow: hidden;
            transition: height 0.2s ease-out;
            }
        </style>
    </head>
    <body>
        <?php
            include "header.php";
        ?>

        <div class="content">
        <?php
        echo $view; ?>
        <?php

        // Siamo nella sezione "Classi"
        if ($view == "classi") {
        ?>
        <button class="accordion">Section 1</button>
        <div class="panel">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
        </div>

        <button class="accordion">Section 2</button>
        <div class="panel">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
        </div>
        </div>
        <br><br><br><br><br><br><br><br><br>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

        <script>
        var acc = document.getElementsByClassName("accordion");
        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.height) {
                    panel.style.height = "0";
                } else {
                    panel.style.height = panel.scrollHeight + 20 + "px";
                } 
            });
        }
        </script>
        <?php
        }
        ?>
    </body>
</html>