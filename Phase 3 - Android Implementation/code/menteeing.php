<?php 
    function showMentee($numSect, $teachRes) 
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

            //free results
            mysqli_free_result($sectRes);
        }

        //close connection
        mysqli_close($myconnection);
        
    }// End showMentee()
?>