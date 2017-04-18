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
        $aptno = $newAptno;
        $street = $newStreet;
        $city = $newCity;
        $state = $newState;
        $zip = $newZip;
        $country = $newCountry;
        mysqli_close($db);
      }

      if (isSet($_POST['addDrink'])) {
        $name = $_POST['name'];
        $desc = $_POST['desc'];
        $price = $_POST['price'];
        $query = 'INSERT INTO drink (id, name, description, price, barid, isOnMenuToday) ' .
                  'VALUES (null, \'' .
                  mysqli_real_escape_string($db, $name) . '\', \'' .
                  mysqli_real_escape_string($db, $desc) . '\', \'' .
                  mysqli_real_escape_string($db, $price) . '\', \'' .
                  $id . '\', \'0\');';
        $result = $db->query($query);
        if ($result === TRUE){
          if ($TRACE){
            echo "TRACE: Tried query:$query<br>\tsuccessfully inserted drink!, return value: $result!<br>";
          }
          else{
            echo "Drink Successfully added!<br>";
          }
        }
        else {
          if ($TRACE) {
            echo "TRACE: Drink not saved in db :(<br>Tried query:$query<br>";
          }
          else{
            echo "Drink was NOT added :(<br>";
          }
        }
      }

      // Called when an event or special is added
      if (isSet($_POST['addEvent'])) {
        $name = $_POST['name'];
        $desc = $_POST['desc'];
        $query = 'INSERT INTO event (id, barid, name, description, IsEventToday) VALUES (' .
                  'null, \'' . $id . '\', \'' .
                  mysqli_real_escape_string($db, $name) . '\', \'' .
                  mysqli_real_escape_string($db, $desc) . '\', 0);';
        $result = $db->query($query);
        if ($result === TRUE){
          if ($TRACE){
            echo "TRACE: Tried query:$query<br>\tsuccessfully inserted event!, return value: $result!<br>";
          }
          else{
            echo "Event Successfully added!<br>";
          }
        }
        else {
          if ($TRACE) {
            echo "TRACE: Event not saved in db :(<br>Tried query:$query<br>";
          }
          else{
            echo "Event was NOT added :(<br>";
          }
        }
      }

      // The function to remove a drink
      if (isSet($_POST['removeDrink'])) {
        $did = $_POST['drink'];
        $query = 'DELETE FROM drink WHERE id=\'' .
                    $did . '\';';
        $result = $db->query($query);
        if ($result === TRUE){
          if ($TRACE){
            echo "TRACE: Tried query:$query<br>\tsuccessfully deleted drink!, return value: $result!<br>";
          }
          else{
            echo "Drink successfully deleted!<br>";
          }
        }
        else {
            echo "Drink was NOT deleted :(<br>";
        }
      }

      if (isSet($_POST['removeEvent'])) {
        $eid = $_POST['event'];
        $query = 'DELETE FROM event WHERE id=\'' . $eid . '\';';
        $result = $db->query($query);
        if ($result === TRUE){
          if ($TRACE){
            echo "TRACE: Tried query:$query<br>\tsuccessfully deleted event!, return value: $result!<br>";
          }
          else{
            echo "Event Successfully deleted!<br>";
          }
        }
        else {
            echo "Event was NOT deleted :(<br>";
        }
      }

      // Get the list of drinks associated with this bar from the DB
      $drinks = array();
      $query = 'SELECT * FROM drink WHERE barid='. $id . ';';
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

      // ==============
      // create a drink
      // ==============
      echo '<hr><form method =\'POST\' action=\'dashboard.php\'>' .
            '<h3>Create drink</h3>' .
            'Name of drink: <input type=\'text\' name=\'name\' required>' .
            '<br><br>Description: <input type=\'text\' name=\'desc\' required>' .
            '<br><br>Price: $<input type=\'number\' name=\'price\' step=\'.01\' required>' .
            '<br><br><input type=\'submit\' value=\'Add Drink\' name=\'addDrink\'>' .
            '</form>';


      // ===============
      // create an event
      // ===============
      echo '<hr><form method=\'POST\' action=\'dashboard.php\'>' .
            '<h3>Create event</h3>';
      echo  'Name of event: <input type=\'text\' name=\'name\' required>' .
            '<br><br>Description: <input type=\'text\' name=\'desc\' size=\'80\' required>';
      echo '<br><br><input type=\'submit\' value=\'Add Event\' name=\'addEvent\'>';
      echo '</form>';

      // ==============
      // Remove a drink
      // ==============

      echo '<hr><form method=\'POST\' action=\'dashboard.php\'>'.
            '<h3>Remove Drink</h3>' .
            'Drinks: <select name=\'drink\' required>';

      $query = 'SELECT * FROM drink WHERE barid=\'' . $id . '\';';
      $drinks = array();
      $result = $db->query($query);
      while ($drink = mysqli_fetch_array($result)) {
        $drinks[] = $drink;
      }
      foreach($drinks as $d) {
        echo '<option value=\'' . $d['id'] . '\'>' . $d['name'] . '</option>';
      }

      echo '</select>' .
            '<br><br><input type=\'submit\' value=\'Remove Drink\' name=\'removeDrink\'>';

      // ===============
      // Remove an event
      // ===============

      echo '<hr><form method=\'POST\' action=\'dashboard.php\'>' .
            '<h3>Remove event</h3>' .
            'Events: <select name=\'event\' required>';

      $query = 'SELECT * FROM event WHERE barid=\'' . $id . '\';';
      $events = array();
      $result = $db->query($query);
      while($event = mysqli_fetch_array($result)) {
        $events[] = $event;
      }
      foreach($events as $e) {
        echo '<option value=\'' . $e['id'] . '\'>' . $e['name'] . '</option>';
      }

      echo '</select>' .
            '<br><br><input type=\'submit\' value=\'Remove Event\' name=\'removeEvent\'>';

?>

<!-- Weekly Calendar -->
<html>
  <hr>
  <h3>Weekly Calendar</h3>
  <table border='1'>
    <tr>
      <th>Sunday</th>
      <th>Monday</th>
      <th>Tuesday</th>
      <th>Wednesday</th>
      <th>Thursday</th>
      <th>Friday</th>
      <th>Saturday</th>
    </tr>
    <tr>
      <td><!-- Sunday -->
        S
      </td>
      <td><!-- Monday -->
        M
      </td>
      <td><!-- Tuesday -->
        T
      </td>
      <td><!-- Wednesday -->
        W
      </td>
      <td><!-- Thursday -->
        T
      </td>
      <td><!-- Friday -->
        F
      </td>
      <td><!-- Saturday -->
        S
      </td>
  </table>
</html>
