<?php
include '../includes/config.php';
session_start();

if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch own notices
$notices = $conn->query("
    SELECT * FROM notices 
    WHERE target_type='teacher' AND target_id='$teacher_id'
    ORDER BY timestamp DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Teacher Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f3460, #16213e);
            color: #fff;
            min-height: 100vh;
            padding-top: 70px;
        }

        /* Navbar */
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

        h2 {
            margin: 30px auto 10px auto;
            text-align: center;
            color: #80ffdb;
            font-weight: 700;
            font-size: 2rem;
            letter-spacing: 1px;
        }

        .functions {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin: 20px 0 40px 0;
        }

        .functions a {
            text-decoration: none;
            background-color: rgba(50,130,184,0.9);
            color: #fff;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .functions a:hover {
            background-color: #3282b8;
        }

        h3 {
            color: #80ffdb;
            font-size: 1.5rem;
            margin-bottom: 10px;
            font-weight: 700;
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 90%;
            max-width: 900px;
            margin: auto;
            background: rgba(255,255,255,0.07);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.4);
            margin-bottom: 40px;
        }

        th, td {
            border: 1px solid rgba(255,255,255,0.2);
            padding: 12px 15px;
            text-align: left;
            color: #e0e0e0;
        }

        th {
            background-color: rgba(50,130,184,0.8);
            font-weight: 700;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: rgba(255,255,255,0.05);
        }

        p {
            font-size: 1.1rem;
            color: #ccc;
            text-align: center;
        }

        @media (max-width: 700px) {
            .navbar ul {
                flex-direction: column;
                gap: 10px;
            }
            .functions {
                flex-direction: column;
                align-items: center;
            }
            table {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="logo">Teacher Pannel</div>
    <ul>
        <li><a href="../index.php">🏠 Home</a></li>
        <li>    <a href="add-subject.php">Add Subjects</a></li>
        <li>    <a href="student-list.php">View Students</a></li>
        <li>    <a href="send-notice.php">Send Notice</a></li>
        <li>    <a href="add-marks.php">Add Marks</a></li>
        <li>        <a href="sent-history.php">Notices & Results History</a></li>
        <!-- <li>        <a href="sent-history.php">Notices & Results History</a></li> -->
    </ul>
</nav>

<!-- Teacher welcome -->
<h2>Welcome, <?= htmlspecialchars($_SESSION['teacher_name']) ?></h2>

<!-- Functional links -->
<!-- <div class="functions">
    <a href="add-subject.php">Add Subjects</a>
    <a href="student-list.php">View Students</a>
    <a href="send-notice.php">Send Notice</a>
    <a href="add-marks.php">Add Marks</a>
    <a href="sent-history.php">Notices & Results History</a>
    <a href="../logout.php">Logout</a>
</div> -->

<!-- Notices section -->
<h3>Your Notices</h3>

<?php if ($notices->num_rows > 0): ?>
<table>
    <thead>
        <tr>
            <th>Subject</th>
            <th>Message</th>
            <th>Sent On</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($n = $notices->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($n['subject']) ?></td>
            <td><?= htmlspecialchars($n['message']) ?></td>
            <td><?= htmlspecialchars($n['timestamp']) ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
    <p>No notices found for you.</p>
<?php endif; ?>

</body>
</html>
