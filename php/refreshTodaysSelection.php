<?php

// scheduled on system to run every morning at 5am



require_once('SQL.php');


$TRACE = 1; // for debugging print statements

// get all the bar ids
$bar_ids = getAllBarIds();

// get todays day (only next day if past 5am NOT midnight)
$today = getTodaysDay();

if ($TRACE)
	echo "num bar ids found ==".count($bar_ids)."<br>";


// loop over all bars and set as appropriate
for ($i=0; $i < count($bar_ids); $i++){
	updateTodaysSelection($bar_ids[$i][0], $today);

	if ($TRACE==1)
		echo "i==".$i."<br>";
}



?>
