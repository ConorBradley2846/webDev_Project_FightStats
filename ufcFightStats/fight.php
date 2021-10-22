<?php
  session_start();
  if(isset($_GET['fightid'])){

    $fightid = $_GET['fightid'];

    $endpoint = "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?fights&fightid={$fightid}";
    
    $result = file_get_contents($endpoint);
    $fightData = json_decode($result, true);

    $fighterA = $fightData[0]["FighterAID"];
    $fighterB = $fightData[0]["FighterBID"];
     
  }

  $sessionOn = 0;
  $faved = 1;

  if(isset($_SESSION['fightsUser'])){
    $sessionOn = 1;
    $currentuser = $_SESSION['fightsUser'];
    $endpoint = "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?favFight&fightidFav={$fightid}&userid={$currentuser}";
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
    <title>UFC Fight Data: Fight page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <link rel="stylesheet" href="css/ui.css">
    <!--<link rel="stylesheet" href="debug.css">-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" 
            integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" 
            crossorigin="anonymous">
    </script>

    <!--
    <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" data-auto-replace-svg="nest"></script>
    -->

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

          //FighterA fights table
          $.ajax({
            type: "GET",
            url: "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?fights&fighter=<?php echo $fighterA; ?>",
            dataType: "json",
            success: function(res){
              var table = $("#fighterATable tbody");
              for(var i = (res.length-1); i >= 0; i--){
                  table.append("<tr><td>"+res[i]["Winner"]+"</td> <td>vs.</td> <td>"+res[i]["Loser"]+"</td> <td>"+res[i]["EventDate"]+"</td> <td>"+res[i]["weightName"]+"</td> <td><span class='tag is-danger'>"+res[i]["SigStrLanded"]+"</span></td> <td><span class='tag is-danger'>"+res[i]["GroundScr"]+"</span></td> <td> <a href='fight.php?fightid="+res[i]["FightID"]+"'> Fight page </a></td> </tr>");
              }
              
            },
            error: function(err) {
              alert(err);
            }
          });

          //FighterB fights table
          $.ajax({
            type: "GET",
            url: "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?fights&fighter=<?php echo $fighterB; ?>",
            dataType: "json",
            success: function(res){
              var table = $("#fighterBTable tbody");
              for(var i = (res.length-1); i >= 0; i--){
                  table.append("<tr><td>"+res[i]["Winner"]+"</td> <td>vs.</td> <td>"+res[i]["Loser"]+"</td> <td>"+res[i]["EventDate"]+"</td> <td>"+res[i]["weightName"]+"</td> <td><span class='tag is-danger'>"+res[i]["SigStrLanded"]+"</span></td> <td><span class='tag is-danger'>"+res[i]["GroundScr"]+"</span></td> <td> <a href='fight.php?fightid="+res[i]["FightID"]+"'> Fight page </a></td> </tr>");
              }
              
            },
            error: function(err) {
              alert(err);
            }
          });


          //Charts Data fetched and Loaded on page load.
          $.ajax({
            type: "GET",
            url: "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/getStatsapi.php?fights&fightid=<?php echo $fightid ?>",
            dateType: "json",
            success: function(res){
              var totHeadSigStrA = Number(res[0]["landedHeadA"]);
              var totHeadSigStrB = Number(res[0]["landedHeadB"]);
              var totBodySigStrA = Number(res[0]["landedBodyA"]);
              var totBodySigStrB = Number(res[0]["landedBodyB"]);
              var totLegSigStrA = Number(res[0]["landedLegA"]);
              var totLegSigStrB = Number(res[0]["landedLegB"]);

              var totSigStrThrownA = Number(res[0]["AttemptedSigStrA"]);
              var totSigStrThrownB = Number(res[0]["AttemptedSigStrB"]);
              var totSigStrLandedA = Number(res[0]["LandedSigStrA"]);
              var totSigStrLandedB = Number(res[0]["LandedSigStrB"]);

              var totDistanceA = Number(res[0]["landedDisA"]);
              var totDistanceB = Number(res[0]["landedDisB"]);
              var totClinchA = Number(res[0]["landedClinchA"]);
              var totClinchB = Number(res[0]["landedClinchB"]);
              var totGroundA = Number(res[0]["landedGroundA"]);
              var totGroundB = Number(res[0]["landedGroundB"]);

              var landedTdA = Number(res[0]["LandedTdA"]);
              var landedTdB = Number(res[0]["LandedTdB"]);
              var attemptedTdA = Number(res[0]["AttemptedTdA"]);
              var attemptedTdB = Number(res[0]["AttemptedTdB"]);
              var fighterKDcountA = Number(res[0]["KnockdownsA"]);
              var fighterKDcountB = Number(res[0]["KnockdownsB"]);

              var fightSigStr = Number(res[0]["SigStrLanded"]);
              var fightGrpScr = Number(res[0]["GroundScr"]);
              var ufcAvgSigStr = Number(66);     ///separate api call could calculate these dynamically to update
              var ufcAvgGrpScrAll = Number(0.6); // every time a new event/fight is added to the database.
                                                 // However time constraints don't permit this currently. 
                                            
              const sigStrByTarget = [];
              sigStrByTarget.push(totHeadSigStrA);
              sigStrByTarget.push(totHeadSigStrB);
              sigStrByTarget.push(totBodySigStrA);
              sigStrByTarget.push(totBodySigStrB);
              sigStrByTarget.push(totLegSigStrA);
              sigStrByTarget.push(totLegSigStrA);

              const sigStrByPosition = [];
              sigStrByPosition.push(totDistanceA);
              sigStrByPosition.push(totDistanceB);
              sigStrByPosition.push(totClinchA);
              sigStrByPosition.push(totClinchB);
              sigStrByPosition.push(totGroundA);
              sigStrByPosition.push(totGroundB);
              
              sigStrByTargetFighterAB(sigStrByTarget);
              sigStrByPositionFighterAB(sigStrByPosition);
              sigStrThrownLandedFighersAB(totSigStrThrownA, totSigStrThrownB, totSigStrLandedA, totSigStrLandedB);
              tdAttLandedFighters(attemptedTdA, attemptedTdB, landedTdA, landedTdB);
              knockDownsFighters(fighterKDcountA, fighterKDcountB);

              grpFightavgVsUFCavg(fightGrpScr, ufcAvgGrpScrAll);
            }

          });


          // Check if user is logged in has already fav the event and update Star icon
          if(<?php echo $sessionOn ?> == 1){
            if(<?php echo $faved ?> == 1){
                  $(".fav").toggleClass("fa-star fa-star-o");
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
                    url: 'favFight.php',
                    type: 'POST',
                    data: {
                      "fightidFav": <?php echo $fightid; ?>
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
                    url: 'unfavFight.php',
                    type: 'POST',
                    data: {
                      "fightidFav": <?php echo $fightid; ?>
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
        function sigStrByTargetFighterAB(data){
            var ctx = document.getElementById('sigStrByTargetFighterAB').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['<?php echo $fightData[0]["FighterA"];?>', '<?php echo $fightData[0]["FighterB"];?>'],
                    datasets: [
                      {
                        label: 'Head Sig Str',
                        data: [data[0], data[1]],
                        backgroundColor: ['rgba(255, 0, 0, 1)', 'rgba(0, 0, 255, 1)'],
                        borderColor: ['rgba(0, 0, 0, 1)'],
                        borderWidth: 1
                      },
                      {
                        label: 'Body Sig. Str',
                        data: [data[2], data[3]],
                        backgroundColor: ['rgba(185, 0, 0, 1)', 'rgba(0, 0, 185, 1)'],
                        borderColor: ['rgba(0, 0, 0, 1)'],
                        borderWidth: 1
                      },
                      {
                        label: 'Leg Sig. Str',
                        data: [data[4], data[5]],
                        backgroundColor: ['rgba(255, 80, 80, 1)', 'rgba(80, 80, 255, 1)'],
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
                      text: 'Total Significant Strikes Landed in fight: ' + (Number([data[0]]) +Number([data[1]]) + Number([data[2]]) +Number([data[3]]) + Number([data[4]]) + Number([data[5]])),
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

        //Signicant Strikes by bodypart whole event:
        function sigStrByPositionFighterAB(data){
            var ctx = document.getElementById('sigStrByPositionFighterAB').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['<?php echo $fightData[0]["FighterA"];?>', '<?php echo $fightData[0]["FighterB"];?>'],
                    datasets: [
                      {
                        label: 'Landed from Distance',
                        data: [data[0], data[1]],
                        backgroundColor: ['rgba(255, 0, 0, 1)', 'rgba(0, 0, 255, 1)'],
                        borderColor: ['rgba(0, 0, 0, 1)'],
                        borderWidth: 1
                      },
                      {
                        label: 'Landed from Clinch',
                        data: [data[2], data[3]],
                        backgroundColor: ['rgba(185, 0, 0, 1)', 'rgba(0, 0, 185, 1)'],
                        borderColor: ['rgba(0, 0, 0, 1)'],
                        borderWidth: 1
                      },
                      {
                        label: 'Landed from Ground',
                        data: [data[4], data[5]],
                        backgroundColor: ['rgba(255, 80, 80, 1)', 'rgba(80, 80, 255, 1)'],
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
                      text: 'Total from distance: ' + (Number([data[0]]) +Number([data[1]])) + '  Total from Clinch: ' + (Number([data[2]]) +Number([data[3]])) + '  Total from Ground: ' + (Number([data[4]]) +Number([data[5]])),
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
        function sigStrThrownLandedFighersAB(totSigStrThrownA, totSigStrThrownB, totSigStrLandedA, totSigStrLandedB){
            var ctx = document.getElementById('sigStrThrownLanded').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['<?php echo $fightData[0]["FighterA"];?>', '<?php echo $fightData[0]["FighterB"];?>'],
                    datasets: [
                      {
                        label: 'Total Sig.Str Landed',
                        data: [totSigStrLandedA, totSigStrLandedB],
                        backgroundColor: ['rgba(255, 0, 0, 1)', 'rgba(0, 0, 255, 1)'],
                        borderColor: ['rgba(0, 0, 0, 1)'],
                        borderWidth: 1
                      },
                      {
                        label: 'Total Sig.Str Thrown',
                        data: [totSigStrThrownA - totSigStrLandedA, totSigStrThrownB - totSigStrLandedB],
                        backgroundColor: ['rgba(255, 80, 80, 1)', 'rgba(80, 80, 255, 1)'],
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
                      text: 'Total Significant Strikes thrown in fight: ' + (totSigStrThrownA + totSigStrThrownB),
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

      //Takedowns Landed vs. Attempted by fighters:
        function tdAttLandedFighters(attemptedTdA, attemptedTdB, landedTdA, landedTdB){
            var ctx = document.getElementById('tdLandedvAtt').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['<?php echo $fightData[0]["FighterA"];?>', '<?php echo $fightData[0]["FighterB"];?>'],
                    datasets: [
                      {
                        label: 'TDs Landed',
                        data: [landedTdA, landedTdB],
                        backgroundColor: ['rgba(255, 0, 0, 1)', 'rgba(0, 0, 255, 1)'],
                        borderColor: ['rgba(0, 0, 0, 1)'],
                        borderWidth: 1
                      },
                      {
                        label: 'TDs Attempted',
                        data: [attemptedTdA - landedTdA, attemptedTdB - landedTdB],
                        backgroundColor: ['rgba(255, 80, 80, 1)','rgba(80, 80, 255, 1)'],
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
                        beginAtZero: true,
                        stepSize: 1,
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

      //Fight Grappling Score Comparsion:
        function grpFightavgVsUFCavg(fightGrpScr, ufcAvgGrpScrAll){
            var ctx = document.getElementById('koVsKOavgEvent').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [''],
                    datasets: [
                      {
                        label: 'Grappling Score accross all UFC fights: '+[ufcAvgGrpScrAll],
                        data: [ufcAvgGrpScrAll],
                        backgroundColor: ['rgba(120, 120, 120, 1)'],
                        borderColor: ['rgba(0, 0, 0, 1)'],
                        borderWidth: 1
                      },
                      {
                        label: 'Grappling Score of this fight: '+[fightGrpScr],
                        data: [fightGrpScr],
                        backgroundColor: ['rgba(255, 255, 255, 1)'],
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

      //Knockdowns by fighter:
        function knockDownsFighters(fighterKDcountA, fighterKDcountB){
            var ctx = document.getElementById('knockdowns').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['<?php echo $fightData[0]["FighterA"];?>', '<?php echo $fightData[0]["FighterB"];?>'],
                    datasets: [
                      {
                        label: [fighterKDcountA] + ' Knockdowns',
                        data: [fighterKDcountA],
                        backgroundColor: ['rgba(0, 0, 255, 1)'],
                        borderColor: ['rgba(0, 0, 0, 1)'],
                        borderWidth: 1
                      },
                      {
                        label: [fighterKDcountB] + ' Knockdowns',
                        data: [fighterKDcountB],
                        backgroundColor: ['rgba(255, 0, 0, 1)'],
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
                        beginAtZero: true,
                        stepSize: 1,
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
                        <strong><?php echo $fightData[0]["FighterA"];?>  <span class="icon has-text-danger"><i class="fa fa-circle"></i></span> vs. <?php echo $fightData[0]["FighterB"];?>  <span class="icon has-text-info"><i class="fa fa-circle"></i></span> </strong>
                    </h1>
                </div>
            </section>
        </div>

        <!-- Event Info row -->
        <div class="row" id="indexmaincards">
          <div class="box">
            <div class="columns">
              <div class="column"> <h1 class="subtitle noFade">Date: <?php echo $fightData[0]["EventDate"] ?></h1> </div>
              <div class="column"> <h1 class="subtitle noFade">Event: <?php echo "<a class='underline' href='event.php?eventid={$fightData[0]["Event"]}'>" . $fightData[0]["EventName"] . "</a>";?> </h1> </div>
              <div class="column"> <h1 class="subtitle noFade">Division: <?php echo $fightData[0]["weightName"] ?></h1> </div>
              <div class="column"> <button class="button is-black" id="favbutton"><i class="fav fa fa-star-o" aria-hidden="true" id="favbutton"></i></button></div>
            </div>
          </div>
        </div> 
        
        <!--Not logged in to fav modal -->
          <div class="modal is-clipped">
            <div class="modal-background"></div>
            <div class="modal-content is-size-4 has-text-white">
              <p>You must be logged in to save a fight as a favorite </p>
              <a class='button is-dark' href='security/login.php'>
                Log in
              </a>
            </div>
            <button class="modal-close is-large" aria-label="close"></button>
          </div>

        <!-- Charts Row -->
        <div class="row" id="indexmaincards">
          <div class="box">

            <!-- First row of charts -->
            <div class="columns">

              <!-- Charts row Left Col -->
              <div class="column is-6" id="indexmaincards">
                <div class="columns pt-3" id="noFade">
                    <label class="label">Signicant Strikes by bodypart:</label>
                </div>
                <div class="columns">
                  <div class="chart-container" style="width:100%;height:100%;">
                    <canvas id="sigStrByTargetFighterAB" style="width:100%;height:100%;"></canvas>
                  </div>
                </div> 
              </div>
              
              <!-- Charts row Right Col -->
              <div class="column is-6" id="indexmaincards">
                <div class="columns pt-3" id="noFade">
                    <label class="label">Signicant Strikes by position:</label>
                </div>
                <div class="columns">
                  <div class="chart-container" style="width:100%;height:100%;">
                    <canvas id="sigStrByPositionFighterAB" style="width:100%;height:100%;"></canvas>
                  </div>
                </div> 
              </div>
              

            </div>

            <!-- Second row of charts -->
            <div class="columns">

              <!-- Charts row Left Col -->
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
            
              <!-- Charts row Right Col -->
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

            </div>

            <!-- Third row of charts -->
            <div class="columns">

              <!-- Charts row Right Col -->
              <div class="column is-6" id="indexmaincards">
                <div class="columns pt-3" id="noFade">
                    <label class="label">KnockDowns:</label>
                </div>
                <div class="columns">
                  <div class="chart-container" style="width:100%;height:100%;">
                    <canvas id="knockdowns" style="width:100%;height:100%;"></canvas>
                  </div>
                </div>
              </div>

              <!-- Charts row Right Col -->
              <div class="column is-6" id="indexmaincards">
                <div class="columns pt-3" id="noFade">
                    <label class="label">Fight Grappling Score Comparsion:</label>
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
        
        <!-- Fighter A row table -->
        <div class="row" id="indexmaincards">
          <div class="column is-full px-2" >
            <div class="box">
                    <div class="table-container">
                    <h1 class="title fighterTitle noFade">
                        <strong><?php echo $fightData[0]["FighterA"];?>  <span class="icon has-text-danger"><i class="fa fa-circle"></i></span> Recent Fights: </strong>
                    </h1>
                    <table class="table noFade has-text-white is-fullwidth" id="fighterATable">
                            <thead class="has-text-white">
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

        <!-- Fighter B row table -->
        <div class="row" id="indexmaincards">
          <div class="column is-full mb-4 px-2">
            <div class="box">
                    <div class="table-container">
                    <h1 class="title fighterTitle noFade">
                        <strong><?php echo $fightData[0]["FighterB"];?>  <span class="icon has-text-info"><i class="fa fa-circle"></i></span> Recent Fights: </strong>
                    </h1>
                    <table class="table noFade has-text-white is-fullwidth" id="fighterBTable">
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