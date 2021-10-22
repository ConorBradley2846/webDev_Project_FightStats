<?php

//GroundScr
//SigStrLanded
  session_start();
  if(isset($_GET['page'])){
    $page = $_GET['page'];

    if(isset($_GET['date'])){
        $date = $_GET['date'];
        $filter = "date={$date}";
        $endpoint = "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?fights={$page}&{$filter}";
        if(isset($_GET['weight'])){
          $weight =$_GET['weight'];
          $filter = "weight={$weight}";
          $endpoint .= "&{$filter}";
        }
    } elseif(isset($_GET['SigStrScr'])){
        $str =$_GET['SigStrScr'];
        $filter = "SigStrScr={$str}";
        $endpoint = "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?fights={$page}&{$filter}";
        if(isset($_GET['weight'])){
          $weight =$_GET['weight'];
          $filter = "weight={$weight}";
          $endpoint .= "&{$filter}";
        }
    } elseif(isset($_GET['GrdScr'])){
        $grp =$_GET['GrdScr'];
        $filter = "GrdScr={$grp}";
        $endpoint = "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?fights={$page}&{$filter}";
        if(isset($_GET['weight'])){
          $weight =$_GET['weight'];
          $filter = "weight={$weight}";
          $endpoint .= "&{$filter}";
        }
    }

    $result = file_get_contents($endpoint);
    $fightsData = json_decode($result, true);

  }

  //Example finished endpoint
  //http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?fights=1&SigStrScr=highest&weight=1


