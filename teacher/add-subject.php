<?php
include '../includes/config.php';
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$classes = $conn->query("SELECT * FROM classes");

if (isset($_POST['add'])) {
    $class_id = $_POST['class_id'];
    $subject_name = $_POST['subject_name'];
    $max_theory = $_POST['max_theory'];
    $max_practical = $_POST['max_practical'];

    $conn->query("INSERT INTO subjects (class_id, subject_name, max_theory, max_practical) 
                  VALUES ('$class_id', '$subject_name', '$max_theory', '$max_practical')");
    echo "Subject added successfully.";
}
?>

<h2>Add Subject</h2>
<form method="post">
    <label>Select Class</label>
    <select name="class_id" required>
        <option value="">Select Class</option>
        <?php while ($class = $classes->fetch_assoc()) { ?>
            <option value="<?= $class['id'] ?>"><?= $class['class_name'] ?></option>
        <?php } ?>
    </select><br><br>

    <input type="text" name="subject_name" placeholder="Subject Name" required><br><br>
    <input type="number" name="max_theory" placeholder="Max Theory Marks" required min="0"><br><br>
    <input type="number" name="max_practical" placeholder="Max Practical Marks" required min="0"><br><br>

    <button name="add">Add Subject</button>
</form>
