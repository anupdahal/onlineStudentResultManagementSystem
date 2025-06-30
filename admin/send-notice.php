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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        /* Body & Container */
        body {
            background: linear-gradient(135deg, #0f3460, #16213e);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            padding: 40px 20px;
            margin: 0;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background-color: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px 40px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        /* Headings */
        h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 25px;
            color: #80ffdb;
            letter-spacing: 1px;
        }

        /* Labels */
        label {
            font-weight: 600;
            margin-top: 20px;
            margin-bottom: 8px;
            display: block;
            color: #80ffdb;
            font-size: 1rem;
        }

        /* Inputs & Selects & Textarea */
        select, input[type="text"], textarea {
            width: 100%;
            padding: 12px 15px;
            border-radius: 10px;
            border: none;
            background-color: rgba(255, 255, 255, 0.12);
            color: #e0e0e0;
            font-size: 1rem;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            box-sizing: border-box;
            font-weight: 400;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        select[multiple] {
            height: 180px;
        }
        input::placeholder,
        textarea::placeholder {
            color: #bbb;
            font-style: italic;
        }
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.25);
            box-shadow: 0 0 10px #3282b8;
            color: white;
        }

        /* Style the multiple select options */
        select option {
            background-color: #16213e;
            color: #e0e0e0;
            padding: 5px;
        }
        select option:hover,
        select option:focus,
        select option:checked {
            background-color: #3282b8;
            color: white;
        }

        /* Buttons */
        button {
            margin-top: 25px;
            background: linear-gradient(to right, #1a508b, #3282b8);
            color: white;
            padding: 14px 25px;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            width: 100%;
            cursor: pointer;
            transition: background 0.3s ease, box-shadow 0.3s ease;
            user-select: none;
        }
        button:hover {
            background: linear-gradient(to right, #3282b8, #1a508b);
            box-shadow: 0 6px 18px rgba(50, 130, 184, 0.7);
        }
        button[type="button"] {
            width: auto;
            padding: 8px 15px;
            margin: 8px 8px 12px 0;
            font-size: 0.85rem;
            background-color: rgba(255, 255, 255, 0.15);
            color: #ddd;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        button[type="button"]:hover {
            background-color: rgba(255, 255, 255, 0.3);
            color: white;
        }

        /* Message & Error */
        .message, .error {
            padding: 14px 20px;
            margin-bottom: 25px;
            text-align: center;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.02em;
        }
        .message {
            background-color: rgba(76, 175, 80, 0.2);
            color: #4aff7a;
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.5);
        }
        .error {
            background-color: rgba(255, 0, 0, 0.1);
            color: #ff6b6b;
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.3);
        }

        /* Search input fields */
        #studentSearch, #teacherSearch {
            margin: 12px 0 12px 0;
            padding: 10px 15px;
            font-size: 1rem;
            background-color: rgba(255,255,255,0.1);
            border-radius: 8px;
            border: none;
            color: #eee;
            font-style: italic;
            transition: background-color 0.3s ease;
            width: 100%;
            box-sizing: border-box;
        }
        #studentSearch::placeholder,
        #teacherSearch::placeholder {
            color: #ccc;
        }
        #studentSearch:focus,
        #teacherSearch:focus {
            background-color: rgba(255,255,255,0.2);
            outline: none;
            box-shadow: 0 0 8px #3282b8;
            color: white;
        }

        /* Responsive */
        @media (max-width: 650px) {
            .container {
                padding: 20px;
            }
            select[multiple] {
                height: 130px;
            }
            button {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
<a href="dashboard.php" style="position: absolute; top: 20px; left: 20px; background-color: #3282b8; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.3); transition: 0.3s;">
    ← Back to Dashboard
</a>

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
