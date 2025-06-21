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
<html>
<head>
    <title>Results Sent by You</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #aaa; padding: 8px; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>

<h2>Results You've Submitted</h2>

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
