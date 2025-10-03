<?php
session_start();
include('config.php');

header('Content-Type: application/json');

if (!isset($_SESSION[WP . 'id'])) {
    echo json_encode(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบ']);
    exit();
}

$user_id = $_SESSION[WP . 'id'];
$audio_id = $_POST['audio_id'];
$type = $_POST['type']; 

// ตรวจสอบว่ามีการกดอยู่แล้วหรือไม่
$check = mysqli_query($conn, "SELECT * FROM likes WHERE user_id='$user_id' AND audio_id='$audio_id'");
if (!$check) {
    echo json_encode(['status' => 'error', 'message' => 'SQL Error']);
    exit();
}

if (mysqli_num_rows($check) > 0) {
    $row = mysqli_fetch_assoc($check);
    if ($row['type'] == $type) {
        // ถ้ากดซ้ำแบบเดิม -> ยกเลิก
        mysqli_query($conn, "DELETE FROM likes WHERE user_id='$user_id' AND audio_id='$audio_id'");
    } else {
        // ถ้ากดคนละแบบ -> อัปเดต
        mysqli_query($conn, "UPDATE likes SET type='$type' WHERE user_id='$user_id' AND audio_id='$audio_id'");
    }
} else {
    // ถ้ายังไม่เคยกด -> แทรกใหม่
    mysqli_query($conn, "INSERT INTO likes (user_id, audio_id, type) VALUES ('$user_id', '$audio_id', '$type')");
}

// ส่งค่าจำนวน Like / Dislike กลับ
$likes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM likes WHERE audio_id='$audio_id' AND type='like'"))['total'];
$dislikes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM likes WHERE audio_id='$audio_id' AND type='dislike'"))['total'];

echo json_encode([
    'status' => 'success',
    'likes' => $likes,
    'dislikes' => $dislikes
]);
?>