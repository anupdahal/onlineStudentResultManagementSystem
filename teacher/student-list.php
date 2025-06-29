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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f3460, #16213e);
            color: white;
            padding-top: 80px;
            min-height: 100vh;
        }

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
        }

        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
            color: #80ffdb;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            gap: 20px;
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

        .container {
            max-width: 1000px;
            margin: auto;
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.4);
        }

        h2 {
            color: #80ffdb;
            text-align: center;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            border: 1px solid rgba(255,255,255,0.1);
            padding: 12px 15px;
            text-align: left;
            font-size: 15px;
        }

        th {
            background-color: rgba(255,255,255,0.1);
            color: #80ffdb;
        }

        tr:nth-child(even) {
            background-color: rgba(255,255,255,0.05);
        }

        tr:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .student-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #3282b8;
        }

        a.back-link {
            display: inline-block;
            text-decoration: none;
            padding: 12px 20px;
            background-color: #1a508b;
            color: white;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            transition: background 0.3s ease;
        }

        a.back-link:hover {
            background-color: #3282b8;
        }

        .link-container {
            text-align: center;
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

            .navbar ul {
                flex-wrap: wrap;
                gap: 10px;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="logo">📚 MySchool</div>
    <ul>
        <li><a href="../index.php">🏠 Home</a></li>
        <li>    <a href="add-subject.php">Add Subjects</a></li>
        <!-- <li>    <a href="student-list.php">View Students</a></li> -->
        <li>    <a href="send-notice.php">Send Notice</a></li>
        <li>    <a href="add-marks.php">Add Marks</a></li>
        <li>        <a href="sent-history.php">Notices & Results History</a></li>
<li>             <a href="dashboard.php">← Back to Teacher's Dashboard</a></li>
    </ul>
</nav>
    </div>
<div class="container">
    <h2>✅ Approved Students List</h2>
    <table>
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Parent Phone</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
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
        </tbody>
    </table>

</div>

</body>
</html>
