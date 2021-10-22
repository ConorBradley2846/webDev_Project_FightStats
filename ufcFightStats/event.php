<?php
  session_start();
  if(isset($_GET['eventid'])){

    $eventid = $_GET['eventid'];

    $endpoint = "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?eventid={$eventid}";
    
    $result = file_get_contents($endpoint);
    $eventData = json_decode($result, true);
     
  }

  $sessionOn = 0;
  $faved = 1;

  if(isset($_SESSION['fightsUser'])){
    $sessionOn = 1;
    $currentuser = $_SESSION['fightsUser'];
    $endpoint = "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?favEvent&eventidFav={$eventid}&userid={$currentuser}";
    $favresult = file_get_contents($endpoint);
    $favData = json_decode($favresult, true);

    if(is_null($favData[0])){
      $faved = 0;
    }
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" 
            integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" 
            crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.2.0/chart.min.js" 
            integrity="sha512-VMsZqo0ar06BMtg0tPsdgRADvl0kDHpTbugCBBrL55KmucH6hP9zWdLIWY//OTfMnzz6xWQRxQqsUFefwHuHyg==" 
            crossorigin="anonymous"></script>

    <script>

        $(document).ready(function() {

          // Check for click events on the navbar burger icon
          $(".navbar-burger").click(function() {
              // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
              $(".navbar-burger").toggleClass("is-active");
              $(".navbar-menu").toggleClass("is-active");
          });

          //Fights table
          $.ajax({
            type: "GET",
            url: "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?fights&eventid=<?php echo $eventid ?>",
            dataType: "json",
            success: function(res){
              var table = $("#eventsTable tbody");
              for(var i = (res.length-1); i >= 0; i--){
                  table.append("<tr><td>"+res[i]["Winner"]+"</td> <td>vs.</td> <td>"+res[i]["Loser"]+"</td> <td>"+res[i]["EventDate"]+"</td> <td>"+res[i]["weightName"]+"</td> <td><span class='tag is-danger'>"+res[i]["SigStrLanded"]+"</span></td> <td><span class='tag is-danger'>"+res[i]["GroundScr"]+"</span></td> <td> <a href='fight.php?fightid="+res[i]["FightID"]+"'> Fight page </a></td> </tr>");
              }
              
            },
            error: function(err) {
              alert(err);
            }
          });

          //Charts Loaded on page load.
          $.ajax({
            type: "GET",
            url: "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?fights&eventid=<?php echo $eventid ?>",
            dateType: "json",
            success: function(res){
              var totHeadSigStr = 0;
              var totBodySigStr = 0;
              var totLegSigStr = 0;
              var totSigStrThrown = 0;
              var landedTD = 0;
              var attemptedTD = 0;
              var eventKOcount = 0;
              var eventKOpercent = 0;

              for(var i = 0; i < (res.length); i++){
                totHeadSigStr += Number(res[i]["landedHeadA"]); 
                totHeadSigStr += Number(res[i]["landedHeadB"]);
                totBodySigStr += Number(res[i]["landedBodyA"]); 
                totBodySigStr += Number(res[i]["landedBodyB"]);
                totLegSigStr += Number(res[i]["landedLegA"]); 
                totLegSigStr += Number(res[i]["landedLegB"]);

                totSigStrThrown += Number(res[i]["AttemptedSigStrA"]);
                totSigStrThrown +=  Number(res[i]["AttemptedSigStrB"]);

                attemptedTD += Number(res[i]["AttemptedTdB"]);
                attemptedTD += Number(res[i]["AttemptedTdA"]);
                landedTD += Number(res[i]["LandedTdB"]);
                landedTD += Number(res[i]["LandedTdA"]);

                if(Number(res[i]["FinishMethod"]) == 5){
                  eventKOcount++;
                }

              }

              eventKOpercent = (eventKOcount/res.length)*100;
              eventKOpercent = eventKOpercent.toFixed(1);
              ufcKOpercentAll = Number(33.5); //separate api call could calculate this dynamically to update 
                                              //every time a new event/fight is added to the database.
                                              //However time constraints don't permit this currently. 
                

              const sigStrByTarget = [];
              sigStrByTarget.push(totHeadSigStr);
              sigStrByTarget.push(totBodySigStr);
              sigStrByTarget.push(totLegSigStr);
              
              sigStrByTargetEvent(sigStrByTarget);
              sigStrThrownLandedEvent(totSigStrThrown, (totHeadSigStr+totBodySigStr+totLegSigStr));
              tdAttLandedEvent(attemptedTD, landedTD);
              koVsKOavg(eventKOpercent, ufcKOpercentAll);
            }

          });

          // Check if user is logged in has already fav the event and update Star icon
          if(<?php echo $sessionOn ?> == 1){
            if(<?php echo $faved ?> == 1){
                  $(".fa").toggleClass("fa-star fa-star-o");
                }
          } 

          // fav/unFav event
          $('#favbutton').click(function () {
            // if not favorited / empty star and clicked
            if($(".fa").hasClass("fa-star-o")){
              //check if sign in as user.
              if(<?php echo $sessionOn ?> == 1){
                //add record to favorite table
                $.ajax({ 
                    url: 'favEvent.php',
                    type: 'POST',
                    data: {
                      "eventidFav": <?php echo $eventid; ?>
                    },
                    success: function (data) {
                        console.log(data);
                    }
                });
                //change to filled star
                $(".fav").toggleClass("fa-star fa-star-o");
                
              } else if(<?php echo $sessionOn ?> == 0) {
                //User not signed in. Prompt user to login
                $(".modal").toggleClass("is-active");
              }
              return;
            //if click (and is favorited), unfavorite by deleting fav record in table. 
            //user should be signed in already for this to happen.
            } else if($(".fa").hasClass("fa-star")){
              
              $.ajax({ 
                    url: 'unfavEvent.php',
                    type: 'POST',
                    data: {
                      "eventidFav": <?php echo $eventid; ?>
                    },
                    success: function (data) {
                        console.log(data);
                    }
                });
              //change to empty star
              $(".fav").toggleClass("fa-star fa-star-o");
              return;
            }
            return;
          });

          $(".modal-close").click(function() {
              // Close modal
              $(".modal").toggleClass("is-active");
          });

        }); //end of Doc ready funct
        
    </script>

    <!-- Charts -->
    <script>
      //Signicant Strikes by bodypart whole event:
        function sigStrByTargetEvent(data){
            var ctx = document.getElementById('sigStrByTarget').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [''],
                    datasets: [
                      {
                        label: 'Total Head Sig Str: '+[data[0]],
                        data: [data[0]],
                        backgroundColor: ['rgba(255, 0, 0, 1)'],
                        borderColor: ['rgba(0, 0, 0, 1)'],
                        borderWidth: 1
                      },
                      {
                        label: 'Total Body Sig. Str: '+[data[1]],
                        data: [data[1]],
                        backgroundColor: ['rgba(185, 0, 0, 1)'],
                        borderColor: ['rgba(0, 0, 0, 1)'],
                        borderWidth: 1
                      },
                      {
                        label: 'Total Leg Sig. Str: '+[data[2]],
                        data: [data[2]],
                        backgroundColor: ['rgba(255, 80, 80, 1)'],
                        borderColor: ['rgba(0, 0, 0, 1)'],
                        borderWidth: 1
                      }
                    ]
                },
                options: {
                  indexAxis: 'y',
                  reponsive:true,
                  maintainAspectRatio: false,
                  plugins: {
                    legend: {
                      position: 'bottom',
                      labels: {
                        color: 'rgb(255, 255, 255)'
                      }
                    },

                    title: {
                      display: true,
                      text: 'Total Significant Strikes Landed: ' + (Number([data[0]]) +Number([data[1]]) + Number([data[2]])),
                      align: 'start',
                      color: 'rgb(255, 255, 255)'
                    }

                  },
                  scales: {
                    x: { 
                      stacked: true,
                      ticks: {
                        color: 'rgb(255, 255, 255)'
                      },
                      grid: {
                        color: 'rgb(255, 255, 255)'
                      }
                      
                    },
                    y: { 
                      stacked: true,
                      ticks: {
                        color: 'rgb(255, 255, 255)'
                      }
                    }
                  }
                }
            });
        }

      //Signicant Strikes Landed vs. Thrown whole event:
        function sigStrThrownLandedEvent(thrown, landed){
            var ctx = document.getElementById('sigStrThrownLanded').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [''],
                    datasets: [
                      {
                        label: 'Total Sig.Str Landed: '+[landed],
                        data: [landed],
                        backgroundColor: ['rgba(255, 0, 0, 1)'],
                        borderColor: ['rgba(0, 0, 0, 1)'],
                        borderWidth: 1
                      },
                      {
                        label: 'Total Sig.Str Thrown: '+[thrown],
                        data: [thrown - landed],
                        backgroundColor: ['rgba(255, 80, 80, 1)'],
                        borderColor: ['rgba(0, 0, 0, 1)'],
                        borderWidth: 1
                      }
                    ]
                },
                options: {
                  indexAxis: 'y',
                  reponsive:true,
                  maintainAspectRatio: false,
                  plugins: {
                    legend: {
                      position: 'bottom',
                      labels: {
                        color: 'rgb(255, 255, 255)'
                      }
                    },

                    title: {
                      display: true,
                      text: '',
                      align: 'start',
                      color: 'rgb(255, 255, 255)'
                    }

                  },
                  scales: {
                    x: { 
                      stacked: true,
                      ticks: {
                        color: 'rgb(255, 255, 255)'
                      },
                      grid: {
                        color: 'rgb(255, 255, 255)'
                      }
                      
                    },
                    y: { 
                      stacked: true,
                      ticks: {
                        color: 'rgb(255, 255, 255)'
                      }
                    }
                  }
                }
            });
        }

      //Takedowns Landed vs. Attempted whole event:
        function tdAttLandedEvent(attempted, landed){
            var ctx = document.getElementById('tdLandedvAtt').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [''],
                    datasets: [
                      {
                        label: 'Total TDs Landed: '+[landed],
                        data: [landed],
                        backgroundColor: ['rgba(255, 0, 0, 1)'],
                        borderColor: ['rgba(0, 0, 0, 1)'],
                        borderWidth: 1
                      },
                      {
                        label: 'Total TDs Attempted: '+[attempted],
                        data: [attempted - landed],
                        backgroundColor: ['rgba(255, 80, 80, 1)'],
                        borderColor: ['rgba(0, 0, 0, 1)'],
                        borderWidth: 1
                      }
                    ]
                },
                options: {
                  indexAxis: 'y',
                  reponsive:true,
                  maintainAspectRatio: false,
                  plugins: {
                    legend: {
                      position: 'bottom',
                      labels: {
                        color: 'rgb(255, 255, 255)'
                      }
                    },

                    title: {
                      display: true,
                      text: '',
                      align: 'start',
                      color: 'rgb(255, 255, 255)'
                    }

                  },
                  scales: {
                    x: { 
                      stacked: true,
                      ticks: {
                        color: 'rgb(255, 255, 255)'
                      },
                      grid: {
                        color: 'rgb(255, 255, 255)'
                      }
                      
                    },
                    y: { 
                      stacked: true,
                      ticks: {
                        color: 'rgb(255, 255, 255)'
                      }
                    }
                  }
                }
            });
        }

      //Number of KO/TKO this event vs Average KO/TKO per UFC Card:
        function koVsKOavg(eventKOpercent, ufcKOpercentAll){
            var ctx = document.getElementById('koVsKOavgEvent').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [''],
                    datasets: [
                      {
                        label: 'Average % of fights ending in KO/TKO accross all UFC events: '+[ufcKOpercentAll],
                        data: [ufcKOpercentAll],
                        backgroundColor: ['rgba(255, 0, 0, 1)'],
                        borderColor: ['rgba(0, 0, 0, 1)'],
                        borderWidth: 1
                      },
                      {
                        label: 'Average % of fights ending in KO/TKO for this event: '+[eventKOpercent],
                        data: [eventKOpercent],
                        backgroundColor: ['rgba(255, 80, 80, 1)'],
                        borderColor: ['rgba(0, 0, 0, 1)'],
                        borderWidth: 1
                      }
                    ]
                },
                options: {
                  indexAxis: 'y',
                  reponsive:true,
                  maintainAspectRatio: false,
                  plugins: {
                    legend: {
                      position: 'bottom',
                      labels: {
                        color: 'rgb(255, 255, 255)'
                      }
                    },

                    title: {
                      display: true,
                      text: '',
                      align: 'start',
                      color: 'rgb(255, 255, 255)'
                    }

                  },
                  scales: {
                    x: { 
                      ticks: {
                        color: 'rgb(255, 255, 255)'
                      },
                      grid: {
                        color: 'rgb(255, 255, 255)'
                      }
                      
                    },
                    y: { 
                      ticks: {
                        color: 'rgb(255, 255, 255)'
                      }
                    }
                  }
                }
            });
        }

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
                    <i class="fa fa-search"></i>
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
                  } else if(isset($_SESSION['fightsUser'])) {
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

    <div id="index-container">

      <div id="content-wrap">

        <!-- Event Title -->
        <div class="row">
            <section>
                <div class="welcome has-text-left pl-6">
                    <h1 class="title noFade">
                        <strong><?php echo $eventData[0]["EventName"] ?></strong>
                    </h1>
                </div>
            </section>
        </div>

        <!-- Event Info row -->
        <div class="row" id="indexmaincards">
          <div class="box">
            <div class="columns">
              <div class="column"> <h1 class="subtitle noFade">Date: <?php echo $eventData[0]["EventDate"] ?></h1> </div>
              <div class="column"> <h1 class="subtitle noFade">Venue: <?php echo $eventData[0]["VenueName"] ?></h1> </div>
              <div class="column"> <h1 class="subtitle noFade">Location: <?php echo $eventData[0]["LocationName"] ?></h1> </div>
              <div class="column"> <button class="button is-black" id="favbutton"><i class="fav fa fa-star-o" aria-hidden="true" id="favbutton"></i></button></div>
            </div>
          </div>
        </div> 
        
        <!--Not logged in to fav modal -->
          <div class="modal is-clipped">
            <div class="modal-background"></div>
            <div class="modal-content is-size-4 has-text-white">
              <p>You must be logged in to save an event as a favorite </p>
              <a class='button is-dark' href='security/login.php'>
                Log in
              </a>
            </div>
            <button class="modal-close is-large" aria-label="close"></button>
          </div>

        <!-- Charts Row -->
        <div class="row" id="indexmaincards">
          <div class="box">

            <!-- Next row of charts -->
            <div class="columns">

              <!-- Charts row Left Col -->
              <div class="column is-6" id="indexmaincards">
                <div class="columns pt-3" id="noFade">
                    <label class="label">Signicant Strikes by bodypart:</label>
                </div>
                <div class="columns">
                  <div class="chart-container" style="width:100%;height:100%;">
                    <canvas id="sigStrByTarget" style="width:100%;height:100%;"></canvas>
                  </div>
                </div> 
              </div>
              
              <!-- Charts row Right Col -->
              <div class="column is-6" id="indexmaincards">
                <div class="columns pt-3" id="noFade">
                    <label class="label">Signicant Strikes Landed vs. Thrown:</label>
                </div>
                <div class="columns">
                  <div class="chart-container" style="width:100%;height:100%;">
                    <canvas id="sigStrThrownLanded" style="width:100%;height:100%;"></canvas>
                  </div>
                </div>
              </div>

            </div>

            <!-- Second row of charts -->
            <div class="columns">

              <!-- Charts row Left Col -->
              <div class="column is-6" id="indexmaincards">
                <div class="columns pt-3" id="noFade">
                    <label class="label">Takedowns landed vs. Takedowns attempted:</label>
                </div>
                <div class="columns">
                  <div class="chart-container" style="width:100%;height:100%;">
                    <canvas id="tdLandedvAtt" style="width:100%;height:100%;"></canvas>
                  </div>
                </div> 
              </div>
              
              <!-- Charts row Right Col -->
              <div class="column is-6" id="indexmaincards">
                <div class="columns pt-3" id="noFade">
                    <label class="label">Number of KO/TKO vs Average KO/TKO per UFC Card:</label>
                </div>
                <div class="columns">
                  <div class="chart-container" style="width:100%;height:100%;">
                    <canvas id="koVsKOavgEvent" style="width:100%;height:100%;"></canvas>
                  </div>
                </div>
              </div>

            </div>

          </div>

        </div>
        
        <!-- Fights row table -->
        <div class="row" id="indexmaincards">
          <div class="column is-full mb-4" id="indexmaincards">
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
                                <th class="is-fullwidth">
                                    <span>Date</span>    
                                </th>
                                <!-- Fight Weight -->
                                <th class="is-fullwidth">
                                    <span>Weight Class</span>    
                                </th>
                                <!-- Tot.Sig Str -->
                                <th class="is-fullwidth">
                                    <span><abbr title="Total Signicant Strikes">Tot.Sig Str</abbr></span>    
                                </th>
                                <!-- Grappling Score -->
                                <th class="is-fullwidth">
                                    <span><abbr title="Grappling Score">Grp Scr</abbr></span>    
                                </th>
                                <!-- Link to fight page -->
                                <th class="is-fullwidth">
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

        <footer class="footer">
          <div class="content has-text-centered">
            <p>
              <b>UFC Fight Data</b> by Conor Bradley. All rights reserved.
            </p>
          </div>
        </footer>

      </div> <!-- Content-wrap div close -->

    </div> <!-- Container div close -->


  </body>



</html>