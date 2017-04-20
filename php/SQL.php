<?php

// remote database connection info:
$servername = "seanwaclawik.com"; //"67.240.52.147"; //"localhost";
$DB_username = "barpi"; //"root";
$DB_password = "MySQL146"; //"";
$defaultdb = "medius_barpi";
$port = 5941;

$TRACE = 0; // for debugging print statements

/*
 *	CheckPassword takes a username and password
 *		password is hashed using md5 and compared to the database
 *		Return value is the database id of the user
 */
function checkPassword($cUsername, $cPassword)
{
	if ($TRACE) {
		echo "TRACE: checking login..<br>";
		echo "DEBUG: HERE .01 <br>";
	}

	global $fdbName, $fdbLocation, $fdbUsername, $fdbPassword;

	// md5 password
	$cPassword=md5($cPassword);


	// create connection
	global $servername, $DB_username, $DB_password, $defaultdb, $port;


	$db = mysqli_connect($servername, $DB_username, $DB_password, $defaultdb, $port);
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysql_connect_error();
	}

	// check the connection
	if ($db->connect_error){
		echo "DEBUG: BAD! ERROR CONNECTING TO DB!<br>";
		die("connection failed: " . $db->connect_error);
	}


	$sql = "SELECT id, username, password FROM bars WHERE username='$cUsername' AND password='$cPassword'";
	$result = $db->query($sql);


	$returnValue = $result->fetch_assoc();


	mysqli_close($db);
	if ($returnValue["id"]!=null){
		echo "TRACE: successfully verified login!<br>";
	}
	else {
		echo "TRACE: user not found in db<br>";
	}

	return $returnValue["id"];
}

/*
 * getSessionInfo takes a session id
 * 		session is lookedup in db
 *		the resulting table info is returned to the caller
 */
function getSessionInfo($sesh)
{
	global $fdbName, $fdbLocation, $fdbUsername, $fdbPassword;
	$db=mysql_connect($fdbLocation, $fdbUsername, $fdbPassword);
	mysql_select_db($fdbName, $db);
	$query=sprintf("SELECT * FROM sessions WHERE sid='%s'", mysql_real_escape_string($sesh));
	$result=mysql_query($query);
	$returnValue=mysql_fetch_assoc($result);
	mysql_free_result($result);
	mysql_close($db);
	return $returnValue;
}

/*
 * deleteOldSesh takes a session id
 * 		session is lookedup in db
 *		the resulting table info is deleted from db
 *		VOID return
 */
function deleteOldSesh($sesh)
{
	global $fdbName, $fdbLocation, $fdbUsername, $fdbPassword;
	$db=mysql_connect($fdbLocation, $fdbUsername, $fdbPassword);
	mysql_select_db($fdbName, $db);
	$query=sprintf("DELETE FROM sessions WHERE sid='%s'", mysql_real_escape_string($sesh));
	mysql_query($query);
	mysql_close($db);
}

/*
 * setSessionInfo takes a session id, ip address, and bar id associated
 * 		session is created in db with input values
 *		VOID return
 */
function setSeshInfo($sesh, $sip, $barid)
{
	global $servername, $DB_username, $DB_password, $defaultdb, $port;


	$db = mysqli_connect($servername, $DB_username, $DB_password, $defaultdb, $port);

	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysql_connect_error();
	}

	// check the connection
	if ($db->connect_error){
		echo "DEBUG: BAD! ERROR CONNECTING TO DB!<br>";
		die("connection failed: " . $db->connect_error);
	}


	$sesh=mysqli_real_escape_string($db, $sesh);
	$sip=mysqli_real_escape_string($db, $sip);
	$barid=mysqli_real_escape_string($db, $barid);

	$sql = "INSERT INTO sessions (sid, ip, barid) VALUES('$sesh', '$sip', $barid)";

	echo "TRACE:  INSERT INTO sessions (sid, ip, barid) VALUES('$sesh', '$sip', $barid)<br>";

	if ($result = $db->query($sql) === TRUE){
		echo "TRACE: successfully started session, return value: $result!<br>";
	}
	else {
		echo "TRACE: session info not saved in db :(<br>";
	}

	mysqli_close($db);

	//return $returnValue["id"];
}

/*
 * getAllBarIds takes VOID
 * 		all bar ids are selected from bar table
 *		returns array of bar ids
 */
function getAllBarIds(){
	if ($TRACE) {
		echo "TRACE: getting bar ids..<br>";
	}

	global $fdbName, $fdbLocation, $fdbUsername, $fdbPassword;


	// create connection
	global $servername, $DB_username, $DB_password, $defaultdb, $port;


	$db = mysqli_connect($servername, $DB_username, $DB_password, $defaultdb, $port);
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysql_connect_error();
	}

	// check the connection
	if ($db->connect_error){
		echo "DEBUG: BAD! ERROR CONNECTING TO DB!<br>";
		die("connection failed: " . $db->connect_error);
	}


	$sql = "SELECT id FROM bars WHERE 1";
	$result = $db->query($sql);

	//echo "SQL: resutl->numRows:".$result->num_rows."<br>";
	$result = $result->fetch_all(MYSQLI_BOTH);

	//echo "SQL: return val=".$result."<br>";
	//echo "SQL: size of ids == ".count($result)."<br>";


	mysqli_close($db);

	return $result;
}


