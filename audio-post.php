<?php
session_start();
include ('config.php');

if (empty($_SESSION[WP.'checklogin']))  {
    $_SESSION['message']='You are not authorized';
    header("location:{$base_url}/login.php");
    exit();
}

$User_id = $_SESSION[WP.'id'];

// ✅ ดึงเฉพาะเพลงของ User นี้เท่านั้น
$query_audio = mysqli_query($conn, "
    SELECT upload.*, user.username 
    FROM upload 
    JOIN user ON upload.user_id = user.id 
    WHERE upload.user_id = '{$User_id}'
    ORDER BY upload.upload_date DESC
");
$row = mysqli_num_rows($query_audio);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Audio Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f7fa; font-family: 'Inter', sans-serif; }
        .container { max-width: 1200px; }
        .page-header { background: white; border-radius: 8px; padding: 2rem; margin: 2rem 0;
                       box-shadow: 0 2px 10px rgba(0,0,0,0.05); text-align: center; }
        .page-header h1 { color: #2d3748; font-weight: 600; margin-bottom: 0.5rem; }
        .page-header p { color: #718096; margin: 0; }
        .section-title { color: #2d3748; font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;
                         padding-bottom: 0.5rem; border-bottom: 2px solid #e2e8f0; }
        .audio-card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem;
                      margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .audio-title { color: #2d3748; font-weight: 600; font-size: 1.1rem; margin-bottom: 1rem; }
        .audio-controls { width: 100%; margin: 1rem 0; }
        .audio-meta { background: #f7fafc; padding: 1rem; border-radius: 6px; margin: 1rem 0;
                      font-size: 0.9rem; color: #4a5568; }
        .btn-interaction { border-radius: 6px; border: 1px solid #e2e8f0; background: white; color: #4a5568;
                           font-size: 0.9rem; padding: 0.5rem 1rem; margin-right: 0.5rem; margin-bottom: 0.5rem; }
        .btn-like { color: #38a169; border-color: #38a169; }
        .btn-dislike { color: #e53e3e; border-color: #e53e3e; }
        .comment-section { border-top: 1px solid #e2e8f0; padding-top: 1.5rem; margin-top: 1.5rem; }
        .btn-comment { background: #4299e1; color: white; border: none; border-radius: 6px;
                       padding: 0.5rem 1.25rem; font-size: 0.9rem; margin-top: 0.75rem; }
        .comments-list { max-height: 200px; overflow-y: auto; margin-top: 1.5rem; }
        .comment-item { padding: 1rem 0; border-bottom: 1px solid #f1f5f9; }
        .comment-author { color: #2d3748; font-weight: 600; font-size: 0.9rem; }
        .comment-text { color: #4a5568; margin: 0.5rem 0; }
        .comment-time { color: #a0aec0; font-size: 0.8rem; }
        .no-content { text-align: center; padding: 3rem; color: #a0aec0; }
    </style>
</head>
<body>
    <?php include 'include/menu.php';?>

    <div class="container">
        <div class="page-header">
            <h1>My Audio Hub</h1>
            <p>เพลงทั้งหมดของฉัน 🎶</p>
        </div>

        <?php if($row > 0):?>
            <div class="section-title">เพลงของฉัน</div>
            
            <div class="row">
                <?php while($User_audio = mysqli_fetch_assoc($query_audio)):?>
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="audio-card">
                            <?php if (!empty($User_audio['upload_url'])): ?>
                                <div class="audio-title">
                                    <?php echo htmlspecialchars($User_audio['name_audio']); ?>
                                </div>
                                
                                <audio controls class="audio-controls">                                               
                                    <source src="<?php echo $base_url; ?>/upload_audio/<?php echo htmlspecialchars($User_audio['upload_url']); ?>">
                                </audio>
                                
                                <div class="audio-meta">
                                    <strong>อัพโหลดโดย:</strong> <?php echo htmlspecialchars($User_audio['username']); ?><br>
                                    <strong>วันที่:</strong> <?php echo htmlspecialchars($User_audio['upload_date']); ?>
                                    <?php if (!empty($User_audio['upload_description'])): ?>
                                        <div class="mt-2"><?php echo nl2br(htmlspecialchars($User_audio['upload_description'])); ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mt-3">
                                    <button class="btn btn-interaction btn-like like-btn" data-audio="<?php echo $User_audio['id']; ?>">
                                        👍 <span id="like-count-<?php echo $User_audio['id']; ?>">
                                            <?php 
                                                echo mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM likes WHERE audio_id = '{$User_audio['id']}' AND type = 'like'"))['total']; 
                                            ?>
                                        </span>
                                    </button>
                                    <button class="btn btn-interaction btn-dislike dislike-btn" data-audio="<?php echo $User_audio['id']; ?>">
                                        👎 <span id="dislike-count-<?php echo $User_audio['id']; ?>">
                                            <?php 
                                                echo mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM likes WHERE audio_id = '{$User_audio['id']}' AND type = 'dislike'"))['total']; 
                                            ?>
                                        </span>
                                    </button>
                                </div>
                                <div>
                                    <a href="delete_post.php?id=<?php echo $User_audio['id']; ?>" 
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบเพลงนี้?');">
                                        🗑 ลบ
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="comment-section">
                                <form action="comment.php" method="POST" class="comment-form">
                                    <input type="hidden" name="audio_id" value="<?php echo $User_audio['id']; ?>">
                                    <textarea name="comment_text" class="form-control" rows="3" placeholder="แสดงความคิดเห็น..." required></textarea>
                                    <button type="submit" class="btn btn-comment">ส่งความคิดเห็น</button>
                                </form>

                                <div class="comments-list">
                                    <?php
                                        $query_comments = mysqli_query($conn, "
                                            SELECT comments.*, user.username 
                                            FROM comments 
                                            JOIN user ON comments.user_id = user.id 
                                            WHERE comments.audio_id = '{$User_audio['id']}' 
                                            ORDER BY comments.created_at DESC
                                        ");
                                        
                                        if (mysqli_num_rows($query_comments) > 0) {
                                            while ($comment = mysqli_fetch_assoc($query_comments)) {
                                                echo "<div class='comment-item'>";
                                                echo "<div class='comment-author'>{$comment['username']}</div>";
                                                echo "<div class='comment-text'>{$comment['comment_text']}</div>";
                                                echo "<div class='comment-time'>{$comment['created_at']}</div>";

                                                
                                                if ($User_id == $User_audio['user_id']) {
                                                    echo "<a href='delete_comment.php?id={$comment['id']}&audio_id={$User_audio['id']}'
                                                            class='btn btn-sm btn-danger mt-2'
                                                            onclick=\"return confirm('คุณแน่ใจหรือไม่ที่จะลบคอมเมนต์นี้?');\">
                                                            🗑 ลบคอมเมนต์
                                                        </a>";
                                                }

                                                echo "</div>";
                                            }
                                        } else {
                                            echo "<div class='text-center text-muted py-3'>ยังไม่มีความคิดเห็น</div>";
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile;?>
            </div>
        <?php else:?>
            <div class="no-content">
                <h4>ยังไม่มีไฟล์เสียง</h4>
                <p>เริ่มต้นด้วยการอัพโหลดเพลงของคุณ</p>
            </div>
        <?php endif;?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".like-btn, .dislike-btn").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            
            let audioId = this.getAttribute("data-audio");
            let type = this.classList.contains("like-btn") ? "like" : "dislike";
            
            fetch("like_dislike.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "audio_id=" + audioId + "&type=" + type
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    document.getElementById(`like-count-${audioId}`).innerText = data.likes;
                    document.getElementById(`dislike-count-${audioId}`).innerText = data.dislikes;
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });
});
</script>
</body>
</html>
