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

$students = $conn->query("SELECT id, name, email, phone FROM students WHERE status='approved' ORDER BY name");
$semesters = $conn->query("SELECT id, class_name FROM classes ORDER BY class_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add Marks</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(135deg, #0f3460, #16213e);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: white;
      margin: 0;
      padding-top: 70px;
    }


    h2 {
      text-align: center;
      color: #80ffdb;
      font-size: 2rem;
      margin-bottom: 30px;
      letter-spacing: 1px;
    }

    form {
      max-width: 600px;
      margin: 0 auto;
      background: rgba(255,255,255,0.05);
      padding: 30px 40px;
      border-radius: 15px;
      backdrop-filter: blur(10px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.4);
    }

    label {
      display: block;
      font-weight: 600;
      margin-bottom: 8px;
      color: #80ffdb;
      font-size: 1rem;
    }

    select, input[type="number"] {
      width: 100%;
      padding: 12px 15px;
      border-radius: 10px;
      border: none;
      background-color: rgba(255,255,255,0.12);
      color: #e0e0e0;
      font-size: 1rem;
      margin-bottom: 20px;
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    select:focus, input:focus {
      outline: none;
      background-color: rgba(255,255,255,0.25);
      box-shadow: 0 0 10px #3282b8;
      color: white;
    }

    button {
      width: 100%;
      padding: 14px 25px;
      background: linear-gradient(to right, #1a508b, #3282b8);
      border: none;
      border-radius: 10px;
      color: white;
      font-weight: 600;
      font-size: 1.1rem;
      cursor: pointer;
      transition: background 0.3s ease, box-shadow 0.3s ease;
    }

    button:hover {
      background: linear-gradient(to right, #3282b8, #1a508b);
      box-shadow: 0 6px 18px rgba(50,130,184,0.7);
    }

    p[style*="color: red"], p[style*="color: green"] {
      padding: 12px 20px;
      border-radius: 10px;
      font-weight: 600;
      max-width: 600px;
      margin: 0 auto 20px auto;
      text-align: center;
    }

    p[style*="color: red"] {
      background-color: rgba(255,0,0,0.1);
      color: #ff6b6b;
    }

    p[style*="color: green"] {
      background-color: rgba(76,175,80,0.2);
      color: #4aff7a;
    }

    a {
      color: #80ffdb;
      display: block;
      max-width: 600px;
      margin: 25px auto 0 auto;
      text-decoration: none;
      font-weight: 600;
    }

    a:hover {
      text-decoration: underline;
    }

    @media (max-width: 650px) {
      .navbar {
        flex-direction: column;
        align-items: flex-start;
        padding: 10px 20px;
      }

      .navbar ul {
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
      }

      form {
        padding: 20px 25px;
      }
    }
  </style>

  <script>
    function loadSubjects(semesterId) {
      if (semesterId === "") {
        document.getElementById("subject_id").innerHTML = '<option value="">-- Select Subject --</option>';
        return;
      }

      var xhr = new XMLHttpRequest();
      xhr.open("GET", "fetch-subjects.php?semester_id=" + semesterId, true);
      xhr.onload = function () {
        if (this.status === 200) {
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

<a href="dashboard.php" style="position: absolute; top: 20px; left: 20px; background-color: #3282b8; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.3); transition: 0.3s;">
    ← Back to Dashboard
</a>  

<h2>Add Marks</h2>

<?php if ($error): ?>
  <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php elseif ($success): ?>
  <p style="color: green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="post" action="">

  <label for="student_id">Select Student:</label>
  <select name="student_id" id="student_id" required>
    <option value="">-- Select Student --</option>
    <?php if ($students && $students->num_rows > 0): ?>
      <?php while ($s = $students->fetch_assoc()): ?>
        <option value="<?= $s['id'] ?>">
          <?= htmlspecialchars($s['name']) ?> — <?= htmlspecialchars($s['email']) ?> — <?= htmlspecialchars($s['phone']) ?>
        </option>
      <?php endwhile; ?>
    <?php else: ?>
      <option value="">No approved students found</option>
    <?php endif; ?>
  </select>

  <label for="semester">Select Semester:</label>
  <select name="semester" id="semester" required onchange="loadSubjects(this.value)">
    <option value="">-- Select Semester --</option>
    <?php while ($sem = $semesters->fetch_assoc()): ?>
      <option value="<?= $sem['id'] ?>"><?= htmlspecialchars($sem['class_name']) ?></option>
    <?php endwhile; ?>
  </select>

  <label for="subject_id">Select Subject:</label>
  <select name="subject_id" id="subject_id" required>
    <option value="">-- Select Subject --</option>
  </select>

  <label for="theory_marks">Theory Marks:</label>
  <input type="number" id="theory_marks" name="theory_marks" min="0" required />

  <label for="practical_marks">Practical Marks:</label>
  <input type="number" id="practical_marks" name="practical_marks" min="0" required />

  <label for="term">Select Term:</label>
  <select name="term" id="term" required>
    <option value="">-- Select Term --</option>
    <option value="First Term">First Term</option>
    <option value="Second Term">Second Term</option>
    <option value="Final Term">Final Term</option>
  </select>

  <button type="submit">Submit Marks</button>
</form>

<!-- <a href="dashboard.php">← Back to Dashboard</a> -->

</body>
</html>
