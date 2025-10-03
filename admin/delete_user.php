<?php
include '../config.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM user WHERE id=$id");

header("Location: index-admin.php");
