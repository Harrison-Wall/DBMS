<?php 
    // Print section and mod info
if( $_POST )
{
    $userID = $_POST['ID'];

    include('connect.php');

    //Check that the user is a moderator
    $getIsMod = "SELECT * FROM moderators WHERE moderator_id = '$userID'";
    $isModRes = mysqli_query($myconnection, $getIsMod) or die('Query failed: ' . mysqli_error($myconnection));

    if( mysqli_num_rows($isModRes) <= 0 )
    {
        echo "Not a Mod";
    }
    else
    {
        $result = " ";

        // Want to exclude sections that are not currently available
        $currDate = date('Y-m-d');

        // Get the current sections
        $getSectionInfo = "SELECT * FROM sections WHERE end_date > '$currDate'";
        $sectionResults = mysqli_query($myconnection, $getSectionInfo) or die('Query failed: ' . mysqli_error($myconnection));

        // For each section print their moderator( or none at all )
        while( $sectionRow = mysqli_fetch_array($sectionResults, MYSQLI_ASSOC) )
        {
            $secID = $sectionRow['sec_id'];
            $secName = $sectionRow['sec_name'];

            // Print sections name
            $result .= $secID . '&&' . $secName . '&&';

            // see if it has a moderator
            $getModInfo = "SELECT name FROM users WHERE id IN (SELECT moderator_id FROM moderate WHERE sec_id = '$secID')";
            $modResults = mysqli_query($myconnection, $getModInfo) or die('Query failed: ' . mysqli_error($myconnection));

            //if so print name
            if( mysqli_num_rows($modResults) > 0 )
            {
                $modRow = mysqli_fetch_array($modResults, MYSQLI_ASSOC);
                $result .= $modRow['name'] . '&&';
            }
            else
            {
                $result .= "None" . '&&';
            }
            
            mysqli_free_result($modResults);
        }

        echo $result;

        mysqli_free_result($sectionResults);
    }

    mysqli_close($myconnection);
    mysqli_free_result($isModRes);  
}

?>