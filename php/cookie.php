<?php
/*
 * Initalizes the cookie information for a user
 */
	require_once('SQL.php');
	$loggedIn=false;
	$usergroup=0;

	if(isset($_COOKIE["forums"]))
	{
		if($_SESSION["id"] == NULL)
		{
			session_id($_COOKIE["forums"]);
			$seshData=getSessionInfo($_COOKIE["forums"]);
			if($seshData != NULL)
			{
				$loggedIn=true;
				$_SESSION["id"]=$seshData["uid"];
				$_SESSION["username"]=$seshData["username"];
				//$_SESSION["usergroup"]=getUserField($_SESSION["id"], "usergroup");
				$id=$_SESSION["id"];
				$username=$_SESSION["username"];
				//$usergroup=$_SESSION["usergroup"];
			}
		}
	}
?>
