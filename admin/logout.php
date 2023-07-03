<?php
    session_start();
    session_unset(); //data unset
    session_destroy(); //data destroyed
    header('location:../index.php');
    exit();
?>