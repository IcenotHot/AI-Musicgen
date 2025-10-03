<?php
session_start();
include('config.php');

if (empty($_SESSION[WP.'checklogin'])) {
    $_SESSION['message'] = 'You are not authorized';
    header("location:{$base_url}/login.php");
    exit();
}

$User_id = $_SESSION[WP.'id'];

if (isset($_GET['id']) && isset($_GET['audio_id'])) {
    $comment_id = intval($_GET['id']);
    $audio_id   = intval($_GET['audio_id']);

    
    $check = mysqli_query($conn, "SELECT * FROM upload WHERE id = '{$audio_id}' AND user_id = '{$User_id}'");

    if (mysqli_num_rows($check) > 0) {
        
        mysqli_query($conn, "DELETE FROM comments WHERE id = '{$comment_id}'");
        $_SESSION['message'] = "ลบคอมเมนต์สำเร็จ";
    } else {
        $_SESSION['message'] = "คุณไม่มีสิทธิ์ลบคอมเมนต์นี้";
    }

    header("Location: audio-post.php"); 
    exit();
}
?>
