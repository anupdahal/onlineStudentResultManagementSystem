<?php include '../includes/config.php'; session_start(); ?>
<?php
if (isset($_GET['approve'])) {
    $id = $_GET['approve'];
    $conn->query("UPDATE students SET status='approved' WHERE id=$id");
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM students WHERE id=$id");
}

$result = $conn->query("SELECT * FROM students WHERE status='pending'");
?>

<h2>Pending Student Approvals</h2>
<table border="1">
    <tr>
        <th>Name</th><th>Email</th><th>Actions</th>
    </tr>
    <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td>
                <a href="?approve=<?= $row['id'] ?>">Approve</a> | 
                <a href="?delete=<?= $row['id'] ?>">Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>
