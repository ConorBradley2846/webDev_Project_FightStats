<?php

    include('conn.php');
    $file = "ufc_stats.csv";
    $counter = 0;

    if(file_exists($file)){

        $filepath = fopen($file, "r");
        fgetcsv($filepath);

        //prepare and bind select
        $selstmt = $conn->prepare("SELECT `FighterID` FROM `fighters` WHERE `FighterName` = ?");
        $selstmt->bind_param("s", $fighternameS);

        //prepare and bind insert
        $stmt = $conn->prepare("INSERT INTO `fighters` (`FighterID`, `FighterName`) VALUES (NULL, ?)");
        $stmt->bind_param("s", $fighternameI);

        while( ($line = fgetcsv($filepath)) !== FALSE){

            $fighternameS = $line[1];
            $selstmt->execute();
            $result = $selstmt->get_result();

            if($result->num_rows == 0) {

                $fighternameI = $line[1];
                $stmt->execute();
                $runinsert = $stmt->get_result();

                if(!$runinsert){
                    echo $conn->error;
                }else{
                    echo "<p>$line[1] has been added to your database.</p>";
                }
                $counter++;

            }

            $fighternameS = $line[2];
            $selstmt->execute();
            $result = $selstmt->get_result();

            if($result->num_rows == 0) {

                $fighternameI = $line[2];
                $stmt->execute();
                $runinsert = $stmt->get_result();

                if(!$runinsert){
                    echo $conn->error;
                }else{
                    echo "<p>$line[2] has been added to your database.</p>";
                }
                $counter++;

            }

        }
        //print_r($result);
        echo $counter . " records added to db";

        fclose($filepath);
    }

?>