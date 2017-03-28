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

	echo "md5 pw:$cPassword<br>";
	//$db=mysql_connect($fdbLocation, $fdbUsername, $fdbPassword);
	//mysql_select_db($fdbName, $db);

	echo "DEBUG: HERE 0.1 <br>";

	// create connect
	global $servername, $username, $password, $defaultdb, $port;
	$db = mysqli_connect($servername, $username, $password, $defaultdb, $port);

	// check the connection
	if ($db->connect_error){
		echo "DEBUG: BAD! ERROR CONNECTING TO DB!<br>";
		die("connection failed: " . $db->connect_error);
	}


	echo "DEBUG: HERE 1 <br>";


	$sql = "SELECT id, username, password FROM bars WHERE username='$cUsername' AND password='$cPassword'";
	$result = $db->query($sql);


	echo "DEBUG: HERE 2 <br>";


	echo "DEBUG: HERE 3 <br>";

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
function setSeshInfo($sesh, $sip, $uid, $uname)
{
	global $fdbName, $fdbLocation, $fdbUsername, $fdbPassword;
	$db=mysql_connect($fdbLocation, $fdbUsername, $fdbPassword);
	mysql_select_db($fdbName, $db);
	$query=sprintf("INSERT INTO sessions (sid, ip, uid, username) VALUES('%s', '%s', %d, '%s')", mysql_real_escape_string($sesh), mysql_real_escape_string($sip), $uid, mysql_real_escape_string($uname));
	mysql_query($query);
	mysql_close($db);
}

?>
