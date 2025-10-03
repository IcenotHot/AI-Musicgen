<nav class="navbar navbar-expand-lg  navbar navbar-dark bg-dark box-shadow">
            <div class="container-fluid ">
                <a class="navbar-brand navber-dark" href="<?php echo $base_url; ?>/Home.php">Build Riff</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?php echo $base_url; ?>/Home.php">Home</a> 
                        
                        </li>
                            <li class="nav-item">
                            <a class="nav-link active" href="<?php echo $base_url; ?>/templates/index.php">GenMusic</a>
                            </li>
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo $base_url; ?>/Profile.php" class = "btn btn-danger">Profile</a></li>
                                <li><a class="dropdown-item" href="<?php echo $base_url; ?>/audio-post.php">Audio Post</a></li>
                            </ul>
                    </div>
            </div>
        </nav> 