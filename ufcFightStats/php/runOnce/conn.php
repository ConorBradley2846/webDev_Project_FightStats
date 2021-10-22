<?php

    $password = "T1MfR5BCRF9GZCBh";
    $user = "cbradley75";
    $webserver = "cbradley75.lampt.eeecs.qub.ac.uk";
    $db = "cbradley75";

    $conn = new mysqli($webserver, $user, $password, $db);

    if($conn->connect_error){
        echo "Connection failed: ".$conn->connect_error;
    }

?>