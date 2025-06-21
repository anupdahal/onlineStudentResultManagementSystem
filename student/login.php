// Assuming you've verified login credentials...
$student = $login_result->fetch_assoc();

$_SESSION['student_id'] = $student['id'];
$_SESSION['student_name'] = $student['name']; // ✅ ADD THIS LINE
