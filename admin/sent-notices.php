<?php
include '../includes/config.php';
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

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
    WHERE sender_teacher_id IS NULL
    ORDER BY timestamp DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Sent Notices</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
    </style>
</head>
<body>

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
            <td><?= htmlspecialchars($n['target_type']) ?></td>
            <td><?= htmlspecialchars($n['subject']) ?></td>
            <td><?= htmlspecialchars($n['message']) ?></td>
            <td><?= $n['timestamp'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
    <p>No notices sent yet.</p>
<?php endif; ?>

<a href="dashboard.php">← Back</a>

</body>
</html>
