<?php
session_start();
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            border: none;
        }
        
        .login-title {
            color: #333;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            font-size: 1.8rem;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 16px;
            transition: border-color 0.2s;
        }
        
        .form-control:focus {
            border-color: #007bff;
            box-shadow: none;
        }
        
        .form-label {
            color: #555;
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .btn-primary {
            background: #007bff;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 500;
            font-size: 16px;
            transition: background-color 0.2s;
        }
        
        .btn-primary:hover {
            background: #0056b3;
        }
        
        .form-check-label {
            color: #666;
        }
        
        .register-link {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }
        
        .register-link:hover {
            color: #0056b3;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-warning alert-dismissible fade show my-4" role="alert">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['message']); ?>
        </div>
        <?php endif; ?>
        
        <div class="row d-flex align-items-center justify-content-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                <div class="login-card">
                    <h1 class="login-title">Sign In</h1>
                    
                    <form action="<?php echo $base_url.'/login-form.php'; ?>" method="post">
                        <div id="result"></div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" 
                                   value="<?php if (isset($_COOKIE['email'])) {echo $_COOKIE['email'];} ?>" 
                                   name="email" 
                                   class="form-control" 
                                   placeholder="Enter your email"
                                   required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" 
                                   value="<?php if (isset($_COOKIE['password'])) {echo $_COOKIE['password'];} ?>" 
                                   name="password" 
                                   class="form-control" 
                                   placeholder="Enter your password"
                                   required>
                        </div>
                        
                        <button type="submit" name="submit" class="btn btn-primary w-100 mb-3">
                            Sign In
                        </button>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                        
                        <div class="text-center">
                            <a href="<?php echo $base_url.'/register.php'; ?>" class="register-link">
                                Don't have an account? Sign up
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
</body>
</html>