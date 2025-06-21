<?php
include '../includes/config.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['student_name'])) {
    $id = $_SESSION['student_id'];
    $result = $conn->query("SELECT name FROM students WHERE id = '$id'");
    if ($row = $result->fetch_assoc()) {
        $_SESSION['student_name'] = $row['name'];
    }
}

$student_id = $_SESSION['student_id'];

// Fetch Notices
$notices = $conn->query("
    SELECT n.*, 
        CASE 
            WHEN n.sender_teacher_id IS NULL THEN 'Admin'
            ELSE t.name
        END AS sender_name
    FROM notices n
    LEFT JOIN teachers t ON n.sender_teacher_id = t.id
    WHERE n.target_type='student' AND n.target_id='$student_id'
    ORDER BY timestamp DESC
");

// Fetch Results
$results = $conn->query("
    SELECT r.*, sub.subject_name, c.class_name, t.name as teacher_name
    FROM results r
    JOIN subjects sub ON r.subject_id = sub.id
    JOIN classes c ON sub.class_id = c.id
    JOIN teachers t ON r.added_by_teacher_id = t.id
    WHERE r.student_id = '$student_id'
    ORDER BY r.term, r.timestamp DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Student Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        /* Reset & base */
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 20px;
            color: #1e293b;
            min-height: 100vh;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
            padding: 30px;
        }

        h2 {
            text-align: center;
            color: #2563eb;
            margin-bottom: 30px;
            font-weight: 700;
            font-size: 2.2rem;
        }

        h3 {
            color: #1e40af;
            font-size: 1.5rem;
            margin-bottom: 15px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            font-size: 0.95rem;
        }

        th, td {
            border: 1px solid #cbd5e1;
            padding: 12px 15px;
            text-align: left;
            color: #334155;
        }

        th {
            background-color: #e0e7ff;
            color: #1e3a8a;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tr:hover {
            background-color: #dbeafe;
        }

        p {
            font-size: 1.1rem;
            color: #64748b;
            text-align: center;
            margin-bottom: 40px;
        }

        a.logout-btn {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
        }

        a.logout-btn:hover {
            background-color: #1e40af;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            h2 {
                font-size: 1.8rem;
            }
            h3 {
                font-size: 1.3rem;
            }
            table, th, td {
                font-size: 0.85rem;
            }
            a.logout-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }

    </style>
</head>
<body>
    <a href="../logout.php" class="logout-btn">Logout</a>
<div class="container">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['student_name']) ?></h2>

    <h3>📢 Notices for You</h3>
    <?php if ($notices->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Sender</th>
                    <th>Sent On</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($n = $notices->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($n['subject']) ?></td>
                    <td><?= htmlspecialchars($n['message']) ?></td>
                    <td><?= htmlspecialchars($n['sender_name']) ?></td>
                    <td><?= htmlspecialchars($n['timestamp']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No notices found.</p>
    <?php endif; ?>

    <h3>📊 Your Results</h3>
    <?php if ($results->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Theory Marks</th>
                    <th>Practical Marks</th>
                    <th>Term</th>
                    <th>Submitted By</th>
                    <th>Submitted On</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($r = $results->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($r['class_name']) ?></td>
                    <td><?= htmlspecialchars($r['subject_name']) ?></td>
                    <td><?= $r['theory_marks'] ?></td>
                    <td><?= $r['practical_marks'] ?></td>
                    <td><?= htmlspecialchars($r['term']) ?></td>
                    <td><?= htmlspecialchars($r['teacher_name']) ?></td>
                    <td><?= htmlspecialchars($r['timestamp']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No results submitted yet.</p>
    <?php endif; ?>

    <!-- <a href="../logout.php" class="logout-btn">Logout</a> -->
</div>

</body>
</html>
