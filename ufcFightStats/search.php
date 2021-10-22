<?php
    session_start();

    $searchstring = $_POST["searchstring"];

?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UFC Fight Data: Search Result</title>
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

            //Search results fights table
            $.ajax({
                type: "GET",
                url: "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?searchfights=<?php echo $searchstring; ?>",
                dataType: "json",
                success: function(res){
                var table = $("#favfightsTable tbody");
                for(var i = (res.length-1); i >= 0; i--){
                    table.append("<tr><td>"+res[i]["Winner"]+"</td> <td>vs.</td> <td>"+res[i]["Loser"]+"</td> <td>"+res[i]["EventDate"]+"</td> <td>"+res[i]["weightName"]+"</td> <td><span class='tag is-danger'>"+res[i]["SigStrLanded"]+"</span></td> <td><span class='tag is-danger'>"+res[i]["GroundScr"]+"</span></td> <td> <a href='fight.php?fightid="+res[i]["FightID"]+"'> Fight page </a></td> </tr>");
                }
                
                },
                error: function(err) {
                alert(err);
                }
            });

            //Fav Events table
            $.ajax({
                type: "GET",
                url: "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?searchevents=<?php echo $searchstring; ?>",
                dataType: "json",
                success: function(res){
                var table = $("#favEventsTable tbody");
                for(var i = (res.length-1); i >= 0; i--){
                    table.append("<tr><td> <a href='event.php?eventid="+res[i]["Event"]+"'>"+ res[i]["EventName"]+" </a></td> <td>"+res[i]["VenueName"]+"</td> <td>"+res[i]["LocationName"]+"</td> <td>"+res[i]["EventDate"]+"</td> <td><span class='tag is-danger'>"+res[i]["LandedStrAvg"]+"</span></td> <td><span class='tag is-danger'>"+res[i]["GrpScoreAvg"]+"</span></td> <td><span class='tag is-danger'>"+res[i]["FinishPercent"]+"</span></td> </tr>");
                }
                
                },
                error: function(err) {
                alert(err);
                }
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
                <strong>Result of search for: <?php echo $searchstring; ?></strong>
              </h1>
            </div>
          </section>
        </div>

        <!-- Fav fights row table -->
        <div class="row" id="indexmaincards">
          <div class="column is-full mb-4 px-2">
            <div class="box">
                    <div class="table-container">
                    <h1 class="title fighterTitle noFade">
                        <strong> Fights: </strong>
                    </h1>
                    <table class="table noFade has-text-white is-fullwidth tabletext" id="favfightsTable">
                            <thead>
                              <tr>
                                <!-- Winner -->
                                <th class="is-fullwidth has-text-white">
                                    <span>Winner</span>    
                                </th>
                                <!-- vs.-->
                                <th class="is-fullwidth has-text-white">
                                    <span> </span>    
                                </th>
                                <!-- Loser -->
                                <th class="is-fullwidth has-text-white">
                                    <span>Loser</span>    
                                </th>
                                <!-- Fight Date -->
                                <th class="is-fullwidth has-text-white">
                                    <span>Date</span>    
                                </th>
                                <!-- Fight Weight -->
                                <th class="is-fullwidth has-text-white">
                                    <span>Weight Class</span>    
                                </th>
                                <!-- Tot.Sig Str -->
                                <th class="is-fullwidth has-text-white">
                                    <span><abbr title="Total Signicant Strikes">Tot.Sig Str</abbr></span>    
                                </th>
                                <!-- Grappling Score -->
                                <th class="is-fullwidth has-text-white">
                                    <span><abbr title="Grappling Score">Grp Scr</abbr></span>    
                                </th>
                                <!-- Link to fight page -->
                                <th class="is-fullwidth has-text-white">
                                    <span><abbr title="Full fight">Link</abbr></span>    
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
            </div>
          </div>
        </div>

        <!-- Fav Events row table -->
        <div class="row" id="indexmaincards">
          <div class="column is-full mb-4 px-2">
            <div class="box">
                    <div class="table-container">
                    <h1 class="title fighterTitle noFade">
                        <strong> Events: </strong>
                    </h1>
                    <table class="table noFade has-text-white is-fullwidth tabletext" id="favEventsTable">
                            <thead>
                              <tr>
                                <!-- Winner -->
                                <th class="is-fullwidth has-text-white">
                                    <span>Title</span>    
                                </th>
                                <!-- Loser -->
                                <th class="is-fullwidth has-text-white">
                                    <span>Venue</span>    
                                </th>
                                <!-- Fight Date -->
                                <th class="is-fullwidth has-text-white">
                                    <span>Location</span>    
                                </th>
                                <!-- Fight Weight -->
                                <th class="is-fullwidth has-text-white">
                                    <span>Date</span>    
                                </th>
                                <!-- Tot.Sig Str -->
                                <th class="is-fullwidth has-text-white">
                                    <span><abbr title="Average Signicant Strikes landed per fight">Landed.Str</abbr></span>    
                                </th>
                                <!-- Grappling Score -->
                                <th class="is-fullwidth has-text-white">
                                    <span><abbr title="Grappling Score">Grp Scr</abbr></span>    
                                </th>
                                <!-- Grappling Score -->
                                <th class="is-fullwidth has-text-white">
                                    <span><abbr title="">Finish Rate</abbr></span>    
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
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