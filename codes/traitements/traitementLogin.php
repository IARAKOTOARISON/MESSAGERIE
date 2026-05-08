<?php
if(!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password'])) {
    header("Location:../login.php?erreur=1");
    exit();
}else {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
}


echo $username;
echo $email;
echo $password;

?>