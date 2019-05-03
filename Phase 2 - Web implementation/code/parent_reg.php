<html>
    <head>
        <title>Parent Registration</title>
    </head>

    <body>
        <h3>* Required Fields</h3>
        <form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?> method="post">
        
            Email: * </br> <input type="text" name="reg_email"/></br>    

            Password: * </br> <input type="password" name="reg_password"/></br>

            Name: *</br>  <input type="text" name="reg_name"/></br>

            Phone: </br> <input type="tel" name="reg_phone"/></br>
            City: </br> <input type="text" name="reg_city"/></br>
            State: </br> <input type="text" name="reg_state"/></br>

            Moderator: * 
            </br> <input type="radio" name="reg_type" value="Yes" checked/> Yes 
            </br> <input type="radio" name="reg_type" value="No"/> No</br>

            <input type="Submit" name="submit" Value="Submit Registration"/>
        </form>
    </body>
</html>

<?php
  include('connect.php');

  if( isset($_POST['submit']) )
  {
    // Input and Error vars
    $email = $name = $pass = $phone = $city = $state = $type = " ";
    $isErr = false;

    // Required Fields need to be filled out    
    if( $_SERVER["REQUEST_METHOD"] == "POST" )
    {
      if (empty($_POST["reg_email"]))
      {
        $isErr = true;
      }
      else
      {
        $email = $_POST["reg_email"];
        $email = mysqli_real_escape_string($myconnection, $email);
      }

      if (empty($_POST["reg_password"]))
      {
        $isErr = true;
      }
      else
      {
        $pass = $_POST["reg_password"];
        $pass = mysqli_real_escape_string($myconnection, $pass);
      }
      
      if (empty($_POST["reg_name"]))
      {
          $isErr = true;
      }
      else
      {
        $name = $_POST["reg_name"];
        $name = mysqli_real_escape_string($myconnection, $name);
      }
    }

    if( $isErr )
    {
      echo '<p> Enter all required fields </p>';
    }
    else
    {
      $type = $_POST["reg_type"];
      $insertUser = "INSERT INTO users (email, password, name";
      $insertValues = " VALUES ('$email', '$pass', '$name'";

      // Check optional fields
      if( !empty($_POST["reg_phone"]) )
      {
        $phone = $_POST["reg_phone"];
        $phone = mysqli_real_escape_string($myconnection, $phone);

        $insertUser .= ", phone";
        $insertValues .= ", '$phone'";
      }

      if( !empty($_POST["reg_city"]) )
      {
        $city = $_POST["reg_city"];
        $city = mysqli_real_escape_string($myconnection, $city);

        $insertUser .= ", city";
        $insertValues .= ", '$city'";
      }

      if( !empty($_POST["reg_state"]) )
      {
        $state = $_POST["reg_state"];
        $state = mysqli_real_escape_string($myconnection, $state);

        $insertUser .= ", state";
        $insertValues .= ", '$state'";
      }

      $insertUser .= ")";
      $insertValues .= ")";

      $insertUser .= $insertValues;

      // Build Insert to User
      mysqli_query($myconnection, $insertUser) or die ('Query failed: '. mysqli_error($myconnection));
            
      // Get the ID of the inserted row
      $getUserID = "SELECT * FROM users WHERE email = '$email'";
      $idResult = mysqli_query($myconnection, $getUserID) or die ('Query failed: '. mysqli_error($myconnection));
      $row = mysqli_fetch_array($idResult, MYSQLI_ASSOC);
      $userID = $row['id'];      

      // Insert into Parent
      $insertParent = "INSERT INTO parents(parent_id) VALUES ('$userID')";
      mysqli_query($myconnection, $insertParent) or die ('Query failed: '. mysqli_erro($myconnection));

      // Insert into moderator
      if( $type == "Yes" )
      {
        $insertMod = "INSERT INTO moderators(moderator_id) VALUES ('$userID')";
        mysqli_query($myconnection, $insertMod) or die ('Query failed: '. mysqli_error($myconnection));
      }

      // Make a session
      session_destroy();
      session_start();
            
      $_SESSION['uID'] = $userID;
      $_SESSION['type'] = "Parent";
      
      // go to account page
      header('Location: parentPage.php');

    }
  }

  mysqli_close($myconnection);
?>