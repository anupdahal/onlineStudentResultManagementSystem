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
<html>
<head>
    <title>Teacher Dashboard</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        h2 { margin-bottom: 10px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 8px; }
        th { background-color: #eee; }
    </style>
</head>
<body>

<h2>Welcome, <?= $_SESSION['teacher_name'] ?></h2>

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
    <tr>
        <th>Subject</th>
        <th>Message</th>
        <th>Sent On</th>
    </tr>
    <?php while ($n = $notices->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($n['subject']) ?></td>
        <td><?= htmlspecialchars($n['message']) ?></td>
        <td><?= $n['timestamp'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
    <p>No notices found for you.</p>
<?php endif; ?>

</body>
</html>
