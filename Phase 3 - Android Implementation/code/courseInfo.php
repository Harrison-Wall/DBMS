
<?php 
    include('connect.php');

    $getCourses = "SELECT * FROM courses";
    $courseResults = mysqli_query($myconnection, $getCourses) or die ('Query failed: '. mysqli_error($myconnection));

    $results = " ";

    // Print out info about courses
    while( $courseRow = mysqli_fetch_array($courseResults, MYSQLI_ASSOC) )
    {
        // Get the course info to use in Sections
        $courseID    = $courseRow['c_id'];
        $courseTitle = $courseRow['title'];
        $mentorReq   = $courseRow['mentor_grade_req'];
        $menteeReq   = $courseRow['mentee_grade_req'];

        // Get Section info from sections
        $getSections    = "SELECT * FROM sections WHERE c_id = '$courseID'";
        $sectionResults = mysqli_query($myconnection, $getSections) or die ('Query failed: '. mysqli_error($myconnection));
        $sectionRow     = mysqli_fetch_array($sectionResults, MYSQLI_ASSOC);

        $sectionId    = $sectionRow['sec_id'];
        $sectionName  = $sectionRow['sec_name'];
        $sectionStart = $sectionRow['start_date'];
        $sectionEnd   = $sectionRow['end_date'];
        $sectionTime  = $sectionRow['time_slot_id'];
        $sectionCap   = $sectionRow['capacity'];

        // Get timeslot info
        $getTimes    = "SELECT * FROM time_slot WHERE time_slot_id = '$sectionTime'";
        $timeResults = mysqli_query($myconnection, $getTimes) or die ('Query failed: '. mysqli_error($myconnection));
        $timeRow     = mysqli_fetch_array($timeResults, MYSQLI_ASSOC);
        
        $DayofWeek = $timeRow['day_of_the_week'];
        $timeStart = $timeRow['start_time'];
        $timeEnd   = $timeRow['end_time'];

        // Get Mentor Info
        $getMentors    = "SELECT * FROM teach WHERE sec_id = '$sectionId'";
        $mentorResults = mysqli_query($myconnection, $getMentors) or die ('Query failed: '. mysqli_error($myconnection));

        // Get Mentee Info
        $getMentees    = "SELECT * FROM enroll WHERE sec_id = '$sectionId'";
        $menteeResults = mysqli_query($myconnection, $getMentors) or die ('Query failed: '. mysqli_error($myconnection)); 

        // get course and section info
        $results .= $courseTitle . '&&';
        $results .= $sectionName . '&&';
        $results .= $sectionStart . '&&';
        $results .= $sectionEnd . '&&';
        $results .= $DayofWeek . '&&';
        $results .= $timeStart . '&&';
        $results .= $timeEnd . '&&';
        $results .= $mentorReq . '&&';
        $results .= $menteeReq . '&&';

        // Free results
        mysqli_free_result($timeResults);
        mysqli_free_result($sectionResults);
        mysqli_free_result($menteeResults);
        mysqli_free_result($mentorResults);
    }

    mysqli_free_result($courseResults);

    echo $results;

    mysqli_close($myconnection);
?>