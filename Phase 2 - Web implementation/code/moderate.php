<html>
    <header>
        <title> Moderate a Section </title>
    </header>

    <body>
        <nav>
            <a href="parentPage.php"> Return </a>
        </nav>

        <h2>Section to moderate: </h2>
        <form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?> method="post">

            Section Name <input type="text" name="secName"/>
            <input type="submit" name="Moderate" value="Submit Mod Request"/>

        </form>
    </body>
</html>

<?php 
    // Print section and mod info
function printModSec($userID)
{
    echo "<h3>Current Sections and Moderators</h3>";

    include('connect.php');

    //Check that the user is a moderator
    $getIsMod = "SELECT * FROM moderators WHERE moderator_id = '$userID'";
    $isModRes = mysqli_query($myconnection, $getIsMod) or die('Query failed: ' . mysqli_error($myconnection));

    if( mysqli_num_rows($isModRes) <= 0 )
    {
        echo "<h4>You are not currently a Moderator</h4>";
        mysqli_close($myconnection);
        mysqli_free_result($isModRes);

        return;
    }

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
        echo $secName . " - ";

        // see if it has a moderator
        $getModInfo = "SELECT name FROM users WHERE id IN (SELECT moderator_id FROM moderate WHERE sec_id = '$secID')";
        $modResults = mysqli_query($myconnection, $getModInfo) or die('Query failed: ' . mysqli_error($myconnection));

        //if so print name
        if( mysqli_num_rows($modResults) > 0 )
        {
            $modRow = mysqli_fetch_array($modResults, MYSQLI_ASSOC);
            echo $modRow['name'];
        }
        else
        {
            echo "none";
        }

        echo "</br>";
        
        mysqli_free_result($modResults);
    }

    mysqli_free_result($sectionResults);

    mysqli_close($myconnection);
}

session_start();
$userID = $_SESSION['uID'];

//handle button should be sec_id 2 mod_id 5
if( isset($_POST['Moderate']) )
{
    include('connect.php');

    // Check that they are a moderator
    $getIsMod = "SELECT * FROM moderators WHERE moderator_id = '$userID'";
    $isModRes = mysqli_query($myconnection, $getIsMod) or die('Query failed: ' . mysqli_error($myconnection));

    if( mysqli_num_rows($isModRes) <= 0 )
    {
        echo "<h4>You are not currently a Moderator</h4>";
        mysqli_free_result($isModRes);
    }
    else
    {
        // Get section name
        $sectionName = $_POST['secName'];
        $sectionName = mysqli_real_escape_string($myconnection, $sectionName);

        if( empty($sectionName) )
        {
            echo "<h4>Enter a section to become a moderator of.</h4>";
        }
        else
        {
            // Check that the section exists
            $getSection = "SELECT sec_id FROM sections WHERE sec_name = '$sectionName'";
            $sectionRes = mysqli_query($myconnection, $getSection) or die('Query failed: ' . mysqli_error($myconnection));

            if( mysqli_num_rows($sectionRes) > 0 )
            {
                $sectionIdRow = mysqli_fetch_array($sectionRes);
                $sectionID = $sectionIdRow['sec_id'];

                // Check that the section does not already have a moderator
                $getModerator = "SELECT * from moderate WHERE sec_id = '$sectionID'";
                $moderateRes = mysqli_query($myconnection, $getModerator) or die('Query failed: ' . mysqli_error($myconnection));

                if( mysqli_num_rows($moderateRes) > 0 )
                {
                    echo "<h4>This section already has a moderator</h4>";
                }
                else
                {
                    // Add user as a moderator
                    $insertUser = "INSERT INTO moderate (sec_id, moderator_id) VALUES ('$sectionID', '$userID')";
                    mysqli_query($myconnection, $insertUser) or die('Query failed: ' . mysqli_error($myconnection));

                }

                mysqli_free_result($moderateRes);
            }
            else
            {
                echo "<h4>This section does not exist</h4>";
            }

            mysqli_free_result($sectionRes);
        }
    }

    mysqli_close($myconnection);
}


printModSec($userID);
?>