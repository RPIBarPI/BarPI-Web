<?php
  require_once('SQL.php');
  require_once('cookie.php');
  require_once('session.php');

  //Get the ID from the session and open a connection to the DB
  $id = $_SESSION["id"];
  $db = mysqli_connect($servername, $DB_username, $DB_password, $defaultdb, $port);
  $query = 'SELECT * FROM bars WHERE id='. $id . ';';
  $result = $db->query($query);
  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $barName = $row['name'];
    $query = 'SELECT * FROM locations WHERE barid=' . $id;
    $result = $db->query($query);
    if ($result->num_rows === 1) {
      $row = $result->fetch_assoc();
      $aptno = $row['aptno'];
      $street = $row['street'];
      $city = $row['city'];
      $state = $row['state'];
      $zip = $row['zip'];
      $country = $row['country'];
      $address = $aptno . ' ' . $street . ', ' . $city . ', ' . $state . ' ' . $zip . ' ' . $country;

      // Called when the update button is clicked
      //  (i.e., name/address is updated)
      if (isset($_POST['update'])) {
        $db = mysqli_connect($servername, $DB_username, $DB_password, $defaultdb, $port);
        $newName = $_POST['barName'];
        $query = 'UPDATE bars SET name=\'' . mysqli_real_escape_string($db, $newName) . '\' WHERE id=\'' . $id . '\';';
        $result = $db->query($query);
        $barName = $newName;
        $newAptno = $_POST['aptno'];
        $newStreet = $_POST['street'];
        $newCity = $_POST['city'];
        $newState = $_POST['state'];
        $newZip = $_POST['zip'];
        echo 'new zip: ' . mysqli_real_escape_string($db, $newZip) . '<br>';
        $newCountry = $_POST['country'];
        $query = 'UPDATE locations SET aptno=\'' . mysqli_real_escape_string($db, $newAptno) .
                  '\', street=\'' . mysqli_real_escape_string($db, $newStreet) . '\', city=\'' .
                  mysqli_real_escape_string($db, $newCity) . '\', state=\'' .
                  mysqli_real_escape_string($db, $newState) . '\', zip=\'' .
                  mysqli_real_escape_string($db, $newZip) . '\', country=\'' .
                  mysqli_real_escape_string($db, $newCountry) . '\' WHERE barid=' .
                  $id;
        $result = $db->query($query);
        $aptno = $newAptNo;
        $street = $newStreet;
        $city = $newCity;
        $state = $newState;
        $zip = $newZip;
        $country = $newCountry;
        mysqli_close($db);
      }

      // Called when an event or special is added
      if (isSet($_POST['add'])) {
        $isSpecial = 0;
        if ($_POST['isSpecial'] === 'on') $isSpecial = 1;
        $price = $_POST['price'];
        if ($price[0] == '$') {
          $price = substr($price, 1);
        }
        $query = 'INSERT INTO event (barid, name, description, drinkid, price, special) ' .
                    'VALUES (\'' . mysqli_real_escape_string($db, $id) . '\', \'' .
                    mysqli_real_escape_string($db, $_POST['name']) . '\', \'' .
                    mysqli_real_escape_string($db, $_POST['description']) . '\', \'' .
                    mysqli_real_escape_string($db, $_POST['drink'].id) .  '\', \'' .
                    mysqli_real_escape_string($db, $price) . '\', \'' .
                    $isSpecial . '\');';
        $result = $db->query($query);
      }

      //The form for updating the name/address
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

      // Get the list of drinks associated with this bar from the DB
      $drinks = array();
      $query = 'SELECT * FROM drink WHERE barid=' . $id;
      $result = $db->query($query);
      if ($result->num_rows > 1) {
        while ($row = $result->fetch_assoc()) {
          array_push($drinks, $row);
        }
      }

      //The form for adding an event or special
      echo '<hr><form method=\'POST\' action=\'dashboard.php\'>' .
            '<h4>Create event/special</h4>' .
            '<input type=\'checkbox\' name=\'isSpecial\'>Special (if left unchecked, this will be registered as an event)' .
            '<br><br>Name of event/special: <input type=\'text\' name=\'name\' required>' .
            '<br><br>Description: <input type=\'text\' name=\'description\' size=\'80\' required>' .
            '<br><br>Drink: <select name=\'drinks\' required>';

      foreach($drinks as $d) {
        echo '<option value=\'' . $d['name'] . '\'>' . $d['name'] . '</option>';
      }

      echo '</select>';
      echo '<br><br>Price: <input type=\'text\' name=\'price\' required>';
      echo '<br><br><input type=\'submit\' value=\'Add Event/Special\' name=\'add\'>';
      echo '</form>';
    } else {
      //This code should never run. This means that there are two locations with the same barid value.
      echo '<h1>ERROR: did not find one row (in locations) associated with barid: ' . $id . '</h1>';
      echo 'Found: ' . $result->num_rows;
    }
  } else {
    // This code should never run. This means that there are two bars with the same id.
    echo '<h1>ERROR: did not find one row (in bars) associated with barid:' . $id . '</h1>';
    echo 'Found: ' . $result;
  }

?>
