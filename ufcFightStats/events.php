<?php

    session_start();
    if(isset($_GET['page'])){
      $page = $_GET['page'];

      $endpoint = "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?allevent={$page}";

      if(isset($_GET['date'])){
          $date = $_GET['date'];
          $filter = "date={$date}";
          $endpoint = "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?allevent={$page}&date={$date}";
      } elseif(isset($_GET['landedStr'])){
          $str =$_GET['landedStr'];
          $filter = "landedStr={$str}";
          $endpoint = "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?allevent={$page}&landedStr={$str}";
      } elseif(isset($_GET['grpScr'])){
          $grp =$_GET['grpScr'];
          $filter = "grpScr={$grp}";
          $endpoint = "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?allevent={$page}&grpScr={$grp}";
      } elseif(isset($_GET['finRate'])){
          $fin =$_GET['finRate'];
          $filter = "finRate={$fin}";
          $endpoint = "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?allevent={$page}&finRate={$fin}";
      }

      $result = file_get_contents($endpoint);
      $eventsData = json_decode($result, true);
    }
    

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
          
          $('.dropdown').on('click', function() {
              // Toggle the "is-active" class on both the table filter dropdown
              $( this ).toggleClass("is-active");

            });

          $('.dropdown-item').on('click', function() {
              // Toggle the "is-active" class on both the table filter dropdown
              $( this ).toggleClass("is-active");

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

            <a class="navbar-item" href="events.php?page=1&date=newest">
                Events
            </a>
            <a class="navbar-item" href="fights.php?page=1&date=newest">
                Fights
            </a>
  
          </div>
        </div>
    
        <div class="navbar-end">
          <div class="navbar-item">
            <div class="buttons">
              <?php
                if(isset($_SESSION['fightsAdmin'])){
                      echo "<a class='button is-black' href='admin.php'>
                              Admin
                            </a>
                            <a class='button is-dark' href='security/logout.php'>
                              Log out
                            </a>";
                  } elseif(isset($_SESSION['fightsUser'])) {
                      echo "<a class='button is-black' href='userprofile.php'>
                              Profile
                            </a>
                            <a class='button is-dark' href='security/logout.php'>
                              Log out
                            </a>";
                  } else {
                      echo "<a class='button is-black' href='signup.php'>
                              <strong>Sign up</strong>
                            </a>
                            <a class='button is-dark' href='security/login.php'>
                              Log in
                            </a>";
                  }
              ?>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <div id="events-container">
      <div id="content-wrap">

        <!-- Page Title row -->
        <div class="row">
          <section>

            <div class="welcome has-text-left pl-6">
              <h1 class="title noFade">
                <strong>UFC events index:</strong>
              </h1>
            </div>
          </section>
        </div>

        <div class="columns m-1" style="overflow-x: auto;">
           <!-- Events table -->
          <div class="column is-full" id="indexmaincards">
            <div class="box">
                    <div class="table-container">
                        <table class="table noFade has-text-white is-fullwidth" id="eventsTable">
                            <thead>
                              <tr>
                                <!-- Title -->
                                <th class="is-fullwidth">
                                            <span>Title</span>    
                                </th>
                                <!-- Venue -->
                                <th class="is-fullwidth">
                                            <span>Venue</span>    
                                </th>
                                <!-- Location -->
                                <th class="is-fullwidth">
                                            <span>Location</span>    
                                </th>
                                <!-- Date -->
                                <th>
                                    <div class="dropdown" id ="dropdown4">
                                        <div class="dropdown-trigger">
                                          <button class="button is-dark" aria-haspopup="true" aria-controls="dropdown-menu4">
                                            <span>Date</span>
                                            <span class="icon is-small">
                                              <i class="fas fa-angle-down" aria-hidden="true"></i>
                                            </span>
                                          </button>
                                        </div>
                                        <div class="dropdown-menu" id="dropdown-menu4" role="menu">
                                          <div class="dropdown-content">
                                            <a href="events.php?date=newest&page=<?php echo $page;?>" class='dropdown-item'>
                                              Most recent
                                            </a> 
                                            <a href="events.php?date=oldest&page=<?php echo $page;?>" class='dropdown-item'>
                                              Oldest
                                            </a>
                  
                                          </div>
                                        </div>
                                    </div>
                                </th>
                                <!-- Landed.Str -->
                                <th>
                                    <div class="dropdown" id ="dropdown5">
                                        <div class="dropdown-trigger">
                                          <button class="button is-dark" aria-haspopup="true" aria-controls="dropdown-menu5">
                                            <span><abbr title="Average Strikes Landed per fight">Landed.Str</abbr></span>
                                            <span class="icon is-small">
                                              <i class="fas fa-angle-down" aria-hidden="true"></i>
                                            </span>
                                          </button>
                                        </div>
                                        <div class="dropdown-menu" id="dropdown-menu5" role="menu">
                                          <div class="dropdown-content">
                                            <a href="events.php?landedStr=highest&page=<?php echo $page;?>" class="dropdown-item">
                                              Highest
                                            </a>
                                            <a href="events.php?landedStr=lowest&page=<?php echo $page;?>" class="dropdown-item">
                                              Lowest
                                            </a>
                                          </div>
                                        </div>
                                    </div>
                                </th>
                                <!-- Grappling.Scr -->
                                <th>
                                    <div class="dropdown" id ="dropdown6">
                                        <div class="dropdown-trigger">
                                          <button class="button is-dark" aria-haspopup="true" aria-controls="dropdown-menu6">
                                            <span><abbr title="Grappling Score">Grp.Scr</abbr></span>
                                            <span class="icon is-small">
                                              <i class="fas fa-angle-down" aria-hidden="true"></i>
                                            </span>
                                          </button>
                                        </div>
                                        <div class="dropdown-menu" id="dropdown-menu6" role="menu">
                                          <div class="dropdown-content">
                                            <a href="events.php?grpScr=highest&page=<?php echo $page;?>" class="dropdown-item">
                                              Highest
                                            </a>
                                            <a href="events.php?grpScr=lowest&page=<?php echo $page;?>" class="dropdown-item">
                                              Lowest
                                            </a>
                                          </div>
                                        </div>
                                    </div>
                                </th>
                                <!-- Finish Rate -->
                                <th>
                                    <div class="dropdown" id ="dropdown7">
                                        <div class="dropdown-trigger">
                                          <button class="button is-dark" aria-haspopup="true" aria-controls="dropdown-menu7">
                                            <span><abbr title="Grappling Score">Finish Rate</abbr></span>
                                            <span class="icon is-small">
                                              <i class="fas fa-angle-down" aria-hidden="true"></i>
                                            </span>
                                          </button>
                                        </div>
                                        <div class="dropdown-menu" id="dropdown-menu7" role="menu">
                                          <div class="dropdown-content">
                                            <a href="events.php?finRate=highest&page=<?php echo $page;?>" class="dropdown-item">
                                              Highest
                                            </a>
                                            <a href="events.php?finRate=lowest&page=<?php echo $page;?>" class="dropdown-item">
                                              Lowest
                                            </a>
                                          </div>
                                        </div>
                                    </div>
                                </th>
                              </tr>
                            </thead>
                            <tbody>

                              <?php

                                    foreach ($eventsData as $event){?>
                                        
                                        <tr>
                                        <th><?php echo "<a href='event.php?eventid={$event["Event"]}'>" . $event["EventName"] . "</a>";?></th>
                                        <td><?php echo $event["VenueName"];?></td>
                                        <td><?php echo $event["LocationName"];?></td>
                                        <td><?php echo $event["EventDate"];?></td>
                                        <td><?php echo '<span class="tag is-danger">' . $event["LandedStrAvg"] . '</span>';?></td>
                                        <td><?php echo '<span class="tag is-danger">' . $event["GrpScoreAvg"] .'</span>';?></td>
                                        <td><?php echo '<span class="tag is-danger">' . $event["FinishPercent"] .'</span>';?></td>
                                        </tr>
                                
                              <?php } ?>
   
                                </tbody>
                            </table>
                    </div>
                


                    <div class="columns noFade">
                      <div class="column noFade has-text-centered">
                        <?php 
                            if($page <= 1){
                              $nextPage = $page+1;
                              echo "<a href='events.php?{$filter}&page={$nextPage}'>Next Page</a>";
                            } else {
                              $prePage = $page - 1;
                              $nextPage = $page+1;
                              echo "<a href='events.php?{$filter}&page={$prePage}'>Pervious Page     </a>       <a href='events.php?{$filter}&page={$nextPage}'>Next Page</a>";
                            }
                            
                        ?>
                    
                    </div>

            </div>

          </div>

          <!-- Right margin Col -->
          <div class="column"></div>

        </div>


        <footer class="footer">
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