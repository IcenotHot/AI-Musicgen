<?php
session_start();
include '../config.php';

if (empty($_SESSION[WP.'checklogin']))  {
    $_SESSION['message']='You are not autherlize';
    header("location:{$base_url}/login.php");
    exit();
}

// ดึงข้อมูล user
$query = mysqli_query($conn, "SELECT * FROM user WHERE role = 'user'");
$row = mysqli_num_rows($query);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - SB Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            .popup-overlay {
                display: none;
                position: fixed;
                top: 0; left: 0;
                width: 100%; height: 100%;
                background: rgba(0,0,0,0.6);
                justify-content: center;
                align-items: center;
                z-index: 9999;
            }
            .popup-content {
                background: #fff;
                padding: 20px;
                border-radius: 10px;
                width: 400px;
                max-width: 90%;
                box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            }
        </style>
    </head>
    <body class="sb-nav-fixed">
        <?php include 'nav.php'?>
        <div id="layoutSidenav">
            <?php include 'side_nav.php'?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>

                        <!-- ตาราง User -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                User data
                            </div>

                            <!-- ปุ่มเปิด Popup Add -->
                            <button class="btn btn-success m-3" onclick="openPostPopup('adduser')">+ Add User</button>

                            <!-- Popup Add User -->
                            <div id="post-popup-adduser" class="popup-overlay">
                                <div class="popup-content">
                                    <h2>Add User</h2>
                                    <form method="post" action="add_user.php">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" name="username" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Age</label>
                                            <input type="number" name="age" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select name="gender" class="form-control">
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                        <br>
                                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                                        <button type="button" class="btn btn-secondary" onclick="closePostPopup('adduser')">Cancel</button>
                                    </form>
                                </div>
                            </div>

                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Email</th>
                                            <th>Username</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($row > 0): ?>
                                            <?php while ($User= mysqli_fetch_assoc($query)): ?>
                                                <tr>
                                                    <td><?php echo $User['id']; ?></td>
                                                    <td><?php echo $User['email']; ?></td>
                                                    <td><?php echo $User['username']; ?></td>
                                                    <td><?php echo $User['age']; ?></td>
                                                    <td><?php echo $User['gender']; ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary" onclick="openPostPopup('edit<?php echo $User['id']; ?>')">Edit</button>
                                                        <a href="delete_user.php?id=<?php echo $User['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                                    </td>
                                                </tr>

                                                <!-- Popup Edit User -->
                                                <div id="post-popup-edit<?php echo $User['id']; ?>" class="popup-overlay">
                                                    <div class="popup-content">
                                                        <h2>Edit User</h2>
                                                        <form method="post" action="edit_user.php">
                                                            <input type="hidden" name="id" value="<?php echo $User['id']; ?>">
                                                            <div class="form-group">
                                                                <label>Email</label>
                                                                <input type="email" name="email" class="form-control" value="<?php echo $User['email']; ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Username</label>
                                                                <input type="text" name="username" class="form-control" value="<?php echo $User['username']; ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Age</label>
                                                                <input type="number" name="age" class="form-control" value="<?php echo $User['age']; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Gender</label>
                                                                <select name="gender" class="form-control">
                                                                    <option value="Male" <?php if($User['gender']=="Male") echo "selected"; ?>>Male</option>
                                                                    <option value="Female" <?php if($User['gender']=="Female") echo "selected"; ?>>Female</option>
                                                                </select>
                                                            </div>
                                                            <br>
                                                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                                                            <button type="button" class="btn btn-secondary" onclick="closePostPopup('edit<?php echo $User['id']; ?>')">Cancel</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>

                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <!-- JS -->
        <script>
            function openPostPopup(id) {
                document.getElementById('post-popup-' + id).style.display = 'flex';
            }
            function closePostPopup(id) {
                document.getElementById('post-popup-' + id).style.display = 'none';
            }
        </script>
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>