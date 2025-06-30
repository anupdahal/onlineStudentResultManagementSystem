<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Result Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0f3460, #16213e);
            color: #f1f1f1;
        }

        /* Navbar */
        .navbar {
            background-color: #0f3460;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 25px;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .navbar h1 {
            margin: 0;
            font-size: 24px;
            color: #80ffdb;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 18px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #80ffdb;
        }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 60px 20px;
        }

        .hero h2 {
            font-size: 36px;
            color: #80ffdb;
            margin-bottom: 15px;
        }

        .hero p {
            font-size: 18px;
            max-width: 800px;
            margin: auto;
            line-height: 1.6;
        }

        /* Features */
        .features {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 40px 20px;
            gap: 25px;
        }

        .feature-box {
            background-color: rgba(255,255,255,0.08);
            padding: 25px 20px;
            border-radius: 10px;
            width: 300px;
            backdrop-filter: blur(6px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
            text-align: center;
        }

        .feature-box h3 {
            color: #80ffdb;
            margin-bottom: 10px;
        }

        .feature-box p {
            font-size: 15px;
            line-height: 1.5;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #ccc;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        @media (max-width: 768px) {
            .hero h2 {
                font-size: 28px;
            }

            .feature-box {
                width: 90%;
            }

            .nav-links a {
                margin-left: 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <h1>Model Campus Damak</h1>
    <div class="nav-links">
        <!-- <a href="#">Home</a> -->
        <a href="register.php">Register Student</a>
        <a href="login.php">Student Login</a>
        <a href="teacher/login.php">Teacher Login</a>
        <a href="admin/login.php">Admin Login</a>
    </div>
</div>

<!-- Hero Section -->
<section class="hero">
    <h2>Welcome to Online Result Management System</h2>
    <p>
        Our platform provides a seamless way for students, teachers, and administrators to manage academic performance.
        It is designed to handle student registrations, marks entry, result publication and notices — all from one secure and modern interface.
    </p>
</section>

<!-- Feature Section -->
<section class="features">
    <div class="feature-box">
        <h3>📄 Student Registration</h3>
        <p>Students can apply for registration with details such as name, contact info, and guardian information.</p>
    </div>
    <div class="feature-box">
        <h3>🧑‍🏫 Teacher Portal</h3>
        <p>Teachers can view students, assign marks, and send notices to selected students via their dashboard.</p>
    </div>
    <div class="feature-box">
        <h3>📊 Result Management</h3>
        <p>View term-wise results, grades, and detailed mark sheets with auto calculation for practical/theory marks.</p>
    </div>
    <div class="feature-box">
        <h3>🔐 Secure Login System</h3>
        <p>Admin, teacher, and student logins are fully secure, with restricted access based on user roles.</p>
    </div>
    <div class="feature-box">
        <h3>📢 Notice Board</h3>
        <p>Teachers can notify students directly from the system with instant delivery of important messages.</p>
    </div>
    <div class="feature-box">
        <h3>🛠️ Admin Dashboard</h3>
        <p>Manage users, view student applications, approve logins, create teachers users, add class and add notices.</p>
    </div>
</section>

<!-- Footer -->
<footer>
    &copy; <?= date("Y") ?> Model Campus Damak | Online Result Management System. All rights reserved.
</footer>

</body>
</html>
