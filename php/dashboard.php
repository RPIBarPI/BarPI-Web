<?php
  require_once('SQL.php');
  require_once('cookie.php');
  require_once('session.php');

  $TRACE = 0; // for debugging print statements

  $id = $_SESSION["id"]; // get barid from the login page
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
        $aptno = $newAptno;
        $street = $newStreet;
        $city = $newCity;
        $state = $newState;
        $zip = $newZip;
        $country = $newCountry;
        mysqli_close($db);
      }

      // TODO: finish and test this
      // TODO: get drink id from the selected drink
      // TODO: convert the inputted price to a float

      if (isSet($_POST['add'])) {
        $price = $_POST['price'];
        if ($price[0] == '$') {
          $price = substr($price, 1);
        }

        $isSpecial = 0;
        if ($_POST['isSpecial'] === 'on') 
        $isSpecial = 1;

        if ($isSpecial==1){
          // insert a drink
          //    $query = 'INSERT INTO drink (id, name, description, price, barid) '.
          //    ' VALUES (null, '.

          //    \''mysqli_real_escape_string($db, $_POST['name'])'\', '.
          //    '  \'H-O2\', \'0\', \'1002\')

          $query = 'INSERT INTO drink (id, name, description, price, barid) ' .
            'VALUES (null, \'' .
             

            mysqli_real_escape_string($db, $_POST['name']) . '\', \'' .
            mysqli_real_escape_string($db, $_POST['description']) . '\', \'' .
            mysqli_real_escape_string($db, $price) . '\', \'' .
            mysqli_real_escape_string($db, $id) . 
            '\');'
          ;
          
          if ($result = $db->query($query) === TRUE){
            if ($TRACE){
              echo "TRACE: Tried query:$query<br>\tsuccessfully inserted special/drink!, return value: $result!<br>";
            }
            else{
              echo "Drink Successfully added!<br>";
            }
          }
          else {
            if ($TRACE) {
              echo "TRACE: special/drink not saved in db :(<br>Tried query:$query<br>";
            }
            else{
              echo "Drink was NOT added :(<br>";
            }
          }

        }
        else {
          // insert an event

          // TODO
          // $query = 'INSERT INTO event (barid, name, description, drinkid, price, special) ' .
          //             'VALUES (\'' . mysqli_real_escape_string($db, $id) . '\', \'' .
          //             mysqli_real_escape_string($db, $_POST['name']) . '\', \'' .
          //             mysqli_real_escape_string($db, $_POST['description']) . '\', \'' .
          //             mysqli_real_escape_string($db, $_POST['drink'].id) .  '\', \'' .
          //             mysqli_real_escape_string($db, $price) . '\', \'' .
          //             $isSpecial . '\');';
          
          $result = $db->query($query);
        }
      }


      $query = 'SELECT * FROM drink WHERE barid='. $id . ' AND IsSpecialToday=1;';
      $result = $db->query($query);
      if ($result) {
        $row = $result->fetch_assoc();
        $special_id = $row['id'];
        $old_special_id = $row['id'];
      }
      else {
        echo "ERROR: Bar ID not found<br>";
      }


      if (isSet($_POST['set_special'])) {

        // if post, update the current special, turn off the old special

        $special_id=$_POST['drink_options'];
        //echo "<h1>HERE 2.0 special==". $special_id . " old_special_id==" . $old_special_id . "</h1>";

        $query = 'UPDATE drink SET IsSpecialToday=0 WHERE id=' . $old_special_id;
        $result = $db->query($query);
        if (!$result){
          echo "WARNING: Failed to remove previous special, Maybe no previous special set?<br>";
        }


        $query = 'UPDATE drink SET IsSpecialToday=1 WHERE id=' . $special_id;
        $result = $db->query($query);
        if (!$result){
          echo "ERROR: Failed to set new special<br>";
        }
        

        $old_special_id = $special_id;
      }


    }
    
    
    else {
      echo '<h1>ERROR: did not find one row (in locations) associated with barid: ' . $id . '</h1>';
      echo 'Found: ' . $result->num_rows;
    }
  } 
  else {
    echo '<h1>ERROR: did not find one row (in bars) associated with barid:' . $id . '</h1>';
    echo 'Found: ' . $result;
  }


?>


<html>
      <form method='POST' action='dashboard.php'>
            <h1>Welcome <?php echo htmlentities($barName); ?></h1>
            <p>Your current address is: <?php echo htmlentities($address); ?> </p>
            Change bar name: 
            <input type="text" name="barName" value="<?php echo htmlentities($barName); ?>" required>
            <br><br>Change bar address: 
            <br><br>Apartment no.: <input type='text' name='aptno' value="<?php echo htmlentities($aptno); ?>" required>
            <br><br>Street: <input type='text' name='street' value="<?php echo htmlentities($street); ?>" required>
            <br><br>City: <input type='text' name='city' value="<?php echo htmlentities($city); ?>" required>
            <br><br>State: <input type='text' name='state' value="<?php echo htmlentities($state); ?>" required>
            <br><br>ZIP: <input type='text' name='zip' value="<?php echo htmlentities($zip); ?>" required>
            <br><br>Country: <input type='text' name='country' value="<?php echo htmlentities($country); ?>" required>
            <br><br><input type='submit' value='Update' name='update'>
           </form>
</html>

<?php


      // =========================
      // create / edit an event or special
      // =========================
      echo '<hr><form method=\'POST\' action=\'dashboard.php\'>' .
            '<h3>Create event/special</h3>' .
            '<input type=\'checkbox\' name=\'isSpecial\'>Special (if left unchecked, this will be registered as an event)' 
            ; ;          
      

      echo  '<br><br>Name of event/special: <input type=\'text\' name=\'name\' required>' .
            '<br><br>Description: <input type=\'text\' name=\'description\' size=\'80\' required>';       ;

      


      echo '<br><br>Price: <input type=\'text\' name=\'price\' required>';
      echo '<br><br><input type=\'submit\' value=\'Add Event/Special\' name=\'add\'>';
      echo '</form>';
  


    // ===================
    // set today's special
    // ===================
    $drinks = array();
    $query = 'SELECT * FROM drink WHERE barid=' . $id;
    $result = $db->query($query);
    if ($result->num_rows >= 1) {
      while ($row = $result->fetch_assoc()) {
        array_push($drinks, $row);
      }
    }
    


    echo '<hr><form method=\'POST\' action=\'dashboard.php\'>' .
            '<h3>Set special</h3>' .
            //'<input type=\'checkbox\' name=\'isSpecial\'>Special (if left unchecked, this will be registered as an event)' .
            
            'Pre-existing Drink: <select name=\'drink_options\' id =\'drink_options\' > <br><br>';
      
      foreach($drinks as $d) {
        echo "<option value=". $d['id'] . ">" . $d['name'] . '</option>';
      }
      echo '<br><br><input type=\'submit\' value=\'Set Special\' name=\'set_special\'>';
      echo '</form>';


      // display the current special
      $query = 'SELECT * FROM drink WHERE IsSpecialToday=1 AND id=' . $special_id . ' AND barid=' . $id;
      $result = $db->query($query);
      if ($result){
        $row = $result->fetch_assoc();
        $sName = $row['name'];
        $sDescription = $row['description'];
        $sPrice = $row['price'];
      }
      else{
        echo "ERROR: Failed to get current special<br>";
      }


      echo  '<b><br><br><br><br>Current special: ' . $sName .
            '<br>Description: ' . $sDescription . 
            '<br>Price: $' . $sPrice . '<br><br><b>'; ;


?>
