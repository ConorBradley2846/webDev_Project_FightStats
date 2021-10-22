<?php
    include('conn.php');

    $counter = 0;

    $read = "SELECT DISTINCT fights.Event, eventTitle.EventName, eventVenue.VenueName, eventLocation.LocationName, eventTitle.EventDate 
    FROM fights 
    INNER JOIN eventTitle ON fights.Event = eventTitle.EventTitleID
    INNER JOIN eventVenue ON eventTitle.EventVenue = eventVenue.VenueID 
    INNER JOIN eventLocation ON eventVenue.VenueLocal = eventLocation.LocationID
    ORDER BY EventDate DESC";
               
    $query = $conn->query($read);

    if(!$query){
        echo $conn->error;
        die();
    }

    while($row = $query->fetch_assoc()){
       
        $eventTotStr = 0;
        $subFin = 0;
        $landedTdtot = 0;
        $attTdtot = 0;
        $subAtttot = 0;
        $revtot = 0;
        $totGrdStr = 0;
            
        $result = $conn->query("SELECT * FROM fights WHERE Event = '{$row["Event"]}'");

        if(!$result){
            echo $conn->error;
            die();
        }
        
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
                $eventTotStr += $eventFight['LandedStrA'] + $eventFight['LandedStrB'];
            }
                
            //avg num of str landed for that event
            $eventAvg = $eventTotStr/$result->num_rows;
            $eventAvg = round($eventAvg);
            //weighted avg of summed ground attributes 
            $eventGrdAvg = ($subFin * 0.3) + ($landedTdtot * 0.2) + ($attTdtot * 0.1) + ($subAtttot * 0.2) + ($revtot * 0.1) + ($totGrdStr * 0.1);
            $eventGrdAvg = round($eventGrdAvg);

            //update sql
            $update = "UPDATE `eventTitle` SET `LandedStrAvg` = '{$eventAvg}', `GrpScoreAvg` = '{$eventGrdAvg}' WHERE `eventTitle`.`EventTitleID` = '{$row["Event"]}'";
            
            echo $update . "<br>";

            $updateStrScore = $conn->query($update);

            if(!$updateStrScore){
                echo $conn->error;
                echo "<p>". $row["EventName"] . " not updated in your database.</p>" . "<br>";
            }else{
                echo "<p>". $row["EventName"] . " updated in your database.</p>" . "<br>";
                $counter++;
            }
            
                
        }
            
    } //parent while
            
    echo $counter . " records Updated.";

?>