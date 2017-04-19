<?php
require_once('php/SQL.php');
require_once('php/cookie.php');
$tooShort=true;
$passRight=false;

$TRACE = 0; // for debugging print statements

$loggingIn=$_GET["login"];

session_start();
if($_SESSION["id"] == NULL)
{
  /* testInput safety checks input data taking out any hazards
   * Returns string with hazards removed
   */
  function testInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  if($loggingIn == "true")
  {
    if ($TRACE){
      echo "DEBUG: loggingIn==true..<br>";
    }

    $uname=testInput($_POST["uname"]);

    if ($TRACE){
      echo "uname==".$uname."<br>";
    }

    $pword=testInput($_POST["pword"]);

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
  $passRight=true;
  $loggingIn ="true";
  $tooShort=false;

}



require_once('php/session.php');


?>
<!DOCTYPE HTML>
<html lang="en">

<!-- Bootstap sourcing -->
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>



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
  // Form to redirect to successful login
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

  // incorrect login information! re-display login information
echo <<<FORMINCORECT

<center>
      <img src="img/logo.png">
      <h2>Admin Sign-In</h2>
      <div class="titlebar"><h4 class="titlebarheader">Login Failed</h4></div>
      <div class="container">
      <p>Your username or password was incorrect.</p>
      <form action="index.php?login=true" method="post" >
        <div class="form-group">
        
        Email:
        <br>
        <input type="email" name="uname" id="uname" class="form-control" required>
        <br>
        Password:
        <br>
        <input type="password" name="pword" class="form-control" required>
        <br>

        </div>
        <div class="checkbox">
        <lable><input type='checkbox' name='rememberme'> Remember me</lable>
        </div>
        <br>
        <br>
        <input type="submit" value="Submit" class="btn btn-default">
      </form>
    </center>

</div>
FORMINCORECT;
}

}
else
{
  // Form if field(s) left empty re-display login information
echo <<<FORMEMPTY
<center>
      <img src="img/logo.png">
      <h2>Admin Sign-In</h2>
      <div class="titlebar"><h4 class="titlebarheader">Login Failed</h4></div>
      <div class="container">
      <p>Your username or password was incorrect.</p>
      <form action="index.php?login=true" method="post" >
        <div class="form-group">
        
        Email:
        <br>
        <input type="email" name="uname" id="uname" class="form-control" required>
        <br>
        Password:
        <br>
        <input type="password" name="pword" class="form-control" required>
        <br>

        </div>
        <div class="checkbox">
        <lable><input type='checkbox' name='rememberme'> Remember me</lable>
        </div>
        <br>
        <br>
        <input type="submit" value="Submit" class="btn btn-default">
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
  // succesful login page
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

  // default -- display login form
echo <<<FORM

    <center>
      <img src="img/logo.png">
      <h2>Admin Sign-In</h2>
      <form action="index.php?login=true" method="post" >
        <div class="form-group">
        
        Email:
        <br>
        <input type="email" name="uname" id="uname" class="form-control" required>
        <br>
        Password:
        <br>
        <input type="password" name="pword" class="form-control" required>
        <br>

        </div>
        <div class="checkbox">
        <lable><input type='checkbox' name='rememberme'> Remember me</lable>
        </div>
        <br>
        <br>
        <input type="submit" value="Submit" class="btn btn-default">
      </form>
    </center>


FORM;
}
}
?>
</body>
</html>


