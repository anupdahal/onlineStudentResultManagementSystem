<?php
include '../includes/config.php';
session_start();

$result = $conn->query("SELECT id, name, email, phone, photo FROM teachers ORDER BY name");
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Teachers</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        img { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
    </style>
</head>
<body>

<h2>All Teachers</h2>

<table>
    <thead>
        <tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td>
                <?php if (!empty($row['photo']) && file_exists("../uploads/teachers/".$row['photo'])): ?>
                    <img src="../uploads/teachers/<?= htmlspecialchars($row['photo']) ?>" alt="Photo of <?= htmlspecialchars($row['name']) ?>">
                <?php else: ?>
                    <img src="../uploads/default-user.png" alt="No photo">
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<a href="dashboard.php">← Back to Admin Dashboard</a>

</body>
</html>
