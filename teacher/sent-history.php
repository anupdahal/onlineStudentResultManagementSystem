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
<html>
<head>
    <title>Sent Notices & Results</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2, h3 { margin-bottom: 10px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
        th { background-color: #eee; }
        a { display: inline-block; margin-top: 10px; }
    </style>
</head>
<body>

<h2>Teacher Sent History</h2>

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
                <td><?= htmlspecialchars($n['recipient_name']) ?> (<?= $n['target_type'] ?>)</td>
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
            <th>Theory Marks</th>
            <th>Practical Marks</th>
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

</body>
</html>
