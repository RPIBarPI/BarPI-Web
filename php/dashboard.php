<?php
  require_once('SQL.php');
  require_once('cookie.php');

  $barName = 'BAR NAME'; //TODO: get this from the DB
  $aptno = 'APTNO'; // TODO: get these from the DB
  $street = 'STREET';
  $city = 'CITY';
  $state = 'STATE';
  $zip = 'ZIP';
  $country = 'COUNTRY';
  $address = $aptno . ' ' . $street . ', ' . $city . ', ' . $state . ' ' . $zip . ' ' . $country;

  //TODO: test this
  if (isset($_POST['update'])) {
    if (strlen($_POST['barName']) > 0) {
      $newName = $_POST['barName'];
      $query = 'UPDATE bars SET name=' . mysql_real_escape_string($newName) . ' WHERE name=' . $barName;
      $result = mysql_query($query);
      $barName = $newName;
    }
    $change = False;
    if (strlen($_POST['aptno']) > 0) {
      $newAptno = $_POST['aptno'];
      $change = True;
    } else {
      $newAptno = $aptno;
    }
    if (strlen($_POST['street']) > 0) {
      $newStreet = $_POST['street'];
      $change = True;
    } else {
      $newStreet = $street;
    }
    if (strlen($_POST['city']) > 0) {
      $newCity = $_POST['city'];
      $change = True;
    } else {
      $newCity = $city;
    }
    if (strlen($_POST['state']) > 0) {
      $newState = $_POST['state'];
      $change = True;
    } else {
      $newState = $state;
    }
    if (strlen($_POST['zip']) > 0) {
      $newZip = $_POST['zip'];
      $change = True;
    } else {
      $newZip = $zip;
    }
    if (strlen($_POST['country']) > 0) {
      $newCountry = $_POST['country'];
      $change = True;
    } else {
      $newCountry = $country;
    }
    if ($change) {
      $query = 'UPDATE locations SET aptno=' . mysql_real_escape_string($newAptno) .
                ', street=' . mysql_real_escape_string($newStreet) . ', city=' .
                mysql_real_escape_string($newCity) . ', state=' .
                mysql_real_escape_string($newState) . ', zip=' .
                mysql_real_escape_string($newZip) . ', country=' .
                mysql_real_escape_string($newCountry) . ' WHERE aptno=' .
                $aptno . ' AND street=' . $street . ' AND city=' . $city .
                ' AND state=' . $state . ' AND zip=' . $zip . ' AND country=' .
                $country;
      $result = mysql_query($query);
      $aptno = $newApto;
      $street = $newStreet;
      $city = $newCity;
      $state = $newState;
      $zip = $newZip;
      $country = $newCountry;
    }
  }

  echo '<form method=\'POST\' action=\'dashboard.php\'>' .
        '<h1>Welcome ' . $barName . '!</h1>' .
        '<p>Your current address is: ' . $address . '</p>' .
        'Change bar name: ' .
        '<input type=\'text\' name=\'barName\'>' .
        '<br><br>Change bar address: ' .
        '<br><br>Apartment no.: <input type=\'text\' name=\'aptno\'>' .
        '<br><br>Street: <input type=\'text\' name=\'street\'>' .
        '<br><br>City: <input type=\'text\' name=\'city\'>' .
        '<br><br>State: <input type=\'text\' name=\'state\'>' .
        '<br><br>ZIP: <input type=\'text\' name=\'zip\'>' .
        '<br><br>Country: <input type=\'text\' name=\'country\'>' .
        '<br><br><input type=\'submit\' value=\'Update\' name=\'update\'>' .
       '</form>';

?>
