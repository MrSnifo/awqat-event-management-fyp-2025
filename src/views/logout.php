<?php
    echo "loaded";
    session_start();

    // unset all the sessions var 
    $_SESSION = array();

    // destroy the session 
    session_destroy();

    //redirect to home Page
    header("Location: /PFA-2024-2025test/src/public/");

?>