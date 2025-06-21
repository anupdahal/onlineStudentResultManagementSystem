<?php
include '../includes/config.php';
session_start();

if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch results entered by this teacher
$sql = "
SELECT 
    r.*, 
    s.name as student_name, 
    sub.subject_name, 
    c.class_name as class_name
FROM results r
JOIN students s ON r.student_id = s.id
JOIN subjects sub ON r.subject_id = sub.id
JOIN classes c ON sub.class_id = c.id
WHERE r.added_by_teacher_id = '$teacher_id'
ORDER BY r.timestamp DESC
";

$results = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Results You Submitted</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 20px;
            color: #1e293b;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        }

        h2 {
            color: #2563eb;
            text-align: center;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th, td {
            border: 1px solid #cbd5e1;
            padding: 10px 14px;
            text-align: left;
            font-size: 15px;
        }

        th {
            background-color: #f1f5f9;
            color: #1e293b;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        a {
            display: inline-block;
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

        @media (max-width: 768px) {
            table, th, td {
                font-size: 13px;
            }

            .container {
                padding: 20px;
            }

            h2 {
                font-size: 20px;
            }

            a {
                font-size: 14px;
                padding: 8px 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📊 Results You've Submitted</h2>

    <?php if ($results->num_rows > 0): ?>
        <table>
            <tr>
                <th>Student</th>
                <th>Class</th>
                <th>Subject</th>
                <th>Theory Marks</th>
                <th>Practical Marks</th>
                <th>Submitted On</th>
            </tr>
            <?php while ($r = $results->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($r['student_name']) ?></td>
                    <td><?= htmlspecialchars($r['class_name']) ?></td>
                    <td><?= htmlspecialchars($r['subject_name']) ?></td>
                    <td><?= htmlspecialchars($r['theory_marks']) ?></td>
                    <td><?= htmlspecialchars($r['practical_marks']) ?></td>
                    <td><?= htmlspecialchars($r['timestamp']) ?></td>
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
