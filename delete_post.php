<?php

session_start();
include 'config.php';

if (!empty($_GET['id'])) {
    $User_id = $_GET['id'];

    // ลบ likes ที่เกี่ยวข้องก่อน
    $stmt_likes = $conn->prepare("DELETE FROM likes WHERE audio_id = ?");
    $stmt_likes->bind_param("i", $User_id);
    $stmt_likes->execute();
    $stmt_likes->close();

    // ลบ comments ที่เกี่ยวข้อง
    $stmt_comments = $conn->prepare("DELETE FROM comments WHERE audio_id = ?");
    $stmt_comments->bind_param("i", $User_id);
    $stmt_comments->execute();
    $stmt_comments->close();

    // ลบข้อมูลใน upload
    $stmt = $conn->prepare("DELETE FROM upload WHERE id = ?");
    $stmt->bind_param("i", $User_id);
    $result = $stmt->execute();

    if ($result) {
        $_SESSION['message'] = 'Audio deleted successfully';
        header("Location: $base_url/audio-post.php");
    } else {
        $_SESSION['message'] = 'Audio not deleted';
        header("Location: $base_url/audio-post.php");
    }

    $stmt->close();
    $conn->close();

    header("Location: $base_url/audio-post.php");
    exit;
}
?>
