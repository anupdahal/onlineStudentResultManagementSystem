<?php
include '../includes/config.php';
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$students = $conn->query("SELECT * FROM students WHERE status='approved'");

?>

<h2>Approved Students List</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Name</th><th>Email</th><th>Phone</th><th>Parent Phone</th><th>Address</th>
    </tr>
    <?php while ($student = $students->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($student['name']) ?></td>
            <td><?= htmlspecialchars($student['email']) ?></td>
            <td><?= htmlspecialchars($student['phone']) ?></td>
            <td><?= htmlspecialchars($student['parent_phone']) ?></td>
            <td><?= htmlspecialchars($student['address']) ?></td>
        </tr>
    <?php } ?>
</table>
