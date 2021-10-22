<?php 

include("connections/conn.php");

//response in json format
header('Content-Type: application/json');

if(isset($_GET['allevent'])){

    $page = $_GET['allevent'];

    $read = "SELECT DISTINCT fights.Event, eventTitle.EventName, eventVenue.VenueName, eventLocation.LocationName, eventTitle.EventDate, eventTitle.LandedStrAvg, eventTitle.GrpScoreAvg, eventTitle.FinishPercent 
                FROM fights 
                INNER JOIN eventTitle ON fights.Event = eventTitle.EventTitleID
                INNER JOIN eventVenue ON eventTitle.EventVenue = eventVenue.VenueID 
                INNER JOIN eventLocation ON eventVenue.VenueLocal = eventLocation.LocationID";

    if(isset($_GET['date'])){
        
        $date = $_GET['date'];

        if($date == "newest"){

            $read .= " ORDER BY EventDate DESC";
            
        } else if ($date == "oldest"){

            $read .= " ORDER BY EventDate ASC";
                    
        }

    }

    if(isset($_GET['landedStr'])){
        
        $landed = $_GET['landedStr'];

        if($landed == "highest"){

            $read .= " ORDER BY LandedStrAvg DESC";
                    
        } else if ($landed == "lowest"){

            $read .= " ORDER BY LandedStrAvg ASC";

        }

    }

    if(isset($_GET['grpScr'])){
        
        $grp = $_GET['grpScr'];

        if($grp == "highest"){

            $read .= " ORDER BY GrpScoreAvg DESC";

        } else if ($grp == "lowest"){

            $read .= " ORDER BY GrpScoreAvg ASC";
                    
        }

    }

    if(isset($_GET['finRate'])){
        
        $fin = $_GET['finRate'];

        if($fin == "highest"){

            $read .= " ORDER BY FinishPercent DESC";

        } else if ($fin == "lowest"){

            $read .= " ORDER BY FinishPercent ASC";

        }

    }

    if(isset($page)){
        if($page == 1){
            $offset = 0;
            $read .=" LIMIT 20 
                    OFFSET {$offset}";

        } else if($page > 1){
            $offset = $page *20;
            $read .=" LIMIT 20 
                    OFFSET {$offset}";

        } 
    }

    $query = $conn->query($read);
        
        if(!$query){
            echo $conn->error;
            die();
        }
    
        $events = array();
    
        while($row = $query->fetch_assoc()){
            //[1] Convert to UTF-8
            $row["EventName"] = mb_convert_encoding($row["EventName"], 'UTF-8', 'UTF-8');
            $row["VenueName"] = mb_convert_encoding($row["VenueName"], 'UTF-8', 'UTF-8');
            $row["LocationName"] = mb_convert_encoding($row["LocationName"], 'UTF-8', 'UTF-8');
    
            $events[] = $row;
        }
        
        //print_r($events);
        echo json_encode($events);
        die();

}


