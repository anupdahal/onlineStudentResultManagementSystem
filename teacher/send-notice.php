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
<html>
<head>
    <title>Send Notice to Students</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .message { margin: 10px 0; color: green; }
        .error { color: red; }
        label { font-weight: bold; display: block; margin-top: 15px; }
        select { width: 100%; height: 150px; }
        textarea { width: 100%; height: 100px; }
        .buttons button { margin-right: 10px; margin-top: 5px; }
    </style>
</head>
<body>

<h2>Send Notice to Students</h2>

<?php if ($message): ?>
    <p class="<?= str_contains($message, '⚠️') ? 'error' : 'message' ?>"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="post">
    <label>Select Students</label>
    <input type="text" id="searchBox" placeholder="Search students..." onkeyup="filterList()">
    <br>
    <div class="buttons">
        <button type="button" onclick="selectAll()">Select All</button>
        <button type="button" onclick="deselectAll()">Deselect All</button>
    </div>
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

    <br><br>
    <button type="submit" name="send">Send Notice</button>
</form>

<a href="dashboard.php">← Back to Dashboard</a>

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
