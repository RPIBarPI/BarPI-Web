<?php

$servername = "localhost";
$username = "root";
$password = "";
$defaultdb = "mysql";



function checkPassword($cUsername, $cPassword)
{
	echo "TRACE: checking login..<br>";
	global $fdbName, $fdbLocation, $fdbUsername, $fdbPassword;
	//TODO: $cPassword=md5($cPassword);

	//$db=mysql_connect($fdbLocation, $fdbUsername, $fdbPassword);
	//mysql_select_db($fdbName, $db);

	// create connect
	global $servername, $username, $password, $defaultdb;
	$db = new mysqli($servername, $username, $password, $defaultdb);

	// check the connection
	if ($db->connect_error){
		echo "DEBUG: BAD! ERROR CONNECTING TO DB!<br>";
		die("connection failed: " . $db->connect_error);
	}




	//$query=sprintf("SELECT id FROM users WHERE username='%s' AND password='%s'", mysql_real_escape_string($cUsername), mysql_real_escape_string($cPassword));
	//	$result=mysql_query($query);

	$sql = "SELECT id, username, pw FROM test WHERE username='$cUsername' AND pw='$cPassword'";
	$result = $db->query($sql);



	// if ($result->num_rows > 0) {
	//     // output data of each row
	//     while($row = $result->fetch_assoc()) {
	//         echo "id: " . $row["id"]. " - uName: " . $row["username"]. " pw: " . $row["pw"]. "<br>";
	//     }
	// } else {
	//     echo "0 results";
	// }

	$returnValue = $result->fetch_assoc();

	//$returnValue=mysql_fetch_assoc($result);
	//mysql_free_result($result);
	//mysql_close($db);

	mysqli_close($db);
	if ($returnValue["id"]!=null){
		echo "TRACE: successfully verified login!<br>";
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