if(isset($_GET['fights'])){

    $page = $_GET['fights'];

    $read = "SELECT fights.FightID, fights.Event, w.FighterName AS 'Winner', 
                    l.FighterName AS 'Loser', 
                    eventTitle.EventDate, fights.FinishMethod, 
                    weightDivision.weightName,
                    `LandedSigStrA`+`LandedSigStrB` AS 'SigStrLanded',
                    ((CASE
                        WHEN`FinishMethod` != 6 THEN `FinishMethod` = 0
                        ELSE `FinishMethod` = 6
                    END)*0.3)+((`LandedTdA`+`LandedTdB`)*0.2)+((`AttemptedTdA`+`AttemptedTdB`)*0.1)+((`SubAttA`+`SubAttB`)*0.2)+((`RevA`+`RevB`)*0.1)+((`landedGroundA`+`landedGroundB`)*0.1) AS 'GroundScr',
       				`Round`,
                    `FinishTime`,
                    `TimeFormat`,
                    a.FighterName AS 'FighterA', 
                        `KnockdownsA`,`LandedSigStrA`,`AttemptedSigStrA`,
                        `SigStrPercentA`,`LandedStrA`,`TotalStrA`,
                        `LandedTdA`,`AttemptedTdA`,`TdPercentA`,
                        `SubAttA`,`RevA`,`ControlTimeA`,`landedHeadA`,
                        `HeadStrA`,`landedBodyA`,`BodyStrA`,`landedLegA`,
                        `LegStrA`,`landedDisA`,`DistanceStrA`,`landedClinchA`,
                        `ClinchStrA`,`landedGroundA`,`GroundStrA`,
                	b.FighterName AS 'FighterB',
                        `KnockdownsB`,`LandedSigStrB`,`AttemptedSigStrB`,
                        `SigStrPercentB`,`LandedStrB`,`TotalStrB`,`LandedTdB`,
                        `AttemptedTdB`,`TdPercentB`,`SubAttB`,`RevB`,
                        `ControlTimeB`,`landedHeadB`,`HeadStrB`,`landedBodyB`,
                        `BodyStrB`,`landedLegB`,`LegStrB`,`landedDisB`,
                        `DistanceStrB`,`landedClinchB`,`ClinchStrB`,
                        `landedGroundB`,`GroundStrB`,
                 eventTitle.EventName, fights.WeightClass, fights.FighterA AS 'FighterAID', fights.FighterB AS 'FighterBID' 

                        FROM fights
                        JOIN fighters w ON fights.Winner = w.FighterID 
                        JOIN fighters l ON fights.Loser = l.FighterID
                        JOIN fighters a ON fights.FighterA = a.FighterID 
                        JOIN fighters b ON fights.FighterB = b.FighterID
                        JOIN eventTitle ON fights.Event = eventTitle.EventTitleID
                        JOIN weightDivision ON fights.WeightClass = weightDivision.weightDivisionID";


    if(isset($_GET['weight'])){
        $weight = $_GET['weight'];

        $read .=" WHERE fights.WeightClass = {$weight}";
    }

    if(isset($_GET['date'])){

        $date = $_GET['date'];

        if($date == "newest"){

            $read .=" ORDER BY EventDate DESC";

        } else if ($date == "oldest"){

            $read .=" ORDER BY EventDate ASC";
            
        }

    }

    if(isset($_GET['SigStrScr'])){

        $date = $_GET['SigStrScr'];

        if($date == "highest"){
            $read .=" ORDER BY SigStrLanded DESC";

        } else if ($date == "lowest"){
            $read .=" ORDER BY SigStrLanded ASC";   
        }
    }

    if(isset($_GET['GrdScr'])){

        $date = $_GET['GrdScr'];

        if($date == "highest"){
            $read .=" ORDER BY GroundScr DESC";

        } else if ($date == "lowest"){
            $read .=" ORDER BY GroundScr ASC";   
        }
    }

    if(isset($_GET['eventid'])){
        $eventFights = $_GET['eventid'];

        $read .= " WHERE fights.Event = {$eventFights}";

    }

    if(isset($_GET['fightid'])){

        $fightid = $_GET['fightid'];

        $read .= " WHERE fights.FightID = {$fightid}";

    }

    if(isset($_GET['fighter'])){

        $fighterid = $_GET['fighter'];

        $read .= " WHERE fights.FighterB = {$fighterid} OR fights.FighterA  = {$fighterid}";

    }


    if(isset($page)){
        if($page == 1){
            $offset = 0;
            $read .=" LIMIT 20 
                    OFFSET {$offset}";

        } else if($page > 1){
            $offset = $page *20;
            $read .=" LIMIT 20 
                    OFFSET {$offset}";

        } 
    }

    $query = $conn->query($read);

        if(!$query){
            echo $conn->error;
            die();
        }

        $fights = array();
    
        while($row = $query->fetch_assoc()){
            //[1] Convert to UTF-8
            $row["Winner"] = mb_convert_encoding($row["Winner"], 'UTF-8', 'UTF-8');
            $row["Loser"] = mb_convert_encoding($row["Loser"], 'UTF-8', 'UTF-8');
            $row["EventName"] = mb_convert_encoding($row["EventName"], 'UTF-8', 'UTF-8');

            $fights[] = $row;
        }
        
        //print_r($events);
        echo json_encode($fights);
        die(); // halt the script
        //echo json_last_error_msg(); // Print out the error if any

}


