<html>
    <header>
        <title> Student Mentoring </title>
    </header>

    <body>
        <nav>
            <a href="studentPage.php"> Return </a>
        </nav>

        <h4>Teach a new section as a mentor?</h4>
        <form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?> method="post">

            Section Name <input type="text" name="sectName"/>
            <input type="submit" name="enroll"/>

        </form>
    </body>
</html>

<?php 
    function showMent($numSect, $teachRes) 
    {
        // show which sections they are currently mentoring in
        echo "<h4>Currently Mentoring in:  </h4>";
        
        if( $numSect === 0 )
        {
            echo "<p>You are not currently mentoring</p>";
        }
        else
        {
            include('connect.php');

            // Loop through results, print section info
            while( $teachRows = mysqli_fetch_array($teachRes, MYSQLI_ASSOC) ) // for each section_id
            {
                $sectID = $teachRows['sec_id'];

                // Get matching section
                $getSects = "SELECT sec_name FROM sections WHERE sec_id = '$sectID'";
                $sectRes = mysqli_query($myconnection, $getSects) or die ('Query failed: '. mysqli_error($myconnection));
                $sectRows = mysqli_fetch_array($sectRes, MYSQLI_ASSOC);

                // print section info
                echo $sectRows['sec_name'];
                echo "</br>";

                //Get mentor info
                $getMent = "SELECT name FROM users WHERE id IN (SELECT mentor_id FROM teach WHERE sec_id = '$sectID')";
                $mentRes = mysqli_query($myconnection, $getMent) or die ('Query failed: '. mysqli_error($myconnection));
                
                //print mentors
                echo "&nbsp&nbspMentors: </br>";
                while( $mentRows = mysqli_fetch_array($mentRes, MYSQLI_ASSOC) )
                {
                    echo "&nbsp&nbsp&nbsp&nbsp";
                    echo $mentRows['name'];
                    echo "</br>";
                }
                echo "</br>";

                // Get mentee info
                $getMee = "SELECT name FROM users WHERE id IN (SELECT mentee_id FROM enroll WHERE sec_id = '$sectID')";
                $meeRes = mysqli_query($myconnection, $getMee) or die ('Query failed: '. mysqli_error($myconnection));
                
                //print mentors
                echo "&nbsp&nbspMentees: </br>";
                while( $meeRows = mysqli_fetch_array($meeRes, MYSQLI_ASSOC) )
                {
                    echo "&nbsp&nbsp&nbsp&nbsp";
                    echo $meeRows['name'];
                    echo "</br>";
                }
                echo "</br>";

                //free results
                mysqli_free_result($meeRes);
                mysqli_free_result($mentRes);
                mysqli_free_result($sectRes);
            }

            //close connection
            mysqli_close($myconnection);
        }

    }// End showMent()

    include('connect.php');

    // Get session info
    session_start();
    $userID = $_SESSION['uID'];

    // handle submit
    if( isset($_POST['enroll']) )
    {  
        // Get section name
        $sectionName = $_POST['sectName'];
        $sectionName = mysqli_real_escape_string($myconnection, $sectionName);

        if( empty($sectionName) )
        {
            return;
        }

        // Check if they are a mentor
        $getMent = "SELECT * FROM mentors WHERE mentor_id = '$userID'";
        $mentRes = mysqli_query($myconnection, $getMent) or die ('Query failed: '. mysqli_error($myconnection));

        if( mysqli_num_rows($mentRes) > 0 )
        {
            $getSect = "SELECT * FROM sections WHERE sec_name = '$sectionName'";
            $sectRes = mysqli_query($myconnection, $getSect) or die ('Query failed: '. mysqli_error($myconnection));
            $sectRows = mysqli_fetch_array($sectRes, MYSQLI_ASSOC);

            // check if section date works
            $sectDate = $sectRows['end_date'];
            $currDate = date( 'Y-m-d' );

            if( $currDate > $sectDate )
            {
                echo "<h4>Section has already ended</h4>";
                mysqli_free_result($sectRes);
            }
            else
            {
                // check if section has space for a mentor
                $sectID = $sectRows['sec_id'];
                $getNumMent = "SELECT * FROM teach WHERE sec_id = '$sectID'";
                $numResult = mysqli_query($myconnection, $getNumMent) or die ('Query failed: '. mysqli_error($myconnection));

                if( mysqli_num_rows($numResult) >= 3 )
                {
                    echo "<h4>This section already has 3 mentors</h4>";
                    mysqli_free_result($numResult);
                    mysqli_free_result($sectRes);
                }
                else
                {
                    // check if grade is met
                    $courseID = $sectRows['c_id'];
                    $getCourseGrade = "SELECT * FROM courses WHERE c_id = '$courseID'";
                    $courseGradeRes = mysqli_query($myconnection, $getCourseGrade) or die ('Query failed: '. mysqli_error($myconnection));
                    $courseGradeRow = mysqli_fetch_array($courseGradeRes, MYSQLI_ASSOC);
                    $courseGrade = $courseGradeRow['mentor_grade_req'];

                    $getStuGrade = "SELECT * FROM students WHERE student_id = '$userID'";
                    $stuGradeRes = mysqli_query($myconnection, $getStuGrade) or die ('Query failed: '. mysqli_error($myconnection));
                    $stuGradeRow = mysqli_fetch_array($stuGradeRes, MYSQLI_ASSOC);
                    $stuGrade = $stuGradeRow['grade'];

                    if( $courseGrade > $stuGrade )
                    {
                        echo "<h4>Grade Requirement Not Met</h4>";
                        mysqli_free_result($numResult);
                        mysqli_free_result($sectRes);
                        mysqli_free_result($courseGradeRes);
                        mysqli_free_result($stuGradeRes);
                    }
                    else
                    {
                        // check they already enrolled in another section of the same course
                        $checkEnroll = 
                        "SELECT c_id FROM sections WHERE sec_id = '$sectID' AND c_id IN 
                        (SELECT c_id FROM sections WHERE sec_id IN 
                        (SELECT sec_id FROM teach WHERE mentor_id = '$userID'))";
                        $enrollResults = mysqli_query($myconnection, $checkEnroll) or die ('Query failed: ' . mysqli_error($myconnection));

                        if( mysqli_num_rows($enrollResults) > 0 ) //Already enrolled in this course
                        {
                            echo "<h4>You are already mentoring a section in this course</h4>";
                            mysqli_free_result($numResult);
                            mysqli_free_result($sectRes);
                            mysqli_free_result($courseGradeRes);
                            mysqli_free_result($stuGradeRes);
                            mysqli_free_result($enrollResults);

                        }
                        else
                        {
                            // Check if timeline works
                            $sectTime = $sectRows['time_slot_id'];
                            $getSectTime = "SELECT * FROM time_slot WHERE time_slot_id = '$sectTime'";
                            $sectTimeRes = mysqli_query($myconnection, $getSectTime) or die ('Query failed: ' . mysqli_error($myconnection));
                            $sectTimeRow = mysqli_fetch_array($sectTimeRes, MYSQLI_ASSOC);

                            // Get time_slot info of what the suer already teaches
                            $getTeachTime = 
                            "SELECT * FROM time_slot WHERE time_slot_id IN 
                            (SELECT time_slot_id FROM sections WHERE sec_id IN 
                            (SELECT sec_id FROM teach WHERE mentor_id = '$userID'))";
                            $teachTimeRes = mysqli_query($myconnection, $getTeachTime) or die ('Query failed: ' . mysqli_error($myconnection));
                            
                            $isConflict = false;

                            while( $teachTimeRows = mysqli_fetch_array($teachTimeRes, MYSQLI_ASSOC) ) // Check against every time user is teaching
                            {
                                // Check day of the week
                                if( $sectTimeRow['day_of_the_week'] == $teachTimeRows['day_of_the_week'] )
                                {
                                    //check start and end times
                                    if( ($sectRows['start_time'] < $teachTimeRows['start_time'] && $sectRows['end_time'] > $teachRows['start_time']) 
                                        || ($sectRows['start_time'] > $teachTimeRows['start_time'] && $sectRows['start_time'] < $teachRows['end_time']) )
                                    {
                                        $isConflict = true;
                                        break;
                                    }      
                                }
                            }

                            // No conflit, finally can enroll as mentor
                            if( !$isConflict )
                            {
                                $insertMent = "INSERT INTO teach(sec_id, mentor_id) VALUES('$sectID', '$userID')";
                                mysqli_query($myconnection, $insertMent) or die ('Query failed: ' . mysqli_error($myconnection));
                            }

                            // free results
                            mysqli_free_result($numResult);
                            mysqli_free_result($sectRes);
                            mysqli_free_result($courseGradeRes);
                            mysqli_free_result($stuGradeRes);
                            mysqli_free_result($enrollResults);
                            mysqli_free_result($sectTimeRes);
                            mysqli_free_result($teachTimeRes);

                            // redirect to same page to refresh
                            header("Location: mentoring.php");
                        }
                    }
                }   
            }
        }
        else
        {
            echo "<h4>You are not a mentor</h4>";
        }

        mysqli_free_result($mentRes);

    }

     // Mentoring Stats
     $getTeach = "SELECT * FROM teach WHERE mentor_id = '$userID'";
     $teachResults = mysqli_query($myconnection, $getTeach) or die ('Query failed: '. mysqli_error($myconnection));
     showMent(mysqli_num_rows($teachResults), $teachResults);
     mysqli_free_result($teachResults);

     //close connection
    mysqli_close($myconnection);
?>