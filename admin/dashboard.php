<?php
session_start();
include '../includes/config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Counts for statuses
$studentApprovedCount = $conn->query("SELECT COUNT(*) as total FROM students WHERE status='approved'")->fetch_assoc()['total'];
$studentInProgressCount = $conn->query("SELECT COUNT(*) as total FROM students WHERE status='pending'")->fetch_assoc()['total'];
$teacherCount = $conn->query("SELECT COUNT(*) as total FROM teachers")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Segoe+UI&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0f3460, #16213e);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
            min-height: 100vh;
            padding-top: 90px;
        }

        /* Navigation Bar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #0f3460;
            padding: 15px 30px;
            z-index: 999;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
            color: #80ffdb;
            font-weight: 600;
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
            margin: 0;
            padding: 0;
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

        .dashboard-box {
            background-color: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 20px;
            max-width: 900px;
            margin: auto;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        h2 {
            font-size: 2rem;
            text-align: center;
            margin-bottom: 40px;
            color: #f1f1f1;
            font-weight: 700;
        }

        .status-summary {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 50px;
            flex-wrap: wrap;
        }

        .status-box {
            flex: 1 1 180px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 25px 20px;
            text-align: center;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.3);
            font-weight: 700;
            font-size: 1.4rem;
            user-select: none;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .status-box.approved {
            background-color: #22c55e;
            color: white;
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.7);
        }

        .status-box.in_progress {
            background-color: #facc15;
            color: #1e293b;
            box-shadow: 0 6px 20px rgba(250, 204, 21, 0.7);
        }

        .status-box.teachers {
            background-color: #3b82f6;
            color: white;
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.7);
        }

        .status-box:hover {
            filter: brightness(1.1);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
            cursor: default;
        }

        ul.dashboard-nav {
            list-style: none;
            padding-left: 0;
            max-width: 900px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        ul.dashboard-nav li {
            flex: 1 1 280px;
        }

        ul.dashboard-nav li a {
            text-decoration: none;
            color: white;
            background: linear-gradient(to right, #1a508b, #3282b8);
            padding: 14px 20px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: background 0.3s ease, transform 0.3s ease;
        }

        ul.dashboard-nav li a:hover {
            background: linear-gradient(to right, #3282b8, #1a508b);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.5);
            transform: translateX(5px);
        }

        .badge {
            background: #ef4444;
            color: white;
            font-size: 0.85rem;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 700;
            min-width: 30px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
            user-select: none;
        }

        .badge.approved { background: #22c55e; color: #fff; }
        .badge.in_progress { background: #facc15; color: #1e293b; }
        .badge.teachers { background: #3b82f6; color: #fff; }

        @media (max-width: 650px) {
            .status-summary {
                flex-direction: column;
                align-items: center;
            }

            ul.dashboard-nav li {
                flex: 1 1 100%;
            }

            ul.dashboard-nav li a {
                font-size: 1rem;
            }

            .navbar ul {
                flex-wrap: wrap;
                gap: 10px;
            }
        }
    </style>
</head>
<body>

<!-- ✅ Navigation Bar -->
<nav class="navbar">
    <div class="logo">🛠️ Admin Panel</div>
    <ul>
        <li><a href="../index.php">🏠 Home</a></li>
        <li><a href="approve-students.php">✔️ Approve Students</a></li>
        <li><a href="add-teacher.php">➕ Add Teacher</a></li>
        <li><a href="view-teachers.php">👩‍🏫 View Teachers</a></li>
        <li><a href="view-students.php">📋 View Students</a></li>
        <li><a href="create-class.php">🏫 Create Class</a></li>
        <li><a href="send-notice.php">📨 Send Notice</a></li>
        <li><a href="sent-notices.php">📑 Sent Notices</a></li>
        <!-- <li><a href="../logout.php">🚪 Logout</a></li> -->
    </ul>
</nav>

<!-- ✅ Dashboard Box -->
<div class="dashboard-box">
    <h2>Welcome, Admin</h2>

    <div class="status-summary">
        <div class="status-box approved">
            ✅ Approved Students<br>
            <span><?= $studentApprovedCount ?></span>
        </div>
        <div class="status-box in_progress">
            ⏳ Students In Progress<br>
            <span><?= $studentInProgressCount ?></span>
        </div>
        <div class="status-box teachers">
            👩‍🏫 Total Teachers<br>
            <span><?= $teacherCount ?></span>
        </div>
    </div>

</div>

</body>
</html>