if(isset($_GET['eventid'])){

    $event = $_GET['eventid'];

    $read = "SELECT `EventTitleID`, `EventName`, eventVenue.VenueName, eventLocation.LocationName, `EventDate`, `LandedStrAvg`, `GrpScoreAvg`, `FinishPercent` FROM `eventTitle` 
    JOIN eventVenue ON eventTitle.EventVenue = eventVenue.VenueID
    JOIN eventLocation ON eventVenue.VenueLocal = eventLocation.LocationID
    WHERE EventTitleID = {$event}";

    $query = $conn->query($read);

        if(!$query){
            echo $conn->error;
            die();
        }

        $eventArray = array();
    
        $row = $query->fetch_assoc();
            //[1] Convert to UTF-8
            $row["EventName"] = mb_convert_encoding($row["EventName"], 'UTF-8', 'UTF-8');
            $row["VenueName"] = mb_convert_encoding($row["VenueName"], 'UTF-8', 'UTF-8');
            $row["LocationName"] = mb_convert_encoding($row["LocationName"], 'UTF-8', 'UTF-8');

            $eventArray[] = $row;
        
        //print_r($events);
        echo json_encode($eventArray);
        die(); // halt the script
        //echo json_last_error_msg(); // Print out the error if any

}


if(isset($_GET['favEvent'])){

    if(isset($_GET['userid'])){

        $userID = $_GET['userid'];

        if(isset($_GET['eventidFav'])){
            $eventID = $_GET['eventidFav'];
            $read = "SELECT * FROM userfavevents WHERE UserID = {$userID} AND EventID = {$eventID}";

            $query = $conn->query($read);
        
            if(!$query){
                echo $conn->error;
                die();
            }
        
            $events = array();
        
            $row = $query->fetch_assoc();
                $events[] = $row;
            
            //print_r($events);
            echo json_encode($events);
            die();


        } else {
            $read = "SELECT * FROM userfavevents WHERE UserID = {$userID}";

            $query = $conn->query($read);
        
            if(!$query){
                echo $conn->error;
                die();
            }
        
            $events = array();
        
            while($row = $query->fetch_assoc()){
                $events[] = $row;
            }

            $eventsDetails = array();

            foreach($events as $userfavEvent){
                $nextRead = "SELECT DISTINCT fights.Event, eventTitle.EventName, eventVenue.VenueName, eventLocation.LocationName, eventTitle.EventDate, eventTitle.LandedStrAvg, eventTitle.GrpScoreAvg, eventTitle.FinishPercent 
                                FROM fights 
                                INNER JOIN eventTitle ON fights.Event = eventTitle.EventTitleID
                                INNER JOIN eventVenue ON eventTitle.EventVenue = eventVenue.VenueID 
                                INNER JOIN eventLocation ON eventVenue.VenueLocal = eventLocation.LocationID
                                                    
                                    WHERE fights.Event = '{$userfavEvent["EventID"]}'";

                    $query = $conn->query($nextRead);
                            
                    if(!$query){
                        echo $conn->error;
                        die();
                    }

                    $eventresult = $query->fetch_assoc();
                    $eventsDetails[] = $eventresult;
            }

            //print_r($events);
            echo json_encode($eventsDetails);
            die();

        }

        
    }

}


