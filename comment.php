<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION[WP.'id'])) {
        $audio_id = $_POST['audio_id'];
        $user_id = $_SESSION[WP.'id'];    
        $comment_text = mysqli_real_escape_string($conn, $_POST['comment_text']);

        $query = "INSERT INTO comments (audio_id, user_id, comment_text) VALUES ('$audio_id', '$user_id', '$comment_text')";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "เพิ่มความคิดเห็นสำเร็จ!";
        } else {
            $_SESSION['message'] = "เกิดข้อผิดพลาด!";
        }
    } else {
        $_SESSION['message'] = "คุณต้องเข้าสู่ระบบก่อน!";
    }
    
    header("Location: Home.php");
    exit();
}
?>