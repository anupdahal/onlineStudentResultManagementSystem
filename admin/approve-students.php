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

<!DOCTYPE html>
<html>
<head>
    <title>Approve Students</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: linear-gradient(135deg, #0f3460, #16213e);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            padding: 40px 20px;
            min-height: 100vh;
        }

        h2 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
            color: #f1f1f1;
        }

        table {
            width: 100%;
            max-width: 900px;
            margin: auto;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(5px);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            background-color: rgba(255, 255, 255, 0.1);
            color: #80ffdb;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.08);
        }

        a {
            color: #00ffd5;
            text-decoration: none;
            margin: 0 5px;
            font-weight: bold;
            transition: 0.3s;
        }

        a:hover {
            color: #ffd166;
        }

        @media (max-width: 600px) {
            table, tr, td, th {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h2>Pending Student Approvals</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>
                    <a href="?approve=<?= $row['id'] ?>" onclick="return confirm('Approve this student?')">Approve</a> | 
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this student?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
