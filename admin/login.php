<?php include '../includes/config.php'; session_start(); ?>
<?php
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Static admin login (can be made dynamic)
    if ($email == "admin@gmail.com" && $password == "admin123") {
        $_SESSION['admin'] = $email;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "❌ Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: linear-gradient(135deg, #0f3460, #16213e);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            min-height: 100vh;
            padding-top: 80px; /* space for navbar */
        }

        /* Navbar styles */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: #0f3460;
            padding: 15px 30px;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        }

        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
            color: #80ffdb;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .navbar ul li a {
            text-decoration: none;
            color: #fff;
            padding: 6px 12px;
            border-radius: 6px;
            transition: 0.3s;
            font-weight: 500;
        }

        .navbar ul li a:hover {
            background-color: #3282b8;
        }

        /* Form box styles */
        .form-box {
            background-color: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.4);
            width: 90%;
            max-width: 400px;
            margin: auto;
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to   { opacity: 1; transform: scale(1); }
        }

        h2 {
            font-size: 28px;
            margin-bottom: 25px;
            color: #f8f8f8;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border: none;
            border-radius: 8px;
            outline: none;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 16px;
            transition: 0.3s;
        }

        input::placeholder {
            color: #ccc;
        }

        input:focus {
            background-color: rgba(255, 255, 255, 0.25);
            box-shadow: 0 0 10px #3282b8;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background: linear-gradient(to right, #1a508b, #3282b8);
            color: white;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }

        button:hover {
            background: linear-gradient(to right, #3282b8, #1a508b);
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
        }

        .error {
            color: #ff6363;
            margin-top: 15px;
            font-weight: bold;
        }

        @media (max-width: 500px) {
            h2 { font-size: 22px; }
            button { font-size: 16px; }
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }
            .navbar ul {
                flex-direction: column;
                gap: 10px;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="logo">📚 MySchool</div>
    <ul>
        <li><a href="../index.php">🏠 Home</a></li>
        <li><a href="../register.php">📝 Student Register</a></li>
        <li><a href="../login.php">👨‍🎓 Student Login</a></li>
        <li><a href="../teacher/login.php">👩‍🏫 Teacher Login</a></li>
        <li><a href="login.php">🛠️ Admin Login</a></li>
    </ul>
</nav>

<!-- Login form -->
<form method="post" class="form-box">
    <h2>Admin Login</h2>
    <input type="email" name="email" placeholder="Admin Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button name="login">Login</button>

    <?php if (isset($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
</form>

</body>
</html>
