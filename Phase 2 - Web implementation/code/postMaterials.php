<html>
    <head>
        <title>Session Materials</title>
    </head>

    <body>
        <nav><a href="parentPage.php">Return</a></nav>
        <h2>Add a new study Material</h2>
        <h3>* Required Fields</h3>
        <form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?> method="post">
        
            Title: * </br> <input type="text" name="title"/></br>    

            Author: </br> <input type="text" name="author"/></br>

            Type: *</br>  <input type="text" name="type"/></br>

            Url: </br> <input type="tel" name="url"/></br>
            Date: *</br> <input type="date" name="date"/></br>
            Notes: </br> <input type="textArea" name="notes"/></br>

            Session Name: *</br> <input type="text" name="sesName"/></br></br>

            <input type="Submit" name="submit" Value="Submit Material"/>
        </form>
    </body>
</html>

<?php 

// print out moderator session info + material(s) for each session
function printSessionMaterials() 
{
    echo "<h3>Current Session Materials</h3>";

    include('connect.php');
    
    session_start();
    $userID = $_SESSION['uID'];

    //Check that the user is a moderator
    $getIsMod = "SELECT * FROM moderate WHERE moderator_id = '$userID'";
    $isModRes = mysqli_query($myconnection, $getIsMod) or die('Query failed: ' . mysqli_error($myconnection));

    if( mysqli_num_rows($isModRes) <= 0 )
    {
        echo "<h4>There are no sections you are a moderator of.</h4>";
        mysqli_close($myconnection);
        mysqli_free_result($isModRes);

        return;
    }

    // For each section print their sessions and materials
    while( $sectionRow = mysqli_fetch_array($isModRes, MYSQLI_ASSOC) )
    {
        $secID = $sectionRow['sec_id'];

        $getSessMaterials = "SELECT ses_name, date, sessions.ses_id, material_id 
            FROM sessions, assign 
            WHERE assign.sec_id = '$secID' AND sessions.sec_id = '$secID'";

        $sessMaterialRes = mysqli_query($myconnection, $getSessMaterials) or die ('Query failed: ' . mysqli_error($myconnection));
        $sessMaterialRow = mysqli_fetch_array($sessMaterialRes, MYSQLI_ASSOC);

        $sessName = $sessMaterialRow['ses_name'];
        $sessDate = $sessMaterialRow['date'];
        $sessID = $sessMaterialRow['ses_id'];
        $matID = $sessMaterialRow['material_id'];

        echo $sessName . " - " . $sessDate . "</br>";

        //Get and print material info for that session
        $getMaterials = "SELECT * FROM material WHERE material_id IN (SELECT material_id from assign WHERE ses_id = '$sessID')";
        $materialResults = mysqli_query($myconnection, $getMaterials) or die('Query failed: ' . mysqli_error($myconnection));

        while( $materialRow = mysqli_fetch_array($materialResults, MYSQLI_ASSOC) )
        {
            echo "&nbsp&nbsp" . $materialRow['title'] . "&nbspby " . $materialRow['author'] . "&nbsp - " . $materialRow['type'];
            echo "&nbspURL: " . $materialRow['url'] . "&nbspAssigned: " . $materialRow['assigned_date'] . "&nbsp Notes - " . $materialRow['notes'];
            echo "</br>";
        }

        echo "</br>";
        
        mysqli_free_result($materialResults);
        mysqli_free_result($sessMaterialRes);
    }

    mysqli_free_result($isModRes);

    mysqli_close($myconnection);
}

printSessionMaterials();

// handle submission
if( isset($_POST['submit']))
{
    include('connect.php');
    //session_start();
    $userID = $_SESSION['uID'];

    // Check user is a moderator of sections
    $getModInfo = "SELECT * FROM moderate WHERE moderator_id = '$userID'";
    $modResults = mysqli_query($myconnection, $getModInfo) or die('Query failed: ' . mysqli_error($myconnection));

    if( mysqli_num_rows($modResults) > 0 )
    {
        // Get input
        $title = $_POST['title'];
        $title = mysqli_real_escape_string($myconnection, $title);

        $author = $_POST['author'];
        mysqli_real_escape_string($myconnection, $author);

        $type = $_POST['type'];
        mysqli_real_escape_string($myconnection, $type);
        
        $url = $_POST['url'];
        mysqli_real_escape_string($myconnection, $url);

        $date = $_POST['date'];

        $notes = $_POST['notes'];
        mysqli_real_escape_string($myconnection, $notes);

        $sessionName = $_POST['sesName'];
        mysqli_real_escape_string($myconnection, $sessionName);

        // Check required input is filled out
        if( empty($title) || empty($type) || empty($sessionName) || empty($date))
        {
            echo "<h4>Fill out all required fields</h4>";
        }
        else
        {
            // check session exists and user is a mod of that section
            $getSess = "SELECT ses_id, sec_id FROM sessions WHERE ses_name  = '$sessionName' AND sec_id IN 
                        (SELECT sec_Id FROM moderate WHERE moderator_id = '$userID')";
            $sessionRes = mysqli_query($myconnection, $getSess) or die('Query failed: ' . mysqli_error($myconnection));

            if( mysqli_num_rows($sessionRes) > 0 )
            {
                $sessionRow = mysqli_fetch_array($sessionRes, MYSQLI_ASSOC);
                $sessionID = $sessionRow['ses_id'];
                $sectionID = $sessionRow['sec_id'];

                // insert into material
                $insertMat = "INSERT INTO material(title, author, type, url, assigned_date, notes) 
                              VALUES ('$title', '$author', '$type', '$url', '$date', '$notes')";
                mysqli_query($myconnection, $insertMat) or die ('Query failed: ' . mysqli_error($myconnection));
                $matID = mysqli_insert_id($myconnection);

                // insert into post
                $insertPost = "INSERT INTO post(moderator_id, material_id) VALUES ('$userID', '$matID')";
                mysqli_query($myconnection, $insertPost) or die ('Query failed: ' . mysqli_error($myconnection));

                // insert into assign
                $insertAssign = "INSERT INTO assign(sec_id, ses_id, moderator_id, material_id) 
                                VALUES ('$sectionID', '$sessionID', '$userID', '$matID')";
                mysqli_query($myconnection, $insertAssign) or die ('Query failed' . mysqli_error("$myconnection"));

                // update page
                header("Location: postMaterials.php");
            }
            else
            {
                echo "<h4>Could not find session: '$sessionName' or your are not a moderator of that section.</h4>";
            }

            mysqli_free_result($sessionRes);
        }        
    }
    else
    {
        echo "<h4>You do not moderate any sections</h4>";
    }

    mysqli_free_result($modResults);

    mysqli_close($myconnection);
}

?>