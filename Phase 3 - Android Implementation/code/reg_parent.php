<?php
  include('connect.php');

  if( $_POST )
  {
    // Input 
    $email =  mysqli_real_escape_string($myconnection, $_POST['Email'] );
    $name =   mysqli_real_escape_string($myconnection, $_POST['Name']);
    $pass =   mysqli_real_escape_string($myconnection, $_POST['Pass']);
    $phone =  mysqli_real_escape_string($myconnection, $_POST['Phone']);
    $city =   mysqli_real_escape_string($myconnection, $_POST['City']);
    $state =  mysqli_real_escape_string($myconnection, $_POST['State']);
    $mod =    mysqli_real_escape_string($myconnection, $_POST['Mod']);

    $insertUser = "INSERT INTO users (email, password, name";
    $insertValues = " VALUES ('$email', '$pass', '$name'";

    // Check optional fields
    if( !empty($phone) )
    {
      $insertUser .= ", phone";
      $insertValues .= ", '$phone'";
    }

    if( !empty($city) )
    {
      $insertUser .= ", city";
      $insertValues .= ", '$city'";
    }

    if( !empty($state) )
    {
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
    if( $mod == "true" )
    {
      $insertMod = "INSERT INTO moderators(moderator_id) VALUES ('$userID')";
      mysqli_query($myconnection, $insertMod) or die ('Query failed: '. mysqli_error($myconnection));
    }

    echo $userID;
  }

  mysqli_close($myconnection);
?>