<?php
include '../config.php';

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    $sql = "UPDATE user SET 
                email='$email', 
                username='$username', 
                age='$age', 
                gender='$gender' 
            WHERE id=$id";

    mysqli_query($conn, $sql);
    header("Location: index-admin.php");
}