<?php

    include('conn.php');
    $file = "ufc_stats_5roundFin.csv";
    $counter = 0;

    if(file_exists($file)){

        $filepath = fopen($file, "r");
        fgetcsv($filepath);
        fgetcsv($filepath);

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

            //Head Str
            $headPreA = str_replace(' of', '', $line[22]);
            $headPreA = preg_split('/\s/', $headPreA);
            $landedHeadA = $headPreA[0];
            $totHeadA = $headPreA[1];

            $headPreB = str_replace(' of', '', $line[40]);
            $headPreB = preg_split('/\s/', $headPreB);
            $landedHeadB = $headPreB[0];
            $totHeadB = $headPreB[1];

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

            //Strikes from Distance
            $disPreA = str_replace(' of', '', $line[25]);
            $disPreA = preg_split('/\s/', $disPreA);
            $landedDisA = $disPreA[0];
            $totDisA = $disPreA[1];

            $disPreB = str_replace(' of', '', $line[43]);
            $disPreB = preg_split('/\s/', $disPreB);
            $landedDisB = $disPreB[0];
            $totDisB = $disPreB[1];

            //Strikes from Clinch
            $clinPreA = str_replace(' of', '', $line[26]);
            $clinPreA = preg_split('/\s/', $clinPreA);
            $landedClinA = $clinPreA[0];
            $totClinA = $clinPreA[1];

            $clinPreB = str_replace(' of', '', $line[44]);
            $clinPreB = preg_split('/\s/', $clinPreB);
            $landedClinB = $clinPreB[0];
            $totClinB = $clinPreB[1];

            //Strikes on Ground
            $grnPreA = str_replace(' of', '', $line[27]);
            $grnPreA = preg_split('/\s/', $grnPreA);
            $landedGrnA = $grnPreA[0];
            $totGrnA = $grnPreA[1];

            $grnPreB = str_replace(' of', '', $line[45]);
            $grnPreB = preg_split('/\s/', $grnPreB);
            $landedGrnB = $grnPreB[0];
            $totGrnB = $grnPreB[1];


            ///Now need to do inserts for all this. But echo out first to test them. 
            /////INSERT Statement

            $insert = "INSERT INTO `fights` (`FightID`, `Event`, `Winner`, `Loser`, `WeightClass`, `FinishMethod`, `Round`, `FinishTime`, `TimeFormat`, `FighterA`, `KnockdownsA`, `LandedSigStrA`, `AttemptedSigStrA`, `SigStrPercentA`, `LandedStrA`, `TotalStrA`, `LandedTdA`, `AttemptedTdA`, `TdPercentA`, `SubAttA`, `RevA`, `ControlTimeA`, `landedHeadA`, `HeadStrA`, `landedBodyA`, `BodyStrA`, `landedLegA`, `LegStrA`, `landedDisA`, `DistanceStrA`, `landedClinchA`, `ClinchStrA`, `landedGroundA`, `GroundStrA`, `FighterB`, `KnockdownsB`, `LandedSigStrB`, `AttemptedSigStrB`, `SigStrPercentB`, `LandedStrB`, `TotalStrB`, `LandedTdB`, `AttemptedTdB`, `TdPercentB`, `SubAttB`, `RevB`, `ControlTimeB`, `landedHeadB`, `HeadStrB`, `landedBodyB`, `BodyStrB`, `landedLegB`, `LegStrB`, `landedDisB`, `DistanceStrB`, `landedClinchB`, `ClinchStrB`, `landedGroundB`, `GroundStrB`) VALUES (NULL, '{$eventdata["EventTitleID"]}', '{$winnerName["FighterID"]}', '{$loserName["FighterID"]}', '{$weightClass["weightDivisionID"]}', '{$finMethod["FinishMethodID"]}', '{$line[5]}', '{$finTimePost}', '{$timeFormat}', '{$figtherNameA["FighterID"]}', '{$line[11]}', '{$line[12]}', '{$line[13]}', '{$sigStrA}', '{$landedStrA}', '{$totStrA}', '{$line[16]}', '{$line[17]}', '{$tdPercentA}', '{$line[19]}', '{$line[20]}', '{$CrtlPostA}', '{$landedHeadA}', '{$totHeadA}', '{$landedBodyA}', '{$totBodyA}', '{$landedLegA}', '{$totLegA}', '{$landedDisA}', '{$totDisA}', '{$landedClinA}', '{$totClinA}', '{$landedGrnA}', '{$totGrnA}', '{$figtherNameB["FighterID"]}', '{$line[29]}', '{$line[30]}', '{$line[31]}', '{$sigStrB}', '{$landedStrB}', '{$totStrB}', '{$line[34]}', '{$line[35]}', '{$tdPercentB}', '{$line[37]}', '{$line[38]}', '{$CrtlPostB}', '{$landedHeadB}', '{$totHeadB}', '{$landedBodyB}', '{$totBodyB}', '{$landedLegB}', '{$totLegB}', '{$landedDisB}', '{$totDisB}', '{$landedClinB}', '{$totClinB}', '{$landedGrnB}', '{$totGrnB}')";

            echo $insert . "<br>";
            /*
            $insertFight = $conn->query($insert);

            
            if(!$insertFight){
                echo $conn->error;
                echo "<p>$line[1] vs. $line[2] at $line[0] has not been added to your database.</p>" . "<br>";
            }else{
                $counter++;
            }
            */
     
    
        } //while
        //print_r($result);
        echo $counter . " records added to db";

        fclose($filepath);
    }

