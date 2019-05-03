<?php 
    if($_POST)
    {
        $userID = $_POST['ID'];
        
        include('connect.php');

        // Get all sections with ther user
        $getSections = 
        "SELECT * 
         FROM sections 
         WHERE sec_id IN ( 
             SELECT sec_id FROM enroll WHERE mentee_id = '$userID' 
             UNION 
             SELECT sec_id FROM teach WHERE mentor_id = '$userID')";
        
        $sectRes = mysqli_query($myconnection, $getSections) or die ('Query Failed: ' . mysqli_error($myconnection));
        
        $response = " ";

        while($sectRow = mysqli_fetch_array($sectRes, MYSQLI_ASSOC))
        {   
            $response .= $sectRow['sec_name'] . '&&';
            $response .= $sectRow['start_date']  . '&&';
            $response .= $sectRow['end_date'] . '&&';

            // Get timslot info
            $sectionTime = $sectRow['time_slot_id'];
            $getTimes    = "SELECT * FROM time_slot WHERE time_slot_id = '$sectionTime'";
            $timeResults = mysqli_query($myconnection, $getTimes) or die ('Query failed: '. mysqli_error($myconnection));
            $timeRow     = mysqli_fetch_array($timeResults, MYSQLI_ASSOC);

            $response .= $timeRow['day_of_the_week'] . '&&';
            $response .= $timeRow['start_time'] . '&&';
            $response .= $timeRow['end_time'] . '&&';
        }

        echo $response;

        mysqli_close($myconnection);
    }
?>