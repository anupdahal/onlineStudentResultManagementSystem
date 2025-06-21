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
<html>
<head>
    <title>Student Dashboard</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>

<h2>Welcome, <?= $_SESSION['student_name'] ?></h2>

<h3>📢 Notices for You</h3>
<?php if ($notices->num_rows > 0): ?>
    <table>
        <tr>
            <th>Subject</th>
            <th>Message</th>
            <th>Sender</th>
            <th>Sent On</th>
        </tr>
        <?php while ($n = $notices->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($n['subject']) ?></td>
                <td><?= htmlspecialchars($n['message']) ?></td>
                <td><?= htmlspecialchars($n['sender_name']) ?></td>
                <td><?= $n['timestamp'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No notices found.</p>
<?php endif; ?>

<h3>📊 Your Results</h3>
<?php if ($results->num_rows > 0): ?>
    <table>
        <tr>
            <th>Class</th>
            <th>Subject</th>
            <th>Theory Marks</th>
            <th>Practical Marks</th>
            <th>Submitted By</th>
            <th>Submitted On</th>
        </tr>
        <?php while ($r = $results->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($r['class_name']) ?></td>
                <td><?= htmlspecialchars($r['subject_name']) ?></td>
                <td><?= $r['theory_marks'] ?></td>
                <td><?= $r['practical_marks'] ?></td>
                <td><?= htmlspecialchars($r['teacher_name']) ?></td>
                <td><?= $r['timestamp'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No results submitted yet.</p>
<?php endif; ?>

<a href="../logout.php">Logout</a>

</body>
</html>
