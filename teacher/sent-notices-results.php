<?php
include '../includes/config.php';
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$teacher_id = $_SESSION['teacher_id'];

// ✅ Count only notices sent by this teacher
$notice_count = $conn->query("
    SELECT COUNT(*) as total FROM notices 
    WHERE sender_teacher_id='$teacher_id'
")->fetch_assoc()['total'];

// ✅ Count only results added by this teacher
$result_count = $conn->query("
    SELECT COUNT(*) as total FROM results 
    WHERE added_by_teacher_id='$teacher_id'
")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Summary</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 20px;
            color: #1e293b;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        }

        h2 {
            color: #1d4ed8;
            margin-bottom: 20px;
            text-align: center;
        }

        .stat {
            font-size: 18px;
            margin: 15px 0;
            padding: 12px;
            border-left: 4px solid #2563eb;
            background-color: #eff6ff;
            border-radius: 6px;
        }

        a {
            display: inline-block;
            margin-top: 25px;
            text-decoration: none;
            color: white;
            background: #2563eb;
            padding: 10px 20px;
            border-radius: 6px;
            transition: 0.3s;
        }

        a:hover {
            background-color: #1e40af;
        }

        @media (max-width: 600px) {
            .stat {
                font-size: 16px;
                padding: 10px;
            }

            a {
                padding: 8px 16px;
                font-size: 14px;
            }

            h2 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📋 Your Contribution Summary</h2>
    <div class="stat">📢 <strong>Total Notices Sent:</strong> <?= $notice_count ?></div>
    <div class="stat">📊 <strong>Total Results Entered:</strong> <?= $result_count ?></div>

    <a href="dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
