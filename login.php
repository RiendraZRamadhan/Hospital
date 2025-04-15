<?php
session_start();

// Predefined users (in a real application, this would be in a database)
$users = [
    'admin@hospital.com' => ['password' => 'admin123', 'role' => 'admin', 'name' => 'Admin User'],
    'doctor@hospital.com' => ['password' => 'doctor123', 'role' => 'doctor', 'name' => 'Dr. Ahmad Wijaya'],
    'patient@hospital.com' => ['password' => 'patient123', 'role' => 'patient', 'name' => 'Budi Santoso']
];

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Check if user is already logged in
if (isset($_SESSION['user_role'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else if (array_key_exists($email, $users) && $users[$email]['password'] === $password) {
        // Successful login
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = $users[$email]['role'];
        $_SESSION['user_name'] = $users[$email]['name'];
        
        header("Location: dashboard.php");
        exit();
    } else {
        $error = 'Invalid email or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - WCHospital</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding-top: 7rem;
        }
        .login-form {
            width: 100%;
            max-width: 40rem;
            padding: 3rem;
            background: #fff;
            border-radius: .5rem;
            box-shadow: var(--box-shadow);
            border: var(--border);
        }
        .login-form h3 {
            font-size: 3rem;
            color: var(--black);
            margin-bottom: 2rem;
            text-align: center;
        }
        .login-form .box {
            width: 100%;
            margin: 1rem 0;
            border: var(--border);
            padding: 1.2rem;
            font-size: 1.6rem;
            color: var(--black);
            border-radius: .5rem;
            text-transform: none;
        }
        .login-form .btn {
            margin-top: 2rem;
            display: block;
            width: 100%;
            text-align: center;
            padding: 1.2rem;
        }
        .login-form .error-message {
            color: #e74c3c;
            margin-bottom: 1.5rem;
            font-size: 1.6rem;
            text-align: center;
        }
        .demo-credentials {
            margin-top: 2rem;
            background: #f5f5f5;
            padding: 1.5rem;
            border-radius: .5rem;
        }
        .demo-credentials h4 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }
        .demo-credentials p {
            font-size: 1.4rem;
            margin-bottom: .5rem;
        }
    </style>
</head>
<body>

<!-- header section starts  -->
<header class="header">
    <a href="index.php" class="logo"> <i class="fas fa-heartbeat"></i> <strong>WC</strong>medical </a>
    <nav class="navbar">
        <a href="index.php#home">home</a>
        <a href="index.php#about">about</a>
        <a href="index.php#services">services</a>
        <a href="index.php#doctors">doctors</a>
        <a href="index.php#appointment">appointment</a>
        <a href="index.php#review">review</a>
        <a href="index.php#blogs">blogs</a>
        <a href="login.php">login</a>
    </nav>
    <div id="menu-btn" class="fas fa-bars"></div>
</header>
<!-- header section ends -->

<div class="login-container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="login-form">
        <h3>Login to WCHospital</h3>
        
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <input type="email" name="email" placeholder="Your Email" class="box" required>
        <input type="password" name="password" placeholder="Your Password" class="box" required>
        <input type="submit" value="Login Now" class="btn">
        
        <div class="demo-credentials">
            <h4>Demo Credentials:</h4>
            <p><strong>Admin:</strong> admin@hospital.com / admin123</p>
            <p><strong>Doctor:</strong> doctor@hospital.com / doctor123</p>
            <p><strong>Patient:</strong> patient@hospital.com / patient123</p>
        </div>
    </form>
</div>

<!-- footer section starts  -->
<section class="footer">
    <div class="credit"> created by <span>Riendra Z.R</span> | all rights reserved </div>
</section>
<!-- footer section ends -->

<script src="js/script.js"></script>
</body>
</html>