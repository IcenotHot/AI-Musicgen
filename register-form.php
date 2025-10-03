<?php
session_start();
include 'config.php';

$email = mysqli_real_escape_string($conn,$_POST['email']);
$username = mysqli_real_escape_string($conn,$_POST['username']);
$age = mysqli_real_escape_string($conn,$_POST['age']);
$gender = mysqli_real_escape_string($conn,$_POST['gender']);
$password = mysqli_real_escape_string($conn,$_POST['password']);

if(!empty($email) && !empty($username) && !empty($age)&& !empty($gender) && !empty($password)){
    $hash = password_hash($password,PASSWORD_DEFAULT);
    $query = mysqli_query($conn,"INSERT INTO user(email,username,age,gender,password) 
    VALUES('$email','$username','$age','$gender','$hash')") or die ('query fail');

    if( $query ){
        $_SESSION['message'] = 'Register complete';
        header("location:{$base_url}/login.php");
    }else{
        $_SESSION['message'] = 'Register could not be saved!';
        header("location:{$base_url}/register.php");
    }

} else{
    $_SESSION['message'] = 'In put is required';
    header("location:{$base_url}/register.php");
}


