<?php
include '../includes/config.php';
session_start();

if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch approved students
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
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 30px;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2f3e46;
        }

        label {
            font-weight: bold;
            margin-top: 15px;
            display: block;
            color: #1b263b;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        textarea {
            resize: vertical;
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
        }

        .error {
            background-color: #f8d7da;
            color: #842029;
        }

        a {
            display: block;
            margin-top: 20px;
            color: #0077b6;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            select, input, textarea {
                font-size: 14px;
            }

            button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Send Notice to Students</h2>

    <?php if ($message): ?>
        <div class="<?= str_contains($message, '⚠️') ? 'message error' : 'message' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <label for="searchBox">Search Students</label>
        <input type="text" id="searchBox" placeholder="Search students..." onkeyup="filterList()">

        <div class="buttons">
            <button type="button" onclick="selectAll()">Select All</button>
            <button type="button" onclick="deselectAll()">Deselect All</button>
        </div>

        <label>Select Students</label>
        <select multiple name="student_ids[]" id="studentList">
            <?php while ($s = $students->fetch_assoc()): ?>
                <option value="<?= $s['id'] ?>">
                    <?= htmlspecialchars($s['name']) ?> - <?= $s['email'] ?> - <?= $s['phone'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Subject</label>
        <input type="text" name="subject" required>

        <label>Message</label>
        <textarea name="message" required></textarea>

        <button type="submit" name="send">Send Notice</button>
    </form>

    <a href="dashboard.php">← Back to Dashboard</a>
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
