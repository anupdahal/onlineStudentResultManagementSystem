<?php
include '../includes/config.php';

if (!isset($_GET['semester_id'])) {
    echo json_encode([]);
    exit;
}

$semester_id = intval($_GET['semester_id']);

// Fetch subjects for this semester
$result = $conn->query("SELECT id, subject_name FROM subjects WHERE class_id = $semester_id ORDER BY subject_name");

$subjects = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($subjects);
