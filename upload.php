<?php
    session_start();
    include 'config.php';
    
    if (empty($_SESSION[WP.'checklogin']))  {
        
        $_SESSION['message']='You are not autherlize';
        header("location:{$base_url}/login.php");
        exit();
}
$User_id = $_SESSION[WP.'id'];
$query = mysqli_query($conn, "SELECT * FROM user WHERE id='{$User_id}'");
$user = mysqli_fetch_assoc($query);



if(!empty($_GET['id'])){
    $query_audio = mysqli_query($conn, "SELECT * FROM audio WHERE id='{$_GET['id']}'");
    $row = mysqli_num_rows($query_audio);

   

    $result = mysqli_fetch_assoc($query_audio);
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width= , initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


</head>
<body class="bg-body-tertiary">
<?php include 'include/menu.php';?>
<div class="container ">

    <div class="container" style="margin-top: 30px;">
        <?php if (!empty($_SESSION['message'])): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <h4 class="mb-3">Upload</h4>
        <form action="<?php echo $base_url; ?>/upload-form.php" method="post" enctype="multipart/form-data">
        <input type="hidden" id="audioUrlInput" name="audio_url" value="<?php echo $result['audio_url']; ?>">
            <div class="row g-5">
                <div class="col-md-6 col-lg-7">
                    <div class="row g-3">
                        <div class="col-sm-12">
                            <label class="form-label">Name for Audio</label>
                            <input type="text" name="fullname" class="form-control" placeholder="" value="">
                        </div>

                        <div class="col-sm-12">
                            <label class="form-label">Pormpt</label>                                   
                            <textarea class="form-control" id="prompt" name="pormpt" rows="3" ><?php echo  $result['description_audio']?></textarea>
                        </div>

                        <hr class="my-4">
                        <div class="text-end">
                                <input type="hidden" name="user_id" value="<?php echo $result['user_id'];?>">
                                <input type="hidden" name="id" value="<?php echo $result['id'];?>">
                                <a onclick ="return confirm('Are you sure you want to go back');"href="<?php echo $base_url; ?>/Profile.php" class="btn btn-secondary btn-lg" role="button">Back</a>
                                <button class="btn btn-primary btn-lg" type="submit" name="submit">Upload</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-5 order-md-last">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-primary">Your Audio</span>
                    </h4>
                    
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                            <audio id="audioPlayer" controls>                                               
                                <source src="<?php echo $base_url; ?>/upload_audio/<?php echo ($result['audio_url']); ?>" >
                            </audio>
                                <li class="list-group-item d-flex justify-content-between bg-body-tertiary">
                                    <div class="text-success">
                                        <h5 class="my-0">Desciption</h5>
                                        <small>Username: <?php echo $user['username']; ?></small><br>
                                        <small>Email: <?php echo $user['email']; ?></small>
                                    </div>
                                </li>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </form>
        <script>
                document.addEventListener("DOMContentLoaded", function() {
                    let audioUrl = document.getElementById("audioSource").src;
                    document.getElementById("audioUrlInput").value = audioUrl;
                });
        </script>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

