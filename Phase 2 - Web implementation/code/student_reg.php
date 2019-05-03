<html>
    <head>
        <title>Student Registration</title>
    </head>

    <body>
        <h3>* Required Fields</h3>
        <form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?> method="post">
        
        Email: * </br> <input type="text" name="reg_email"/></br>    

        Password: * </br> <input type="password" name="reg_password"/></br>

        Name: *</br>  <input type="text" name="reg_name"/></br></br>

        Grade Level: * 
            </br><input type="radio" name="reg_grade" value="Freshman" checked/> Freshman
            </br><input type="radio" name="reg_grade" value="Sophmore"/> Sophmore
            </br><input type="radio" name="reg_grade" value="Junior"/> Junior
            </br><input type="radio" name="reg_grade" value="Senior"/> Senior</br></br>

        Phone: </br> <input type="tel" name="reg_phone"/></br>
        City: </br> <input type="text" name="reg_city"/></br>
        State: </br> <input type="text" name="reg_state"/></br></br>

        Account Type: *
            </br><input type="radio" name="reg_type" value="mentee" checked/> Mentee
            </br><input type="radio" name="reg_type" value="mentor"/> Mentor
            </br><input type="radio" name="reg_type" value="both"/> Both</br></br>

        Parent's Email: *</br> <input type="text" name="reg_parent_email"/></br></br>

            <input type="submit" name="submit" Value="Submit Registration"/>
        </form>
    </body>
</html>

<?php
    include('connect.php');

    if( isset($_POST['submit']) )
    {
        // Input and Error vars
        $email = $name = $pass = $phone = $city = $state = $parent = $type = $grade = " ";
        $isErr = false;

        // Required Fields need to be filled out    
        if( $_SERVER["REQUEST_METHOD"] == "POST" )
        {
            if (empty($_POST["reg_email"]))
            {
                $isErr = true;
            }
            else
            {
                $email = $_POST["reg_email"];
                $email = mysqli_real_escape_string($myconnection, $email);
            }

            if (empty($_POST["reg_password"]))
            {
                $isErr = true;
            }
            else
            {
                $pass = $_POST["reg_password"];
                $pass = mysqli_real_escape_string($myconnection, $pass);
            }
            
            if (empty($_POST["reg_name"]))
            {
                $isErr = true;
            }
            else
            {
                $name = $_POST["reg_name"];
                $name = mysqli_real_escape_string($myconnection, $name);
            }

            if( empty($_POST["reg_parent_email"] ))
            {
                $isErr = true;
            }
            else
            {
                $parent = $_POST["reg_parent_email"];
                $parent = mysqli_real_escape_string($myconnection, $parent);
            }
        }

        if( $isErr )
        {
            echo '<p> Enter all required fields </p>';
        }
        else
        {
            // Check that the parent is registered
            // Get parent's ID
            $getParentID = "SELECT * FROM users WHERE email = '$parent'";
            $pidResult = mysqli_query($myconnection, $getParentID) or die ('Query failed: '. mysqli_error($myconnection));

            if( !mysqli_num_rows($pidResult) )
            {
                echo "<h4>Please register a parent first.</h4>";

            }
            else
            {
                $parentRow = mysqli_fetch_array($pidResult, MYSQLI_ASSOC);
                $parentID = $parentRow['id'];

                $grade = $_POST['reg_grade'];
                switch($grade)
                {
                    case "Freshman":
                        $grade = 1;
                        break;
                    case "Sophmore":
                        $grade = 2;
                        break;
                    case "Junior":
                        $grade = 3;
                        break;
                    case "Senior":
                        $grade = 4;
                        break;
                }

                $type = $_POST["reg_type"];

                $insertUser = "INSERT INTO users (email, password, name";
                $insertValues = " VALUES ('$email', '$pass', '$name'";

                // Check optional fields
                if( !empty($_POST["reg_phone"]) )
                {
                    $phone = $_POST["reg_phone"];
                    $phone = mysqli_real_escape_string($myconnection, $phone);

                    $insertUser .= ", phone";
                    $insertValues .= ", '$phone'";
                }

                if( !empty($_POST["reg_city"]) )
                {
                    $city = $_POST["reg_city"];
                    $city = mysqli_real_escape_string($myconnection, $city);

                    $insertUser .= ", city";
                    $insertValues .= ", '$city'";
                }

                if( !empty($_POST["reg_state"]) )
                {
                    $state = $_POST["reg_state"];
                    $state = mysqli_real_escape_string($myconnection, $state);

                    $insertUser .= ", state";
                    $insertValues .= ", '$state'";
                }

                $insertUser .= ")";
                $insertValues .= ")";

                $insertUser .= $insertValues;

                // Insert into user
                $Result  = mysqli_query($myconnection, $insertUser) or die ('Query failed: '. mysqli_error($myconnection));
                
                // Get the ID of the inserted row
                $getUserID = "SELECT * FROM users WHERE email = '$email'";
                $idResult = mysqli_query($myconnection, $getUserID) or die ('Query failed: '. mysqli_error($myconnection));
                $row = mysqli_fetch_array($idResult, MYSQLI_ASSOC);
                $userID = $row['id'];

                // insert into student
                $insertStudent = "INSERT INTO students(student_id, grade) VALUES ('$userID', '$grade')";
                mysqli_query($myconnection, $insertStudent) or die ('Query failed: '. mysqli_error($myconnection));

                // insert into parenting
                $insertParenting = "INSERT INTO parenting(parent_id, student_id) VALUES ('$parentID', '$userID')";
                mysqli_query($myconnection, $insertParenting) or die ('Query failed: '. mysqli_error($myconnection));

                // insert into mentee and or mentor
                $insertMentee = "INSERT INTO mentees(mentee_id) VALUES ('$userID')";
                $insertMentor = "INSERT INTO mentors(mentor_id) VALUES ('$userID')";

                switch($type)
                {
                    case "mentee":
                        mysqli_query($myconnection, $insertMentee) or die ('Query failed: '. mysqli_error($myconnection));
                        break;
                    case "mentor":
                        mysqli_query($myconnection, $insertMentor) or die ('Query failed: '. mysqli_error($myconnection));
                        break;
                    case "both":
                        mysqli_query($myconnection, $insertMentee) or die ('Query failed: '. mysqli_error($myconnection));
                        mysqli_query($myconnection, $insertMentor) or die ('Query failed: '. mysqli_error($myconnection));
                        break;
                }

                mysqli_free_result($Result);
                mysqli_free_result($idResult);
                mysqli_free_result($pidResult);

                // Make a session
                session_destroy();
                session_start();
                
                $_SESSION['uID'] = $userID;
                $_SESSION['type'] = "Student";
                
                // go to account page
                header('Location: studentPage.php');
            }
            mysqli_free_result($pidResult);
        }
    }

    mysqli_close($myconnection);
?>