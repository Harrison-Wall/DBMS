<html>
    <head>
        <title>Sign In</title>
    </head>

    <body>
        <form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?> method="post">

            <p>Enter your Email and Password</p>

            Email:</br> <input type="text" name="email"></br>
            Password:</br> <input type="password" name="password"></br></br>

            <input type="submit" name="submit" value="Sign In"/>
        </form>

    </body>
</html>

<?php

    include('connect.php');

    if( isset($_POST['submit']) )
    {
        // Error and Input Vars
        $email = $password = "";
        $isErr = false;

        // Check for input
        if( empty( $_POST['email'] ) )
        {
            $isErr = true;
        }
        else
        {
            $email = $_POST['email'];
            $email = mysqli_real_escape_string($myconnection, $email);
        }

        if( empty( $_POST['password'] ) )
        {
            $isErr = true;
        }
        else
        {
            $password = $_POST['password'];
            $password = mysqli_real_escape_string($myconnection, $password);
        }

        if( !$isErr ) // No Error free to run SQL commands on Database
        {
            $query = "Select * FROM users WHERE email = '$email' AND password = '$password'";  
            $result = mysqli_query($myconnection, $query) or die ('Query failed: ' . mysqli_error($myconnection));

            if ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC)) 
            {
                $userID = $row['id'];

                //Check if UserID is parent or student
                $checkParent  = "SELECT * FROM parents WHERE parent_id = '$userID'";
                $checkStudent = "SELECT * FROM students WHERE student_id = '$userID'";

                $parentResult  = mysqli_query($myconnection, $checkParent) or die ('Query failed: '. mysqli_error($myconnection));
                $studentResult = mysqli_query($myconnection, $checkStudent) or die ('Query failed: '. mysqli_error($myconnection));

                // Store parent/student in the session
                session_destroy();
                session_start();

                if( mysqli_num_rows($studentResult) )
                {
                    mysqli_free_result($studentResult);
                    mysqli_free_result($parentResult);

                    $_SESSION['uID'] = $userID;
                    $_SESSION['type'] = "Student";
                    header('Location: studentPage.php');
                }
                else if( mysqli_num_rows($parentResult) )
                {
                    mysqli_free_result($studentResult);
                    mysqli_free_result($parentResult);

                    $_SESSION['uID'] = $userID;
                    $_SESSION['type'] = "Parent";
                    header('Location: parentPage.php');
                }
                else
                {
                    echo '<p> Error Logging In</p>
                    <p> Check your email and password</p>';
                }

                mysqli_free_result($studentResult);
                mysqli_free_result($parentResult);
            }
            else
            {
                echo '<p> Error Logging In</p>
                    <p> Check your email and password</p>';
            }

            mysqli_free_result($result);

        }
        else
        {
            echo '<p> Fill out all fields</p>';
        }
    }    

    mysqli_close($myconnection);
?>