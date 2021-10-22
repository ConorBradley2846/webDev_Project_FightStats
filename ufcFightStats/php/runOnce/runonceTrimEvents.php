<?php
    include('conn.php');
    $filestats = "ufc_stats.csv";
    $filestatstrim = "ufc_stats_trim.csv";
    $counter = 0;
    $big_array = [];
   
    if(file_exists($filestats) && file_exists($filestatstrim)){
        $filepathtwo = fopen($filestatstrim, "w");
        $filepath = fopen($filestats, "r");
        fgetcsv($filepath);


        while(($lineS = fgetcsv($filepath)) !== FALSE){
           
            $big_array[] = $lineS[0];

        }
        $uniqueEvent = array_unique($big_array);


        foreach($uniqueEvent as $event){

            $result = $conn->query("SELECT `EventTitleID` FROM `eventTitle` WHERE `EventName` = '{$event}'");

            if (!empty($result) && $result->num_rows == 0) {

                $linearray = array($event);

                fputcsv($filepathtwo, $linearray);
                $counter++;

                unset($linearray);

                

            } //if

        } //parent while
            
    }

        fclose($filepathtwo);
        echo $counter . " rows inputed to csv.";


/* Counting whats left.

        $countleft[] = array_diff($uniqueEvent, $whatsleft);
        print_r($countleft);

        foreach($countleft as $countU){

            foreach($countU as $c){
                $counter++;
            }
           
        }

*/
/* Isolate the events in the other file
<?php
    include('conn.php');
    $filestats = "ufc_stats.csv";
    $fileevents = "ufc_events.csv";
    $fileeventstrim = "ufc_events_trim.csv";
    $counter = 0;
    $allevents[] = 0;
   
    if(file_exists($filestats) && file_exists($fileevents)){

        $filepath = fopen($filestats, "r");
        $filepathtwo = fopen($fileevents, "r");
        $filepathwrite = fopen($fileeventstrim, "w");
        fgetcsv($filepath);
        fgetcsv($filepathtwo);

        while(($lineS = fgetcsv($filepath)) !== FALSE){

            $allevents[] = $lineS[0];
            $filepathtwo = fopen($fileevents, "r");

            while(($lineE = fgetcsv($filepathtwo)) !== FALSE){

                $sim = similar_text("{$lineS[0]}", "{$lineE[1]}", $percent);
            
                if($percent > 95){

                    $big_array[] = $lineS[0];
                    //echo $lineS[0] . " " . $lineE[1] . "<br>";

                } 

            } // while

                fclose($filepathtwo);

        } // parent while
        fclose($filepath);

        $uniqueEvent = array_unique($allevents);


        $countleft = array_diff($uniqueEvent, $big_array );

        foreach($countleft as $countU){
            
            $counter++;
            $linearray = array($countU);

            fputcsv($filepathwrite, $linearray);
    
            unset($linearray);

        }
            
    }
        echo $counter . " records added.";
        fclose($filepathwrite);


*/

?>

*/






?>



