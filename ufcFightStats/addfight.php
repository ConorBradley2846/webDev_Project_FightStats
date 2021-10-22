
<?php
    $newFight = $_POST['postedfight'];

    $endpoint = "http://cbradley75.lampt.eeecs.qub.ac.uk/ufcFightStats/API/postStatsapi.php";

    $posteddate = http_build_query(
        array('var1'=>$name)
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

