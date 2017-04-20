<!-- Code that handles all of the actions that can take place -->
<?php
  require_once('SQL.php');
  require_once('cookie.php');
  require_once('session.php');

  $TRACE = 0; // for debugging print statements


  // check if logging out
  if (isset($_POST['Logout'])){
    $_SESSION['id']=NULL;
    $_SESSION['username']=NULL; //$username;
  }

  // not logged in! return to sign-in page
  if (! isset($_SESSION['id']) || ! isset($_SESSION['username'])){
      session_destroy();
      echo "ERROR: Not logged in! returning to login page..";
      header("Location: ../index.php"); /* Redirect browser */
      exit();
  }

  $id = $_SESSION["id"]; // get barid from the login page
  // Set up the connection to the database
  $db = mysqli_connect($servername, $DB_username, $DB_password, $defaultdb, $port);
  // Get the information associated with this bar
  $query = 'SELECT * FROM bars WHERE id='. $id . ';';
  $result = $db->query($query);
  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $barName = $row['name'];
    // Get the address of this bar
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
      }

      // Called when the "Add Drink" button is clicked
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

          // update what will be displayed to app users
          updateTodaysSelection($id, getTodaysDay() );

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

      // Called when the "Add Event" button is clicked
      if (isSet($_POST['addEvent'])) {
        $name = $_POST['name'];
        $desc = $_POST['desc'];
        $query = 'INSERT INTO event (id, barid, name, description, IsEventToday) VALUES (' .
                  'null, \'' . $id . '\', \'' .
                  mysqli_real_escape_string($db, $name) . '\', \'' .
                  mysqli_real_escape_string($db, $desc) . '\', 0);';
        $result = $db->query($query);
        if ($result === TRUE){
          // update what will be displayed to app users
          updateTodaysSelection($id, getTodaysDay() );

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

      // Called when the "Remove Drink" button is clicked
      if (isSet($_POST['removeDrink'])) {
        $did = $_POST['drink'];
        $query = 'DELETE FROM drink WHERE id=\'' .
                    $did . '\';';
        $result = $db->query($query);
        if ($result === TRUE){
          // update what will be displayed to app users
          updateTodaysSelection($id, getTodaysDay() );


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
        // In addition, you must also remove any entries from the specialinfo table
        //  associated with the drink being removed
        $query = 'DELETE FROM specialinfo WHERE drinkid=' . $did . ';';
        $result = $db->query($query);
        if ($result == TRUE) {
          echo 'Drink special deleted!<br>';
        } else {
          echo 'Drink special not deleted<br>';
          echo $db->error . '<br>';
        }
      }

      // Called when the "Remove Event" button is clicked
      if (isSet($_POST['removeEvent'])) {
        $eid = $_POST['event'];
        $query = 'DELETE FROM event WHERE id=\'' . $eid . '\';';
        $result = $db->query($query);
        if ($result === TRUE){

          // update what will be displayed to app users
          updateTodaysSelection($id, getTodaysDay() );
          
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
        // In addition to deleting the row from the event table, you must also
        //  delete the event from the barCalendar table, and any drinks associated
        //  with the event from the specialinfo table
        $query = 'UPDATE barCalendar SET sunday = NULL WHERE sunday =' . $eid . ';';
        $db->query($query);
        $query = 'UPDATE barCalendar SET monday = NULL where monday =' . $eid . ';';
        $db->query($query);
        $query = 'UPDATE barCalendar SET tuesday = NULL WHERE tuesday=' . $eid . ';';
        $db->query($query);
        $query = 'UPDATE barCalendar SET wednesday = NULL WHERE wednesday =' . $eid . ';';
        $db->query($query);
        $query = 'UPDATE barCalendar SET thursday = NULL WHERE thursday =' . $eid . ';';
        $db->query($query);
        $query = 'UPDATE barCalendar SET friday = NULL WHERE friday=' . $eid . ';';
        $db->query($query);
        $query = 'UPDATE barCalendar SET saturday = NULL WHERE saturday =' . $eid . ';';
        $db->query($query);
        $query = 'DELETE FROM specialinfo WHERE eventid=' . $eid . ';';
        $db->query($query);
      }

      // Called when the "Select Event" button is clicked in the scheduler
      if (isSet($_POST['selectEvent'])) {
        $day = substr($_POST['event'], 0, 2);
        $eid = substr($_POST['event'], 3);
        switch ($day) {
          case 'su':
            $day = 'sunday';
            break;
          case 'mo':
            $day = 'monday';
            break;
          case 'tu':
            $day = 'tuesday';
            break;
          case 'we':
            $day = 'wednesday';
            break;
          case 'th':
            $day = 'thursday';
            break;
          case 'fr':
            $day = 'friday';
            break;
          case 'sa':
            $day = 'saturday';
            break;
        }
        $query = 'UPDATE barCalendar SET ' . $day . '=' . $eid . ' WHERE' .
                  ' barid=' . $id . ';';
        $result = $db->query($query);
        if ($result === TRUE) {
          echo 'Event selected! :)<br>';
        } else {
          echo 'Event selection failed :(<br>';
        }
      }

      // Called when the "Add Special" button is clicked in the scheduler
      if (isSet($_POST['addSpecial'])) {
        $did = $_POST['did'];
        $eid = $_POST['eid'];
        $price = $_POST['price'];
        $query = 'INSERT INTO specialinfo (eventid, drinkid, price) VALUES (\'' .
                  $eid . '\', \'' . $did . '\', \'' .
                  mysqli_real_escape_string($db, $price) . '\');';
        $result = $db->query($query);
      }

      // Called when the "Remove Event" button is clicked the scheduler
      if (isSet($_POST['removeEventFromDay'])) {
        $day = $_POST['day'];
        $query = 'UPDATE barCalendar SET ' . $day . ' = NULL where barid=' . $id . ';';
        $result = $db->query($query);
        if ($result == TRUE) {
          echo 'Successfully removed event from ' . $day . '<br>';
        } else {
          echo 'Failed to remove event from ' . $day . ' :(<br>';
        }
      }

      // Called when the "Remove Special" button is clicked in the scheduler
      if (isSet($_POST['removeDrinkFromSpecial'])) {
        $eid = $_POST['eid'];
        $did = $_POST['did'];
        $query = 'DELETE FROM specialinfo WHERE eventid=' . $eid . ' and drinkid=' .
                  $did . ';';
        $result = $db->query($query);
        if ($result == TRUE) {
          echo 'Successfully removed drink '. $did . ' from special!<br>';
        } else {
          echo 'Failed to remove drink from this special :(<br>';
        }
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

<!-- Bootstap sourcing -->
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>


      <!-- Displays the bar's information and address -->
      <form method='POST' action='dashboard.php'>
            <h1>Welcome <?php echo htmlentities($barName); ?>          <button type="submit" name="Logout" value='Logout' class="btn btn-default">Logout</button>

            </h1>

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

      <!-- Displays the "Create Drink" form -->
      <hr>
      <form method ='POST' action='dashboard.php'>
        <h3>Create drink</h3>
          Name of drink: <input type='text' name='name' required>
          <br><br>Description: <input type='text' name='desc' required>
          <br><br>Price: $<input type='number' name='price' step='.01' required>
          <br><br><input type='submit' value='Add Drink' name='addDrink'>
      </form>

      <!-- Displays the "Create Event" form -->
      <hr>
      <form method='POST' action='dashboard.php'>
        <h3>Create event</h3>
        Name of event: <input type='text' name='name' required>
        <br><br>Description: <input type='text' name='desc' size='80' required>'
        <br><br><input type='submit' value='Add Event' name='addEvent'>'
      </form>

      <!-- Displays the "Remove Drink" form -->
      <hr>
      <form method='POST' action='dashboard.php'>
        <h3>Remove Drink</h3>
        Drinks: <select name='drink' required>
        <?php
          $query = 'SELECT * FROM drink WHERE barid=\'' . $id . '\';';
          $drinks = array();
          $result = $db->query($query);
          while ($drink = mysqli_fetch_array($result)) {
            $drinks[] = $drink;
          }
          foreach($drinks as $d) {
            echo '<option value=\'' . $d['id'] . '\'>' . $d['name'] . '</option>';
          }
        ?>
        </select>
        <br><br><input type='submit' value='Remove Drink' name='removeDrink'>
      </form>

      <!-- Displays the "Remove Event" form -->
      <hr>
      <form method='POST' action='dashboard.php'>
        <h3>Remove Event</h3>
        Events: <select name='event' required>
        <?php
          $query = 'SELECT * FROM event WHERE barid=\'' . $id . '\';';
          $events = array();
          $result = $db->query($query);
          while($event = mysqli_fetch_array($result)) {
            $events[] = $event;
          }
          foreach($events as $e) {
            echo '<option value=\'' . $e['id'] . '\'>' . $e['name'] . '</option>';
          }
        ?>
        </select>
        <br><br><input type='submit' value='Remove Event' name='removeEvent'>
      </form>

<!-- Function that displays a specified day in the calendar -->
<?php
  function displayCalendar($eid, $day, $db, $id, $events) {
    // First, check to see if ther is an event on that day
    if ($eid === NULL) {
      // If not display the "Select Event" form
      echo '<form method=\'POST\' action=\'dashboard.php\'>';
      echo '<select name=\'event\' required>';
      foreach ($events as $e) {
        $abbr = substr($day, 0, 2);
        echo '<option value=\'' . $abbr . ':' . $e['id'] . '\'>' . $e['name'] . '</option>';
      }
      echo '</select><br>';
      echo '<input type=\'submit\' value=\'Select Event\' name=\'selectEvent\'>';
      echo '</form>';
    } else {
      // If there is an event, display it
      $query = 'SELECT * FROM event WHERE id=' . $eid . ';';
      $result = $db->query($query);
      $event = mysqli_fetch_row($result);
      echo 'Event: ' . $event[2] . '<br>';
      echo 'Description: ' . $event[3] . '<br>';
      $query = 'SELECT * FROM specialinfo where eventid=' . $eid . ';';
      $result = $db->query($query);
      if ($result == TRUE) {
        $specials = array();
        while ($special = mysqli_fetch_array($result)) {
          $specials[] = $special;
        }
        echo '<hr><h4>Specials for this event</h4>';
        // Display each special associated with this event (if there are any)
        foreach ($specials as $s) {
          $query = 'SELECT * FROM drink WHERE id=' . $s[2] . ';';
          $result = $db->query($query);
          if ($result == TRUE) {
            $row = $result->fetch_assoc();
            $did = $row['id'];
            $query = 'SELECT * FROM specialinfo WHERE drinkid=' . $did . ';';
            $result = $db->query($query);
            if ($result == TRUE) {
              $drink = $result->fetch_assoc();
              echo 'Drink name: ' . $row['name'] . '<br>';
              echo 'Drink descrption: ' . $row['description'] . '<br>';
              echo 'Special price: $' . $drink['price'] . '<br><br>';
              echo '<form method=\'POST\' action=\'dashboard.php\'>';
              echo '<input type=\'hidden\' name=\'did\' value=\'' . $row['id'] .
                      '\'>';
              echo '<input type=\'hidden\' name=\'eid\' value=\'' . $s[1] . '\'>';
              echo '<input type=\'submit\' value=\'Remove Special\' name=\'removeDrinkFromSpecial\'>';
              echo '</form><br>';
            }
          } else {
            'Failed to retrieve this drink :(<br>';
          }
        }
      } else {
        echo 'Failed to get specials associated with this event :(<br>';
      }
      // Display the "Add Special" and "Remove Event" form
      $query = 'SELECT * FROM drink WHERE barid=' . $id . ';';
      $result = $db->query($query);
      if ($result == TRUE) {
        $drinks = array();
        while ($drink = mysqli_fetch_array($result)) {
          $drinks[] = $drink;
        }
        echo '<hr><h4>Add a drink special to this event:</h4>';
        echo '<form method=\'POST\' action=\'dashboard.php\'>';
        echo 'Drinks: <select name=\'did\' required>';
        foreach ($drinks as $d) {
          echo '<option value=\'' . $d['id'] . '\'>' . $d['name'] . '</option>';
        }
        echo '</select><br>';
        echo 'Price: $<input type=\'number\' name=\'price\' step=\'.01\' required>';
        echo '<input type=\'hidden\' name=\'eid\' value=\'' . $eid . '\'>';
        echo '<br><input type=\'submit\' value=\'Add Special\' name=\'addSpecial\'>';
        echo '</form>';
        echo '<hr><form method=\'POST\' action=\'dashboard.php\'>';
        echo '<input type=\'hidden\' name=\'day\' value=\'' . $day . '\'>';
        echo '<input type=\'submit\' name=\'removeEventFromDay\' value =\'Remove Event\'>';
        echo '</form>';
      } else {
        echo 'Could not load drinks for the schedule :(<br>';
      }
    }
  }
?>

  <!-- HTML to display the calendar -->
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
    <?php
      $query = 'SELECT * FROM barCalendar WHERE barid=\'' . $id . '\';';
      $result = $db->query($query);
      $row = mysqli_fetch_row($result);
      $sunID = null;
      $monID = null;
      $tuesID = null;
      $wedID = null;
      $thuID = null;
      $friID = null;
      $satID = null;
      if ($result == TRUE) {
        $sunID = $row[2];
        $monID = $row[3];
        $tuesID = $row[4];
        $wedID = $row[5];
        $thuID = $row[6];
        $friID = $row[7];
        $satID = $row[8];
      } else {
        echo 'There are no events for this bar yet. Add some!<br><br>';
      }
      $query = 'SELECT * FROM event WHERE barid=\'' . $id . '\';';
      $events = array();
      $result = $db->query($query);
      while($event = mysqli_fetch_array($result)) {
        $events[] = $event;
      }
    ?>
    <tr>
      <td><!-- Sunday -->
        <?php
          displayCalendar($sunID, "sunday", $db, $id, $events);
        ?>
      </td>
      <td><!-- Monday -->
        <?php
          displayCalendar($monID, "monday", $db, $id, $events);
        ?>
      </td>
      <td><!-- Tuesday -->
        <?php
          displayCalendar($tuesID, "tuesday", $db, $id, $events);
        ?>
      </td>
      <td><!-- Wednesday -->
        <?php
          displayCalendar($wedID, "wednesday", $db, $id, $events);
        ?>
      </td>
      <td><!-- Thursday -->
        <?php
          displayCalendar($thuID, "thursday", $db, $id, $events);
        ?>
      </td>
      <td><!-- Friday -->
        <?php
          displayCalendar($friID, "friday", $db, $id, $events);
        ?>
      </td>
      <td><!-- Saturday -->
        <?php
          displayCalendar($satID, "saturday", $db, $id, $events);
        ?>
      </td>
  </table>
</html>
