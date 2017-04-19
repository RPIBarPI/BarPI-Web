<?php
require_once('SQL.php');
session_start();

// sets the session if not already

if($_SESSION["id"] != NULL)
{
$loggedIn=true;
$id=$_SESSION["id"];
$username=$_SESSION["username"];
//$usergroup=$_SESSION["usergroup"];
//refreshOnline($id);
}



/*function refreshOnline() {
    $.get('http://frosthotel.co.uk/refreshstats', function(data) {
        if(data!=$('#toupdate').html()) $('#toupdate').html(data);
    });
}*/


?>
