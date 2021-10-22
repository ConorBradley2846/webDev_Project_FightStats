<?php

$pw="T1MfR5BCRF9GZCBh";
$user = "cbradley75";
$webserver = "cbradley75.lampt.eeecs.qub.ac.uk";
$db = "cbradley75";

//mysqli api library in PHP to connect to the DB
$conn = new mysqli($webserver, $user, $pw, $db);

if($conn->connect_error){
    echo "Connection failed: ".$conn->connect_error;
}

?>