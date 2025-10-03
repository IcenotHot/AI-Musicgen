<?php
session_start();
include 'config.php';

$User_id = $_SESSION[WP.'id'];

if (isset($_POST['submit'])) {
    $audio_id = mysqli_real_escape_string($conn, $_POST['id']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $name_audio = mysqli_real_escape_string($conn, $_POST['fullname']);
    $audio_prompt = mysqli_real_escape_string($conn, $_POST['pormpt']);
    $audio_url = mysqli_real_escape_string($conn, $_POST['audio_url']);

    // ตั้งค่าโฟลเดอร์ปลายทาง
    $upload_dir = 'Home_audio/';



    $now = date('Y-m-d H:i:s');

    // ตรวจสอบว่ามีไฟล์ถูกอัปโหลดมาหรือไม่
    if (!empty($_FILES['audio_file']['name'])) {
        $file_name = basename($_FILES['audio_file']['name']);
        $audio_tmp = $_FILES['audio_file']['tmp_name'];
        $audio_location = $upload_dir . $file_name;

        // พยายามย้ายไฟล์ไปยังโฟลเดอร์
        if (move_uploaded_file($audio_tmp, $audio_location)) {
            $audio_url = $audio_location;
        } else {
            $_SESSION['message'] = 'File upload failed: ' . error_get_last()['message'];
            header("Location: $base_url/upload.php");
            exit();
        }
    }

    // บันทึกค่า audio_url ลงฐานข้อมูล
    $query = "INSERT INTO upload (upload_date, user_id, audio_id, name_audio, upload_description, upload_url) 
              VALUES ('$now', '$user_id', '$audio_id', '$name_audio', '$audio_prompt', '$audio_url')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = 'Upload Success';
    } else {
        $_SESSION['message'] = 'Database error: ' . mysqli_error($conn);
    }

    header("Location: $base_url/upload.php");
    exit();
}
?>