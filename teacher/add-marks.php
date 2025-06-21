<?php
include '../includes/config.php';
session_start();

if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$teacher_id = $_SESSION['teacher_id'];
$error = '';
$success = '';

// Fetch students with name, email, phone
$students = $conn->query("SELECT id, name, email, phone FROM students WHERE status='approved' ORDER BY name");

// Fetch semesters (classes/faculties)
$semesters = $conn->query("SELECT id, class_name FROM classes ORDER BY class_name");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $theory = $_POST['theory_marks'];
    $practical = $_POST['practical_marks'];
    $term = $_POST['term'];

    $check = $conn->query("SELECT * FROM results WHERE student_id='$student_id' AND subject_id='$subject_id' AND term='$term'");
    if ($check->num_rows > 0) {
        $error = "Marks for this student, subject, and term already exist.";
    } else {
        $stmt = $conn->prepare("INSERT INTO results (student_id, subject_id, theory_marks, practical_marks, term, added_by_teacher_id, timestamp) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiiisi", $student_id, $subject_id, $theory, $practical, $term, $teacher_id);
        if ($stmt->execute()) {
            $success = "Marks submitted successfully for term: " . htmlspecialchars($term);
        } else {
            $error = "Error submitting marks.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Add Marks</title>
<script>
function loadSubjects(semesterId) {
    if (semesterId == "") {
        document.getElementById("subject_id").innerHTML = '<option value="">-- Select Subject --</option>';
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch-subjects.php?semester_id=" + semesterId, true);
    xhr.onload = function() {
        if (this.status == 200) {
            var subjects = JSON.parse(this.responseText);
            var options = '<option value="">-- Select Subject --</option>';
            for (var i = 0; i < subjects.length; i++) {
                options += '<option value="' + subjects[i].id + '">' + subjects[i].subject_name + '</option>';
            }
            document.getElementById("subject_id").innerHTML = options;
        }
    };
    xhr.send();
}

</script>
</head>
<body>

<h2>Add Marks</h2>

<?php if ($error): ?>
    <p style="color: red;"><?= $error ?></p>
<?php elseif ($success): ?>
    <p style="color: green;"><?= $success ?></p>
<?php endif; ?>

<form method="post" action="">

    <label for="student_id">Select Student:</label>
    <select name="student_id" id="student_id" required>
        <option value="">-- Select Student --</option>
        <?php while ($s = $students->fetch_assoc()): ?>
            <option value="<?= $s['id'] ?>">
                <?= htmlspecialchars($s['name']) ?> — <?= htmlspecialchars($s['email']) ?> — <?= htmlspecialchars($s['phone']) ?>
            </option>
        <?php endwhile; ?>
    </select>
    <br><br>

    <label for="semester">Select Semester:</label>
    <select name="semester" id="semester" required onchange="loadSubjects(this.value)">
        <option value="">-- Select Semester --</option>
        <?php while ($sem = $semesters->fetch_assoc()): ?>
            <option value="<?= $sem['id'] ?>"><?= htmlspecialchars($sem['class_name']) ?></option>
        <?php endwhile; ?>
    </select>
    <br><br>

    <label for="subject_id">Select Subject:</label>
    <select name="subject_id" id="subject_id" required>
        <option value="">-- Select Subject --</option>
    </select>
    <br><br>

    <label for="theory_marks">Theory Marks:</label>
    <input type="number" id="theory_marks" name="theory_marks" min="0" required />
    <br><br>

    <label for="practical_marks">Practical Marks:</label>
    <input type="number" id="practical_marks" name="practical_marks" min="0" required />
    <br><br>

    <label for="term">Select Term:</label>
    <select name="term" id="term" required>
        <option value="First Term">First Term</option>
        <option value="Second Term">Second Term</option>
        <option value="Final Term">Final Term</option>
    </select>
    <br><br>

    <button type="submit">Submit Marks</button>
</form>

<br>
<a href="dashboard.php">← Back to Dashboard</a>

</body>
</html>
