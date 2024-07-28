<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cook Fitness - Login/Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            overflow-x: hidden;
        }
        .login-container {
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        .login-form, .signup-form {
            background-color: #f8f9fa;
            padding: 2rem;
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100%;
            transition: transform 0.5s ease-in-out;
        }
        .signup-form {
            left: 100%;
        }
        .image-container {
            background-image: url('images/home_img.png');
            background-size: cover;
            background-position: center;
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            transition: transform 0.5s ease-in-out;
        }
        .slide-left .login-form {
            transform: translateX(-100%);
        }
        .slide-left .signup-form {
            transform: translateX(-100%);
        }
        .slide-left .image-container {
            transform: translateX(-100%);
        }
    </style>
</head>
<body>
    <?php
if (isset($_GET['error'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
}
if (isset($_GET['success'])) {
    echo '<div class="alert alert-success">Registration successful. Please log in.</div>';
}
?>
    <div class="container-fluid login-container">
        <div class="login-form d-flex align-items-center justify-content-center">
            <form class="w-75" action="login.php" method="post">
                <h2 class="mb-4">COOK FITNESS</h2>
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary">Sign In</button>
                <div class="mt-3">
                    <a href="#" class="text-muted">Forgot Password?</a>
                </div>
                <div class="mt-3">
                    <a href="#" id="signupLink">Don't have an account? Sign Up</a>
                </div>
            </form>
        </div>
        <div class="signup-form d-flex align-items-center justify-content-center">
            <form class="w-75" action="register.php" method="post">
                <h2 class="mb-4">COOK FITNESS - Sign Up</h2>
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="btn btn-primary">Sign Up</button>
                <div class="mt-3">
                    <a href="#" id="loginLink">Already have an account? Sign In</a>
                </div>
            </form>
        </div>
        <div class="image-container"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('signupLink').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('.login-container').classList.add('slide-left');
        });

        document.getElementById('loginLink').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('.login-container').classList.remove('slide-left');
        });
    </script>
</body>
</html>