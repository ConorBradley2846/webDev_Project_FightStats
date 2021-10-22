<?php
    session_start();
    $userID = $_SESSION['fightsUser'];
    $fightID = $_POST['fightidFav'];

    $endpoint = "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/postStatsapi.php?unfavFight";

    $posteddata = http_build_query(
        array('userid'=>$userID, 'fightidFav'=>$fightID)
    );

    $opts= array(
        'http' => array(
            'method'=>'POST',
            'header'=>'Content-Type: application/x-www-form-urlencoded',
            'content'=>$posteddata
        )

    );

    $context = stream_context_create($opts);

    $result = file_get_contents($endpoint, false, $context);



?>