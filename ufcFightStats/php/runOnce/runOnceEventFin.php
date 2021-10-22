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
       
        $finish = 0;
        
            
        $result = $conn->query("SELECT * FROM fights WHERE Event = '{$row["Event"]}'");

        if(!$result){
            echo $conn->error;
            die();
        }
        
        if($result->num_rows != 0) {

            //Go through each fight of event. 
            while($eventFight = $result->fetch_assoc()){

                if($eventFight['FinishMethod'] == 6){
                    $finish++;
                } else if($eventFight['FinishMethod'] == 5){
                    $finish++;
                } else if($eventFight['FinishMethod'] == 7){
                    $finish++;
                }

            }
                
            //finish rate in percent for that event 
            $finRate = ($finish/$result->num_rows) * 100;
            $finRate = round($finRate);

            //update sql
            $update = "UPDATE `eventTitle` SET `FinishPercent` = '{$finRate}' WHERE `eventTitle`.`EventTitleID` = '{$row["Event"]}'";
            
            echo $update . "<br>";

            
            $updateFinRate = $conn->query($update);

            if(!$updateFinRate){
                echo $conn->error;
                echo "<p>". $row["EventName"] . " finish rate not updated in your database.</p>" . "<br>";
            }else{
                echo "<p>". $row["EventName"] . " finish rate updated in your database.</p>" . "<br>";
                $counter++;
            }
            
                
        }
            
    } //parent while
            
    echo $counter . " records Updated.";

?>