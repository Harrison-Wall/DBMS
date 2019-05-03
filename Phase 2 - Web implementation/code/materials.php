<html>
    <header>
        <title> Study Materials </title>
    </header>

    <body>
        <nav>
            <a href="studentPage.php"> Return </a>
        </nav>

        <h2>Study Materials for your sessions: </h2>
    </body>
</html>

<?php 
    include('connect.php');

    //Get user info
    session_start();
    $userID = $_SESSION['uID'];

    //Get sections and session info
    $getSections = "SELECT sec_id FROM enroll WHERE mentee_id = '$userID' UNION SELECT sec_id FROM teach WHERE mentor_id = '$userID'";
    $sectionResults = mysqli_query($myconnection, $getSections) or die ("Query failed: " . mysqli_error($myconnection));
    
    while( $sectionRows = mysqli_fetch_array($sectionResults, MYSQLI_ASSOC) ) // For each section
    {
        // Get session, material info from Assign
        $secID = $sectionRows['sec_id'];

        $getAssignInfo = "SELECT * FROM assign WHERE sec_id = '$secID'";
        $assignResults = mysqli_query($myconnection, $getAssignInfo) or die ('Query failed: ' . mysqli_error($myconnection));

        while( $assignRows = mysqli_fetch_array($assignResults, MYSQLI_ASSOC) ) // for each material assigned
        {
            $sesID = $assignRows['ses_id']; 
            $matId = $assignRows['material_id'];

            // Get and Print session name and date
            $getSessions = "SELECT * FROM sessions WHERE ses_id = '$sesID'";
            $sessionResults = mysqli_query($myconnection, $getSessions) or die ('Query failed: ' . mysqli_error($myconnection)); 
            $sessionRow = mysqli_fetch_array($sessionResults, MYSQLI_ASSOC);
            echo $sessionRow['ses_name'] . "&nbsp" .$sessionRow['date']."</br>";

            // Get and print Material info
            $getMaterial = "SELECT * FROM material WHERE material_id = '$matId'";
            $materialResults = mysqli_query($myconnection, $getMaterial) or die ('Query failed: ' . mysqli_error($myconnection));
            $materialRow = mysqli_fetch_array($materialResults, MYSQLI_ASSOC);
            echo "&nbsp&nbspTitle: " . $materialRow['title'] . "</br>";
            echo "&nbsp&nbspAuthor: " . $materialRow['author'] . "</br>";
            echo "&nbsp&nbspUrl: ". $materialRow['url'] ."</br>";
            echo "&nbsp&nbspDue: " . $materialRow['assigned_date'] . "</br>";
            echo "&nbsp&nbspNotes: " . $materialRow['notes'] . "</br>";
            echo "</br>";

            // Free results
            mysqli_free_result($materialResults);
            mysqli_free_result($sessionResults);
        }

        //Free results
        mysqli_free_result($assignResults);
    }

    //Free results
    mysqli_free_result($sectionResults);

    //close connection
    mysqli_close($myconnection);
?>