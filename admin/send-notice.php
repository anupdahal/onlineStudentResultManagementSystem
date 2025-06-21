<?php
include '../includes/config.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Fetch approved students and all teachers
$students = $conn->query("SELECT id, name, phone, email FROM students WHERE status='approved' ORDER BY name");
$teachers = $conn->query("SELECT id, name, phone, email FROM teachers ORDER BY name");

$message = '';
if (isset($_POST['send'])) {
    $target_type = $_POST['target_type'];
    $subject = $_POST['subject'];
    $message_body = $_POST['message'];

    // Recipients come as array of IDs
    $recipient_ids = $_POST['recipient_ids'] ?? [];

    if (empty($recipient_ids)) {
        $message = "Please select at least one recipient.";
    } else if (empty($subject) || empty($message_body)) {
        $message = "Subject and message are required.";
    } else {
        foreach ($recipient_ids as $recipient_id) {
            $conn->query("INSERT INTO notices (target_type, target_id, subject, message, sender_teacher_id) 
                          VALUES ('$target_type', '$recipient_id', '$subject', '$message_body', NULL)"); // NULL means admin sent
        }
        $message = "Notice sent to selected recipients.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Send Notice</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .container { max-width: 700px; margin: auto; }
        select { width: 100%; height: 150px; }
        label { font-weight: bold; margin-top: 15px; display: block; }
        button { margin-top: 10px; }
        .info { font-size: 0.9em; color: #555; }
        .message { color: green; margin-bottom: 20px; }
        .error { color: red; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container">

<h2>Send Notice</h2>

<?php if ($message): ?>
    <p class="<?= strpos($message, 'sent') !== false ? 'message' : 'error' ?>"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="post" id="noticeForm">
    <label for="target_type">Select Recipient Type</label>
    <select id="target_type" name="target_type" required onchange="toggleRecipientList()">
        <option value="">--Select--</option>
        <option value="student">Students</option>
        <option value="teacher">Teachers</option>
    </select>

    <div id="student_list" style="display:none;">
        <label>Select Students:</label>
        <button type="button" onclick="selectAll('student_select')">Select All Students</button>
        <button type="button" onclick="deselectAll('student_select')">Deselect All</button>
        <br>
        <input type="text" id="studentSearch" placeholder="Search students..." onkeyup="filterOptions('student_select', 'studentSearch')">
        <select id="student_select" name="recipient_ids[]" multiple size="10">
            <?php while ($s = $students->fetch_assoc()): ?>
                <option value="<?= $s['id'] ?>">
                    <?= htmlspecialchars($s['name']) ?> - <?= htmlspecialchars($s['phone']) ?> - <?= htmlspecialchars($s['email']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div id="teacher_list" style="display:none;">
        <label>Select Teachers (Ctrl/Cmd + click to select multiple):</label>
        <button type="button" onclick="selectAll('teacher_select')">Select All Teachers</button>
        <button type="button" onclick="deselectAll('teacher_select')">Deselect All</button>
        <br>
        <input type="text" id="teacherSearch" placeholder="Search teachers..." onkeyup="filterOptions('teacher_select', 'teacherSearch')">
        <select id="teacher_select" name="recipient_ids[]" multiple size="10">
            <?php while ($t = $teachers->fetch_assoc()): ?>
                <option value="<?= $t['id'] ?>">
                    <?= htmlspecialchars($t['name']) ?> - <?= htmlspecialchars($t['phone']) ?> - <?= htmlspecialchars($t['email']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <label for="subject">Subject</label>
    <input type="text" name="subject" id="subject" required placeholder="Enter notice subject">

    <label for="message">Message</label>
    <textarea name="message" id="message" rows="5" required placeholder="Enter notice message"></textarea>

    <br><button type="submit" name="send">Send Notice</button>
</form>

</div>

<script>
// Show relevant list based on recipient type
function toggleRecipientList() {
    var type = document.getElementById('target_type').value;
    document.getElementById('student_list').style.display = (type === 'student') ? 'block' : 'none';
    document.getElementById('teacher_list').style.display = (type === 'teacher') ? 'block' : 'none';
}

// Select all options in a multiple select
function selectAll(selectId) {
    var select = document.getElementById(selectId);
    for (var i=0; i < select.options.length; i++) {
        select.options[i].selected = true;
    }
}

// Deselect all options
function deselectAll(selectId) {
    var select = document.getElementById(selectId);
    for (var i=0; i < select.options.length; i++) {
        select.options[i].selected = false;
    }
}

// Filter options in select based on search input
function filterOptions(selectId, inputId) {
    var input = document.getElementById(inputId);
    var filter = input.value.toLowerCase();
    var select = document.getElementById(selectId);

    for (var i=0; i < select.options.length; i++) {
        var option = select.options[i];
        var text = option.text.toLowerCase();
        option.style.display = text.includes(filter) ? "" : "none";
    }
}
</script>

</body>
</html>
