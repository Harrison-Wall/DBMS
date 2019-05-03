<?php
    include('connect.php');

    if( $_POST )
    {
        $userID = $_POST['ID'];
        $email =  mysqli_real_escape_string($myconnection, $_POST['Email'] );
        $name =   mysqli_real_escape_string($myconnection, $_POST['Name']);
        $pass =   mysqli_real_escape_string($myconnection, $_POST['Pass']);
        $phone =  mysqli_real_escape_string($myconnection, $_POST['Phone']);
        $city =   mysqli_real_escape_string($myconnection, $_POST['City']);
        $state =  mysqli_real_escape_string($myconnection, $_POST['State']);

        $numUpdated = 0;
        $updateUser = "UPDATE users SET ";

        if( !empty($email) )
        {
            $updateUser .= "email = '$email' ";
            $numUpdated++;
        }

        if( !empty($pass) )
        {
            if( $numUpdated != 0)
                $updateUser .= ",";

            $updateUser .= "password = '$pass' ";
            $numUpdated++;
        }

        if(!empty($name))
        {
            if( $numUpdated != 0)
                $updateUser .= ",";

            $updateUser .= "name = '$name' ";
            $numUpdated++;
        }

        if( !empty($phone) )
        {
            if( $numUpdated != 0)
                $updateUser .= ",";

            $updateUser .= "phone = '$phone' ";
            $numUpdated++;
        }

        if( !empty($city) )
        {
            if( $numUpdated != 0)
                $updateUser .= ",";

            $updateUser .= "city = '$city' ";
            $numUpdated++;
        }

        if( !empty($state) )
        {
            if( $numUpdated != 0)
                $updateUser .= ",";

            $updateUser .= "state = '$state'";
            $numUpdated++;
        }

        if( $numUpdated > 0 )
        {
            $updateUser .= " WHERE id = '$userID'";
            $Result  = mysqli_query($myconnection, $updateUser) or die ('Query failed: '. mysqli_error($myconnection));

             // Respond with nothing for success
            echo " ";
        }
        else
        {
            echo "Fill fields to update";
        }   
    }

    mysqli_close($myconnection);
?>