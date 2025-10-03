<?php
    session_start();
    include 'config.php';

    if (!empty($_GET['id']))  {
        $User_id = $_GET['id'];
        
        $query = mysqli_query($conn, "DELETE FROM audio WHERE id='{$User_id}'");
        mysqli_close($conn);

        if($query){
            $_SESSION['massage'] = 'Audio deleted successfully';
        } else {
            $_SESSION['massage'] = 'Audio not deleted';
        }

        header("Location: $base_url/Profile.php");
        exit;
    }
?>
