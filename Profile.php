<?php
session_start();
include 'config.php';

if (empty($_SESSION[WP.'checklogin']))  {
    $_SESSION['message']='You are not authorized';
    header("location:{$base_url}/login.php");
    exit();
}
$User_id = $_SESSION[WP.'id'];
$query = mysqli_query($conn, "SELECT * FROM user WHERE id='{$User_id}'");
$user = mysqli_fetch_assoc($query);

$query_audio = mysqli_query($conn, "SELECT * FROM audio WHERE user_id='{$User_id}'");
$row = mysqli_num_rows($query_audio); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .container {
            max-width: 1000px;
        }

        .profile-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin: 2rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .profile-header {
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
        }

        .profile-header h1 {
            color: #2d3748;
            font-weight: 600;
            font-size: 1.75rem;
            margin: 0;
        }

        .form-label {
            color: #4a5568;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 0.75rem;
            background-color: #f7fafc;
        }

        .form-control:focus {
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
            background-color: white;
        }

        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.625rem 1.25rem;
        }

        .btn-primary {
            background: #4299e1;
            border-color: #4299e1;
        }

        .btn-primary:hover {
            background: #3182ce;
            border-color: #3182ce;
        }

        .btn-danger {
            background: #e53e3e;
            border-color: #e53e3e;
        }

        .btn-danger:hover {
            background: #c53030;
            border-color: #c53030;
        }

        .btn-outline-primary {
            color: #4299e1;
            border-color: #4299e1;
        }

        .btn-outline-primary:hover {
            background: #4299e1;
            border-color: #4299e1;
        }

        .btn-outline-danger {
            color: #e53e3e;
            border-color: #e53e3e;
        }

        .btn-outline-danger:hover {
            background: #e53e3e;
            border-color: #e53e3e;
        }

        .audio-section {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .section-title {
            color: #2d3748;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .table {
            border: none;
            margin: 0;
        }

        .table thead th {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            color: #4a5568;
            font-weight: 600;
            padding: 1rem;
        }

        .table tbody td {
            border: 1px solid #e2e8f0;
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: #f9fafb;
        }

        .audio-controls {
            width: 100%;
            max-width: 300px;
        }

        .prompt-text {
            color: #4a5568;
            font-size: 0.9rem;
            line-height: 1.5;
            max-width: 300px;
            word-wrap: break-word;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .action-buttons .btn {
            font-size: 0.875rem;
        }

        .no-records {
            text-align: center;
            color: #a0aec0;
            font-style: italic;
            padding: 2rem;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        @media (max-width: 768px) {
            .profile-card {
                margin: 1rem 0;
                padding: 1.5rem;
            }

            .button-group {
                flex-direction: column;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include 'include/menu.php';?>

    <div class="container">
        <div class="profile-card">
            <div class="profile-header">
                <h1>โปรไฟล์ของฉัน</h1>
            </div>
            
            <form action="<?php echo $base_url;?>/product-form.php" method="post" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">อีเมล</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ชื่อผู้ใช้</label>
                        <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                    </div>
                </div>
                
                <div class="button-group">
                    <a href="<?php echo $base_url; ?>/templates/index.php" class="btn btn-primary">Back</a>
                    <a onclick="return confirm('คุณต้องการออกจากระบบหรือไม่?');" href="<?php echo $base_url; ?>/logout.php" class="btn btn-danger">Logout</a>                         
                </div>
            </form>
        </div>

        <div class="audio-section">
            <div class="section-title">เพลงของฉัน</div>
            
            <?php if ($row > 0): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 35%;">เพลง</th>
                                <th style="width: 40%;">คำอธิบาย</th>
                                <th style="width: 25%;">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($User_audio = mysqli_fetch_assoc($query_audio)): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($User_audio['audio_url'])): ?>
                                            <audio controls class="audio-controls">                                               
                                                <source src="<?php echo $base_url; ?>/upload_audio/<?php echo htmlspecialchars($User_audio['audio_url']); ?>">
                                                เบราว์เซอร์ของคุณไม่รองรับการเล่นเสียง
                                            </audio>
                                        <?php else: ?>
                                            <span class="text-muted">ไม่มีไฟล์เสียง</span>
                                        <?php endif; ?>  
                                    </td>
                                    <td>
                                        <div class="prompt-text">
                                            <?php echo nl2br(htmlspecialchars($User_audio['description_audio'])); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?php echo $base_url; ?>/upload.php?id=<?php echo $User_audio['id']; ?>" class="btn btn-outline-primary btn-sm">Upload</a>
                                            <a onclick="return confirm('คุณต้องการลบรายการนี้หรือไม่?');" href="<?php echo $base_url; ?>/delete_audio.php?id=<?php echo $User_audio['id']; ?>" class="btn btn-outline-danger btn-sm">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-records">
                    <p>ยังไม่มีเพลงที่สร้าง</p>
                    <small class="text-muted">เริ่มต้นสร้างเพลงแรกของคุณได้เลย</small>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>