<?php
include '../includes/config.php';
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$classes = $conn->query("SELECT * FROM classes");

$message = '';
if (isset($_POST['add'])) {
    $class_id = $_POST['class_id'];
    $subject_name = $_POST['subject_name'];
    $max_theory = $_POST['max_theory'];
    $max_practical = $_POST['max_practical'];

    if ($conn->query("INSERT INTO subjects (class_id, subject_name, max_theory, max_practical) 
                      VALUES ('$class_id', '$subject_name', '$max_theory', '$max_practical')")) {
        $message = "Subject added successfully.";
    } else {
        $message = "Error adding subject: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Add Subject</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
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
    padding-top: 80px;
  }

  /* Navbar style */
  .navbar {
    position: fixed;
    top: 0;
    width: 100%;
    background-color: #0f3460;
    padding: 15px 30px;
    z-index: 1000;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
    color: #80ffdb;
    font-weight: 600;
  }
  .navbar .logo {
    font-size: 20px;
    font-weight: bold;
  }
  .navbar ul {
    list-style: none;
    display: flex;
    gap: 20px;
    margin: 0;
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

  h2 {
    text-align: center;
    color: #80ffdb;
    font-size: 2rem;
    margin-bottom: 30px;
    letter-spacing: 1px;
  }

  form {
    max-width: 500px;
    margin: 0 auto;
    background: rgba(255,255,255,0.05);
    padding: 30px 35px;
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

  select, input[type="text"], input[type="number"] {
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

  select:focus, input[type="text"]:focus, input[type="number"]:focus {
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

  p.message {
    background-color: rgba(76,175,80,0.2);
    color: #4aff7a;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    max-width: 500px;
    margin: 0 auto 20px auto;
    text-align: center;
    box-shadow: 0 0 10px rgba(76,175,80,0.5);
  }

  p.error {
    background-color: rgba(255,0,0,0.1);
    color: #ff6b6b;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    max-width: 500px;
    margin: 0 auto 20px auto;
    text-align: center;
    box-shadow: 0 0 10px rgba(255,0,0,0.3);
  }

  @media (max-width: 600px) {
    .navbar ul {
      flex-direction: column;
      gap: 10px;
    }
    form {
      padding: 20px 25px;
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
        <!-- <li>    <a href="add-subject.php">Add Subjects</a></li> -->
        <li>    <a href="student-list.php">View Students</a></li>
        <li>    <a href="send-notice.php">Send Notice</a></li>
        <li>    <a href="add-marks.php">Add Marks</a></li>
        <li>        <a href="sent-history.php">Notices & Results History</a></li>
<li>             <a href="dashboard.php">← Back to Teacher's Dashboard</a></li>
    </ul>
</nav>
    </div>
<h2>Add Subject</h2>

<?php if ($message): ?>
  <p class="<?= strpos($message, 'successfully') !== false ? 'message' : 'error' ?>">
    <?= htmlspecialchars($message) ?>
  </p>
<?php endif; ?>

<form method="post" autocomplete="off">
  <label for="class_id">Select Class</label>
  <select name="class_id" id="class_id" required>
    <option value="">Select Class</option>
    <?php while ($class = $classes->fetch_assoc()) { ?>
      <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['class_name']) ?></option>
    <?php } ?>
  </select>

  <label for="subject_name">Subject Name</label>
  <input type="text" name="subject_name" id="subject_name" placeholder="Subject Name" required>

  <label for="max_theory">Max Theory Marks</label>
  <input type="number" name="max_theory" id="max_theory" placeholder="Max Theory Marks" min="0" required>

  <label for="max_practical">Max Practical Marks</label>
  <input type="number" name="max_practical" id="max_practical" placeholder="Max Practical Marks" min="0" required>

  <button name="add" type="submit">Add Subject</button>
</form>

</body>
</html>