?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UFC FightData: Fights Index</title>
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

        <!-- Welcome message row -->
        <div class="row">
          <section>

            <div class="welcome has-text-left pl-6">
              <h1 class="title noFade">
                <strong>UFC fights index:</strong>
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
                                <!-- Winner -->
                                <th class="is-fullwidth">
                                    <span>Winner</span>    
                                </th>
                                <!-- vs.-->
                                <th class="is-fullwidth">
                                    <span> </span>    
                                </th>
                                <!-- Loser -->
                                <th class="is-fullwidth">
                                    <span>Loser</span>    
                                </th>
                                <!-- Fight Date -->
                                <th>
                                    <div class="dropdown">
                                        <div class="dropdown-trigger">
                                          <button class="button is-dark" aria-haspopup="true" aria-controls="dropdown-menu">
                                            <span>Date</span>
                                            <span class="icon is-small">
                                              <i class="fas fa-angle-down" aria-hidden="true"></i>
                                            </span>
                                          </button>
                                        </div>
                                        <div class="dropdown-menu" id="dropdown-menu" role="menu">
                                            <div class="dropdown-content">
                                              <?php
                                                if(isset($_GET['weight'])){
                                                  echo "<a href='fights.php?page={$page}&weight={$weight}&date=newest' class='dropdown-item'>
                                                          Most recent
                                                        </a>
                                                        <a href='fights.php?page={$page}&weight={$weight}&date=oldest' class='dropdown-item'>
                                                          Oldest
                                                        </a>";
                                                } else {
                                                  echo "<a href='fights.php?page={$page}&date=newest' class='dropdown-item'>
                                                          Most recent
                                                        </a>
                                                        <a href='fights.php?page={$page}&date=oldest' class='dropdown-item'>
                                                          Oldest
                                                        </a>";
                                                }
                                                
                                              ?>
                                        
                                            </div>
                                        </div>
                                    </div>
                                </th>
                                <!-- Fight Weight -->
                                <th>
                                    <div class="dropdown">
                                        <div class="dropdown-trigger">
                                          <button class="button is-dark" aria-haspopup="true" aria-controls="dropdown-menu">
                                            <span>Weight Class</span>
                                            <span class="icon is-small">
                                              <i class="fas fa-angle-down" aria-hidden="true"></i>
                                            </span>
                                          </button>
                                        </div>
                                        <div class="dropdown-menu" id="dropdown-menu" role="menu">
                                          <div class="dropdown-content">
                                          <?php
                                              if(!isset($_GET['date'])){
                                                echo "<a href='fights.php?page={$page}&date=newest&{$filter}' class='dropdown-item'>
                                                        Clear Selection
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=1&{$filter}' class='dropdown-item'>
                                                        Women's Strawweight Bout
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=2&{$filter}' class='dropdown-item'>
                                                      Women's Flyweight Bout
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=3&{$filter}' class='dropdown-item'>
                                                      Women's Bantamweight Bout                                            
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=4&{$filter}' class='dropdown-item'>
                                                      Women's Featherweight Bout                                            
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=5&{$filter}' class='dropdown-item'>
                                                      UFC Women's Strawweight Title Bout                                            
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=6&{$filter}' class='dropdown-item'>
                                                      UFC Women's Flyweight Title Bout                                           
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=7&{$filter}' class='dropdown-item'>
                                                      UFC Women's Bantamweight Title Bout                                           
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=8&{$filter}' class='dropdown-item'>
                                                      UFC Women's Featherweight Title Bout                                          
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=9&{$filter}' class='dropdown-item'>
                                                      Flyweight Bout                                         
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=10&{$filter}' class='dropdown-item'>
                                                      UFC Flyweight Title Bout                                           
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=11&{$filter}' class='dropdown-item'>
                                                      Bantamweight Bout                                         
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=12&{$filter}' class='dropdown-item'>
                                                      UFC Bantamweight Title Bout                                           
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=13&{$filter}' class='dropdown-item'>
                                                      Featherweight Bout                                           
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=14&{$filter}' class='dropdown-item'>
                                                      UFC Featherweight Title Bout                                           
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=15&{$filter}' class='dropdown-item'>
                                                      Lightweight Bout                                          
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=16&{$filter}' class='dropdown-item'>
                                                      UFC Lightweight Title Bout                                         
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=17&{$filter}' class='dropdown-item'>
                                                      Welterweight Bout                                         
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=18&{$filter}' class='dropdown-item'>
                                                      UFC Welterweight Title Bout                                         
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=19&{$filter}' class='dropdown-item'>
                                                      Middleweight Bout                                           
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=20&{$filter}' class='dropdown-item'>
                                                      UFC Middleweight Title Bout                                           
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=21&{$filter}' class='dropdown-item'>
                                                      Light Heavyweight Bout                                         
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=22&{$filter}' class='dropdown-item'>
                                                      UFC Light Heavyweight Title Bout                                         
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=23&{$filter}' class='dropdown-item'>
                                                      Heavyweight Bout                                         
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=24&{$filter}' class='dropdown-item'>
                                                      UFC Heavyweight Title Bout                                           
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=25&{$filter}' class='dropdown-item'>
                                                      Catch Weight Bout                                           
                                                      </a>";
                                              } else {
                                                echo "<a href='fights.php?page={$page}&date={$date}' class='dropdown-item'>
                                                        Clear Selection
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=1&date={$date}' class='dropdown-item'>
                                                      Women's Strawweight Bout
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=2&date={$date}' class='dropdown-item'>
                                                      Women's Flyweight Bout
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=3&date={$date}' class='dropdown-item'>
                                                      Women's Bantamweight Bout                                            
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=4&date={$date}' class='dropdown-item'>
                                                      Women's Featherweight Bout                                            
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=5&date={$date}' class='dropdown-item'>
                                                      UFC Women's Strawweight Title Bout                                            
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=6&date={$date}' class='dropdown-item'>
                                                      UFC Women's Flyweight Title Bout                                           
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=7&date={$date}' class='dropdown-item'>
                                                      UFC Women's Bantamweight Title Bout                                           
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=8&date={$date}' class='dropdown-item'>
                                                      UFC Women's Featherweight Title Bout                                          
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=9&date={$date}' class='dropdown-item'>
                                                      Flyweight Bout                                         
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=10&date={$date}' class='dropdown-item'>
                                                      UFC Flyweight Title Bout                                           
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=11&date={$date}' class='dropdown-item'>
                                                      Bantamweight Bout                                         
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=12&date={$date}' class='dropdown-item'>
                                                      UFC Bantamweight Title Bout                                           
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=13&date={$date}' class='dropdown-item'>
                                                      Featherweight Bout                                           
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=14&date={$date}' class='dropdown-item'>
                                                      UFC Featherweight Title Bout                                           
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=15&date={$date}' class='dropdown-item'>
                                                      Lightweight Bout                                          
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=16&date={$date}' class='dropdown-item'>
                                                      UFC Lightweight Title Bout                                         
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=17&date={$date}' class='dropdown-item'>
                                                      Welterweight Bout                                         
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=18&date={$date}' class='dropdown-item'>
                                                      UFC Welterweight Title Bout                                         
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=19&date={$date}' class='dropdown-item'>
                                                      Middleweight Bout                                           
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=20&date={$date}' class='dropdown-item'>
                                                      UFC Middleweight Title Bout                                           
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=21&date={$date}' class='dropdown-item'>
                                                      Light Heavyweight Bout                                         
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=22&date={$date}' class='dropdown-item'>
                                                      UFC Light Heavyweight Title Bout                                         
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=23&date={$date}' class='dropdown-item'>
                                                      Heavyweight Bout                                         
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=24&date={$date}' class='dropdown-item'>
                                                      UFC Heavyweight Title Bout                                           
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight=25&date={$date}' class='dropdown-item'>
                                                      Catch Weight Bout                                           
                                                      </a>";
                                              }
                                              
                                            ?>
                              
                                          </div>
                                        </div>
                                    </div>
                                </th>
                                <!-- Tot.Sig Str -->
                                <th>
                                    <div class="dropdown" id ="dropdown7">
                                        <div class="dropdown-trigger">
                                          <button class="button is-dark" aria-haspopup="true" aria-controls="dropdown-menu7">
                                            <span><abbr title="Total Signicant Stirkes">Tot.Sig Str</abbr></span>
                                            <span class="icon is-small">
                                              <i class="fas fa-angle-down" aria-hidden="true"></i>
                                            </span>
                                          </button>
                                        </div>
                                        <div class="dropdown-menu" id="dropdown-menu7" role="menu">
                                          <div class="dropdown-content">
                                            <?php
                                              if(isset($_GET['weight'])){
                                                echo "<a href='fights.php?page={$page}&weight={$weight}&SigStrScr=highest' class='dropdown-item'>
                                                        Highest
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight={$weight}&SigStrScr=lowest' class='dropdown-item'>
                                                        Lowest
                                                      </a>";
                                              } else {
                                                echo "<a href='fights.php?page={$page}&SigStrScr=highest' class='dropdown-item is-active'>
                                                      Highest
                                                      </a>
                                                      <a href='fights.php?page={$page}&SigStrScr=lowest' class='dropdown-item'>
                                                        Lowest
                                                      </a>";
                                              }
                                              
                                            ?>
                                          </div>
                                        </div>
                                    </div>
                                </th>
                                <!-- Grappling Score -->
                                <th>
                                    <div class="dropdown" id ="dropdown7">
                                        <div class="dropdown-trigger">
                                          <button class="button is-dark" aria-haspopup="true" aria-controls="dropdown-menu7">
                                            <span><abbr title="Grappling Score">Grp.Scr</abbr></span>
                                            <span class="icon is-small">
                                              <i class="fas fa-angle-down" aria-hidden="true"></i>
                                            </span>
                                          </button>
                                        </div>
                                        <div class="dropdown-menu" id="dropdown-menu7" role="menu">
                                          <div class="dropdown-content">
                                          <?php
                                              if(isset($_GET['weight'])){
                                                echo "<a href='fights.php?page={$page}&weight={$weight}&GrdScr=highest' class='dropdown-item'>
                                                        Highest
                                                      </a>
                                                      <a href='fights.php?page={$page}&weight={$weight}&GrdScr=lowest' class='dropdown-item'>
                                                        Lowest
                                                      </a>";
                                              } else {
                                                echo "<a href='fights.php?page={$page}&GrdScr=highest' class='dropdown-item is-active'>
                                                      Highest
                                                      </a>
                                                      <a href='fights.php?page={$page}&GrdScr=lowest' class='dropdown-item'>
                                                        Lowest
                                                      </a>";
                                              }
                                              
                                            ?>
                                          </div>
                                        </div>
                                    </div>
                                </th>
                                <!-- Fight's event -->
                                <th class="is-fullwidth">
                                            <span>Event</span>    
                                </th>
                                <!-- Link to fight page -->
                                <th class="is-fullwidth">
                                    <span><abbr title="Full fight">Link</abbr></span>    
                                </th>
                              </tr>
                            </thead>
                            <tbody>

                              <?php

                                      foreach ($fightsData as $fight){?>
                                        
                                        <tr>
                                        <td><?php echo $fight["Winner"];?></td>
                                        <td><?php echo "vs.";?></td>
                                        <td><?php echo $fight["Loser"];?></td>
                                        <td><?php echo $fight["EventDate"];?></td>
                                        <td><?php echo $fight["weightName"];?></td>
                                        <td><?php echo '<span class="tag is-danger">' . $fight["SigStrLanded"] . '</span>';?></td>
                                        <td><?php echo '<span class="tag is-danger">' . $fight["GroundScr"] . '</span>';?></td>
                                        <th><?php echo "<a href='event.php?eventid={$fight["Event"]}'>" . $fight["EventName"] . "</a>";?></th>
                                        <td><?php echo "<a href='fight.php?fightid={$fight["FightID"]}'> Fight page </a>";?></td>
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
                              echo "<a href='fights.php?date={$date}&page={$nextPage}'>Next Page</a>";
                            } else {
                              $prePage = $page - 1;
                              $nextPage = $page+1;
                              echo "<a href='fights.php?date={$date}&page={$prePage}'>Pervious Page     </a>       <a href='fights.php?date={$date}&page={$nextPage}'>Next Page</a>";
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