if(isset($_GET['favFight'])){

    if(isset($_GET['userid'])){

        $userID = $_GET['userid'];

        if(isset($_GET['fightidFav'])){
            $fightID = $_GET['fightidFav'];

            $read = "SELECT * FROM userfavfights WHERE UserID = {$userID} AND FightID = {$fightID}";

            $query = $conn->query($read);
        
            if(!$query){
                echo $conn->error;
                die();
            }
        
            $figths = array();
        
            $row = $query->fetch_assoc();
            $figths[] = $row;
        
            //print_r($events);
            echo json_encode($figths);
            die();

        } else if (!isset($_GET['fightidFav'])) {
            //Give the actual info back aswell. 
            $read = "SELECT * FROM userfavfights WHERE UserID = {$userID}";

            $query = $conn->query($read);
        
            if(!$query){
                echo $conn->error;
                die();
            }
        
            $figthsIDs = array();
        
            while($row = $query->fetch_assoc()){
                $figthsIDs[] = $row;
            }

            $figthsDetails = array();

            foreach($figthsIDs as $userfavfight){
                $nextRead = "SELECT fights.FightID, fights.Event, w.FighterName AS 'Winner', 
                                l.FighterName AS 'Loser', 
                                eventTitle.EventDate, fights.FinishMethod, 
                                weightDivision.weightName,
                                `LandedSigStrA`+`LandedSigStrB` AS 'SigStrLanded',
                                ((CASE
                                    WHEN`FinishMethod` != 6 THEN `FinishMethod` = 0
                                    ELSE `FinishMethod` = 6
                                END)*0.3)+((`LandedTdA`+`LandedTdB`)*0.2)+((`AttemptedTdA`+`AttemptedTdB`)*0.1)+((`SubAttA`+`SubAttB`)*0.2)+((`RevA`+`RevB`)*0.1)+((`landedGroundA`+`landedGroundB`)*0.1) AS 'GroundScr',
                                `Round`,
                                `FinishTime`,
                                `TimeFormat`,
                                a.FighterName AS 'FighterA', 
                                    `KnockdownsA`,`LandedSigStrA`,`AttemptedSigStrA`,
                                    `SigStrPercentA`,`LandedStrA`,`TotalStrA`,
                                    `LandedTdA`,`AttemptedTdA`,`TdPercentA`,
                                    `SubAttA`,`RevA`,`ControlTimeA`,`landedHeadA`,
                                    `HeadStrA`,`landedBodyA`,`BodyStrA`,`landedLegA`,
                                    `LegStrA`,`landedDisA`,`DistanceStrA`,`landedClinchA`,
                                    `ClinchStrA`,`landedGroundA`,`GroundStrA`,
                                b.FighterName AS 'FighterB',
                                    `KnockdownsB`,`LandedSigStrB`,`AttemptedSigStrB`,
                                    `SigStrPercentB`,`LandedStrB`,`TotalStrB`,`LandedTdB`,
                                    `AttemptedTdB`,`TdPercentB`,`SubAttB`,`RevB`,
                                    `ControlTimeB`,`landedHeadB`,`HeadStrB`,`landedBodyB`,
                                    `BodyStrB`,`landedLegB`,`LegStrB`,`landedDisB`,
                                    `DistanceStrB`,`landedClinchB`,`ClinchStrB`,
                                    `landedGroundB`,`GroundStrB`,
                            eventTitle.EventName, fights.WeightClass, fights.FighterA AS 'FighterAID', fights.FighterB AS 'FighterBID' 

                                    FROM fights
                                    JOIN fighters w ON fights.Winner = w.FighterID 
                                    JOIN fighters l ON fights.Loser = l.FighterID
                                    JOIN fighters a ON fights.FighterA = a.FighterID 
                                    JOIN fighters b ON fights.FighterB = b.FighterID
                                    JOIN eventTitle ON fights.Event = eventTitle.EventTitleID
                                    JOIN weightDivision ON fights.WeightClass = weightDivision.weightDivisionID
                                    
                                    WHERE fights.FightID = '{$userfavfight["FightID"]}'";

                    $query = $conn->query($nextRead);
                            
                    if(!$query){
                        echo $conn->error;
                        die();
                    }

                    $fightresult = $query->fetch_assoc();
                    $figthsDetails[] = $fightresult;
            }

            //print_r($events);
            echo json_encode($figthsDetails);
            die();

        }

        
    }

}


