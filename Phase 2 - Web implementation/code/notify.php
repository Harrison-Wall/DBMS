<?php 
    // Assuming who-ever sets this up on a server has this set to run every week on friday

    function checkMentees()
    {
        include('connect.php');

        date_default_timezone_set("America/New_York");
        $currDay = date('l');

        if( strcmp($currDay, "Friday") == 0 )
        {   
            // Get this and next week's dates
            $date = date_create();
            $currDate = date_format($date,"Y-m-d");

            date_add($date,date_interval_create_from_date_string("7 days"));
            $nextWeek = date_format($date,"Y-m-d");

            // get number of mentees participating in each session that meets in the next week
            $getSessions = 
            "SELECT COUNT(student_id), ses_id, participate.sec_id
                FROM participate, enroll 
                WHERE participate = 1 
                AND student_id = mentee_id
                AND participate.sec_id = enroll.sec_id 
                AND ses_id IN (SELECT ses_id FROM sessions WHERE date > '$currDate' AND date <= '$nextWeek')
                GROUP BY ses_id";
            
            $sessionRes = mysqli_query($myconnection, $getSessions) or die ('Query failed: ' . mysqli_error($myconnection));

            $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
            $fPath = "$DOCUMENT_ROOT/code/notifyCanceled.txt";

            if( mysqli_num_rows($sessionRes) > 0 )
            {
                echo "NOT WORKING";
                while( $sessionRow = mysqli_fetch_array($sessionRes, MYSQLI_ASSOC) ) // check each sessions count
                {   echo "NOT WORKING";
                    if( $sessionRow['COUNT(student_id)'] < 3 ) // Notify participents of that sessions of cancellation
                    {
                        echo "NOT WORKING";
                        $sessionID = $sessionRow['ses_id'];

                        // Get email's of participents
                        $getEmails = 
                            "SELECT name, email 
                             FROM users 
                             WHERE id IN (SELECT student_id FROM participate WHERE ses_id = '$sessionID')";
                        $emailResults = mysqli_query($myconnection, $getEmails) or die ('Query failed: ' . mysqli_error($myconnection));

                        notify($emailResults, $fPath);

                        mysqli_free_result($emailResults);
                    }
                }
            }
            else // No mentees participating?
            {
                $getMtrSess = 
                "SELECT name, email
                 FROM users
                 WHERE id IN 
                 (SELECT mentor_id
                  FROM teach
                  WHERE sec_id IN 
                  (SELECT sec_id
                   FROM sessions WHERE date > '$currDate' AND date <= '$nextWeek'))";
            
                $allMtrRes = mysqli_query($myconnection, $getMtrSess) or die ('Query failed: ' . mysqli_error($myconnection));

                if( mysqli_num_results($allMtrRes) > 0 ) // Or just no sessions
                {
                    notify($allMtrRes, $fPath);
                }

                mysqli_free_result($allMtrRes);
            }

            mysqli_free_result($sessionRes);
        }

        mysqli_close($myconnection);
    } // END checkMentees()
    
    function checkMentors()
    {
        include('connect.php');

        date_default_timezone_set("America/New_York");
        $currDay = date('l');

        if( strcmp($currDay, "Friday") == 0 )
        {   
            // Get this and next week's dates
            $date = date_create();
            $currDate = date_format($date,"Y-m-d");

            date_add($date,date_interval_create_from_date_string("7 days"));
            $nextWeek = date_format($date,"Y-m-d");

            // get number of mantors participating in each session that meets in the next week
            $getSessions = 
            "SELECT COUNT(student_id), ses_id, participate.sec_id
                FROM participate, teach 
                WHERE participate = 1 
                AND student_id = mentor_id
                AND participate.sec_id = teach.sec_id 
                AND ses_id IN (SELECT ses_id FROM sessions WHERE date > '$currDate' AND date <= '$nextWeek')
                GROUP BY ses_id";
            
            $sessionRes = mysqli_query($myconnection, $getSessions) or die ('Query failed: ' . mysqli_error($myconnection));

            $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
            $fPath = "$DOCUMENT_ROOT/code/notifySolo.txt";

            if( mysqli_num_rows($sessionRes) > 0 )
            {
                while( $sessionRow = mysqli_fetch_array($sessionRes, MYSQLI_ASSOC) ) // check each sessions count
                {  
                    if( $sessionRow['COUNT(student_id)'] < 2 ) // Notify mod of only one mentor
                    {
                        $sectionID = $sessionRow['sec_id'];

                        // Get mods email
                        $getEmails = 
                            "SELECT name, email 
                                FROM users 
                                WHERE id IN (SELECT moderator_id FROM moderate WHERE sec_id = '$sectionID')";
                        $emailResults = mysqli_query($myconnection, $getEmails) or die ('Query failed: ' . mysqli_error($myconnection));

                        notify( $emailResults, $fPath );
                        
                        mysqli_free_result($emailResults);
                    }
                }
            }
            else // No results -> No mentors are participating?
            {
                $getAllSess = 
                "SELECT name, email
                 FROM users
                 WHERE id IN 
                 (SELECT moderator_id
                  FROM moderate
                  WHERE sec_id IN 
                  (SELECT sec_id
                   FROM sessions WHERE date > '$currDate' AND date <= '$nextWeek'))";
            
                $allSessRes = mysqli_query($myconnection, $getAllSess) or die ('Query failed: ' . mysqli_error($myconnection));

                if( mysqli_num_results($allSessRes) > 0 ) // Or just no sessions
                {
                    notify($allSessRes, $fPath);
                }

                mysqli_free_result($allSessRes);
            }

            mysqli_free_result($sessionRes);
        }

        mysqli_close($myconnection);
    } // END checkMentors()

    function notify( $dataResults, $filePath )
    {
        // Open file to write email to
        $fp = fopen($filePath, 'ab');
        flock($fp, LOCK_EX);

        if(!$fp)
        {
            // Cannot write out emails
            echo "NOT WORKING";
            exit;
        }
        
        while( $emailRow = mysqli_fetch_array($dataResults, MYSQLI_ASSOC) ) // Print all emails to a file
        {
            $outPut = "Name: " . $emailRow['name'] . "\tEmail: " . $emailRow['email'] . "\n";
            echo $outPut;
            fwrite($fp, $outPut, strlen($outPut));
        }

        flock($fp, LOCK_UN);
        fclose($fp);
    }

    checkMentees();
?>