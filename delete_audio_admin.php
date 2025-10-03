<?php
session_start();
include 'config.php';


if (isset($_GET['id'])) {
    $audio_id = intval($_GET['id']);

    // ✅ ลบไฟล์ audio ออกจากโฟลเดอร์ (ถ้ามี)
    $query_file = mysqli_query($conn, "SELECT audio_url FROM audio WHERE id = $audio_id");
    if ($row = mysqli_fetch_assoc($query_file)) {
        $file_path = "../upload_audio/" . $row['audio_url'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    // ✅ ลบ record ในฐานข้อมูล
    mysqli_query($conn, "DELETE FROM audio WHERE id = $audio_id");

    $_SESSION['message'] = "ลบเพลงเรียบร้อยแล้ว";
}
header("location: audio_table.php"); // กลับไปหน้าตาราง (เปลี่ยนชื่อไฟล์ตามจริง)
exit();
?>
