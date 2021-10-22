<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UFC FightData: Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <link rel="stylesheet" href="../css/ui.css">
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
        });

    </script>

  </head>

  <body class="has-navbar-fixed-top" id="signuppage">

    <nav class="navbar is-fixed-top is-dark" role="navigation" aria-label="main navigation">
      <div class="navbar-brand">
        <a class="navbar-item" href="..\index.php">
          <img src="..\images\ufclogo.png" width="112" height="28">
        </a>
    
        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
        </a>
      </div>
    
      <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">

            <div class="navbar-item">
              <form action="search.php" method="post">
                <p class="control has-icons-right";>
                  <input class="input" name="searchstring" type="text" required="required" value="" placeholder="Search for Event or fight:">
                  <span class="icon is-small is-right is-dark">
                    <i class="fas fa-search"></i>
                  </span>
                </p>
              </form>
            </div>

          <a class="navbar-item" href="..\events.php?page=1&date=newest">
            Events
          </a>
          <a class="navbar-item" href="..\fights.php?page=1&date=newest">
            Fights
          </a>

        </div>
    
        <div class="navbar-end">
          <div class="navbar-item">
            <div class="buttons">
              <a class="button is-black" href="..\signup.php">
                <strong>Sign up</strong>
              </a>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <div id="signup-container">

        <div id="content-wrap">

            <div class="column is-7 ml-6 mr-6">

                <div class="box">

                        <h1 class="title noFade">Log in</h1>

                        <?php
                            if(isset($_GET['invalid'])){
                                echo "<h1 class='subtitle noFade'>Invalid email or password: Please try again.</h1>";
                            }
                        ?>
        
                        <?php
                            if(isset($_GET['newacc'])){
                                echo "<h1 class='subtitle noFade'>Account Created. You may now favorite fights and events. Please login to begin.</h1>";
                            }
                        ?>

                        <form action="signin.php" method="post">
                            
                            <div class="columns noFade">
                                <div class="field">
                                    <label class="label">Email</label>
                                    <p class="control has-icons-left">
                                        <input class="input is-medium" name="useremail" type="email" required="required" value="" placeholder="chael@goat.com">
                                        <span class="icon is-small is-left"> <i class="fas fa-envelope"></i> </span>
                                    </p>
                                    
                                </div>
                            </div>

                            <div class="columns noFade">
                                <div class="field">
                                    <label class="label">Password</label>
                                    <p class="control has-icons-left">
                                        <input class="input is-medium" name="passfield" type="password" required="required" value="" placeholder="Password">
                                        <span class="icon is-small is-left">
                                        <i class="fas fa-lock"></i>
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="columns noFade">
                                <div class="field is-grouped">
                                    <div class="control">
            
                                        <input  class="button is-link" name="submit" type="submit" value="Login"></input>
                                        
                                    </div>
                                </div>
                            </div>

                        </form>

                </div>
            </div>
        
            <footer class="footer is-fixed-bottom">
                <div class="content has-text-centered">
                <p>
                    <b>UFC Fight Data</b> by Conor Bradley. All rights reserved.
                </p>
                </div>
            </footer>

        </div>
    </div>
 
  </body>

  

</html>