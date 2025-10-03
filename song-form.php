<?php
session_start();
include 'config.php';

if (!isset($_SESSION[WP.'id'])) {
    header("Location: login-form.php");
    exit();
}


$user_id = $_SESSION[WP.'id'];

$description_audio = isset($_POST['description_audio']) ? trim($_POST['description_audio']) : ''; 

if (isset($_POST['submit'])) {
    $folder = 'upload_audio/';
    $original_filename = 'static/audio/audio_output.wav';
    $audio_ext = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));

    $allowed_ext = ['mp3', 'wav', 'ogg'];

    if (!in_array($audio_ext, $allowed_ext)) {
        $_SESSION['massge'] = "รูปแบบไฟล์ไม่ถูกต้อง! อัปโหลดเฉพาะ mp3, wav หรือ ogg";
        header("location:{$base_url}/templates/index.php");
        exit();
    }

    $audio_newname = uniqid('audio_', true) . '.' . $audio_ext;
    $audio_location = $folder . $audio_newname;

    if (file_exists($original_filename)) {
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // ย้ายไฟล์ไปยังโฟลเดอร์ upload_audio
        if (rename($original_filename, $audio_location)) {
            $sql = "INSERT INTO audio (user_id, audio_url, description_audio) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("iss", $user_id, $audio_newname, $description_audio); // ✅ แก้ไขให้ตรงกับคอลัมน์ในฐานข้อมูล
                if ($stmt->execute()) {
                    $_SESSION['massge'] = "อัปโหลดไฟล์สำเร็จ!";
                } else {
                    $_SESSION['massge'] = "เกิดข้อผิดพลาดในการบันทึกข้อมูล!";
                }
                $stmt->close();
            } else {
                $_SESSION['massge'] = "เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL!";
            }
        } else {
            $_SESSION['massge'] = "เกิดข้อผิดพลาดในการย้ายไฟล์!";
        }

        $conn->close();
    } else {
        $_SESSION['massge'] = "ไม่พบไฟล์ที่ต้องการบันทึก!";
    }

    header("location:{$base_url}/templates/index.php");
    exit();
}
?>
