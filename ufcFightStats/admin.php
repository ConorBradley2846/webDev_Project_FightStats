<?php
    session_start();
    if(!isset($_SESSION['fightsAdmin'])){
        header("Location: index.php");
    }

    include("connections/conn.php");

    $currentuser = $_SESSION['fightsAdmin'];

    $current = "SELECT * FROM users WHERE userID=$currentuser";

    $result = $conn->query($current);

    if(!$result){
        echo $conn->error;
    }

    //dont use a while loop here as only one user so one row generally.
    $userdetails = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UFC Fight Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <link rel="stylesheet" href="css/ui.css">
    <!--<link rel="stylesheet" href="debug.css">-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" 
            integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" 
            crossorigin="anonymous">
    </script>

    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>

    <script>

        $(document).ready(function() {

        // Check for click events on the navbar burger icon
        $(".navbar-burger").click(function() {

            // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
            $(".navbar-burger").toggleClass("is-active");
            $(".navbar-menu").toggleClass("is-active");

        });

        $('#fighttab').click(function(){
            $('#adminform').load('adminaddform.php #fightform', function() {
            });
        });

        $('#eventtab').click(function(){
            $('#adminform').load('adminaddform.php #eventform', function() {
            });
        });
    
        });

    </script>

  </head>

  <body class="has-navbar-fixed-top">

    <nav class="navbar is-fixed-top is-dark" role="navigation" aria-label="main navigation">
      <div class="navbar-brand">
        <a class="navbar-item" href="\ufcFightStats\index.php">
          <img src="images/ufclogo.png" width="112" height="28">
        </a>
    
        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
        </a>
      </div>
    
      <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">
          <a class="navbar-item" href="events.php?page=1&date=newest">
            Events
          </a>
          <a class="navbar-item" href="fights.php?page=1&date=newest">
            Fights
          </a>
        </div>
    
        <div class="navbar-end">
          <div class="navbar-item">
            <div class="buttons">
                <a class='button is-dark' href='security/logout.php'>
                    Log out
                </a>
            </div>
          </div>
        </div>
      </div>
    </nav>

    
    <div id="events-container">
      <div id="content-wrap">

        <!-- Welcome message row -->
        <div class="row">
          <section>

            <div class="welcome has-text-left pl-6">
              <h1 class="title noFade">
                <strong>Administration:</strong>
              </h1>
            </div>
          </section>
        </div>

        <div class="columns m-1" style="overflow-x: auto;">

           <!-- Panel Col -->
            <div class="column is-3" id="indexmaincards">

                <div class="box">
                    <nav class="panel">
                        <a class="panel-block has-text-white is-active" id="fighttab"> Fight </a>
                        <a class="panel-block has-text-white" id="eventtab"> Event </a>
                    </nav>
                </div>

            </div>

          <!-- main Col -->
          <div class="column p-2">

            <div class="box" id="adminform">

            </div>

          </div>

        </div>

      </div>

      
    <footer class="footer">
        <div class="content has-text-centered">
        <p>
            <b>UFC Fight Data</b> by Conor Bradley. All rights reserved.
        </p>
        </div>
    </footer>

    </div>



  </body>

</html>