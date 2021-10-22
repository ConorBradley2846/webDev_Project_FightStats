<?php
    include('conn.php');
    $filestats = "ufc_stats_trim.csv";
    $fileevents = "ufc_events_trim.csv";
    $counter = 0;
    $datePre;
    $datePost = [];
    $mnth;
   
    if(file_exists($filestats) && file_exists($fileevents)){

        $filepath = fopen($filestats, "r");
        $filepathtwo = fopen($fileevents, "r");
        fgetcsv($filepath);
        fgetcsv($filepathtwo);

        while(($lineS = fgetcsv($filepath)) !== FALSE){

            $result = $conn->query("SELECT `EventTitleID` FROM `eventTitle` WHERE `EventName` = '{$lineS[0]}'");

            if($result->num_rows == 0) {

                $filepathtwo = fopen($fileevents, "r");

                while(($lineE = fgetcsv($filepathtwo)) !== FALSE){

                    $sim = similar_text("{$lineS[0]}", "{$lineE[0]}", $percent);
        
                    if($percent > 95){
                        
                        echo $lineS[0] . " " . $lineE[0] . "<br>";

                        $venueFK = $conn->query("SELECT `VenueID` FROM `eventVenue` WHERE `VenueName` = '{$lineE[3]}'");

                        $venuedata = $venueFK->fetch_assoc();

                        //Process the date
                        $datePre = $lineE[2];

                        $result = str_replace(',', '', $datePre);
                    
                        $datePost = preg_split('/\s/', $result);

                        if($datePost[0] == "Jan"){
                            $mnth = "01";
                        } elseif($datePost[0] == "Feb"){
                            $mnth = "02";
                        } elseif($datePost[0] == "Mar"){
                            $mnth = "03";
                        } elseif($datePost[0] == "Apr"){
                            $mnth = "04";
                        } elseif($datePost[0] == "May"){
                            $mnth = "05";
                        } elseif($datePost[0] == "Jun"){
                            $mnth = "06";
                        } elseif($datePost[0] == "Jul"){
                            $mnth = "07";
                        } elseif($datePost[0] == "Aug"){
                            $mnth = "08";
                        } elseif($datePost[0] == "Sep"){
                            $mnth = "09";
                        } elseif($datePost[0] == "Oct"){
                            $mnth = "10";
                        } elseif($datePost[0] == "Nov"){
                            $mnth = "11";
                        } elseif($datePost[0] == "Dec"){
                            $mnth = "12";
                        } 
                        
                        $inputdate = $datePost[2] . "-" . $mnth . "-" . $datePost[1];

                        $insert = "INSERT INTO `eventTitle` (`EventTitleID`, `EventName`, `EventVenue`, `EventDate`) VALUES (NULL, '{$lineS[0]}', '{$venuedata["VenueID"]}', '{$inputdate}')";
                        
                        $insertvenue = $conn->query($insert);
        
                        if(!$insertvenue){
                            echo $conn->error;
                        }else{
                            echo "<p>$lineS[0] has been added to your database.</p>" . "<br>";
                            $counter++;
                        }
                        

                    } 

                } // while
                fclose($filepathtwo);

            } //if

        } //parent while
            
    }
        echo $counter . " records inputed.";


/* Counting whats left.

        $countleft[] = array_diff($uniqueEvent, $whatsleft);
        print_r($countleft);

        foreach($countleft as $countU){

            foreach($countU as $c){
                $counter++;
            }
           
        }

*/

?>