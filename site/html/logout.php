<?php
    error_reporting(0);
    session_start();
    
    // Deletes old session
    session_regenerate_id(true);

    session_destroy();
    header('Location: index.php');
?>