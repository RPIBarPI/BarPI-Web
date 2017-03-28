<?php
  require_once('SQL.php');
  require_once('cookie.php');

  $barName = 'BAR NAME'; //TODO: get this from the DB
  $address = 'ADDRESS'; //TODO: get this from the DB


  if (isset($_POST['update'])) {
    if (strlen($_POST['barName']) > 0) $barName = $_POST['barName'];
    if (strlen($_POST['barAddress']) > 0) $address = $_POST['barAddress'];
    //TODO: update in database
  }

  echo '<form method=\'POST\' action=\'dashboard.php\'>' .
        '<h1>Welcome ' . $barName . '!</h1>' .
        '<p>Your current address is: ' . $address . '</p>' .
        'Change bar name: ' .
        '<input type=\'text\' name=\'barName\'>' .
        '<br><br>Change bar address: ' .
        '<input type=\'text\' name=\'barAddress\'>' .
        '<br><br><input type=\'submit\' value=\'Update\' name=\'update\'>' .
       '</form>';

?>
