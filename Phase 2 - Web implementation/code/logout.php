<?php 
    session_start();
    session_unset($_SESSION['uID']);
    session_unset($_SESSION['type']);
    session_destroy();

    header("Location: site.html");
?>