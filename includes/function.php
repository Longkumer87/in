<?php
//function for administrator
function isAdmin() {
    session_start();
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
        header("Location: 404.php");
        exit;
    }

    
    if (isset($_SESSION['role']) && $_SESSION['role'] == 1) {
        return true;
    } else {
        header("Location: 404.php");
        exit;
    }
}



 





