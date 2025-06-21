<?php
include '../includes/config.php';
session_start();

// Fetch all approved students with photos and details
$result = $conn->query("SELECT id, name, email, phone, parent_phone, photo, address FROM students WHERE status='approved' ORDER BY name");
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Students</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        img { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
    </style>
</head>
<body>

<h2>All Approved Students</h2>

<table>
    <thead>
        <tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Parent's Phone</th>
            <th>Address</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td>
                <?php if (!empty($row['photo']) && file_exists("../uploads/students/".$row['photo'])): ?>
                    <img src="../uploads/students/<?= htmlspecialchars($row['photo']) ?>" alt="Photo of <?= htmlspecialchars($row['name']) ?>">
                <?php else: ?>
                    <img src="../uploads/default-user.png" alt="No photo">
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['parent_phone']) ?></td>
            <td><?= htmlspecialchars($row['address']) ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<a href="dashboard.php">← Back to Admin Dashboard</a>

</body>
</html>
