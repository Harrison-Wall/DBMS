<?php 
    include('connect.php');

    if( $_POST )
    {
        $userID = $_POST['ID'];

        // Mentoring Stats
        $getTeach = "SELECT * FROM teach WHERE mentor_id = '$userID'";
        $teachResults = mysqli_query($myconnection, $getTeach) or die ('Query failed: '. mysqli_error($myconnection));
        $numSect = mysqli_num_rows($teachResults);

        if( $numSect < 0 )
        {
            echo "None";
        }
        else
        {
            $results = "";

            // Loop through results, print section info
            while( $teachRow = mysqli_fetch_array($teachResults, MYSQLI_ASSOC) ) // for each section_id
            {
                $sectID = $teachRow['sec_id'];

                // Get matching section
                $getSects = "SELECT sec_name FROM sections WHERE sec_id = '$sectID'";
                $sectRes = mysqli_query($myconnection, $getSects) or die ('Query failed: '. mysqli_error($myconnection));
                $sectRows = mysqli_fetch_array($sectRes, MYSQLI_ASSOC);

                // add section info
                $results .= $sectRows['sec_name'] . '&&' . 'Mentors: ' . '&&';

                // Get mentor info
                $getMent = "SELECT name FROM users WHERE id IN (SELECT mentor_id FROM teach WHERE sec_id = '$sectID')";
                $mentRes = mysqli_query($myconnection, $getMent) or die ('Query failed: '. mysqli_error($myconnection));
                
                // add mentors
                while( $mentRows = mysqli_fetch_array($mentRes, MYSQLI_ASSOC) )
                {
                    $results .= $mentRows['name'] . '&&';
                }

                $results .= 'Mentees: ' . '&&';

                // Get mentee info
                $getMee = "SELECT name FROM users WHERE id IN (SELECT mentee_id FROM enroll WHERE sec_id = '$sectID')";
                $meeRes = mysqli_query($myconnection, $getMee) or die ('Query failed: '. mysqli_error($myconnection));
                
                // add mentors
                while( $meeRows = mysqli_fetch_array($meeRes, MYSQLI_ASSOC) )
                {
                    $results .= $meeRows['name'] . '&&';
                }

                $results .= '||'; // Deliminates each section

                //free data
                mysqli_free_result($meeRes);
                mysqli_free_result($mentRes);
                mysqli_free_result($sectRes);
            }

            // Send back results
            echo $results;
        }

        mysqli_free_result($teachResults);
    }

    mysqli_close($myconnection);
?>