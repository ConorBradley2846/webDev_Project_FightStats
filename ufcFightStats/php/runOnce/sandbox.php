
<?php

include('conn.php');
$file = "ufc_stats_5roundFin.csv";
$counter = 0;
$added = 0;
$notadded = array();

if(file_exists($file)){

    $filepath = fopen($file, "r");
    fgetcsv($filepath);
    fgetcsv($filepath);
    $counter += 2;

    /////PREPARE & BIND   SELECT 

    ///// Finding the event FK
    $stmtEvent = $conn->prepare("SELECT `EventTitleID` FROM `eventTitle` WHERE `EventName` = ?");
    if ( false===$stmtEvent ) {
        die('prepare() failed: ' . htmlspecialchars($conn->error));
        }
    $rc = $stmtEvent->bind_param("s", $eventTitle);
    if ( false===$rc ) {
        die('bind_param() failed: ' . htmlspecialchars($stmtEvent->error));
        }

    /// Finding the fighter) FK
    $stmtFighter = $conn->prepare("SELECT `FighterID` FROM `fighters` WHERE `FighterName` = ?");
    if ( false===$stmtFighter ) {
        die('prepare() failed: ' . htmlspecialchars($conn->error));
        }
    $rc = $stmtFighter->bind_param("s", $fighterName);
    if ( false===$rc ) {
        die('bind_param() failed: ' . htmlspecialchars($stmtFighter->error));
        }
     
    //Finding the weightClass FK
    $stmtWeight = $conn->prepare("SELECT `weightDivisionID` FROM `weightDivision` WHERE `weightName` = ?");
    if ( false===$stmtWeight ) {
        die('prepare() failed: ' . htmlspecialchars($conn->error));
        }
    $rc = $stmtWeight->bind_param("s", $weightName);
    if ( false===$rc ) {
        die('bind_param() failed: ' . htmlspecialchars($stmtWeight->error));
        }
       
    //Finding the FinsihMethod FK
    $stmtFinish = $conn->prepare("SELECT `FinishMethodID` FROM `finishMethod` WHERE `FinishMethod` = ?");
    if ( false===$stmtFinish ) {
        die('prepare() failed: ' . htmlspecialchars($conn->error));
        }
    $rc = $stmtFinish->bind_param("s", $finishName);
    if ( false===$rc ) {
        die('bind_param() failed: ' . htmlspecialchars($stmtFinish->error));
        }
    
    while( ($line = fgetcsv($filepath)) !== FALSE){

        //$line = fgetcsv($filepath);

        //Execute event title FK find and assign
        $eventTitle = $line[0];
        $rc = $stmtEvent->execute();
        if ( false===$rc ) {
            die('execute() failed: ' . htmlspecialchars($stmtEvent->error));
        }
        $result = $stmtEvent->get_result();
        $eventdata = $result->fetch_assoc(); //$eventdata["EventTitleID"]

        //Execute Fighter name FK find and assign
        $fighterName = $line[1];
        $rc = $stmtFighter->execute();
        if ( false===$rc ) {
            die('execute() failed: ' . htmlspecialchars($stmtFighter->error));
        }
        $result = $stmtFighter->get_result();
        $loserName = $result->fetch_assoc(); //$loserName["FighterID"]

        $fighterName = $line[2];
        $rc = $stmtFighter->execute();
        if ( false===$rc ) {
            die('execute() failed: ' . htmlspecialchars($stmtFighter->error));
        }
        $result = $stmtFighter->get_result();
        $winnerName = $result->fetch_assoc(); //$winnerName["FighterID"]

        $fighterName = $line[10];
        $rc = $stmtFighter->execute();
        if ( false===$rc ) {
            die('execute() failed: ' . htmlspecialchars($stmtFighter->error));
        }
        $result = $stmtFighter->get_result();
        $figtherNameA = $result->fetch_assoc(); //$figtherNameA["FighterID"]
       
        $fighterName = $line[28];
        $rc = $stmtFighter->execute();
        if ( false===$rc ) {
            die('execute() failed: ' . htmlspecialchars($stmtFighter->error));
        }
        $result = $stmtFighter->get_result();
        $figtherNameB = $result->fetch_assoc(); //$figtherNameB["FighterID"]

        //Execute weight class FK find and assign
        $weightName = $line[3];
        $rc = $stmtWeight->execute();
        if ( false===$rc ) {
            die('execute() failed: ' . htmlspecialchars($stmtWeight->error));
        }
        $result = $stmtWeight->get_result();
        $weightClass = $result->fetch_assoc(); //$weightClass["weightDivisionID"]
        
        //Execute weight class FK find and assign
        $finishName = $line[4];
        $rc = $stmtFinish->execute();
        if ( false===$rc ) {
            die('execute() failed: ' . htmlspecialchars($stmtFinish->error));
        }
        $result = $stmtFinish->get_result();
        $finMethod = $result->fetch_assoc(); //$finMethod["FinishMethodID"]

        //Finish Time of fight
        $finTimePre = $line[6];
        $finTimePost = "00:" . $finTimePre;

        //Time Format
        if($line[7] == "3 Rnd (5-5-5)"){
            $timeFormat = "3";
        } else if($line[7] == "5 Rnd (5-5-5-5-5)"){
            $timeFormat = "5";
        }

        //Sig. Str 
        if($line[14] != "0"){
            $sigStrA = str_replace('%', '', $line[14]);
        } else {
            $sigStrA = $line[14];
        }
        if($line[32] != "0"){
            $sigStrB = str_replace('%', '', $line[32]);
        } else {
            $sigStrB = $line[32];
        }

        //Total Str
        $strPreA = str_replace(' of', '', $line[15]);
        $strPreA = preg_split('/\s/', $strPreA);
        $landedStrA = $strPreA[0];
        $totStrA = $strPreA[1];

        $strPreB = str_replace(' of', '', $line[33]);
        $strPreB = preg_split('/\s/', $strPreB);
        $landedStrB = $strPreB[0];
        $totStrB = $strPreB[1];
        
        //TD% 
        if($line[18] != "0"){
            $tdPercentA = str_replace('%', '', $line[18]);
        } else {
            $tdPercentA = $line[18];
        }

        if($line[36] != "0"){
            $tdPercentB = str_replace('%', '', $line[36]);
        } else {
            $tdPercentB = $line[36];
        }

        //Control (CTRL) time
        $CrtlPreA = $line[21];
        $CrtlPreB = $line[39];
        $CrtlPostA = "00:" . $CrtlPreA;
        $CrtlPostB = "00:" . $CrtlPreB;

        //Body Str
        $bodyPreA = str_replace(' of', '', $line[23]);
        $bodyPreA = preg_split('/\s/', $bodyPreA);
        $landedBodyA = $bodyPreA[0];
        $totBodyA = $bodyPreA[1];

        $bodyPreB = str_replace(' of', '', $line[41]);
        $bodyPreB = preg_split('/\s/', $bodyPreB);
        $landedBodyB = $bodyPreB[0];
        $totBodyB = $bodyPreB[1];

        //Leg Str
        $legPreA = str_replace(' of', '', $line[24]);
        $legPreA = preg_split('/\s/', $legPreA);
        $landedLegA = $legPreA[0];
        $totLegA = $legPreA[1];

        $legPreB = str_replace(' of', '', $line[42]);
        $legPreB = preg_split('/\s/', $legPreB);
        $landedLegB = $legPreB[0];
        $totLegB = $legPreB[1];

        //Head Str A
        if(strpos($line[22], '%') !== false){
            //landed head
            $landedHeadA = $line[12] - ($landedBodyA + $landedLegA);
            if($landedHeadA < 1){
                $landedHeadA = rand(0,round(($line[12] * 0.75)));
            }
            //att head
            $totHeadA = $line[13] - ($totBodyA + $totLegA);
            if($totHeadA < 1){
                $totHeadA = rand(0,round(($line[13] * 0.75)));
            }

        } else {
            $headPreA = str_replace(' of', '', $line[22]);
            $headPreA = preg_split('/\s/', $headPreA);
            $landedHeadA = $headPreA[0];
            $totHeadA = $headPreA[1];
        }

        //Head Str B
        if(strpos($line[40], '%') !== false){
            //landed head
            $landedHeadB = $line[30] - ($landedBodyB + $landedLegB);
            if($landedHeadB < 1){
                $landedHeadB = rand(0,round(($line[30] * 0.75)));
            }
            //att head
            $totHeadB = $line[31] - ($totBodyB + $totLegB);
            if($totHeadB < 1){
                $totHeadB = rand(0,round(($line[31] * 0.75)));
            }

        } else {
            $headPreB = str_replace(' of', '', $line[40]);
            $headPreB = preg_split('/\s/', $headPreB);
            $landedHeadB = $headPreB[0];
            $totHeadB = $headPreB[1];
        }

        //Strikes from Distance

        $landedDisA = round($line[12] * 0.32);
        $totDisA = round($line[13] * 0.32);

        $landedDisB = round($line[30] * 0.32);
        $totDisB = round($line[31] * 0.32);


        /*
        $disPreA = str_replace(' of', '', $line[25]);
        $disPreA = preg_split('/\s/', $disPreA);
        $landedDisA = $disPreA[0];
        $totDisA = $disPreA[1];

        $disPreB = str_replace(' of', '', $line[43]);
        $disPreB = preg_split('/\s/', $disPreB);
        $landedDisB = $disPreB[0];
        $totDisB = $disPreB[1];
        */

        //Strikes from Clinch

        $landedClinA = round($line[12] * 0.32);
        $totClinA = round($line[13] * 0.32);

        $landedClinB = round($line[30] * 0.32);
        $totClinB = round($line[31] * 0.32);
        /*
        $clinPreA = str_replace(' of', '', $line[26]);
        $clinPreA = preg_split('/\s/', $clinPreA);
        $landedClinA = $clinPreA[0];
        $totClinA = $clinPreA[1];

        $clinPreB = str_replace(' of', '', $line[44]);
        $clinPreB = preg_split('/\s/', $clinPreB);
        $landedClinB = $clinPreB[0];
        $totClinB = $clinPreB[1];
        */

        //Strikes on Ground

        $landedGrnA = round($line[12] * 0.32);
        $totGrnA = round($line[13] * 0.32);

        $landedGrnB = round($line[30] * 0.32);
        $totGrnB = round($line[31] * 0.32);


        /*
        $grnPreA = str_replace(' of', '', $line[27]);
        $grnPreA = preg_split('/\s/', $grnPreA);
        $landedGrnA = $grnPreA[0];
        $totGrnA = $grnPreA[1];

        $grnPreB = str_replace(' of', '', $line[45]);
        $grnPreB = preg_split('/\s/', $grnPreB);
        $landedGrnB = $grnPreB[0];
        $totGrnB = $grnPreB[1];
        */

        ///Now need to do inserts for all this. But echo out first to test them. 
        /////INSERT Statement

        $insert = "INSERT INTO `fights` (`FightID`, `Event`, `Winner`, `Loser`, `WeightClass`, `FinishMethod`, `Round`, `FinishTime`, `TimeFormat`, `FighterA`, `KnockdownsA`, `LandedSigStrA`, `AttemptedSigStrA`, `SigStrPercentA`, `LandedStrA`, `TotalStrA`, `LandedTdA`, `AttemptedTdA`, `TdPercentA`, `SubAttA`, `RevA`, `ControlTimeA`, `landedHeadA`, `HeadStrA`, `landedBodyA`, `BodyStrA`, `landedLegA`, `LegStrA`, `landedDisA`, `DistanceStrA`, `landedClinchA`, `ClinchStrA`, `landedGroundA`, `GroundStrA`, `FighterB`, `KnockdownsB`, `LandedSigStrB`, `AttemptedSigStrB`, `SigStrPercentB`, `LandedStrB`, `TotalStrB`, `LandedTdB`, `AttemptedTdB`, `TdPercentB`, `SubAttB`, `RevB`, `ControlTimeB`, `landedHeadB`, `HeadStrB`, `landedBodyB`, `BodyStrB`, `landedLegB`, `LegStrB`, `landedDisB`, `DistanceStrB`, `landedClinchB`, `ClinchStrB`, `landedGroundB`, `GroundStrB`) VALUES (NULL, '{$eventdata["EventTitleID"]}', '{$winnerName["FighterID"]}', '{$loserName["FighterID"]}', '{$weightClass["weightDivisionID"]}', '{$finMethod["FinishMethodID"]}', '{$line[5]}', '{$finTimePost}', '{$timeFormat}', '{$figtherNameA["FighterID"]}', '{$line[11]}', '{$line[12]}', '{$line[13]}', '{$sigStrA}', '{$landedStrA}', '{$totStrA}', '{$line[16]}', '{$line[17]}', '{$tdPercentA}', '{$line[19]}', '{$line[20]}', '{$CrtlPostA}', '{$landedHeadA}', '{$totHeadA}', '{$landedBodyA}', '{$totBodyA}', '{$landedLegA}', '{$totLegA}', '{$landedDisA}', '{$totDisA}', '{$landedClinA}', '{$totClinA}', '{$landedGrnA}', '{$totGrnA}', '{$figtherNameB["FighterID"]}', '{$line[29]}', '{$line[30]}', '{$line[31]}', '{$sigStrB}', '{$landedStrB}', '{$totStrB}', '{$line[34]}', '{$line[35]}', '{$tdPercentB}', '{$line[37]}', '{$line[38]}', '{$CrtlPostB}', '{$landedHeadB}', '{$totHeadB}', '{$landedBodyB}', '{$totBodyB}', '{$landedLegB}', '{$totLegB}', '{$landedDisB}', '{$totDisB}', '{$landedClinB}', '{$totClinB}', '{$landedGrnB}', '{$totGrnB}')";
        $counter++;
        //echo $counter;
        //echo $insert . "<br>";
        //echo "<br>";
        
        
        
        $insertFight = $conn->query($insert);

        
        if(!$insertFight){
            echo $conn->error;
            echo "<p>$line[1] vs. $line[2] at $line[0] has not been added to your database.</p>" . "<br>";
            $notadded[] = $counter;
        }else{
            $added++;
        }
        
 

    } //while
    //print_r($result);
    $added -= 2;
    echo $added . " records added to db" . "<br>";
    print_r($notadded);

    fclose($filepath);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=, initial-scale=1.0">
<title>Document</title>
</head>
<body>

<!--
<a href="events.php?date=newest&page<?php// echo $page;?>" class='dropdown-item is-active'>
Most recent
</a>
-->



</body>
</html>