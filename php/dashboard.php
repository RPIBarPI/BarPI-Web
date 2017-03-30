<?php
  require_once('SQL.php');
  require_once('cookie.php');
  require_once('session.php');

  $id = 1; // TODO: get this from the DB
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
    $newAptno = $_POST['aptno'];
    $newStreet = $_POST['street'];
    $newCity = $_POST['city'];
    $newState = $_POST['state'];
    $newZip = $_POST['zip'];
    $newCountry = $_POST['country'];
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

  // TODO: finish and test this
  // TODO: get drink id from the selected drink
  // TODO: convert the inputted price to a float
  if (isSet($_POST['add'])) {
    $isSpecial = 0;
    if ($_POST['isSpecial'] === 'on') $isSpecial = 1;
    $query = 'INSERT INTO event (barid, name, description, drinkid, price, special) ' .
                'VALUES (' . mysql_real_escape_string($id) . ', ' .
                mysql_real_escape_string($_POST['name']) . ', ' .
                mysql_real_escape_string($_POST['description']) . ', ' .
                mysql_real_escape_string($_POST['drink'].id) .  ', ' .
                mysql_real_escape_string($_POST['price']) . ', ' .
                $isSpecial . ');';
    $result = mysql_query($query);
  }

  echo '<form method=\'POST\' action=\'dashboard.php\'>' .
        '<h1>Welcome ' . $barName . '!</h1>' .
        '<p>Your current address is: ' . $address . '</p>' .
        'Change bar name: ' .
        '<input type=\'text\' name=\'barName\' required>' .
        '<br><br>Change bar address: ' .
        '<br><br>Apartment no.: <input type=\'text\' name=\'aptno\' required>' .
        '<br><br>Street: <input type=\'text\' name=\'street\' required>' .
        '<br><br>City: <input type=\'text\' name=\'city\' required>' .
        '<br><br>State: <input type=\'text\' name=\'state\' required>' .
        '<br><br>ZIP: <input type=\'text\' name=\'zip\' required>' .
        '<br><br>Country: <input type=\'text\' name=\'country\' required>' .
        '<br><br><input type=\'submit\' value=\'Update\' name=\'update\'>' .
       '</form>';

  $query = 'SELECT * FROM drink WHERE barid=' . $id;
  //TODO: test and make sure the result is in the correct format
  //$drinks = mysql_query($query);
  // NOTE: for now, just use a sample list
  $drinks = array("White Russian", "Lucky 7", "Blue Moon Pitcher");

  echo '<hr><form method=\'POST\' action=\'dashboard.php\'>' .
        '<h4>Create event/special</h4>' .
        '<input type=\'checkbox\' name=\'isSpecial\'>Special (if left unchecked, this will be registered as an event)' .
        '<br><br>Name of event/special: <input type=\'text\' name=\'name\' required>' .
        '<br><br>Description: <input type=\'text\' name=\'description\' size=\'80\' required>' .
        '<br><br>Drink: <select name=\'drinks\' required>';

  foreach($drinks as $d) {
    echo '<option value=\'' . $d . '\'>' . $d . '</option>';
  }

  echo '</select>';
  echo '<br><br>Price: <input type=\'text\' name=\'price\' required>';
  echo '<br><br><input type=\'submit\' value=\'Add Event/Special\' name=\'add\'>';
  echo '</form>';

?>
