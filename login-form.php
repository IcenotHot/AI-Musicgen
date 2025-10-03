<?php
session_start();
include 'config.php';


    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if(!empty($email) && !empty($password)){
        $query = mysqli_query($conn,"SELECT * FROM user WHERE email='{$email}'");
        $row = mysqli_num_rows($query);
        if ($row ==  1){
            $user = mysqli_fetch_assoc($query);
            if(password_verify($password,$user['password'])){
                $_SESSION[WP.'checklogin'] = true;
                $_SESSION[WP.'email'] = $user['email'];
                $_SESSION[WP.'id'] = $user['id'];
                $_SESSION[WP.'username'] = $user['username'];
                $_SESSION[WP.'password'] = $user['password'];
                $_SESSION['role'] = $user['role'];
                    
                if (!empty($_POST['remember'])) {   
                    setcookie('email', $user['email'], time() + (30 * 24 * 60 * 60));
                    setcookie('password', $password , time() + (30 * 24 * 60 * 60));
                } else{
                    if (isset($_COOKIE['email'])){
                        setcookie('email','');
                        if (isset($_COOKIE['password'])){
                            setcookie('password','');
                        }
                    }
                }
                    if ($user['role'] == 'admin') {
                        header("location: {$base_url}/admin/index-admin.php"); // ไปหน้า admin
                    } else {
                        header("location:{$base_url}/templates/index.php");
                    }
            }else{
                $_SESSION['message'] = 'User or password invalid';
                header("location:{$base_url}/templates/index.php");
            }
        }else{
                $_SESSION['message'] = 'Username not found';
                header("location:{$base_url}/templates/index.php");;
        }
    }else{
        $_SESSION['message'] = 'User or password required';
        header("location:{$base_url}/templates/index.php");
    }
