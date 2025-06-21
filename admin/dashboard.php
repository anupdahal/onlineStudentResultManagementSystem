<?php
session_start();
include '../includes/config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Counts for statuses
$studentApprovedCount = $conn->query("SELECT COUNT(*) as total FROM students WHERE status='approved'")->fetch_assoc()['total'];
$studentInProgressCount = $conn->query("SELECT COUNT(*) as total FROM students WHERE status='in_progress'")->fetch_assoc()['total'];
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
            padding: 40px 20px;
            animation: bgShift 20s ease infinite;
        }

        @keyframes bgShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
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

        /* Status summary boxes */
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
            background-color: #22c55e; /* green */
            color: white;
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.7);
        }

        .status-box.in_progress {
            background-color: #facc15; /* yellow */
            color: #1e293b;
            box-shadow: 0 6px 20px rgba(250, 204, 21, 0.7);
        }

        .status-box.teachers {
            background-color: #3b82f6; /* blue */
            color: white;
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.7);
        }

        .status-box:hover {
            filter: brightness(1.1);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
            cursor: default;
        }

        /* Navigation styling */
        ul {
            list-style: none;
            padding-left: 0;
            max-width: 900px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        ul li {
            flex: 1 1 280px;
        }

        ul li a {
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

        ul li a:hover {
            background: linear-gradient(to right, #3282b8, #1a508b);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.5);
            transform: translateX(5px);
        }

        /* Badge styles */
        .badge {
            background: #ef4444; /* red */
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

        /* Different badge colors for variety */
        .badge.approved {
            background: #22c55e;
            color: #fff;
        }

        .badge.in_progress {
            background: #facc15;
            color: #1e293b;
        }

        .badge.teachers {
            background: #3b82f6;
            color: #fff;
        }

        @media (max-width: 650px) {
            .status-summary {
                flex-direction: column;
                align-items: center;
            }

            ul li {
                flex: 1 1 100%;
            }

            ul li a {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
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

        <ul>
            <li><a href="approve-students.php">Approve Students <span class="badge in_progress"><?= $studentInProgressCount ?></span></a></li>
            <li><a href="add-teacher.php">Add Teacher</a></li>
            <li><a href="view-teachers.php">View Teachers <span class="badge teachers"><?= $teacherCount ?></span></a></li>
            <li><a href="view-students.php">View Students <span class="badge approved"><?= $studentApprovedCount ?></span></a></li>
            <li><a href="create-class.php">Create Class</a></li>
            <li><a href="send-notice.php">Send Notice</a></li>
            <li><a href="sent-notices.php">View Sent Notices</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>
</body>
</html>