/* takes a bar id number and day ['sunday', 'monday', ... 'saturday'] as arguments
 * updateTodaysSelection removes any old 'isOnMenuToday' from drink table and 'IsEventToday' from event table
 * for a given bar based on today's day it checks the barCalendar table and selects isOnMenuToday & IsEventToday
 *		if event id matches, Returns VOID
 */
function updateTodaysSelection($barid, $today)
{
	if ($TRACE) {
		echo "TRACE: updating selection for barid=" . $barid . " and day =='".$today."'";
	}

	global $fdbName, $fdbLocation, $fdbUsername, $fdbPassword, $TRACE;


	// create connection
	global $servername, $DB_username, $DB_password, $defaultdb, $port;


	$db = mysqli_connect($servername, $DB_username, $DB_password, $defaultdb, $port);
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysql_connect_error();
	}

	// check the connection
	if ($db->connect_error){
		echo "DEBUG: BAD! ERROR CONNECTING TO DB!<br>";
		die("connection failed: " . $db->connect_error);
	}

  echo 'BARID: ' . $barid . '<br>';
  //		1) for all drinks with barid=$barid, turn off 'isOnMenuToday'
  $query = 'UPDATE drink SET isOnMenuToday=0 WHERE barid=' . $barid . ';';
  $result = $db->query($query);
  if ($result == TRUE) {
    echo 'Successfully set all isOnMenuToday\'s to 0<br>';
  } else {
    echo 'Failed to set all isOnMenuToday\'s to 0<br>';
    echo $db->error . '<br>';
  }

  //		2) for all events with barid=$barid, turn off 'IsEventToday'
  $query = 'UPDATE event SET IsEventToday=0 WHERE barid=' . $barid . ';';
  $result = $db->query($query);
  if ($result == TRUE) {
    echo 'Successfully set all IsEventToday\'s to 0<br>';
  } else {
    echo 'Failed to set all IsEventToday\'s to 0<br>';
  }

  //		3) get event id (if any) for $barid with todays day ($today) from barCalendar table
  $query = 'SELECT ' . $today . ' FROM barCalendar WHERE barid=' . $barid . ';';
  $result = $db->query($query);
  $eid = NULL;
  if ($result == TRUE) {
    $eid = $result->fetch_row()[0];
    if ($eid != NULL) {
      echo 'Successfully retrieved event ID for today\'s event from DB<br>';
      echo 'EID: ' . $eid . '<br>';
    } else {
      echo 'No event on the schedule for today<br>';
    }
  } else {
    echo 'Failed to retrieve today\'s event from DB<br>';
  }

  //		4) set IsEventToday to TRUE for the event id we got above in step #3 from event table
  if ($eid != NULL) {
    $query = 'UPDATE event SET IsEventToday=1 WHERE id=' . $eid . ';';
    $result = $db->query($query);
    if ($result == TRUE) {
      echo 'Successfully updated IsEventToday to TRUE for today\'s event<br>';
    } else {
      echo 'Failed to update IsEventToday for today\'s event<br>';
    }
  }

  //		5) get list of drink id(s) from specialinfo table associated with the event id from step #3
  $drinks = array();
  if ($eid != NULL) {
    $query = 'SELECT drinkid FROM specialinfo WHERE eventid=' . $eid . ';';
    $result = $db->query($query);
    if ($result == TRUE) {
      echo 'Successfully retrieved ' . $result->num_rows .
              ' drink specials associated with this event<br>';
      for ($i = 0; $i < $result->num_rows; $i++) {
        $drinks[$i] = $result->fetch_row()[0];
      }
    } else {
      echo 'Failed to retrieve drink specials associated with this event<br>';
    }
  }

  //		6) set isOnMenuToday to TRUE in drink table for drink ids above in step #5
  if (count($drinks) != 0) {
    foreach ($drinks as $d) {
      $query = 'UPDATE drink SET isOnMenuToday=1 WHERE id=' . $d . ';';
      $result = $db->query($query);
      if ($result == TRUE) {
        echo 'Successfully put drink ' . $d . ' on the menu today<br>';
      } else {
        echo 'Failed to put drink ' . $d . ' on the menu today<br>';
      }
    }
  }

  echo '<br>';
	mysqli_close($db);


}

function getTodaysDay(){
	global $TRACE;

	date_default_timezone_set("America/New_York");

	// get day of week
	$dayNum = getdate();
	$dayNum = $dayNum['wday'];
	$dayStr="ERORR! day not found";

	if ($TRACE==1)
		echo "<br><br> dayNum== ".$dayNum."<br><br>";

	if ($dayNum == 0){
		$dayStr="sunday";
	}
	else if ($dayNum == 1) {
		$dayStr="monday";
	}
	else if ($dayNum == 2) {
		$dayStr="tuesday";
	}
	else if ($dayNum == 3) {
		$dayStr="wednesday";
	}
	else if ($dayNum == 4){
		$dayStr="thursday";
	}
	else if ($dayNum == 5) {
		$dayStr="friday";
	}
	else if ($dayNum == 6) {
		$dayStr="saturday";
	}

	if ($TRACE==1)
		echo "day of week is:" .  $dayStr. " dayname() == ". date('l');

	return $dayStr;
}




?>
