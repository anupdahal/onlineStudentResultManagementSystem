<?php include 'includes/config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Result Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: linear-gradient(135deg, #0f3460, #16213e);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            min-height: 100vh;
            padding-top: 80px;
        }

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

        .container {
            text-align: center;
            padding: 60px 20px;
        }

        h2 {
            font-size: 32px;
            margin-bottom: 30px;
            color: #f1f1f1;
        }

        .nav-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .nav-links a {
            background: linear-gradient(to right, #1a508b, #3282b8);
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            border-radius: 10px;
            font-weight: bold;
            transition: 0.3s ease;
        }

        .nav-links a:hover {
            background: linear-gradient(to right, #3282b8, #1a508b);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 600px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar ul {
                flex-direction: column;
                margin-top: 10px;
            }

            .container {
                padding: 40px 20px;
            }

            h2 {
                font-size: 24px;
            }

            .nav-links {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>

    <!-- ✅ Sticky Top Navbar -->
    <nav class="navbar">
        <div class="logo">📚 MySchool</div>
        <ul>
            <li><a href="index.php">🏠 Home</a></li>
            <li><a href="register.php">📝 Register</a></li>
            <li><a href="student/login.php">👨‍🎓 Student Login</a></li>
            <li><a href="teacher/login.php">👩‍🏫 Teacher Login</a></li>
            <li><a href="admin/login.php">🛠️ Admin Login</a></li>
        </ul>
    </nav>

    <!-- 🏠 Original Index Layout -->
    <div class="container">
        <h2>Result Management System</h2>
        <div class="nav-links">
            <a href="register.php">Student Registration</a>
            <a href="login.php">Student Login</a>
            <a href="admin/login.php">Admin Login</a>
            <a href="teacher/login.php">Teacher Login</a>
        </div>
    </div>

</body>
</html>
