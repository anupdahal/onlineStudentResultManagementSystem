<?php
include '../includes/config.php';
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$teacher_id = $_SESSION['teacher_id'];

// ✅ Count only notices sent by this teacher
$notice_count = $conn->query("
    SELECT COUNT(*) as total FROM notices 
    WHERE sender_teacher_id='$teacher_id'
")->fetch_assoc()['total'];

// ✅ Count only results added by this teacher
$result_count = $conn->query("
    SELECT COUNT(*) as total FROM results 
    WHERE added_by_teacher_id='$teacher_id'
")->fetch_assoc()['total'];
?>

<h2>Notices and Results Sent by You</h2>
<p>Total Notices Sent: <?= $notice_count ?></p>
<p>Total Results Entered: <?= $result_count ?></p>
