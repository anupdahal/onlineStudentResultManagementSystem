<?php
include '../includes/config.php';
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$students = $conn->query("SELECT * FROM students WHERE status='approved'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approved Students List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 20px;
            color: #1e293b;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        }

        h2 {
            color: #2563eb;
            text-align: center;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            border: 1px solid #cbd5e1;
            padding: 10px 14px;
            text-align: left;
            font-size: 15px;
            vertical-align: middle;
        }

        th {
            background-color: #f1f5f9;
            color: #1e293b;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Student photo thumbnail */
        .student-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 1px solid #ddd;
        }

        a {
            display: inline-block;
            text-decoration: none;
            padding: 10px 16px;
            background-color: #2563eb;
            color: white;
            border-radius: 6px;
            transition: background 0.3s ease;
        }

        a:hover {
            background-color: #1d4ed8;
        }

        @media (max-width: 768px) {
            table, th, td {
                font-size: 13px;
            }

            .container {
                padding: 20px;
            }

            h2 {
                font-size: 20px;
            }

            a {
                font-size: 14px;
                padding: 8px 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>✅ Approved Students List</h2>
    <table>
        <tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Parent Phone</th>
            <th>Address</th>
        </tr>
        <?php while ($student = $students->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php if (!empty($student['photo']) && file_exists("../uploads/" . $student['photo'])): ?>
                        <img src="<?= htmlspecialchars('../uploads/' . $student['photo']) ?>" alt="Photo of <?= htmlspecialchars($student['name']) ?>" class="student-photo" />
                    <?php else: ?>
                        <img src="../uploads/default.png" alt="No photo" class="student-photo" />
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($student['name']) ?></td>
                <td><?= htmlspecialchars($student['email']) ?></td>
                <td><?= htmlspecialchars($student['phone']) ?></td>
                <td><?= htmlspecialchars($student['parent_phone']) ?></td>
                <td><?= htmlspecialchars($student['address']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
