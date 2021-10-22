<?php

    include('conn.php');

    $file = "ufc_events.csv";
    $big_array = [];
    $counter = 0;

    if(file_exists($file)){

        $filepath = fopen($file, "r");
        fgetcsv($filepath);
    

        while(($line = fgetcsv($filepath)) !== FALSE){

            $result = $conn->query("SELECT `VenueID` FROM `eventVenue` WHERE `VenueName` = '{$line[3]}'");

            if($result->num_rows == 0) {

                $locationFK = $conn->query("SELECT `LocationID` FROM `eventLocation` WHERE `LocationName` = '{$line[4]}'");

                $locationdata = $locationFK->fetch_assoc();
                
                $insert = "INSERT INTO `eventVenue` (`VenueID`, `VenueName`, `VenueLocal`) VALUES (NULL, '{$line[3]}', '{$locationdata["LocationID"]}')";

                $insertvenue = $conn->query($insert);

                if(!$insertvenue){
                    echo $conn->error;
                }else{
                    echo "<p>$line[3] has been added to your database.</p>";
                }

            } else {
                echo "Already in dataBase";
            }
        
        }

        $conn->close();
        
    }

?>