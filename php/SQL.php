<?php

$servername = "67.240.52.147"; //"localhost";
$username = "barpi"; //"root";
$password = "MySQL146"; //"";
$defaultdb = "medius_barpi";
$port = 5941;



function checkPassword($cUsername, $cPassword)
{
	echo "TRACE: checking login..<br>";
	echo "DEBUG: HERE .01 <br>";
	global $fdbName, $fdbLocation, $fdbUsername, $fdbPassword;
	$cPassword=md5($cPassword);


	// create connect
	global $servername, $username, $password, $defaultdb, $port;


	$db = mysqli_connect($servername, $username, $password, $defaultdb, $port);
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

	//$returnValue=mysql_fetch_assoc($result);
	//mysql_free_result($result);
	//mysql_close($db);

	mysqli_close($db);
	if ($returnValue["id"]!=null){
		echo "TRACE: successfully verified login!<br>";
	}
	else {
		echo "TRACE: user not found in db<br>";
	}

	return $returnValue["id"];
}
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
function deleteOldSesh($sesh)
{
	global $fdbName, $fdbLocation, $fdbUsername, $fdbPassword;
	$db=mysql_connect($fdbLocation, $fdbUsername, $fdbPassword);
	mysql_select_db($fdbName, $db);
	$query=sprintf("DELETE FROM sessions WHERE sid='%s'", mysql_real_escape_string($sesh));
	mysql_query($query);
	mysql_close($db);
}
function setSeshInfo($sesh, $sip, $barid)
{
	global $servername, $username, $password, $defaultdb, $port;
	

	$db = mysqli_connect($servername, $username, $password, $defaultdb, $port);

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

?>
