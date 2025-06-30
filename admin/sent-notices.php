<?php
include '../includes/config.php';
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Fetch all notices sent by admin (assuming sender_teacher_id is NULL for admin)
$notices = $conn->query("
    SELECT n.*, 
           CASE n.target_type
               WHEN 'student' THEN (SELECT name FROM students WHERE id = n.target_id)
               WHEN 'teacher' THEN (SELECT name FROM teachers WHERE id = n.target_id)
               ELSE 'Unknown'
           END AS recipient_name
    FROM notices n
    WHERE sender_teacher_id IS NULL
    ORDER BY timestamp DESC
");

if (!$notices) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Sent Notices</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: linear-gradient(135deg, #0f3460, #16213e);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            padding: 30px;
        }

        h2 {
            text-align: center;
            font-size: 26px;
            margin-bottom: 20px;
            color: #f1f1f1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(8px);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            background-color: rgba(255, 255, 255, 0.1);
            color: #80ffdb;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.08);
        }

        td {
            color: #ddd;
            font-size: 15px;
        }

        a {
            display: block;
            margin: 20px auto;
            text-align: center;
            color: #80ffdb;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
        }

        a:hover {
            color: #ffd166;
        }

        @media (max-width: 600px) {
            table, th, td {
                font-size: 13px;
            }
        }

        .no-notice {
            text-align: center;
            margin-top: 40px;
            font-size: 18px;
            color: #ccc;
        }
    </style>
</head>
<body>
<a href="dashboard.php" style="position: absolute; top: 20px; left: 20px; background-color: #3282b8; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.3); transition: 0.3s;">
    ← Back to Dashboard
</a>

<h2>All Notices Sent by Admin</h2>

<?php if ($notices->num_rows > 0): ?>
<table>
    <tr>
        <th>Recipient</th>
        <th>Type</th>
        <th>Subject</th>
        <th>Message</th>
        <th>Time</th>
    </tr>
    <?php while ($n = $notices->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($n['recipient_name']) ?></td>
            <td><?= htmlspecialchars(ucfirst($n['target_type'])) ?></td>
            <td><?= htmlspecialchars($n['subject']) ?></td>
            <td><?= htmlspecialchars($n['message']) ?></td>
            <td><?= date("Y-m-d h:i A", strtotime($n['timestamp'])) ?></td>
        </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
    <p class="no-notice">No notices sent yet.</p>
<?php endif; ?>

</body>
</html>
