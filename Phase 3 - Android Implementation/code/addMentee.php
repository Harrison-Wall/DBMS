<?php
    include('connect.php');

    // handle submit
    if( $_POST )
    {  
        $userID = $_POST['ID'];

        // Get section name
        $sectionName = $_POST['sectName'];
        $sectionName = mysqli_real_escape_string($myconnection, $sectionName);

        // Check if they are a mentee
        $getMentee = "SELECT * FROM mentees WHERE mentee_id = '$userID'";
        $menteeRes = mysqli_query($myconnection, $getMentee) or die ('Query failed: '. mysqli_error($myconnection));

        if( mysqli_num_rows($menteeRes) > 0 )
        {
            $getSect = "SELECT * FROM sections WHERE sec_name = '$sectionName'";
            $sectRes = mysqli_query($myconnection, $getSect) or die ('Query failed: '. mysqli_error($myconnection));
            $sectRows = mysqli_fetch_array($sectRes, MYSQLI_ASSOC);

            // check if section date works
            $sectDate = $sectRows['end_date'];
            $currDate = date( 'Y-m-d' );

            if( $currDate > $sectDate )
            {
                echo "Section has already ended";
                mysqli_free_result($sectRes);
            }
            else
            {
                // check if section has space for a mentee
                $sectID = $sectRows['sec_id'];
                $getNumMent = "SELECT * FROM teach WHERE sec_id = '$sectID'";
                $numResult = mysqli_query($myconnection, $getNumMent) or die ('Query failed: '. mysqli_error($myconnection));

                if( mysqli_num_rows($numResult) >= 6 )
                {
                    echo "This section already has 6 mentors";
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
                    $courseGrade = $courseGradeRow['mentee_grade_req'];

                    $getStuGrade = "SELECT * FROM students WHERE student_id = '$userID'";
                    $stuGradeRes = mysqli_query($myconnection, $getStuGrade) or die ('Query failed: '. mysqli_error($myconnection));
                    $stuGradeRow = mysqli_fetch_array($stuGradeRes, MYSQLI_ASSOC);
                    $stuGrade = $stuGradeRow['grade'];

                    if( $courseGrade > $stuGrade )
                    {
                        echo "Grade Requirement Not Met";
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
                        (SELECT sec_id FROM enroll WHERE mentee_id = '$userID'))";
                        $enrollResults = mysqli_query($myconnection, $checkEnroll) or die ('Query failed: ' . mysqli_error($myconnection));

                        if( mysqli_num_rows($enrollResults) > 0 ) //Already enrolled in this course
                        {
                            echo "You are already a mentee in a section in this course";
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

                            // Get time_slot info of what the user is a mentee in
                            $getTeachTime = 
                            "SELECT * FROM time_slot WHERE time_slot_id IN 
                            (SELECT time_slot_id FROM sections WHERE sec_id IN 
                            (SELECT sec_id FROM enroll WHERE mentee_id = '$userID'))";
                            $teachTimeRes = mysqli_query($myconnection, $getTeachTime) or die ('Query failed: ' . mysqli_error($myconnection));
                            
                            $isConflict = false;

                            while( $teachTimeRows = mysqli_fetch_array($teachTimeRes, MYSQLI_ASSOC) ) // Check against every time user is learning
                            {
                                // Check day of the week
                                if( $sectTimeRow['day_of_the_week'] == $teachTimeRows['day_of_the_week'] )
                                {
                                    //check start and end times
                                    if( ($sectRows['start_time'] < $teachTimeRows['start_time'] && $sectRows['end_time'] > $teachRows['start_time']) 
                                        || ($sectRows['start_time'] > $teachTimeRows['start_time'] && $sectRows['start_time'] < $teachRows['end_time']) )
                                    {
                                        $isConflict = true;
                                        echo "Time conflict";
                                        break;
                                    }      
                                }
                            }

                            // No conflit, finally can enroll as mentor
                            if( !$isConflict )
                            {
                                $insertMent = "INSERT INTO enroll(sec_id, mentee_id) VALUES('$sectID', '$userID')";
                                mysqli_query($myconnection, $insertMent) or die ('Query failed: ' . mysqli_error($myconnection));
                                echo (" ");
                            }

                            // free results
                            mysqli_free_result($numResult);
                            mysqli_free_result($sectRes);
                            mysqli_free_result($courseGradeRes);
                            mysqli_free_result($stuGradeRes);
                            mysqli_free_result($enrollResults);
                            mysqli_free_result($sectTimeRes);
                            mysqli_free_result($teachTimeRes);
                        }
                    }
                }   
            }
        }

        mysqli_free_result($menteeRes);
    }

    mysqli_close($myconnection);
?>