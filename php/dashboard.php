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

      if (isSet($_POST['addSpecial'])) {
        $did = $_POST['did'];
        $eid = $_POST['eid'];
        $price = $_POST['price'];
        $query = 'INSERT INTO specialinfo (eventid, drinkid, price) VALUES (\'' .
                  $eid . '\', \'' . $did . '\', \'' .
                  mysqli_real_escape_string($db, $price) . '\');';
        $result = $db->query($query);
      }

      if (isSet($_POST['removeEventFromDay'])) {
        // TODO: fix this then add the functionality to remove an event from a day
        //    to all of the days
        // TODO: TEST EVERYTHING
        $day = $_POST['day'];
        $query = 'UPDATE barCalendar SET ' . $day . ' = NULL where barid=' . $id . ';';
        $result = $db->query($query);
        if ($result == TRUE) {
          echo 'Successfully removed event from ' . $day . '<br>';
        } else {
          echo 'Failed to remove event from ' . $day . ' :(<br>';
        }
      }

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
      echo '</form>';

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
      echo '</form>';

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
          if ($sunID === NULL) {
            echo '<form method=\'POST\' action=\'dashboard.php\'>';
            echo '<select name=\'event\' required>';
            foreach ($events as $e) {
              echo '<option value=\'su:' . $e['id'] . '\'>' . $e['name'] . '</option>';
            }
            echo '</select><br>';
            echo '<input type=\'submit\' value=\'Select Event\' name=\'selectEvent\'>';
            echo '</form>';
          } else {
            $query = 'SELECT * FROM event WHERE id=' . $sunID . ';';
            $result = $db->query($query);
            $event = mysqli_fetch_row($result);
            echo 'Event: ' . $event[2] . '<br>';
            echo 'Description: ' . $event[3] . '<br>';
            $query = 'SELECT * FROM specialinfo where eventid=' . $sunID . ';';
            $result = $db->query($query);
            if ($result == TRUE) {
              $specials = array();
              while ($special = mysqli_fetch_array($result)) {
                $specials[] = $special;
              }
              echo '<hr><h4>Specials for this event</h4>';
              foreach ($specials as $s) {
                $query = 'SELECT * FROM drink WHERE id=' . $s[2] . ';';
                $result = $db->query($query);
                if ($result == TRUE) {
                  $row = $result->fetch_assoc();
                  echo 'Drink name: ' . $row['name'] . '<br>';
                  echo 'Drink descrption: ' . $row['description'] . '<br>';
                  echo 'Special price: $' . $row['price'] . '<br><br>';
                  echo '<form method=\'POST\' action=\'dashboard.php\'>';
                  echo '<input type=\'hidden\' name=\'did\' value=\'' . $row['id'] .
                          '\'>';
                  echo '<input type=\'hidden\' name=\'eid\' value=\'' . $s[1] . '\'>';
                  echo '<input type=\'submit\' value=\'Remove Special\' name=\'removeDrinkFromSpecial\'>';
                  echo '</form><br>';
                } else {
                  'Failed to retrieve this drink :(<br>';
                }
              }
            } else {
              echo 'Failed to get specials associated with this event :(<br>';
            }
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
              echo '<input type=\'hidden\' name=\'eid\' value=\'' . $sunID . '\'>';
              echo '<br><input type=\'submit\' value=\'Add Special\' name=\'addSpecial\'>';
              echo '</form>';
              echo '<hr><form method=\'POST\' action=\'dashboard.php\'>';
              echo '<input type=\'hidden\' name=\'day\' value=\'sunday\'>';
              echo '<input type=\'submit\' name=\'removeEventFromDay\' value =\'Remove Event\'>';
              echo '</form>';
            } else {
              echo 'Could not load drinks for the schedule :(<br>';
            }
          }
        ?>
      </td>
      <td><!-- Monday -->
        <?php
          if ($monID === NULL) {
            echo '<form method=\'POST\' action=\'dashboard.php\'>';
            echo '<select name=\'event\' required>';
            foreach ($events as $e) {
              echo '<option value=\'mo:' . $e['id'] . '\'>' . $e['name'] . '</option>';
            }
            echo '</select><br>';
            echo '<input type=\'submit\' value=\'Select Event\' name=\'selectEvent\'>';
            echo '</form>';
          } else {
            $query = 'SELECT * FROM event WHERE id=' . $monID . ';';
            $result = $db->query($query);
            $event = mysqli_fetch_row($result);
            echo 'Event: ' . $event[2] . '<br>';
            echo 'Description: ' . $event[3] . '<br>';
            $query = 'SELECT * FROM specialinfo where eventid=' . $monID . ';';
            $result = $db->query($query);
            if ($result == TRUE) {
              $specials = array();
              while ($special = mysqli_fetch_array($result)) {
                $specials[] = $special;
              }
              echo '<hr><h4>Specials for this event</h4>';
              foreach ($specials as $s) {
                $query = 'SELECT * FROM drink WHERE id=' . $s[2] . ';';
                $result = $db->query($query);
                if ($result == TRUE) {
                  $row = $result->fetch_assoc();
                  echo 'Drink name: ' . $row['name'] . '<br>';
                  echo 'Drink descrption: ' . $row['description'] . '<br>';
                  echo 'Special price: $' . $row['price'] . '<br><br>';
                  echo '<form method=\'POST\' action=\'dashboard.php\'>';
                  echo '<input type=\'hidden\' name=\'did\' value=\'' . $row['id'] .
                          '\'>';
                  echo '<input type=\'hidden\' name=\'eid\' value=\'' . $s[1] . '\'>';
                  echo '<input type=\'submit\' value=\'Remove Special\' name=\'removeDrinkFromSpecial\'>';
                  echo '</form><br>';
                } else {
                  'Failed to retrieve this drink :(<br>';
                }
              }
            } else {
              echo 'Failed to get specials associated with this event :(<br>';
            }
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
              echo '<input type=\'hidden\' name=\'eid\' value=\'' . $monID . '\'>';
              echo '<br><input type=\'submit\' value=\'Add Special\' name=\'addSpecial\'>';
              echo '</form>';
              echo '<hr><form method=\'POST\' action=\'dashboard.php\'>';
              echo '<input type=\'hidden\' name=\'day\' value=\'monday\'>';
              echo '<input type=\'submit\' name=\'removeEventFromDay\' value =\'Remove Event\'>';
              echo '</form>';
            } else {
              echo 'Could not load drinks for the schedule :(<br>';
            }
          }
        ?>
      </td>
      <td><!-- Tuesday -->
        <?php
          if ($tuesID === NULL) {
            echo '<form method=\'POST\' action=\'dashboard.php\'>';
            echo '<select name=\'event\' required>';
            foreach ($events as $e) {
              echo '<option value=\'tu:' . $e['id'] . '\'>' . $e['name'] . '</option>';
            }
            echo '</select><br>';
            echo '<input type=\'submit\' value=\'Select Event\' name=\'selectEvent\'>';
            echo '</form>';
          } else {
            $query = 'SELECT * FROM event WHERE id=' . $tuesID . ';';
            $result = $db->query($query);
            $event = mysqli_fetch_row($result);
            echo 'Event: ' . $event[2] . '<br>';
            echo 'Description: ' . $event[3] . '<br>';
            $query = 'SELECT * FROM specialinfo where eventid=' . $tuesID . ';';
            $result = $db->query($query);
            if ($result == TRUE) {
              $specials = array();
              while ($special = mysqli_fetch_array($result)) {
                $specials[] = $special;
              }
              echo '<hr><h4>Specials for this event</h4>';
              foreach ($specials as $s) {
                $query = 'SELECT * FROM drink WHERE id=' . $s[2] . ';';
                $result = $db->query($query);
                if ($result == TRUE) {
                  $row = $result->fetch_assoc();
                  echo 'Drink name: ' . $row['name'] . '<br>';
                  echo 'Drink descrption: ' . $row['description'] . '<br>';
                  echo 'Special price: $' . $row['price'] . '<br><br>';
                  echo '<form method=\'POST\' action=\'dashboard.php\'>';
                  echo '<input type=\'hidden\' name=\'did\' value=\'' . $row['id'] .
                          '\'>';
                  echo '<input type=\'hidden\' name=\'eid\' value=\'' . $s[1] . '\'>';
                  echo '<input type=\'submit\' value=\'Remove Special\' name=\'removeDrinkFromSpecial\'>';
                  echo '</form><br>';
                } else {
                  'Failed to retrieve this drink :(<br>';
                }
              }
            } else {
              echo 'Failed to get specials associated with this event :(<br>';
            }
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
              echo '<input type=\'hidden\' name=\'eid\' value=\'' . $tuesID . '\'>';
              echo '<br><input type=\'submit\' value=\'Add Special\' name=\'addSpecial\'>';
              echo '</form>';
              echo '<hr><form method=\'POST\' action=\'dashboard.php\'>';
              echo '<input type=\'hidden\' name=\'day\' value=\'tuesday\'>';
              echo '<input type=\'submit\' name=\'removeEventFromDay\' value =\'Remove Event\'>';
              echo '</form>';
            } else {
              echo 'Could not load drinks for the schedule :(<br>';
            }
          }
        ?>
      </td>
      <td><!-- Wednesday -->
        <?php
          if ($wedID === NULL) {
            echo '<form method=\'POST\' action=\'dashboard.php\'>';
            echo '<select name=\'event\' required>';
            foreach ($events as $e) {
              echo '<option value=\'we:' . $e['id'] . '\'>' . $e['name'] . '</option>';
            }
            echo '</select><br>';
            echo '<input type=\'submit\' value=\'Select Event\' name=\'selectEvent\'>';
            echo '</form>';
          } else {
            $query = 'SELECT * FROM event WHERE id=' . $wedID . ';';
            $result = $db->query($query);
            $event = mysqli_fetch_row($result);
            echo 'Event: ' . $event[2] . '<br>';
            echo 'Description: ' . $event[3] . '<br>';
            $query = 'SELECT * FROM specialinfo where eventid=' . $wedID . ';';
            $result = $db->query($query);
            if ($result == TRUE) {
              $specials = array();
              while ($special = mysqli_fetch_array($result)) {
                $specials[] = $special;
              }
              echo '<hr><h4>Specials for this event</h4>';
              foreach ($specials as $s) {
                $query = 'SELECT * FROM drink WHERE id=' . $s[2] . ';';
                $result = $db->query($query);
                if ($result == TRUE) {
                  $row = $result->fetch_assoc();
                  echo 'Drink name: ' . $row['name'] . '<br>';
                  echo 'Drink descrption: ' . $row['description'] . '<br>';
                  echo 'Special price: $' . $row['price'] . '<br><br>';
                  echo '<form method=\'POST\' action=\'dashboard.php\'>';
                  echo '<input type=\'hidden\' name=\'did\' value=\'' . $row['id'] .
                          '\'>';
                  echo '<input type=\'hidden\' name=\'eid\' value=\'' . $s[1] . '\'>';
                  echo '<input type=\'submit\' value=\'Remove Special\' name=\'removeDrinkFromSpecial\'>';
                  echo '</form><br>';
                } else {
                  'Failed to retrieve this drink :(<br>';
                }
              }
            } else {
              echo 'Failed to get specials associated with this event :(<br>';
            }
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
              echo '<input type=\'hidden\' name=\'eid\' value=\'' . $wedID . '\'>';
              echo '<br><input type=\'submit\' value=\'Add Special\' name=\'addSpecial\'>';
              echo '</form>';
              echo '<hr><form method=\'POST\' action=\'dashboard.php\'>';
              echo '<input type=\'hidden\' name=\'day\' value=\'wednesday\'>';
              echo '<input type=\'submit\' name=\'removeEventFromDay\' value =\'Remove Event\'>';
              echo '</form>';
            } else {
              echo 'Could not load drinks for the schedule :(<br>';
            }
          }
        ?>
      </td>
      <td><!-- Thursday -->
        <?php
          if ($thuID === NULL) {
            echo '<form method=\'POST\' action=\'dashboard.php\'>';
            echo '<select name=\'event\' required>';
            foreach ($events as $e) {
              echo '<option value=\'th:' . $e['id'] . '\'>' . $e['name'] . '</option>';
            }
            echo '</select><br>';
            echo '<input type=\'submit\' value=\'Select Event\' name=\'selectEvent\'>';
            echo '</form>';
          } else {
            $query = 'SELECT * FROM event WHERE id=' . $thuID . ';';
            $result = $db->query($query);
            $event = mysqli_fetch_row($result);
            echo 'Event: ' . $event[2] . '<br>';
            echo 'Description: ' . $event[3] . '<br>';
            $query = 'SELECT * FROM specialinfo where eventid=' . $thuID . ';';
            $result = $db->query($query);
            if ($result == TRUE) {
              $specials = array();
              while ($special = mysqli_fetch_array($result)) {
                $specials[] = $special;
              }
              echo '<hr><h4>Specials for this event</h4>';
              foreach ($specials as $s) {
                $query = 'SELECT * FROM drink WHERE id=' . $s[2] . ';';
                $result = $db->query($query);
                if ($result == TRUE) {
                  $row = $result->fetch_assoc();
                  echo 'Drink name: ' . $row['name'] . '<br>';
                  echo 'Drink descrption: ' . $row['description'] . '<br>';
                  echo 'Special price: $' . $row['price'] . '<br><br>';
                  echo '<form method=\'POST\' action=\'dashboard.php\'>';
                  echo '<input type=\'hidden\' name=\'did\' value=\'' . $row['id'] .
                          '\'>';
                  echo '<input type=\'hidden\' name=\'eid\' value=\'' . $s[1] . '\'>';
                  echo '<input type=\'submit\' value=\'Remove Special\' name=\'removeDrinkFromSpecial\'>';
                  echo '</form><br>';
                } else {
                  'Failed to retrieve this drink :(<br>';
                }
              }
            } else {
              echo 'Failed to get specials associated with this event :(<br>';
            }
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
              echo '<input type=\'hidden\' name=\'eid\' value=\'' . $thuID . '\'>';
              echo '<br><input type=\'submit\' value=\'Add Special\' name=\'addSpecial\'>';
              echo '</form>';
              echo '<hr><form method=\'POST\' action=\'dashboard.php\'>';
              echo '<input type=\'hidden\' name=\'day\' value=\'thursday\'>';
              echo '<input type=\'submit\' name=\'removeEventFromDay\' value =\'Remove Event\'>';
              echo '</form>';
            } else {
              echo 'Could not load drinks for the schedule :(<br>';
            }
          }
        ?>
      </td>
      <td><!-- Friday -->
        <?php
          if ($friID === NULL) {
            echo '<form method=\'POST\' action=\'dashboard.php\'>';
            echo '<select name=\'event\' required>';
            foreach ($events as $e) {
              echo '<option value=\'fr:' . $e['id'] . '\'>' . $e['name'] . '</option>';
            }
            echo '</select><br>';
            echo '<input type=\'submit\' value=\'Select Event\' name=\'selectEvent\'>';
            echo '</form>';
          } else {
            $query = 'SELECT * FROM event WHERE id=' . $friID . ';';
            $result = $db->query($query);
            $event = mysqli_fetch_row($result);
            echo 'Event: ' . $event[2] . '<br>';
            echo 'Description: ' . $event[3] . '<br>';
            $query = 'SELECT * FROM specialinfo where eventid=' . $friID . ';';
            $result = $db->query($query);
            if ($result == TRUE) {
              $specials = array();
              while ($special = mysqli_fetch_array($result)) {
                $specials[] = $special;
              }
              echo '<hr><h4>Specials for this event</h4>';
              foreach ($specials as $s) {
                $query = 'SELECT * FROM drink WHERE id=' . $s[2] . ';';
                $result = $db->query($query);
                if ($result == TRUE) {
                  $row = $result->fetch_assoc();
                  echo 'Drink name: ' . $row['name'] . '<br>';
                  echo 'Drink descrption: ' . $row['description'] . '<br>';
                  echo 'Special price: $' . $row['price'] . '<br><br>';
                  echo '<form method=\'POST\' action=\'dashboard.php\'>';
                  echo '<input type=\'hidden\' name=\'did\' value=\'' . $row['id'] .
                          '\'>';
                  echo '<input type=\'hidden\' name=\'eid\' value=\'' . $s[1] . '\'>';
                  echo '<input type=\'submit\' value=\'Remove Special\' name=\'removeDrinkFromSpecial\'>';
                  echo '</form><br>';
                } else {
                  'Failed to retrieve this drink :(<br>';
                }
              }
            } else {
              echo 'Failed to get specials associated with this event :(<br>';
            }
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
              echo '<input type=\'hidden\' name=\'eid\' value=\'' . $friID . '\'>';
              echo '<br><input type=\'submit\' value=\'Add Special\' name=\'addSpecial\'>';
              echo '</form>';
              echo '<hr><form method=\'POST\' action=\'dashboard.php\'>';
              echo '<input type=\'hidden\' name=\'day\' value=\'friday\'>';
              echo '<input type=\'submit\' name=\'removeEventFromDay\' value =\'Remove Event\'>';
              echo '</form>';
            } else {
              echo 'Could not load drinks for the schedule :(<br>';
            }
          }
        ?>
      </td>
      <td><!-- Saturday -->
        <?php
          if ($satID === NULL) {
            echo '<form method=\'POST\' action=\'dashboard.php\'>';
            echo '<select name=\'event\' required>';
            foreach ($events as $e) {
              echo '<option value=\'sa:' . $e['id'] . '\'>' . $e['name'] . '</option>';
            }
            echo '</select><br>';
            echo '<input type=\'submit\' value=\'Select Event\' name=\'selectEvent\'>';
            echo '</form>';
          } else {
            $query = 'SELECT * FROM event WHERE id=' . $satID . ';';
            $result = $db->query($query);
            $event = mysqli_fetch_row($result);
            echo 'Event: ' . $event[2] . '<br>';
            echo 'Description: ' . $event[3] . '<br>';
            $query = 'SELECT * FROM specialinfo where eventid=' . $satID . ';';
            $result = $db->query($query);
            if ($result == TRUE) {
              $specials = array();
              while ($special = mysqli_fetch_array($result)) {
                $specials[] = $special;
              }
              echo '<hr><h4>Specials for this event</h4>';
              foreach ($specials as $s) {
                $query = 'SELECT * FROM drink WHERE id=' . $s[2] . ';';
                $result = $db->query($query);
                if ($result == TRUE) {
                  $row = $result->fetch_assoc();
                  echo 'Drink name: ' . $row['name'] . '<br>';
                  echo 'Drink descrption: ' . $row['description'] . '<br>';
                  echo 'Special price: $' . $row['price'] . '<br>';
                  echo '<form method=\'POST\' action=\'dashboard.php\'>';
                  echo '<input type=\'hidden\' name=\'did\' value=\'' . $row['id'] .
                          '\'>';
                  echo '<input type=\'hidden\' name=\'eid\' value=\'' . $s[1] . '\'>';
                  echo '<input type=\'submit\' value=\'Remove Special\' name=\'removeDrinkFromSpecial\'>';
                  echo '</form><br>';
                } else {
                  'Failed to retrieve this drink :(<br>';
                }
              }
            } else {
              echo 'Failed to get specials associated with this event :(<br>';
            }
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
              echo '<input type=\'hidden\' name=\'eid\' value=\'' . $satID . '\'>';
              echo '<br><input type=\'submit\' value=\'Add Special\' name=\'addSpecial\'>';
              echo '</form>';
              echo '<hr><form method=\'POST\' action=\'dashboard.php\'>';
              echo '<input type=\'hidden\' name=\'day\' value=\'saturday\'>';
              echo '<input type=\'submit\' name=\'removeEventFromDay\' value =\'Remove Event\'>';
              echo '</form>';
            } else {
              echo 'Could not load drinks for the schedule :(<br>';
            }
          }
        ?>
      </td>
  </table>
</html>
