<?php
    session_start();
    if (isset($_SESSION['username'])){
        unset( $_SESSION['username']);
        session_destroy();  // Hủy session
    }
    header('Location: index.php');
?>