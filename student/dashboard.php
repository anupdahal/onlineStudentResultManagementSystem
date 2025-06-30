<?php
include '../includes/config.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['student_name'])) {
    $id = $_SESSION['student_id'];
    $result = $conn->query("SELECT name FROM students WHERE id = '$id'");
    if ($row = $result->fetch_assoc()) {
        $_SESSION['student_name'] = $row['name'];
    }
}

$student_id = $_SESSION['student_id'];

// Fetch Notices
$notices = $conn->query("
    SELECT n.*, 
        CASE 
            WHEN n.sender_teacher_id IS NULL THEN 'Admin'
            ELSE t.name
        END AS sender_name
    FROM notices n
    LEFT JOIN teachers t ON n.sender_teacher_id = t.id
    WHERE n.target_type='student' AND n.target_id='$student_id'
    ORDER BY timestamp DESC
");

// Fetch Results
$results = $conn->query("
    SELECT r.*, sub.subject_name, c.class_name, t.name as teacher_name
    FROM results r
    JOIN subjects sub ON r.subject_id = sub.id
    JOIN classes c ON sub.class_id = c.id
    JOIN teachers t ON r.added_by_teacher_id = t.id
    WHERE r.student_id = '$student_id'
    ORDER BY r.term, r.timestamp DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Student Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        /* Reset & base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f3460, #16213e);
            color: white;
            min-height: 100vh;
            padding-top: 70px; /* space for fixed navbar */
        }

        /* Navbar styles matching other pages */
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
            color: #80ffdb;
            font-weight: 600;
        }
        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
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

        /* Container styling for dashboard content */
        .container {
            max-width: 900px;
            margin: auto;
            background-color: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px 40px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }

        h2 {
            text-align: center;
            color: #80ffdb;
            margin-bottom: 30px;
            font-weight: 700;
            font-size: 2rem;
        }

        h3 {
            color: #a0c4ff;
            font-size: 1.5rem;
            margin-bottom: 15px;
            border-bottom: 2px solid #3282b8;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            font-size: 0.95rem;
            background: rgba(0,0,0,0.2);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid rgba(255,255,255,0.2);
            padding: 12px 15px;
            text-align: left;
            color: #dbeafe;
        }

        th {
            background-color: rgba(50, 130, 184, 0.8);
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }

        tr:hover {
            background-color: #3282b8;
            color: white;
        }

        p {
            font-size: 1.1rem;
            color: #a0aec0;
            text-align: center;
            margin-bottom: 40px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            h2 {
                font-size: 1.6rem;
            }
            h3 {
                font-size: 1.3rem;
            }
            table, th, td {
                font-size: 0.85rem;
            }
            .navbar ul {
                gap: 10px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="logo">Student Pannel</div>
    <ul>
        <li><a href="../index.php">🏠 Home</a></li>
        <li><a href="my-info.php">👤 My Info</a></li>
        <li><a href="marksheet.php">📄 Print Marksheet</a></li>
        <!-- <li><a href="../logout.php">🚪 Logout</a></li> -->
    </ul>
</nav>

<style>
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
    color: #80ffdb;
    font-weight: 600;
}
.navbar .logo {
    font-size: 20px;
    font-weight: bold;
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
body {
    padding-top: 70px; /* to offset fixed navbar height */
}
</style>

<!-- <a href="my-info.php" style="display:inline-block; padding:12px 20px; background:#1a508b; color:white; text-decoration:none; border-radius:8px; margin-top:20px;">
    📄 My Information
</a>
<a href="marksheet.php" style="display:inline-block; margin-top:20px; padding:10px 20px; background:#1a508b; color:#fff; text-decoration:none; border-radius:8px;">
    🖨️ Print Full Marksheet
</a> -->


<div class="container">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['student_name']) ?></h2>

    <h3>📢 Notices for You</h3>
    <?php if ($notices->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Sender</th>
                    <th>Sent On</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($n = $notices->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($n['subject']) ?></td>
                    <td><?= htmlspecialchars($n['message']) ?></td>
                    <td><?= htmlspecialchars($n['sender_name']) ?></td>
                    <td><?= htmlspecialchars($n['timestamp']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No notices found.</p>
    <?php endif; ?>

    <h3>📊 Your Results</h3>
    <?php if ($results->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Theory Marks</th>
                    <th>Practical Marks</th>
                    <th>Term</th>
                    <th>Submitted By</th>
                    <th>Submitted On</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($r = $results->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($r['class_name']) ?></td>
                    <td><?= htmlspecialchars($r['subject_name']) ?></td>
                    <td><?= $r['theory_marks'] ?></td>
                    <td><?= $r['practical_marks'] ?></td>
                    <td><?= htmlspecialchars($r['term']) ?></td>
                    <td><?= htmlspecialchars($r['teacher_name']) ?></td>
                    <td><?= htmlspecialchars($r['timestamp']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No results submitted yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
