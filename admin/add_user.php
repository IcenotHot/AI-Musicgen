<?php
include '../config.php';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    $sql = "INSERT INTO user (email, username, age, gender, role) 
            VALUES ('$email','$username','$age','$gender','user')";
    mysqli_query($conn, $sql);

    header("Location: index-admin.php");
}
?>

