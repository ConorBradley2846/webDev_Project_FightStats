<?php
  session_start();

    $endpoint = "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?allevent=1&date=newest";
    
    $result = file_get_contents($endpoint);
    $eventData = json_decode($result, true);
                  
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
            crossorigin="anonymous">
    </script>


    <script>

        $(document).ready(function() {

          // Check for click events on the navbar burger icon
          $(".navbar-burger").click(function() {

              // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
              $(".navbar-burger").toggleClass("is-active");
              $(".navbar-menu").toggleClass("is-active");

          });

          //Charts Loaded on page load.
          $.ajax({
            type: "GET",
            url: "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?fights&eventid=<?php echo $eventData[0]['Event'] ?>",
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
        });

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

    <div id="index-container">
      <div id="content-wrap">

        <!-- Welcome message row -->
        <div class="row">
          <section>

            <div class="welcome has-text-centered">
              <h1 class="title noFade">
                <strong>Welcome to UFC Fight Data.</strong>
              </h1>
              <p class="subtitle noFade">
                  Your number one location for UFC fight data breakdowns. 
              </p>
            </div>

          </section>
        </div>

        <!-- Search bar -->
        <form action="search.php" method="post">
          <div class="columns is-centered">
              <div class="column searchbar is-half">
                <div class="field has-addons">
                  <div class="control is-expanded">
                    <input class="input" name="searchstring" type="text" required="required" value="" placeholder="Search for Event or fight: E.g. UFC 202: Diaz vs. McGregor 2">
                  </div>
                  <div class="control">
                    <input  class="button is-dark" name="submit" type="submit" value="Search"></input>
                  </div>
                </div>
              </div>
          </div>
        </form>


        <div class="columns">

          <!-- Left margin Col -->
          <div class="column"></div>

           <!-- Upcoming event col -->
          <div class="column is-two-fifths" id="indexmaincards">

            <div class="box">

                    <div class="columns noFade">
                        <h1>Next UFC Event</h1>
                    </div>


                    <!-- Fight card main title -->
                    <div class="columns noFade">
                      <div class="field is-size-3" id="nextEventTile">
                        <h1 >UFC 259: Blachowicz vs. Adesanya</h1>
                      </div>
                    </div>
                
                    <!-- Main event col -->
                    <div class="columns noFade">
                      <div class="column noFade" id="maineventcol">
                        <div class="columns noFade">
                          <div class="column vscards is-two-fifths is-size-4 has-text-centered">
                              Jan Blachowicz
                          </div>
                          <div class="column vscards is-size-5 has-text-centered">
                            vs.
                          </div>
                          <div class="column vscards is-two-fifths is-size-4 has-text-centered">
                                Israel Adesanya
                          </div>
                        </div>

                        <div class="columns noFade">
                          <div class="columnn is-full noFade" id="titlefightcol">
                            Light Heavyweight Title Bout
                          </div>
                        </div>

                        <div class="columns noFade">
                          <div class="column vscards is-two-fifths is-size-6 has-text-centered">
                              +185
                          </div>
                          <div class="column vscards is-size-6 has-text-centered">
                            ODDS
                          </div>
                          <div class="column vscards is-two-fifths is-size-6 has-text-centered">
                              -230
                          </div>
                        </div>

                      </div>
                    </div>
                
                    <!-- Co-main event col -->
                    <div class="columns noFade">
                      <div class="column noFade nonmainevent">
                        <div class="columns noFade">
                          <div class="column vscards is-two-fifths is-size-5 has-text-centered">
                              Amanda Nunes
                          </div>
                          <div class="column vscards is-size-5 has-text-centered">
                            vs.
                          </div>
                          <div class="column vscards is-two-fifths is-size-5 has-text-centered">
                                Megan Anderson
                          </div>
                        </div>

                        <div class="columns noFade">
                          <div class="columnn is-full noFade" id="titlefightcol">
                              Featherweight Title Bout
                          </div>
                        </div>

                        <div class="columns noFade">
                          <div class="column vscards is-two-fifths is-size-6 has-text-centered">
                              -1250
                          </div>
                          <div class="column vscards is-size-6 has-text-centered">
                            ODDS
                          </div>
                          <div class="column vscards is-two-fifths is-size-6 has-text-centered">
                              +750
                          </div>
                        </div>

                      </div>
                    </div>

                    <!-- second-co event col -->
                    <div class="columns noFade">
                      <div class="column noFade nonmainevent">
                        <div class="columns noFade">
                          <div class="column vscards is-two-fifths is-size-5 has-text-centered">
                              Petr Yan
                          </div>
                          <div class="column vscards is-size-5 has-text-centered">
                            vs.
                          </div>
                          <div class="column vscards is-two-fifths is-size-5 has-text-centered">
                                Aljamain Sterling
                          </div>
                        </div>

                        <div class="columns noFade">
                          <div class="columnn is-full noFade" id="titlefightcol">
                              Bantamweight Title Bout
                          </div>
                        </div>

                        <div class="columns noFade">
                          <div class="column vscards is-two-fifths is-size-6 has-text-centered">
                              -110
                          </div>
                          <div class="column vscards is-size-6 has-text-centered">
                            ODDS
                          </div>
                          <div class="column vscards is-two-fifths is-size-6 has-text-centered">
                              -110
                          </div>
                        </div>

                      </div>
                    </div>


                    <div class="columns noFade">
                      <div class="column noFade has-text-right"></div>
                      <a href="#">Click to view full event</a>
                    </div>

            </div>

          </div>

          <!-- Divider Col-->
          <div class="column"></div>
          
          <!-- Most recent event summary breakdown -->
          <div class="column is-6" id="indexmaincards">

            <div class="box">

                    <div class="columns noFade">
                      <h1>Latest event at a glance:</h1>
                    </div>

                    <div class="columns noFade">
                      <div class="field is-size-3" id="nextEventTile">
                        <h1 >UFC Fight Night: Rozenstruik vs. Gane</h1>
                      </div>
                    </div>

                    <div class="column" id="indexmaincards">
                      <div class="columns pt-3" id="noFade">
                          <label class="label">Signicant Strikes Landed vs. Thrown:</label>
                      </div>
                      <div class="columns">
                        <div class="chart-container" style="width:100%;height:100%;">
                          <canvas id="sigStrThrownLanded" style="width:100%;height:100%;"></canvas>
                        </div>
                      </div>
                    </div>
                
                    
                    <div class="column" id="indexmaincards">
                      <div class="columns pt-3" id="noFade">
                          <label class="label">Signicant Strikes by bodypart:</label>
                      </div>
                      <div class="columns">
                        <div class="chart-container" style="width:100%;height:100%;">
                          <canvas id="sigStrByTarget" style="width:100%;height:100%;"></canvas>
                        </div>
                      </div> 
                    </div>

                    <div class="column" id="indexmaincards">
                      <div class="columns pt-3" id="noFade">
                          <label class="label">Takedowns landed vs. Takedowns attempted:</label>
                      </div>
                      <div class="columns">
                        <div class="chart-container" style="width:100%;height:100%;">
                          <canvas id="tdLandedvAtt" style="width:100%;height:100%;"></canvas>
                        </div>
                      </div> 
                    </div>

                    <div class="column" id="indexmaincards">
                      <div class="columns pt-3" id="noFade">
                          <label class="label">Number of KO/TKO vs Average KO/TKO per UFC Card:</label>
                      </div>
                      <div class="columns">
                        <div class="chart-container" style="width:100%;height:100%;">
                          <canvas id="koVsKOavgEvent" style="width:100%;height:100%;"></canvas>
                        </div>
                      </div>
                    </div>

                    <div class="column noFade">
                      <div class="column noFade has-text-right"></div>
                      <a href="event.php?eventid=<?php echo $eventData[0]['Event'] ?>">Click to view full event</a>
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