<?php
include '../includes/config.php';
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Fetch all approved students
$result = $conn->query("SELECT id, name, email, phone, parent_phone, address, photo FROM students WHERE status='approved' ORDER BY name");

if (!$result) {
    die("Database query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Approved Students</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: linear-gradient(135deg, #0f3460, #16213e);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px 20px;
            color: white;
        }

        h2 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
            color: #f1f1f1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(255,255,255,0.05);
            backdrop-filter: blur(6px);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        th {
            background-color: rgba(255,255,255,0.1);
            color: #80ffdb;
        }

        tr:hover {
            background-color: rgba(255,255,255,0.08);
        }

        img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #3282b8;
        }

        a {
            display: inline-block;
            margin-top: 25px;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #1a508b;
            color: white;
            border-radius: 6px;
            font-weight: bold;
            transition: 0.3s;
            text-align: center;
        }

        a:hover {
            background-color: #3282b8;
        }

        @media (max-width: 768px) {
            table, th, td {
                font-size: 14px;
            }

            img {
                width: 45px;
                height: 45px;
            }
        }

        @media (max-width: 480px) {
            table, th, td {
                font-size: 12px;
            }

            body {
                padding: 20px 10px;
            }
        }
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
                <?php if (!empty($row['photo']) && file_exists("../uploads/" . $row['photo'])): ?>
                    <img src="../uploads/<?= htmlspecialchars($row['photo']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
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

<div style="text-align:center;">
    <a href="dashboard.php">← Back to Admin Dashboard</a>
</div>

</body>
</html>
