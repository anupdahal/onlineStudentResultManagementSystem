<?php
include '../includes/config.php';
session_start();

if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$teacher_id = $_SESSION['teacher_id'];
$students = $conn->query("SELECT id, name, email, phone FROM students WHERE status='approved' ORDER BY name");

$message = '';
if (isset($_POST['send'])) {
    $subject = $_POST['subject'];
    $body = $_POST['message'];
    $recipients = $_POST['student_ids'] ?? [];

    if (empty($recipients)) {
        $message = "⚠️ Please select at least one student.";
    } elseif (empty($subject) || empty($body)) {
        $message = "⚠️ Subject and message are required.";
    } else {
        foreach ($recipients as $student_id) {
            $conn->query("INSERT INTO notices (target_type, target_id, subject, message, sender_teacher_id)
                          VALUES ('student', '$student_id', '$subject', '$body', '$teacher_id')");
        }
        $message = "✅ Notice sent to selected student(s).";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Notice to Students</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0f3460, #16213e);
            padding-top: 80px;
            color: white;
            min-height: 100vh;
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: #0f3460;
            padding: 15px 30px;
            z-index: 999;
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
            max-width: 800px;
            margin: auto;
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.4);
            backdrop-filter: blur(10px);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #80ffdb;
        }

        label {
            font-weight: bold;
            margin-top: 15px;
            display: block;
            color: #80ffdb;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 8px;
            border: none;
            background: rgba(255,255,255,0.12);
            color: #e0e0e0;
            font-size: 15px;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            background: rgba(255,255,255,0.2);
            box-shadow: 0 0 5px #3282b8;
        }

        #studentList {
            height: 150px;
        }

        .buttons {
            margin: 10px 0;
        }

        .buttons button {
            padding: 8px 14px;
            border: none;
            background-color: #0077b6;
            color: white;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            margin-right: 10px;
        }

        .buttons button:hover {
            background-color: #023e8a;
        }

        button[type="submit"] {
            margin-top: 20px;
            background: #198754;
            width: 100%;
            font-size: 16px;
            padding: 12px;
            transition: background 0.3s ease;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
        }

        button[type="submit"]:hover {
            background: #146c43;
        }

        .message {
            margin-top: 10px;
            padding: 10px;
            border-radius: 6px;
            background-color: #d1e7dd;
            color: #0f5132;
            text-align: center;
            font-weight: bold;
        }

        .error {
            background-color: #f8d7da;
            color: #842029;
        }

        a.back-link {
            display: inline-block;
            margin-top: 20px;
            color: #80ffdb;
            text-decoration: none;
            text-align: center;
            display: block;
        }

        a.back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            .navbar ul {
                flex-direction: column;
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
        <li>    <a href="student-list.php">View Students</a></li>
        <!-- <li>    <a href="send-notice.php">Send Notice</a></li> -->
        <li>    <a href="add-marks.php">Add Marks</a></li>
        <li>        <a href="sent-history.php">Notices & Results History</a></li>
<li>             <a href="dashboard.php">← Back to Teacher's Dashboard</a></li>
    </ul>
</nav>

<div class="container">
    <h2>📩 Send Notice to Students</h2>

    <?php if ($message): ?>
        <div class="<?= str_contains($message, '⚠️') ? 'message error' : 'message' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <label for="searchBox">🔍 Search Students</label>
        <input type="text" id="searchBox" placeholder="Search students..." onkeyup="filterList()">

        <div class="buttons">
            <button type="button" onclick="selectAll()">Select All</button>
            <button type="button" onclick="deselectAll()">Deselect All</button>
        </div>

        <label>👥 Select Students</label>
        <select multiple name="student_ids[]" id="studentList">
            <?php while ($s = $students->fetch_assoc()): ?>
                <option value="<?= $s['id'] ?>">
                    <?= htmlspecialchars($s['name']) ?> - <?= $s['email'] ?> - <?= $s['phone'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>📌 Subject</label>
        <input type="text" name="subject" required>

        <label>📝 Message</label>
        <textarea name="message" rows="4" required></textarea>

        <button type="submit" name="send">Send Notice</button>
    </form>

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

<script>
function filterList() {
    var input = document.getElementById("searchBox").value.toLowerCase();
    var options = document.getElementById("studentList").options;
    for (var i = 0; i < options.length; i++) {
        var text = options[i].text.toLowerCase();
        options[i].style.display = text.includes(input) ? "" : "none";
    }
}

function selectAll() {
    var options = document.getElementById("studentList").options;
    for (var i = 0; i < options.length; i++) {
        if (options[i].style.display !== "none") {
            options[i].selected = true;
        }
    }
}

function deselectAll() {
    var options = document.getElementById("studentList").options;
    for (var i = 0; i < options.length; i++) {
        options[i].selected = false;
    }
}
</script>

</body>
</html>
