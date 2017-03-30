<?php
require_once('php/SQL.php');
require_once('php/cookie.php');
$tooShort=true;
$passRight=false;


$loggingIn=$_GET["login"];

session_start();
if($_SESSION["id"] == NULL)
{

  function testInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  if($loggingIn == "true")
  {
    echo "DEBUG: loggingIn==true..<br>";
    $uname=testInput($_POST["uname"]);
    echo "uname==".$uname."<br>";
    $pword=testInput($_POST["pword"]);
    echo "P:$pword";
    $rememberMe=$_POST["rememberme"];
    if((strlen($uname) < 50) && (strlen($uname) > 1) && (strlen($pword) > 5))
    {
      $tooShort=false;
      $tempID=checkPassword($uname, $pword);
      if($tempID != NULL)
      {
        echo 'credentials verified.. logging in..';
        $passRight=true;
        $id=$tempID;
        // $username=getUserField($id, "username");

        $_SESSION['id']=$id;
        $_SESSION['username']= $uname; //$username;

        $cookieTime=NULL;
        if($rememberMe == "on") $cookieTime=time()+(60*60*24*365*8);
        setcookie("forums", session_id(), $cookieTime, "/forums/");
        setSeshInfo(session_id(), $_SERVER['REMOTE_ADDR'], $id);
      }
    }
  }

}
else {
  echo "TRACE: ALREADY LOGGED IN!<br>";
}



require_once('php/session.php');


?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<!--<link href="style/style.css" rel="stylesheet" type="text/css"> -->
<title>BarPI | Login</title>
</head>
<body>
<?php //include("header.php");
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
<p>Redirecting...or click <a href="php/dashboard.php">here</a> to proceed.</p>
</div>
<meta http-equiv='Refresh' content='3;url=php/dashboard.php'>
LOGGINGIN;
}
else
{
echo <<<FORMINCORECT

<center>
      <img src="img/logo.png">
      <h2>Admin Sign-In</h2>
      <div class="titlebar"><h4 class="titlebarheader">Login Failed</h4></div>
      <div class="container">
      <p>Your username or password was incorrect.</p>
      <form action="index.php?login=true" method="post" >
        Email:
        <br>
        <input type="email" name="uname" id="uname" required>
        <br>
        Password:
        <br>
        <input type="password" name="pword" required>
        <br>
        Remember me:
        <input type='checkbox' name='rememberme'>
        <br>
        <br>
        <input type="submit" value="Submit">
      </form>
    </center>

</div>
FORMINCORECT;
}
}
else
{
echo <<<FORMEMPTY
<center>
      <img src="img/logo.png">
      <h2>Admin Sign-In</h2>
      <div class="titlebar"><h4 class="titlebarheader">Login Failed</h4></div>
      <div class="container">
      <p>Your username or password was incorrect.</p>
      <form action="index.php?login=true" method="post" >
        Email:
        <br>
        <input type="email" name="uname" id="uname" required>
        <br>
        Password:
        <br>
        <input type="password" name="pword" required>
        <br>
        Remember me:
        <input type='checkbox' name='rememberme'>
        <br>
        <br>
        <input type="submit" value="Submit">
      </form>
    </center>

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
<p>Redirecting...or click <a href="php/dashboard.php">here</a> to proceed.</p>
</div>
<meta http-equiv='Refresh' content='3;url=php/dashboard.php'>
LOGGEDIN;
}
else
{
echo <<<FORM

    <center>
      <img src="img/logo.png">
      <h2>Admin Sign-In</h2>
      <form action="index.php?login=true" method="post" >
        Email:
        <br>
        <input type="email" name="uname" id="uname" required>
        <br>
        Password:
        <br>
        <input type="password" name="pword" required>
        <br>
        Remember me:
        <input type='checkbox' name='rememberme'>
        <br>
        <br>
        <input type="submit" value="Submit">
      </form>
    </center>


FORM;
}
}
?>
</body>
</html>


