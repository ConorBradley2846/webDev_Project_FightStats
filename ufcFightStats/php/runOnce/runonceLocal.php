<?php

    include('conn.php');
    $file = "ufc_events.csv";
    $counter = 0;

    if(file_exists($file)){

        $filepath = fopen($file, "r");
        fgetcsv($filepath);

        while( ($line = fgetcsv($filepath)) !== FALSE){
           
            $big_array[] = $line[4];

        }
        $uniqueLocal = array_unique($big_array);

        echo sizeof($uniqueLocal) . "<br>";

        foreach($uniqueLocal as $local){

            
            $insert = "INSERT INTO `eventLocation` (`LocationID`, `LocationName`) VALUES (NULL, '{$local}')";
            $result = $conn->query($insert);

            if(!$result){
                echo $conn->error;
            }else{
                echo "<p>$local has been added to your database.</p>";
            }
            
            $counter++;
            echo $counter . " " . $local . "<br>";
 
        }
        //print_r($result);
    }

?>