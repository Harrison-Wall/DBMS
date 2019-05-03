<?php
    if( $_POST )
    {
        include('connect.php');

        $userID    = $_POST['ID'];
        $sectionID = $_POST['secID'];

        // Add user as a moderator
        $insertUser = "INSERT INTO moderate (sec_id, moderator_id) VALUES ('$sectionID', '$userID')";
        mysqli_query($myconnection, $insertUser) or die('Query failed: ' . mysqli_error($myconnection));

        echo " ";  // Respond with nothing so we know there was no error
        mysqli_close($myconnection);
    }
?>