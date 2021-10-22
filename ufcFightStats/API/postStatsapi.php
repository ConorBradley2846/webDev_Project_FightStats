<?php

include("connections/conn.php");

//response in json format
header('Content-Type: application/json');

if($_SERVER["REQUEST_METHOD"]==='POST' && isset($_GET['favEvent'])){

    $user = $_POST['userid']; 
    $event = $_POST['eventidFav'];

    $addfav = "INSERT INTO `userfavevents` (`UserFavEventID`, `UserID`, `EventID`) VALUES (NULL, '{$user}', '{$event}')";

    $query = $conn->query($addfav);
        
    if(!$query){
        echo $conn->error;
        die();
    } else {
        //echo "Event: " . $event . " added as favorite for userid: " . $user;
    }

    die();
    
}

if($_SERVER["REQUEST_METHOD"]==='POST' && isset($_GET['unfavEvent'])){

    $user = $_POST['userid']; 
    $event = $_POST['eventidFav'];

    $dropfav = "DELETE FROM `userfavevents` WHERE `userfavevents`.`UserID` = {$user} AND `userfavevents`.`EventID` = {$event}";

    $query = $conn->query($dropfav);
        
    if(!$query){
        echo $conn->error;
        die();
    } else {
        //echo "Event: " . $event . " removed as favorite for userid: " . $user;
    }

    die();
    

}


if($_SERVER["REQUEST_METHOD"]==='POST' && isset($_GET['favFight'])){

    $user = $_POST['userid']; 
    $fight = $_POST['fightidFav'];

    $addfav = "INSERT INTO `userfavfights` (`UserFavFightID`, `UserID`, `FightID`) VALUES (NULL, '{$user}', '{$fight}')";

    $query = $conn->query($addfav);
        
    if(!$query){
        echo $conn->error;
        die();
    } else {
        //echo "Fight: " . $fight . " added as favorite for userid: " . $user;
    }

    die();
    
}

if($_SERVER["REQUEST_METHOD"]==='POST' && isset($_GET['unfavFight'])){

    $user = $_POST['userid']; 
    $fight = $_POST['fightidFav'];

    $dropfav = "DELETE FROM `userfavfights` WHERE `userfavfights`.`UserID` = {$user} AND `userfavfights`.`FightID` = {$fight}";

    $query = $conn->query($dropfav);
        
    if(!$query){
        echo $conn->error;
        die();
    } else {
        //echo "Event: " . $event . " removed as favorite for userid: " . $user;
    }

    die();
    

}


?>