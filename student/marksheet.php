<?php
include '../includes/config.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$selected_class = $_GET['class_id'] ?? '';
$selected_term = $_GET['term'] ?? '';
$results = [];
$total_theory = $total_practical = $total_subjects = 0;
$percentage = $gpa = $grade = '';

// Hardcoded school details (since you don't have a school table)
$school = [
    'name' => 'My School Name',
    'address' => 'Kathmandu, Nepal',
    'phone' => '+977-1-1234567',
];

// Fetch student full details
$student = $conn->query("SELECT * FROM students WHERE id='$student_id'")->fetch_assoc();

$classes = $conn->query("SELECT * FROM classes ORDER BY class_name");

if ($selected_class && $selected_term) {
    $sql = "
        SELECT r.*, s.subject_name, c.class_name
        FROM results r
        JOIN subjects s ON r.subject_id = s.id
        JOIN classes c ON s.class_id = c.id
        WHERE r.student_id = '$student_id'
        AND s.class_id = '$selected_class'
        AND r.term = '$selected_term'
    ";
    $res = $conn->query($sql);

    while ($row = $res->fetch_assoc()) {
        $results[] = $row;
        $total_theory += $row['theory_marks'];
        $total_practical += $row['practical_marks'];
        $total_subjects++;
    }

    if ($total_subjects > 0) {
        $total_marks = $total_theory + $total_practical;
        $percentage = round($total_marks / ($total_subjects * 200) * 100, 2); // max theory + practical = 200 per subject

        if ($percentage >= 90) $grade = "A+";
        elseif ($percentage >= 80) $grade = "A";
        elseif ($percentage >= 70) $grade = "B+";
        elseif ($percentage >= 60) $grade = "B";
        elseif ($percentage >= 50) $grade = "C";
        elseif ($percentage >= 40) $grade = "D";
        else $grade = "F";

        $gpa = round(($percentage / 25), 2); // GPA out of 4
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Marksheet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: linear-gradient(135deg, #0f3460, #16213e);
            font-family: 'Segoe UI', sans-serif;
            color: white;
            padding-top: 70px; /* adjusted for fixed navbar */
            margin: 0;
        }
        /* ✅ Fixed Width & Consistent Navbar */

body {
    padding-top: 70px; /* to offset fixed navbar height */
}


        .container {
            max-width: 900px;
            margin: auto;
            background: rgba(255,255,255,0.05);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.4);
            backdrop-filter: blur(8px);
        }
        h2 {
            text-align: center;
            color: #80ffdb;
            margin-bottom: 30px;
        }
        form {
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }
        select {
            padding: 10px;
            border-radius: 8px;
            border: none;
            font-size: 1rem;
            background: rgba(255,255,255,0.1);
            color: white;
        }
        button {
            background: #1a508b;
            color: white;
            border: none;
            padding: 10px 20px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #3282b8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            color: white;
            font-size: 0.95rem;
        }
        th, td {
            border: 1px solid rgba(255,255,255,0.2);
            padding: 12px;
        }
        th {
            background-color: rgba(50, 130, 184, 0.8);
        }
        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.05);
        }
        .summary {
            margin-top: 25px;
            text-align: left;
            font-size: 1.1rem;
            color: #a0f0ff;
        }
        .print-btn {
            margin-top: 20px;
            text-align: center;
        }

        /* Print Styles */
        @media print {
            body {
                background: white !important;
                color: #000 !important;
                font-family: Georgia, serif;
                font-size: 14pt;
                line-height: 1.4;
            }
            .navbar, .print-btn, form {
                display: none !important;
            }
            .container {
                box-shadow: none !important;
                background: white !important;
                color: black !important;
                padding: 0 !important;
                margin: 0 auto !important;
                max-width: 700px !important;
                border: none !important;
                border-radius: 0 !important;
            }
            h1, h2, h3 {
                color: #1a3e72 !important;
                font-weight: bold;
                margin-bottom: 0.25em;
            }
            table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 12pt !important;
            }
            th, td {
                border: 1px solid #444 !important;
                padding: 8px !important;
                color: #000 !important;
                background: #f9f9f9 !important;
                text-align: left;
            }
            th {
                background-color: #dbe9f4 !important;
                font-weight: 600;
            }
            tr:nth-child(even) {
                background-color: #fff !important;
            }
            .summary {
                margin-top: 20px !important;
                text-align: left !important;
                font-size: 13pt !important;
                color: #222 !important;
            }
            .school-header h1, .school-header p {
                color: #1a3e72 !important;
                text-align: center !important;
                margin: 0 0 4px 0 !important;
            }
            .student-info p {
                color: #000 !important;
                margin: 3px 0 !important;
                font-size: 12pt !important;
            }
            .flex-row {
                /* display: block !important; */
            }
            .info-box {
                background: transparent !important;
                padding: 0 !important;
                margin-bottom: 1em !important;
                border: none !important;
            }
        }

        .school-header, .student-info {
            margin-bottom: 30px;
        }
        .school-header h1, .school-header p {
            margin: 0;
            text-align: center;
        }
        .student-info p {
            margin: 5px 0;
        }
        .flex-row {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .info-box {
            flex: 1 1 45%;
            background: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .navbar {
    position: fixed;
    top: 0;
    width: 100%;
    background-color: #0f3460;
    padding: 15px 10px;
    z-index: 1000;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
    color: #80ffdb;
}

.navbar .logo {
    font-size: 20px;
    font-weight: bold;
    margin-left: 25px;
}

.navbar ul {
    list-style: none;
    display: flex;
    gap: 20px;
    margin: 0;
    margin-right: 25px;
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

    </style>
</head>
<body>

<a href="dashboard.php" style="position: absolute; top: 20px; left: 20px; background-color: #3282b8; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.3); transition: 0.3s;">
    ← Back to Dashboard
</a>



<div class="container">

    <div class="school-header">
        <h1><?= htmlspecialchars($school['name']) ?></h1>
        <p><?= htmlspecialchars($school['address']) ?></p>
        <p>Phone: <?= htmlspecialchars($school['phone']) ?></p>
    </div>

    <h2>📄 Student Marksheet</h2>

    <form method="get">
        <select name="class_id" required>
            <option value="">-- Select Class --</option>
            <?php
            $classes->data_seek(0);
            while ($c = $classes->fetch_assoc()):
            ?>
                <option value="<?= $c['id'] ?>" <?= $selected_class == $c['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['class_name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <select name="term" required>
            <option value="">-- Select Term --</option>
            <option <?= $selected_term == "First Term" ? 'selected' : '' ?>>First Term</option>
            <option <?= $selected_term == "Second Term" ? 'selected' : '' ?>>Second Term</option>
            <option <?= $selected_term == "Final Term" ? 'selected' : '' ?>>Final Term</option>
        </select>

        <button type="submit">Show Marksheet</button>
    </form>

    <?php if ($results): ?>
        <div class="flex-row">
            <div class="info-box">
                <h3>Student Details</h3>
                <p><strong>Name:</strong> <?= htmlspecialchars($student['name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($student['phone'] ?? '-') ?></p>
                <p><strong>Parent Phone:</strong> <?= htmlspecialchars($student['parent_phone'] ?? '-') ?></p>
                <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($student['address'] ?? '-')) ?></p>
            </div>
            <div class="info-box">
                <h3>Class & Term</h3>
                <p><strong>Class:</strong> <?= htmlspecialchars($results[0]['class_name']) ?></p>
                <p><strong>Term:</strong> <?= htmlspecialchars($selected_term) ?></p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Theory</th>
                    <th>Practical</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['subject_name']) ?></td>
                        <td><?= (int)$r['theory_marks'] ?></td>
                        <td><?= (int)$r['practical_marks'] ?></td>
                        <td><?= (int)$r['theory_marks'] + (int)$r['practical_marks'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="summary">
            <strong>Total Marks:</strong> <?= $total_theory + $total_practical ?>,
            <strong>Percentage:</strong> <?= $percentage ?>%,
            <strong>GPA:</strong> <?= $gpa ?> / 4.0,
            <strong>Grade:</strong> <?= $grade ?>
        </div>

        <div class="print-btn">
            <button onclick="window.print()">🖨️ Print Marksheet</button>
        </div>

    <?php elseif ($selected_class && $selected_term): ?>
        <p class="summary">No results found for the selected class and term.</p>
    <?php endif; ?>
</div>

</body>
</html>
