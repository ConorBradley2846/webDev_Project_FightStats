<?php

//$endpoint = "https://en.wikipedia.org/w/api.php?action=parse&format=json&prop=sections&page=List_of_UFC_events&section=4&prop=wikitext";
//$data = file_get_contents($endpoint);
//$data = json_decode($data, true);
//$data = $data['parse']['wikitext']['*'];
//print_r($data);


//|{{dts|2021|Mar|27}}\n
//'/^\|\{\{dts\|(\d)\|([a-Z]+)\|(\d)\}\}/m'

$data = file_get_contents("https://en.wikipedia.org/w/api.php?action=parse&format=json&prop=wikitext&page=List_of_UFC_events&section=4");
$data = json_decode($data, true);
$data = $data['parse']['wikitext']['*'];

preg_match_all('/^\s*\|\{\{dts\|([0-9]+)\|([a-z]+)\|([0-9]+)\}\}/im', $data, $matches, PREG_SET_ORDER);

//$wins = array();

foreach($matches as $match) {
    echo "<p>" . $match[1] . $match[2] . $match[3] . "</p>";
}

//print_r($matches);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Call</title>
</head>
<body>
<!--
    <h1>Nick Diaz's Wins</h1>
    <table border="1">
        <tr><th>Means</th><th>Wins</th></tr>
        <tr><td>Knockout</td><td><?php echo $wins['ko']; ?></td></tr>
        <tr><td>Submission</td><td><?php echo $wins['sub']; ?></td></tr>
        <tr><td>Decision</td><td><?php echo $wins['dec']; ?></td></tr>
        <tr><td>Disqualification</td><td><?php echo $wins['dq']; ?></td></tr>
        <tr><td>Other</td><td><?php echo $wins['other']; ?></td></tr>
        <tr><td><strong>Total</strong></td><td><strong><?php echo array_sum($wins); ?></strong></td></tr>
    </table>
-->
    
</body>
</html>