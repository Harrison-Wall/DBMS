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
        $parent = mysqli_real_escape_string($myconnection, $_POST['ParentEmail']);
        $mentee = mysqli_real_escape_string($myconnection, $_POST['Mentee']);
        $mentor = mysqli_real_escape_string($myconnection, $_POST['Mentor']);
        $grade =  mysqli_real_escape_string($myconnection, $_POST['Grade']);
    
        // Check that the parent is registered
        $getParentID = "SELECT * FROM users WHERE email = '$parent'";
        $pidResult = mysqli_query($myconnection, $getParentID) or die ('Query failed: '. mysqli_error($myconnection));

        if( !mysqli_num_rows($pidResult) )
        {
            echo "";
        }
        else
        {
            $parentRow = mysqli_fetch_array($pidResult, MYSQLI_ASSOC);
            $parentID = $parentRow['id'];

            switch($grade)
            {
                case "Freshman":
                    $grade = 1;
                    break;
                case "Sophomore":
                    $grade = 2;
                    break;
                case "Junior":
                    $grade = 3;
                    break;
                case "Senior":
                    $grade = 4;
                    break;
            }

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

            // Insert into user
            mysqli_query($myconnection, $insertUser) or die ('Query failed: '. mysqli_error($myconnection));
            
            // Get the ID of the inserted row
            $getUserID = "SELECT * FROM users WHERE email = '$email'";
            $idResult = mysqli_query($myconnection, $getUserID) or die ('Query failed: '. mysqli_error($myconnection));
            $row = mysqli_fetch_array($idResult, MYSQLI_ASSOC);
            $userID = $row['id'];

            // insert into student
            $insertStudent = "INSERT INTO students(student_id, grade) VALUES ('$userID', '$grade')";
            mysqli_query($myconnection, $insertStudent) or die ('Query failed: '. mysqli_error($myconnection));

            // insert into parenting
            $insertParenting = "INSERT INTO parenting(parent_id, student_id) VALUES ('$parentID', '$userID')";
            mysqli_query($myconnection, $insertParenting) or die ('Query failed: '. mysqli_error($myconnection));

            // insert into mentee and or mentor
            if( $mentor == "true" )
            {
                $insertMentor = "INSERT INTO mentors(mentor_id) VALUES ('$userID')";
                mysqli_query($myconnection, $insertMentor) or die ('Query failed: '. mysqli_error($myconnection));
            }

            if( $mentee == "true")
            {
                $insertMentee = "INSERT INTO mentees(mentee_id) VALUES ('$userID')";
                mysqli_query($myconnection, $insertMentee) or die ('Query failed: '. mysqli_error($myconnection));
            }

            // Return the new user ID
            echo $userID;

            mysqli_free_result($idResult);
        }
        mysqli_free_result($pidResult);
    }

    mysqli_close($myconnection);
?>