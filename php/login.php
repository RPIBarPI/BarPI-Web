<?php
require_once('SQL.php');
require_once('cookie.php');
$tooShort=true;
$passRight=false;
$loggingIn=$_GET["login"];
session_start();
if($_SESSION["id"] == NULL)
{
if($loggingIn == "true")
{
$uname=$_POST["uname"];
$pword=$_POST["pword"];
$rememberMe=$_POST["rememberme"];
if((strlen($uname) < 17) && (strlen($uname) > 0) && (strlen($pword) > 0))
{
$tooShort=false;
$tempID=checkPassword($uname, $pword);
if($tempID != NULL)
{
$passRight=true;
$id=$tempID;
$username=getUserField($id, "username");
$usergroup=getUserField($id, "usergroup");
$_SESSION['id']=$id;
$_SESSION['username']=$username;
$_SESSION['usergroup']=$usergroup;
$cookieTime=NULL;
if($rememberMe == "on") $cookieTime=time()+(60*60*24*365*8);
setcookie("forums", session_id(), $cookieTime, "/forums/");
setSeshInfo(session_id(), $_SERVER['REMOTE_ADDR'], $id, $username);
}
}
}
}
require_once('session.php');
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<?php require_once('meta.php'); ?>
<link rel="icon" type="image/png" href="style/favicon.png">
<link href="style/style.css" rel="stylesheet" type="text/css">
<title>Robot Universe Forums | Login</title>
</head>
<body>
<?php include("header.php");
if($loggingIn == "true")
{
if($tooShort == false)
{
if($passRight == true)
{
echo <<<LOGGINGIN
<div class="titlebar"><h4 class="titlebarheader">Logging In</h4></div>
<div class="container">
<p>You have been successfully logged in.</p>
<p>Redirecting...or click <a href="index.php">here</a> to proceed.</p>
</div>
<meta http-equiv='Refresh' content='3;url=index.php'>
LOGGINGIN;
}
else
{
echo <<<FORMINCORECT
<div class="titlebar"><h4 class="titlebarheader">Login Failed</h4></div>
<div class="container">
<p>Your username or password was incorrect.</p>
<form action='login.php?login=true' method='post'>
<b>Username:</b><br> <input type='text' maxlength='16' name='uname' required><br>
<b>Password:</b><br> <input type='password' name='pword' required><br>
<b>Remember Me:</b>
<input type='checkbox' name='rememberme'><br><br>
<input type='submit' value="Submit" class="submitbtn">
</form>
</div>
FORMINCORECT;
}
}
else
{
echo <<<FORMEMPTY
<div class="titlebar"><h4 class="titlebarheader">Login Failed</h4></div>
<div class="container">
<p>You left one or more fields blank.</p>
<form action='login.php?login=true' method='post'>
<b>Username:</b><br> <input type='text' maxlength='16' name='uname' required><br>
<b>Password:</b><br> <input type='password' name='pword' required><br>
<b>Remember Me:</b>
<input type='checkbox' name='rememberme'><br><br>
<input type='submit' value="Submit" class="submitbtn">
</form>
</div>
FORMEMPTY;
}
}
else
{
if($loggedIn == true)
{
echo <<<LOGGEDIN
<div class="titlebar"><h4 class="titlebarheader">Already Logged In</h4></div>
<div class="container">
<p>You are already logged in...</p>
<p>Redirecting...or click <a href="index.php">here</a> to proceed.</p>
</div>
<meta http-equiv='Refresh' content='3;url=index.php'>
LOGGEDIN;
}
else
{
echo <<<FORM
<div class="titlebar"><h4 class="titlebarheader">Login</h4></div>
<div class="container">
<form action='login.php?login=true' method='post'>
<b>Username:</b><br> <input type='text' maxlength='16' name='uname' required><br>
<b>Password:</b><br> <input type='password' name='pword' required><br>
<b>Remember Me:</b>
<input type='checkbox' name='rememberme'><br><br>
<input type='submit' value="Submit" class="submitbtn">
</form>
</div>
FORM;
}
}
?>
</body>
</html>