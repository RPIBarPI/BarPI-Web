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


	$sql = "SELECT id FROM 'bars' WHERE 1";
	$result = $db->query($sql);


	return $result;
}

?>
