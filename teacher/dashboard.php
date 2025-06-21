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
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f3460, #16213e);
            color: #fff;
            margin: 20px;
            min-height: 100vh;
        }
        h2 {
            margin-bottom: 10px;
            color: #80ffdb;
            font-weight: 700;
            font-size: 2rem;
            letter-spacing: 1px;
        }
        ul {
            list-style: none;
            padding: 0;
            margin-bottom: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        ul li {
            display: inline-block;
        }
        ul li a {
            text-decoration: none;
            background-color: rgba(50,130,184,0.9);
            color: #fff;
            padding: 8px 15px;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease;
            user-select: none;
            display: inline-block;
        }
        ul li a:hover {
            background-color: #3282b8;
        }
        h3 {
            color: #80ffdb;
            font-size: 1.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            max-width: 900px;
            background: rgba(255,255,255,0.07);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.4);
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
            max-width: 900px;
        }
        @media (max-width: 700px) {
            ul {
                flex-direction: column;
                gap: 10px;
            }
            table {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<h2>Welcome, <?= htmlspecialchars($_SESSION['teacher_name']) ?></h2>

<ul>
    <li><a href="add-subject.php">Add Subjects</a></li>
    <li><a href="student-list.php">View Students</a></li>
    <li><a href="send-notice.php">Send Notice</a></li>
    <li><a href="add-marks.php">Add Marks</a></li>
    <li><a href="sent-history.php">Notices & Results history</a></li>
    <li><a href="../logout.php">Logout</a></li>
</ul>

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
