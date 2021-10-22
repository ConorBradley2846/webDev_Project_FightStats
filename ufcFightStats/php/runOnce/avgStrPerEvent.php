<?php

include('conn.php');
$counter = 0;
$eventTotStr = 0;
$eventAvg = 0;
$eventAvgSum = 0;


$eventsAll = $conn->query("SELECT EventTitleID FROM eventTitle");

    while($eventRow = $eventsAll->fetch_assoc()){

        $eventTotStr = 0;

        //echo "SELECT * FROM `fights` WHERE `Event` = {$eventRow['EventTitleID']}" . "<br>";
        
        $result = $conn->query("SELECT * FROM fights WHERE Event = {$eventRow['EventTitleID']}");

        if(!$result){
            echo $conn->error;
            die();
        }
        
        //print_r($result);
        
        if($result->num_rows != 0) {

            //Add to total of events
            $counter++;
            //Go through each fight of event. 
            while($eventFight = $result->fetch_assoc()){
                $eventTotStr += $eventFight['LandedStrA'] + $eventFight['LandedStrB'];
            }
            
            //avg num of str landed for that event
            $eventAvg = $eventTotStr/$result->num_rows;
            echo round($eventAvg) . "<br>";
            
        }
        
       
    }



function eventStrScore(int $eventID) {

        include('php/conn.php');

        $eventTotStr = 0;
        
        $result = $conn->query("SELECT * FROM fights WHERE Event = {$eventID}");

        if(!$result){
            echo $conn->error;
            die();
        }
     
        if($result->num_rows != 0) {

            //Go through each fight of event. 
            while($eventFight = $result->fetch_assoc()){
                $eventTotStr += $eventFight['LandedStrA'] + $eventFight['LandedStrB'];
            }
            
            //avg num of str landed for that event
            $eventAvg = $eventTotStr/$result->num_rows;
            return round($eventAvg);
            
        }
    
} // end of eventStrScore

function eventGrdScore(int $eventID) {

    include('php/conn.php');

    $subFin = 0;
    $landedTdtot = 0;
    $attTdtot = 0;
    $subAtttot = 0;
    $revtot = 0;
    $totGrdStr = 0;

    $result = $conn->query("SELECT * FROM fights WHERE Event = {$eventID}");

    if(!$result){
        echo $conn->error;
        die();
    }
    //print_r($result);
    if($result->num_rows != 0) {

        //Go through each fight of event. 
        while($eventFight = $result->fetch_assoc()){
            if($eventFight['FinishMethod'] == 6){
                $subFin = 1;
            } else{
                $subFin = 0;
            }

            $landedTdtot += $eventFight['LandedTdA'] + $eventFight['LandedTdB'];
            $attTdtot += $eventFight['AttemptedTdA'] + $eventFight['AttemptedTdB'];
            $subAtttot += $eventFight['SubAttA'] + $eventFight['SubAttB'];
            $revtot += $eventFight['RevA'] + $eventFight['RevB'];
            $totGrdStr += $eventFight['landedGroundA'] + $eventFight['landedGroundB'];

        }
        
        //weighted avg of summed ground attributes 
        $eventGrdAvg = ($subFin * 0.3) + ($landedTdtot * 0.2) + ($attTdtot * 0.1) + ($subAtttot * 0.2) + ($revtot * 0.1) + ($totGrdStr * 0.1);

        return round($eventGrdAvg);
        
    }

} // end of eventGrdScore



?>
