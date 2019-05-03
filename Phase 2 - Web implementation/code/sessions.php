<html>
    <header>
        <title> Session Information </title>
    </header>

    <body>
        <nav>
            <a href="parentPage.php"> Return </a>
        </nav>

        <h2>Add mentor to a session: </h2>
        <form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?> method="post">

            Session Name <input type="text" name="sesName"/>
            Mentor Name <input type="text" name="mentName"/>
            <input type="submit" name="submit" value="Add mentor"/>

        </form>
    </body>
</html>

<?php 
    // Print out a list of sessions of sections they are moderating with < 2 mentors
    function printSessions($userID)
    {
        include('connect.php');

        $getSessions = 
            "SELECT ses_name, ses_id, sec_id, date FROM sessions WHERE sec_id IN 
            (SELECT sec_ID FROM moderate WHERE moderator_id = '$userID')";
        $sessionRes = mysqli_query($myconnection, $getSessions) or die ('Query failed: ' . mysqli_error($myconnection));

        if( mysqli_num_rows($sessionRes) > 0 )
        {
            //print session info
            while( $sessionRow = mysqli_fetch_array($sessionRes, MYSQLI_ASSOC) )
            {
                echo $sessionRow['ses_name'] . " - " . $sessionRow['date'] . "</br>";

                // Count each sessions participating mentors
                $sesID = $sessionRow['ses_id'];
                $secID = $sessionRow['sec_id'];

                $getMtor = 
                    "SELECT COUNT(student_id)
                     FROM participate 
                     WHERE participate = 1
                     AND ses_id = '$sesID'
                     AND student_id IN ( SELECT mentor_id FROM teach WHERE sec_id = '$secID')";

                $mtorResults = mysqli_query($myconnection, $getMtor) or die ('Query failed: ' . mysqli_error($myconnection));
                $mtroRow = mysqli_fetch_array($mtorResults, MYSQLI_ASSOC);

                // if < 2 print any potential mentors
                if( mysqli_num_rows($mtorResults) < 1 || $mtroRow['COUNT(student_id)'] < 2 )
                {
                    // get potential mentors that are not participating
                    $getPotential = 
                        "SELECT id, name, email
                         FROM users 
                         WHERE id IN (SELECT mentor_ID 
                            FROM teach
                            WHERE sec_id = '$secID'
                            AND mentor_ID NOT IN (SELECT student_id
                                    FROM participate
                                    WHERE participate = 1
                                    AND ses_id = '$sesID'))";
                    $potentialRes = mysqli_query($myconnection, $getPotential) or die ('Query failed: ' . mysqli_error($myconnection));

                    if( mysqli_num_rows($potentialRes) < 1 )
                    {
                        echo "No potential mentors found </br>";
                    }
                    else
                    {
                        echo "</br>&nbsp Potential Mentors:";

                        // list those mentors
                        while($potentialRow = mysqli_fetch_array($potentialRes, MYSQLI_ASSOC))
                        {
                            echo "</br>&nbsp&nbsp&nbsp&nbsp". $potentialRow['name'];
                        }
                    }

                    mysqli_free_result($potentialRes);
                }
                else
                {
                    echo "This session has enough mentors</br>";
                }

                echo "</br></br>";

                mysqli_free_result($mtorResults);
            }
        }
        else
        {
            echo "<h4>No sessions which you moderate were found.<h4>";
        }

        mysqli_free_result($sessionRes);

        mysqli_close($myconnection);
    }   

    session_start();
    $userID = $_SESSION['uID'];

    // handle form submit
    if( isset($_POST['submit']) )
    {
        include('connect.php');

        // Get input
        $sessionName = $_POST['sesName'];
        $sessionName = mysqli_real_escape_string($myconnection, $sessionName);

        $mentorName = $_POST['mentName'];
        $mentorName = mysqli_real_escape_string($myconnection, $mentorName);

        if( empty($sessionName) || empty($mentorName) )
        {
            echo "<h4>Please fill out all fields.</h4>";
        }
        else
        {
            // Check user is a mod of that section
            $checkSec = 
                "SELECT moderator_id, sec_id FROM moderate WHERE moderator_id = '$userID' 
                 AND sec_id IN (SELECT sec_id FROM sessions WHERE ses_name = '$sessionName')";
            $secResult = mysqli_query($myconnection, $checkSec) or die ('Query failed: ' . mysqli_error($myconnection));
            if( mysqli_num_rows($secResult) < 1 )
            {
                echo "<h4>Error with session: Session does not exist or you are not a moderator of the section.</h4>";
            }
            else
            {
                $secRow = mysqli_fetch_array($secResult, MYSQLI_ASSOC);
                $secID = $secRow['sec_id'];

                // Get session id
                $getSes = "SELECT ses_id FROM sessions WHERE ses_name = '$sessionName'";
                $sessRes = mysqli_query($myconnection, $getSes) or die ('Query failed: ' . mysqli_error($myconnection));
                $sessRow = mysqli_fetch_array($sessRes, MYSQLI_ASSOC);
                $sesID = $sessRow['ses_id'];

                // check there are < 3 mentors
                $getMtor = 
                    "SELECT COUNT(student_id)
                     FROM participate 
                     WHERE participate = 1
                     AND ses_id = '$sesID'
                     AND student_id IN ( SELECT mentor_id FROM teach WHERE sec_id = '$secID')";
                $mtorResults = mysqli_query($myconnection, $getMtor);
                $mtorRow = mysqli_fetch_array($mtorResults, MYSQLI_ASSOC);
                if( $mtorRow['COUNT(student_id)'] < 3 )
                {
                     // check mentor can be added
                    $getMentor = 
                        "SELECT * FROM teach WHERE sec_id='$secID' 
                        AND mentor_id IN (SELECT id FROM users WHERE name = '$mentorName')";
                    $mentorRes = mysqli_query($myconnection, $getMentor) or die ('Query failed: ' . mysqli_error($myconnection));

                    if( mysqli_num_rows($mentorRes) < 1 )
                    {
                        echo "<h4>Error adding mentor: Either mentor does not exist or is not enrolled in section.</h4>";
                    }
                    else
                    {   
                        $mentorRow = mysqli_fetch_array($mentorRes, MYSQLI_ASSOC);
                        $studId = $mentorRow['mentor_id'];
                        // Add mentor to that session
                        $insertPart = 
                            "INSERT INTO participate(student_id, sec_id, ses_id, participate)
                            VALUES ('$studId', '$secID', '$sesID', 1)";
                        mysqli_query($myconnection, $insertPart) or die('Query failed: ' . mysqli_error($myconnection));

                        echo "<h4>Mentor added</h4>";

                        printSessions();
                    }
                    mysqli_free_result($mentorRes);
                }
                else
                {
                    echo "<h4>This session already has enough mentors<h4>";
                }

                mysqli_free_result($mtorResults);
            }
            mysqli_free_result($secResult);
        }
        mysqli_close($myconnection);
    }

    printSessions($userID); 
?>