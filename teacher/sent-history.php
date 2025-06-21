<?php
include '../includes/config.php';
session_start();

if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch sent notices
$notices = $conn->query("
    SELECT n.*, 
        CASE 
            WHEN n.target_type = 'student' THEN s.name
            WHEN n.target_type = 'teacher' THEN t.name
            ELSE 'Unknown'
        END AS recipient_name
    FROM notices n
    LEFT JOIN students s ON n.target_type = 'student' AND n.target_id = s.id
    LEFT JOIN teachers t ON n.target_type = 'teacher' AND n.target_id = t.id
    WHERE n.sender_teacher_id = '$teacher_id'
    ORDER BY n.timestamp DESC
");

// Fetch sent results
$results = $conn->query("
    SELECT 
        r.*, 
        s.name AS student_name, 
        sub.subject_name, 
        c.class_name
    FROM results r
    JOIN students s ON r.student_id = s.id
    JOIN subjects sub ON r.subject_id = sub.id
    JOIN classes c ON sub.class_id = c.id
    WHERE r.added_by_teacher_id = '$teacher_id'
    ORDER BY r.timestamp DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sent Notices & Results</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 20px;
            color: #1e293b;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            background: #fff;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
        }

        h2 {
            color: #0f172a;
            text-align: center;
            margin-bottom: 25px;
        }

        h3 {
            color: #2563eb;
            margin-top: 30px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
            margin-bottom: 30px;
        }

        th, td {
            border: 1px solid #cbd5e1;
            padding: 10px;
            text-align: left;
            font-size: 15px;
        }

        th {
            background-color: #f1f5f9;
            color: #1e293b;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            padding: 10px 16px;
            background-color: #2563eb;
            color: white;
            border-radius: 6px;
            transition: background 0.3s ease;
        }

        a:hover {
            background-color: #1d4ed8;
        }

        p {
            font-style: italic;
            color: #64748b;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .container {
                padding: 20px;
            }

            th, td {
                font-size: 13.5px;
            }

            a {
                padding: 8px 14px;
                font-size: 14px;
            }

            h2 {
                font-size: 20px;
            }

            h3 {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📘 Teacher Sent History</h2>

    <h3>📢 Notices You Sent</h3>
    <?php if ($notices->num_rows > 0): ?>
        <table>
            <tr>
                <th>To</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Time</th>
            </tr>
            <?php while ($n = $notices->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($n['recipient_name']) ?> (<?= htmlspecialchars($n['target_type']) ?>)</td>
                    <td><?= htmlspecialchars($n['subject']) ?></td>
                    <td><?= htmlspecialchars($n['message']) ?></td>
                    <td><?= $n['timestamp'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No notices sent yet.</p>
    <?php endif; ?>

    <h3>📊 Results You Submitted</h3>
    <?php if ($results->num_rows > 0): ?>
        <table>
            <tr>
                <th>Student</th>
                <th>Class</th>
                <th>Subject</th>
                <th>Theory</th>
                <th>Practical</th>
                <th>Submitted On</th>
            </tr>
            <?php while ($r = $results->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($r['student_name']) ?></td>
                    <td><?= htmlspecialchars($r['class_name']) ?></td>
                    <td><?= htmlspecialchars($r['subject_name']) ?></td>
                    <td><?= $r['theory_marks'] ?></td>
                    <td><?= $r['practical_marks'] ?></td>
                    <td><?= $r['timestamp'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No results submitted yet.</p>
    <?php endif; ?>

    <a href="dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
