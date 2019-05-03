<html>
    <header>
        <title> Student Mentoring </title>
    </header>

    <body>
    </body>
</html>

<?php 
    
function drawTable()
{
    include('connect.php');

    $getCourses = "SELECT * FROM courses";
    $courseResults = mysqli_query($myconnection, $getCourses) or die ('Query failed: '. mysqli_error($myconnection));

    // Set up table
    echo "<table>".
      "<tr>".
         "<th>Title</th>".
         "<th>Section</th>".
         "<th>Start Date</th>".
         "<th>End Date</th>".
         "<th>Time Slot</th>".
         "<th>Capcity</th>".
         "<th>Mentor Req</th>".
         "<th>Mentee Req</th>".
         "<th>Enrolled Mentor</th>".
         "<th>Enrolled Mentee</th>".
     "</tr>";

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

        // Print course and section info
        echo "<tr>";
            echo "<td>";
            echo $courseTitle;
            echo "<td>";

            echo "<td>";
            echo $sectionName;
            echo "<td>";

            echo "<td>";
            echo $sectionStart;
            echo "<td>";

            echo "<td>";
            echo $sectionEnd;
            echo "<td>";

            echo "<td>";
            echo $DayofWeek;
            echo " ";
            echo $timeStart;
            echo '-';
            echo $timeEnd;
            echo "<td>";

            echo "<td>";
            echo $sectionCap;
            echo "<td>";

            echo "<td>";
            echo $mentorReq;
            echo "<td>";

            echo "<td>";
            echo $menteeReq;
            echo "<td>";

            echo "<td>";
            echo mysqli_num_rows($mentorResults);
            echo "<td>";

            echo "<td>";
            echo mysqli_num_rows($menteeResults);
            echo "<td>";
        echo "</tr>";

        // Free results
        mysqli_free_result($timeResults);
        mysqli_free_result($sectionResults);
        mysqli_free_result($menteeResults);
        mysqli_free_result($mentorResults);
    }

    // End able
    echo "</table>";
    mysqli_free_result($courseResults);
}

session_start();
$userType = $_SESSION['type'];

if( $userType == "Parent" )
{
    echo "<nav><a href=\"parentPage.php\">Return</a></nav>";
}
else
{
    echo "<nav><a href=\"studentPage.php\">Return</a></nav>";
}

drawTable();

?>