<?php 
    if( $_POST )
    {
        include('connect.php');

        $userId = $_POST['ID'];
        $type = $_POST['Type'];

        $response = "";

        // Get user name
        $getName = "SELECT name FROM users WHERE id = '$userId'";
        $nameRes = mysqli_query($myconnection, $getName) or die ('Query Failed: ' . mysqli_error($myconnection));
        $nameRow = mysqli_fetch_array($nameRes, MYSQLI_ASSOC);
        $name = $nameRow['name'];

        if( $type == "0" ) // Parent
        {
            // Get moderator status
            $getIsMod = "SELECT * FROM moderators WHERE moderator_id = '$userId'";
            $isModRes = mysqli_query($myconnection, $getIsMod) or die('Query failed: ' . mysqli_error($myconnection));

            if( mysqli_num_rows($isModRes) <= 0 ) // Not a moderator
            {
                $response = $name . '&&' . 'Parent' . '&&';
            }
            else // Is a moderator
            {
                $response = $name . '&&' . 'Moderator' . '&&';
            }

            mysqli_free_result($isModRes);

        }
        else if($type == "1" ) // Student
        {
            $grade = $mentee = $mentor = " ";

            // Get grade level
            $getGrade = "SELECT grade FROM students WHERE student_id = '$userId'";
            $gradeRes = mysqli_query($myconnection, $getGrade) or die ('Query Failed: ' . mysqli_error($myconnection));
            $gradeRow = mysqli_fetch_array($gradeRes, MYSQLI_ASSOC);
            $grade = $gradeRow['grade'];

            // Get mentee status
            $getIsMent = "SELECT * FROM mentees WHERE mentee_id = '$userId'";
            $isMentRes = mysqli_query($myconnection, $getIsMent) or die ('Query Failed: ' . mysqli_error($myconnection));
            if( mysqli_num_rows($isMentRes) > 0 ) // Is a mentee
            {
                $mentee = "Mentee";
            }

            // Get mentor status
            $getIsMtor = "SELECT * FROM mentors WHERE mentor_id = '$userId'";
            $isMtorRes = mysqli_query($myconnection, $getIsMtor) or die ('Query Failed: ' . mysqli_error($myconnection));
            if( mysqli_num_rows($isMtorRes) > 0 ) // Is a mentee
            {
                $mentor = "Mentor";
            }

            $response = $name . '&&' . $grade . '&&' . $mentee . '&&' . $mentor . '&&';

            // Free results
            mysqli_free_result($gradeRes);
            mysqli_free_result($isMentRes);
            mysqli_free_result($isMtorRes);
        }
        else
        {
            $response = "SOME&&ERROR&&OCCURED&&DUDE";
        }

        echo $response;

        mysqli_free_result($nameRes);
        mysqli_close($myconnection);
    }
?>