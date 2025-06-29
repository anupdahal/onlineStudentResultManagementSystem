<?php
include '../includes/config.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit;
}

$student_id = $_SESSION['student_id'];

// Fetch student info
$stmt = $conn->prepare("SELECT name, email, phone, parent_phone, photo, address FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Student not found.");
}
$student = $result->fetch_assoc();

// Fetch results with subject and class info
$stmt2 = $conn->prepare("
    SELECT r.term, r.theory_marks, r.practical_marks,
           sub.subject_name, sub.max_theory, sub.max_practical,
           c.class_name, t.name AS teacher_name, r.timestamp
    FROM results r
    JOIN subjects sub ON r.subject_id = sub.id
    JOIN classes c ON sub.class_id = c.id
    JOIN teachers t ON r.added_by_teacher_id = t.id
    WHERE r.student_id = ?
    ORDER BY r.term, r.timestamp DESC
");
$stmt2->bind_param("i", $student_id);
$stmt2->execute();
$results = $stmt2->get_result();

$total_theory = 0;
$total_practical = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Marksheet - <?= htmlspecialchars($student['name']) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: white;
            color: black;
            padding: 30px;
            max-width: 900px;
            margin: auto;
        }

        h1, h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        .student-info p {
            margin: 5px 0;
            font-size: 1.1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        th, td {
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
            font-size: 1rem;
        }

        th {
            background-color: #0f3460;
            color: white;
        }

        .print-btn {
            display: block;
            width: 160px;
            margin: 0 auto 30px;
            padding: 12px;
            background: #0f3460;
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .print-btn:hover {
            background: #16213e;
        }

        .photo {
            text-align: right;
            margin-bottom: 20px;
        }

        .photo img {
            height: 100px;
            width: auto;
            border-radius: 6px;
        }
    </style>
    <script>
        function printPage() {
            window.print();
        }
    </script>
</head>
<body>

<h1>Model Campus Damak</h1>
<h2>Student Marksheet</h2>

<div class="photo">
    <?php if (!empty($student['photo'])): ?>
        <img src="../uploads/<?= htmlspecialchars($student['photo']) ?>" alt="Photo of student">
    <?php endif; ?>
</div>

<div class="student-info">
    <p><strong>Name:</strong> <?= htmlspecialchars($student['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($student['phone']) ?></p>
    <p><strong>Parent Phone:</strong> <?= htmlspecialchars($student['parent_phone']) ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($student['address']) ?></p>
</div>

<?php if ($results->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Term</th>
                <th>Class</th>
                <th>Subject</th>
                <th>Theory Marks</th>
                <th>Practical Marks</th>
                <th>Teacher</th>
                <th>Submitted On</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $results->fetch_assoc()): 
                $total_theory += $row['theory_marks'];
                $total_practical += $row['practical_marks'];
            ?>
            <tr>
                <td><?= htmlspecialchars($row['term']) ?></td>
                <td><?= htmlspecialchars($row['class_name']) ?></td>
                <td><?= htmlspecialchars($row['subject_name']) ?></td>
                <td><?= $row['theory_marks'] . " / " . $row['max_theory'] ?></td>
                <td><?= $row['practical_marks'] . " / " . $row['max_practical'] ?></td>
                <td><?= htmlspecialchars($row['teacher_name']) ?></td>
                <td><?= htmlspecialchars($row['timestamp']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total</th>
                <th><?= $total_theory ?></th>
                <th><?= $total_practical ?></th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
    </table>
<?php else: ?>
    <p style="text-align:center;">No results found yet.</p>
<?php endif; ?>

<a class="print-btn" onclick="printPage()">🖨️ Print Marksheet</a>

</body>
</html>
