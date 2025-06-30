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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0f3460, #16213e);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 80px;
            color: white;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: #0f3460;
            padding: 15px 30px;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
            color: #80ffdb;
            font-weight: 600;
        }

        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        .navbar ul li a {
            text-decoration: none;
            color: #fff;
            padding: 6px 12px;
            border-radius: 6px;
            transition: 0.3s;
            font-weight: 500;
        }

        .navbar ul li a:hover {
            background-color: #3282b8;
        }

        h2 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
            color: #80ffdb;
        }

        table {
            width: 95%;
            margin: auto;
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

        a.back-link {
            display: inline-block;
            margin: 30px auto;
            text-decoration: none;
            padding: 12px 25px;
            background-color: #1a508b;
            color: white;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        a.back-link:hover {
            background-color: #3282b8;
        }

        .link-container {
            text-align: center;
        }

        @media (max-width: 768px) {
            table, th, td {
                font-size: 14px;
            }

            img {
                width: 45px;
                height: 45px;
            }

            .navbar ul {
                gap: 10px;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 480px) {
            table, th, td {
                font-size: 12px;
            }

            body {
                padding-top: 70px;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<a href="dashboard.php" style="position: absolute; top: 20px; left: 20px; background-color: #3282b8; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.3); transition: 0.3s;">
    ← Back to Dashboard
</a>

<h2>✅ Approved Students List</h2>

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

<!-- <div class="link-container">
    <a href="dashboard.php" class="back-link">← Back to Admin Dashboard</a>
</div> -->

</body>
</html>
