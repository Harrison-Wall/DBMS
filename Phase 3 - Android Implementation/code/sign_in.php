<?php
    include('connect.php');

    if( $_POST )
    {
        // Input Vars
        $email = $_POST['Email'];
        $email = mysqli_real_escape_string($myconnection, $email);
    
        $password = $_POST['Pass'];
        $password = mysqli_real_escape_string($myconnection, $password);

        $type = $_POST['type'];
        $type = mysqli_real_escape_string($myconnection, $type);

        $query = "Select * FROM users WHERE email = '$email' AND password = '$password'";  
        $result = mysqli_query($myconnection, $query) or die ('Query failed: ' . mysqli_error($myconnection));

        if ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC)) 
        {
            $userID = $row['id'];

            //Check if UserID is parent or student
            $checkParent  = "SELECT * FROM parents WHERE parent_id = '$userID'";
            $checkStudent = "SELECT * FROM students WHERE student_id = '$userID'";

            $parentResult  = mysqli_query($myconnection, $checkParent) or die ('Query failed: '. mysqli_error($myconnection));
            $studentResult = mysqli_query($myconnection, $checkStudent) or die ('Query failed: '. mysqli_error($myconnection));

            if( mysqli_num_rows($studentResult) && $type == "1")
            {
               // Is a student
               echo $userID;
            }
            else if( mysqli_num_rows($parentResult) && $type == "0")
            {
                // Is a parent
                echo $userID;
            }
            else
            {
                echo "";
            }

            mysqli_free_result($studentResult);
            mysqli_free_result($parentResult);
        }
        else
        {
            echo "";
        }

        mysqli_free_result($result);
    }    

    mysqli_close($myconnection);
?>