?>




<!--

    Unused SQL non prepared statements for reference 

    //$winnerFK = $conn->query("SELECT `FighterID` FROM `fighters` WHERE `FighterName` = '{$line[2]}'");
        //$winnerName = $winnerFK->fetch_assoc();
        //'{$winnerName["FighterID"]}'

     //$loserFK = $conn->query("SELECT `FighterID` FROM `fighters` WHERE `FighterName` = '{$line[1]}'");
        //$loserName = $loserFK->fetch_assoc();
        //'{$loserName["FighterID"]}'

    //$eventFK = $conn->query("SELECT `EventTitleID` FROM `eventTitle` WHERE `EventName` = '{$line[0]}'");
        //$eventTitle = $eventFK->fetch_assoc();
        //'{$eventTitle["EventTitleID"]}'

    Check I dont think I'll need (Check if already in database)
       //prepare and bind select
        $checkstmt = $conn->prepare("SELECT `FightID` FROM `fights` WHERE `Event` = ? AND `Winner` = ? AND `Loser` = ?");
        $checkstmt->bind_param("iii", $eventCheck, $winnerCheck, $loserCheck); 
        


    Prepared Statements I made but might be redundant

    /// Finding the winner (fighter) FK
        $stmtWinner = $conn->prepare("SELECT `FighterID` FROM `fighters` WHERE `FighterName` = ?");
        if ( false===$stmtWinner ) {
            die('prepare() failed: ' . htmlspecialchars($conn->error));
            }
        $rc = $stmtWinner->bind_param("s", $winnerName);
        if ( false===$rc ) {
            die('bind_param() failed: ' . htmlspecialchars($stmtWinner->error));
            }

        /// Finding the Loser (fighter) FK
        $stmtLoser = $conn->prepare("SELECT `FighterID` FROM `fighters` WHERE `FighterName` = ?");
        if ( false===$stmtLoser ) {
            die('prepare() failed: ' . htmlspecialchars($conn->error));
            }
        $rc = $stmtLoser->bind_param("s", $loserName);
        if ( false===$rc ) {
            die('bind_param() failed: ' . htmlspecialchars($stmtLoser->error));
            }


         /// Finding the FighterA (fighter) FK
         $stmtFighterA = $conn->prepare("SELECT `FighterID` FROM `fighters` WHERE `FighterName` = ?");
         if ( false===$stmtFighterA ) {
             die('prepare() failed: ' . htmlspecialchars($conn->error));
             }
         $rc = $stmtFighterA->bind_param("s", $FighterAName);
         if ( false===$rc ) {
             die('bind_param() failed: ' . htmlspecialchars($stmtFighterA->error));
             }

        
         /// Finding the FighterA (fighter) FK
         $stmtFighterB = $conn->prepare("SELECT `FighterID` FROM `fighters` WHERE `FighterName` = ?");
         if ( false===$stmtFighterB ) {
             die('prepare() failed: ' . htmlspecialchars($conn->error));
             }
         $rc = $stmtFighterB->bind_param("s", $FighterBName);
         if ( false===$rc ) {
             die('bind_param() failed: ' . htmlspecialchars($stmtFighterB->error));
             }


    ///Copy for safe keeping
                INSERT INTO `fights` (`FightID`, `Event`, `Winner`, `Loser`, `WeightClass`, `FinishMethod`, `Round`, `FinishTime`, `TimeFormat`, `FighterA`, `KnockdownsA`, `LandedSigStrA`, `AttemptedSigStrA`, `SigStrPercentA`, `LandedStrA`, `TotalStrA`, `LandedTdA`, `AttemptedTdA`, `TdPercentA`, `SubAttA`, `RevA`, `ControlTimeA`, `landedHeadA`, `HeadStrA`, `landedBodyA`, `BodyStrA`, `landedLegA`, `LegStrA`, `landedDisA`, `DistanceStrA`, `landedClinchA`, `ClinchStrA`, `landedGroundA`, `GroundStrA`, `FighterB`, `KnockdownsB`, `LandedSigStrB`, `AttemptedSigStrB`, `SigStrPercentB`, `LandedStrB`, `TotalStrB`, `LandedTdB`, `AttemptedTdB`, `TdPercentB`, `SubAttB`, `RevB`, `ControlTimeB`, `landedHeadB`, `HeadStrB`, `landedBodyB`, `BodyStrB`, `landedLegB`, `LegStrB`, `landedDisB`, `DistanceStrB`, `landedClinchB`, `ClinchStrB`, `landedGroundB`, `GroundStrB`) VALUES (NULL, '{$eventdata["EventTitleID"]}', '{$winnerName["FighterID"]}', '{$loserName["FighterID"]}', '$weightClass["weightDivisionID"]', '$finMethod["FinishMethodID"]', '{$line[5]}', '{$finTimePost}', '{$timeFormat}', '{$figtherNameA["FighterID"]}', '{$line[11]}', '{$line[12]}', '{$line[13]}', '{$sigStrA}', '{$landedStrA}', '{$totStrA}', '{$line[16]}', '{$line[17]}', '{$tdPercentA}', '{$line[19]}', '{$line[20]}', '{$CrtlPostA}', '{$landedHeadA}', '{$totHeadA}', '{$landedBodyA}', '{$totBodyA}', '{$landedLegA}', '{$totLegA}', '{$landedDisA}', '{$totDisA}', '{$landedClinA}', '{$totClinA}', '{$landedGrnA}', '{$totGrnA}', '{$figtherNameB["FighterID"]}', '{$line[29]}', '{$line[30]}', '{$line[31]}', '{$sigStrB}', '{$landedStrB}', '{$totStrB}', '{$line[34]}', '{$line[35]}', '{$tdPercentB}', '{$line[37]}', '{$line[38]}', '{$CrtlPostB}', '{$landedHeadB}', '{$totHeadB}', '{$landedBodyB}', '{$totBodyB}', '{$landedLegB}', '{$totLegB}', '{$landedDisB}', '{$totDisB}', '{$landedClinB}', '{$totClinB}', '{$landedGrnB}', '{$totGrnB}')

                
            echo $eventdata["EventTitleID"] . "<br>";
            echo $loserName["FighterID"] . "<br>";
            echo $winnerName["FighterID"] . "<br>";
            echo $figtherNameA["FighterID"] . "<br>";
            echo $figtherNameB["FighterID"] . "<br>";
            echo $weightClass["weightDivisionID"] . "<br>";
            echo $finMethod["FinishMethodID"] . "<br>";
            echo $finTimePost . "<br>";
            echo $timeFormat . "<br>";
            echo $sigStrA . "<br>";
            echo $sigStrB . "<br>";
            echo $landedStrA . "<br>";
            echo $totStrA . "<br>";
            echo $landedStrB . "<br>";
            echo $totStrB . "<br>";
            echo $tdPercentA . "<br>";
            echo $tdPercentB . "<br>";
           

            echo $CrtlPostA . "<br>";
            echo $CrtlPostB . "<br>";
            
            echo $landedHeadA . "<br>";
            echo $totHeadA . "<br>";
            
            echo $landedHeadB . "<br>";
            echo $totHeadB . "<br>";
            
            echo $landedBodyA . "<br>";
            echo $totBodyA . "<br>";
            
            echo $landedBodyB . "<br>";
            echo $totBodyB . "<br>";
            
            echo $landedLegA . "<br>";
            echo $totLegA . "<br>";
            
            echo $landedLegB . "<br>";
            echo $totLegB . "<br>";
            
            echo $landedDisA . "<br>";
            echo $totDisA . "<br>";
            
            echo $landedDisB . "<br>";
            echo $totDisB . "<br>";
            
            echo $landedClinA . "<br>";
            echo $totClinA . "<br>";
            
            echo $landedClinB . "<br>";
            echo $totClinB . "<br>";
            
            echo $landedGrnA . "<br>";
            echo $totGrnA . "<br>";
            
            echo $landedGrnB . "<br>";
            echo $totGrnB . "<br>";

               /*
            INSERT INTO `fights` (`FightID`,        `Event`,                        `Winner`,                       `Loser`,                    `WeightClass`, `FinishMethod`,      `Round`,    `FinishTime`, `TimeFormat`,     `FighterA`,      `KnockdownsA`, `LandedSigStrA`, `AttemptedSigStrA`, `SigStrPercentA`, `TotalStrA`,     `LandedTdA`, `AttemptedTdA`, `TdPercentA`,      `SubAttA`,      `RevA`,     `ControlTimeA`,     `HeadStrA`,     `BodyStrA`, `LegStrA`,      `DistanceStrA`,     `ClinchStrA`,       `GroundStrA`,       `FighterB`,      `KnockdownsB`,      `LandedSigStrB`,   `AttemptedSigStrB`, `SigStrPercentB`,   `TotalStrB`,    `LandedTdB`, `AttemptedTdB`,    `TdPercentB`, `SubAttB`,    `RevB`,     `ControlTimeB`, `HeadStrB`,     `BodyStrB`, `LegStrB`,  `DistanceStrB`, `ClinchStrB`, `GroundStrB`) 
                            VALUES (NULL,    '{$eventdata["EventTitleID"]}',   '{$winnerName["FighterID"]}',   '{$loserName["FighterID"]}',        '11',           '2',                '3',     '00:02:07',     '3',               '2',            '3',            '3',            '3',                    '3',            '3',            '3',            '3',            '3',        '3',            '3',        '00:02:07',         '3',            '3',            '3',         '3',               '3',                '3',                '3',                '3',                '3',            '3',                '3',                '3',            '3',         '3',               '3',           '3'    ,     '3',        '00:02:07',      '3',           '3',        '3',        '3',            '3',            '3')
                                                        $line[0]FK                       $line[2]FK                     $line[1]                    $line[3]FK      $line[4]FK          $line[5]    $line[6]??      $line[7]??      $line[10]FK       $line[11]     $line[12]           $line[13]           $line[14]       $line[15]       $line[16]       $line[17]       $line[18]??     $line[19]       $line[20]   $line[21]           $line[22]       $line[23]   $line[24]        $line[25]          $line[26]           $line[27]           $line[28]FK         $line[29]          $line[30]        $line[31]           $line[32]           $line[33]       $line[34]     $line[35]         $line[36]       $line[37]   $line[38]   $line[39]       $line[40]       $line[41]   $line[42]   $line[43]       $line[44]       $line[45]
             */






-->