if(isset($_GET['searchfights'])){
    
    $searchstring = $_GET['searchfights'];

    $read = "SELECT fights.FightID, fights.Event, w.FighterName AS 'Winner', 
                    l.FighterName AS 'Loser', 
                    eventTitle.EventDate, fights.FinishMethod, 
                    weightDivision.weightName,
                    `LandedSigStrA`+`LandedSigStrB` AS 'SigStrLanded',
                    ((CASE
                        WHEN`FinishMethod` != 6 THEN `FinishMethod` = 0
                        ELSE `FinishMethod` = 6
                    END)*0.3)+((`LandedTdA`+`LandedTdB`)*0.2)+((`AttemptedTdA`+`AttemptedTdB`)*0.1)+((`SubAttA`+`SubAttB`)*0.2)+((`RevA`+`RevB`)*0.1)+((`landedGroundA`+`landedGroundB`)*0.1) AS 'GroundScr',
       				`Round`,
                    `FinishTime`,
                    `TimeFormat`,
                    a.FighterName AS 'FighterA', 
                        `KnockdownsA`,`LandedSigStrA`,`AttemptedSigStrA`,
                        `SigStrPercentA`,`LandedStrA`,`TotalStrA`,
                        `LandedTdA`,`AttemptedTdA`,`TdPercentA`,
                        `SubAttA`,`RevA`,`ControlTimeA`,`landedHeadA`,
                        `HeadStrA`,`landedBodyA`,`BodyStrA`,`landedLegA`,
                        `LegStrA`,`landedDisA`,`DistanceStrA`,`landedClinchA`,
                        `ClinchStrA`,`landedGroundA`,`GroundStrA`,
                	b.FighterName AS 'FighterB',
                        `KnockdownsB`,`LandedSigStrB`,`AttemptedSigStrB`,
                        `SigStrPercentB`,`LandedStrB`,`TotalStrB`,`LandedTdB`,
                        `AttemptedTdB`,`TdPercentB`,`SubAttB`,`RevB`,
                        `ControlTimeB`,`landedHeadB`,`HeadStrB`,`landedBodyB`,
                        `BodyStrB`,`landedLegB`,`LegStrB`,`landedDisB`,
                        `DistanceStrB`,`landedClinchB`,`ClinchStrB`,
                        `landedGroundB`,`GroundStrB`,
                 eventTitle.EventName, fights.WeightClass, fights.FighterA AS 'FighterAID', fights.FighterB AS 'FighterBID' 

                        FROM fights
                        JOIN fighters w ON fights.Winner = w.FighterID 
                        JOIN fighters l ON fights.Loser = l.FighterID
                        JOIN fighters a ON fights.FighterA = a.FighterID 
                        JOIN fighters b ON fights.FighterB = b.FighterID
                        JOIN eventTitle ON fights.Event = eventTitle.EventTitleID
                        JOIN weightDivision ON fights.WeightClass = weightDivision.weightDivisionID";


    $query = $conn->query($read);

        if(!$query){
            echo $conn->error;
            die();
        }

        $fights = array();

        while($row = $query->fetch_assoc()){
            //[1] Convert to UTF-8
            $row["Winner"] = mb_convert_encoding($row["Winner"], 'UTF-8', 'UTF-8');
            $row["Loser"] = mb_convert_encoding($row["Loser"], 'UTF-8', 'UTF-8');
            $row["EventName"] = mb_convert_encoding($row["EventName"], 'UTF-8', 'UTF-8');

            
            similar_text("{$row['Winner']}", "{$searchstring}", $percent);
        
            if($percent > 80){

                $fights[] = $row;

            }else{
                similar_text("{$row['Loser']}", "{$searchstring}", $percent);
                if($percent > 80){
                    $fights[] = $row;
                }
            }

        }

        //print_r($events);
        echo json_encode($fights);
        die(); // halt the script
        //echo json_last_error_msg(); // Print out the error if any



}


if(isset($_GET['searchevents'])){
    
    $searchstring = $_GET['searchevents'];

    $read = "SELECT DISTINCT fights.Event, eventTitle.EventName, eventVenue.VenueName, eventLocation.LocationName, eventTitle.EventDate, eventTitle.LandedStrAvg, eventTitle.GrpScoreAvg, eventTitle.FinishPercent 
                FROM fights 
                INNER JOIN eventTitle ON fights.Event = eventTitle.EventTitleID
                INNER JOIN eventVenue ON eventTitle.EventVenue = eventVenue.VenueID 
                INNER JOIN eventLocation ON eventVenue.VenueLocal = eventLocation.LocationID";


    $query = $conn->query($read);

        if(!$query){
            echo $conn->error;
            die();
        }

        $events = array();

        while($row = $query->fetch_assoc()){
            //[1] Convert to UTF-8
            $row["EventName"] = mb_convert_encoding($row["EventName"], 'UTF-8', 'UTF-8');
            $row["VenueName"] = mb_convert_encoding($row["VenueName"], 'UTF-8', 'UTF-8');
            $row["LocationName"] = mb_convert_encoding($row["LocationName"], 'UTF-8', 'UTF-8');
    
            
        
           similar_text("{$row['EventName']}", "{$searchstring}", $percent);
        
            if($percent > 50){
                $events[] = $row;
            }

        }

        //print_r($events);
        echo json_encode($events);
        die(); // halt the script
        //echo json_last_error_msg(); // Print out the error if any

}

/*
///References 
[1] https://stackoverflow.com/questions/46305169/php-json-encode-malformed-utf-8-characters-possibly-incorrectly-encoded

*/
?>