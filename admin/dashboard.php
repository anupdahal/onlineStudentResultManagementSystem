<?php
session_start();
include '../includes/config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$studentCount = $conn->query("SELECT COUNT(*) as total FROM students WHERE status='approved'")->fetch_assoc()['total'];
$teacherCount = $conn->query("SELECT COUNT(*) as total FROM teachers")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        h2, h3 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        a { display: inline-block; margin-top: 10px; }
    </style>
</head>
<body>

<h2>Admin Dashboard</h2>

<p><strong>Total Approved Students:</strong> <?= $studentCount ?></p>
<p><strong>Total Teachers:</strong> <?= $teacherCount ?></p>

<h3>Navigation</h3>
<ul>
    <li><a href="approve-students.php">Approve Students</a></li>
    <li><a href="add-teacher.php">Add Teacher</a></li>
    <li><a href="view-teachers.php">View Teachers</a></li>
    <li><a href="view-students.php">View Students</a></li> <!-- ✅ new link -->
    <li><a href="create-class.php">Create Class</a></li>
    <li><a href="send-notice.php">Send Notice</a></li>
    <li><a href="sent-notices.php">View Sent Notices</a></li>
    <li><a href="../logout.php">Logout</a></li>
</ul>

</body>
</html>
