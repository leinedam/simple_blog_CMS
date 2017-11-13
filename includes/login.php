<?php session_start(); ?>
<?php include "db.php"; ?>
<?php include "../admin/functions.php"; ?>




<?php
    if(isset($_POST['login'])){
        
        login_user( $username = $_POST['username'],  $password = $_POST['password']);

    }
?>