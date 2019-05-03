<html>
    <header>
        <title> Class Participation </title>
    </header>

    <body>
        <nav>
            <a href="studentPage.php"> Return </a>
        </nav>

        <h2>Confirm session participation: </h2>
        <form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?> method="post">

            Session Name <input type="text" name="sesName"/>
            <input type="submit" name="Participate"/>

        </form>
    </body>
</html>

<?php 
    if( isset($_POST['Participate']) )
    {
        include('connect.php');
        session_start();
        $userID = $_SESSION['uID'];

        // Get input
        $sessionName = $_POST['sesName'];
        $sessionName = mysqli_real_escape_string($myconnection, $sessionName);

        if( empty($sessionName) ) // User didn;t eneter any input
        {
            echo "<h4>Enter a session to particpate in.</h4>";
        }
        else
        {
            // Get session info
            $getSession = "SELECT * FROM sessions WHERE ses_name = '$sessionName'";
            $sessionResults = mysqli_query($myconnection, $getSession) or die('Query failed: ' . mysqli_error($myconnection));

            if( mysqli_num_rows($sessionResults) > 0 ) // Found a matching session
            {
                $sessionRow = mysqli_fetch_array($sessionResults, MYSQLI_ASSOC);
                $secID = $sessionRow['sec_id'];
                $sesID = $sessionRow['ses_id'];
                $isEnrolled = false;

                // Check user is enrolled/teaching that section
                $getSectionInfo = 
                "SELECT sec_id FROM enroll WHERE mentee_id = '$userID' 
                 UNION SELECT sec_id FROM teach WHERE mentor_id = '$userID'";
                $sectionrResults = mysqli_query($myconnection, $getSectionInfo) or die ('Query failed: ' . mysqli_error($myconnection));
                while( $sectionRows = mysqli_fetch_array($sectionrResults, MYSQLI_ASSOC) )
                {
                    if( $secID === $sectionRows['sec_id'] ) // They are enrolled in the section
                    {
                        $isEnrolled = true;
                        break;
                    }
                }

                if( $isEnrolled )
                {
                    // add to particpate
                    $insertParticpate = 
                    "INSERT INTO participate(student_id, sec_id, ses_id, participate) 
                    VALUES ('$userID', '$secID', '$sesID', 1)";
                    mysqli_query($myconnection, $insertParticpate) or die ('Query failed: ' . mysqli_error($myconnection));
                }
                else
                {
                    echo "<h4>You are not part of that session's section.</h4>";
                }

                mysqli_free_result($sectionrResults);   
            }
            else
            {
                echo "<h4>Could not find a session with that name</h4>";
            }

            mysqli_free_result($sessionResults);
        }

        mysqli_close($myconnection);
    }
?>