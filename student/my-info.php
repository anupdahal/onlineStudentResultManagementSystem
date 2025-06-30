<?php
include '../includes/config.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$message = '';

// Fetch student info
$result = $conn->query("SELECT * FROM students WHERE id = '$student_id'");
$student = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $parent_phone = $_POST['parent_phone'];
    $address = $_POST['address'];

    // Photo upload handling
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "../uploads/";
        $filename = $student_id . "_" . basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . $filename;
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
        $photo_sql = ", photo='$filename'";
    } else {
        $photo_sql = "";
    }

    $update = $conn->query("UPDATE students SET 
        name = '$name', 
        email = '$email', 
        phone = '$phone', 
        parent_phone = '$parent_phone', 
        address = '$address' 
        $photo_sql 
        WHERE id = '$student_id'");

    if ($update) {
        $message = "✅ Your information was updated successfully.";
        $student = $conn->query("SELECT * FROM students WHERE id = '$student_id'")->fetch_assoc();
    } else {
        $message = "❌ Update failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Information</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f3460, #16213e);
            color: white;
            min-height: 100vh;
            padding-top: 70px;
        }

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
        }

        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            gap: 20px;
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

        .container {
            max-width: 800px;
            margin: 30px auto;
            background-color: rgba(255, 255, 255, 0.05);
            padding: 30px 40px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #80ffdb;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #a0c4ff;
            font-weight: 600;
        }

        input, textarea {
            width: 100%;
            padding: 10px 15px;
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            background-color: rgba(255,255,255,0.12);
            color: #e0e0e0;
        }

        input[type="file"] {
            background: transparent;
        }

        textarea {
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(to right, #1a508b, #3282b8);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
        }

        button:hover {
            background: linear-gradient(to right, #3282b8, #1a508b);
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
        }

        .message.success {
            background-color: rgba(76,175,80,0.2);
            color: #4aff7a;
        }

        .message.error {
            background-color: rgba(255,0,0,0.1);
            color: #ff6b6b;
        }

        img.profile {
            max-width: 120px;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar ul {
                margin-top: 10px;
                gap: 10px;
                flex-wrap: wrap;
            }

            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<a href="dashboard.php" style="position: absolute; top: 20px; left: 20px; background-color: #3282b8; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.3); transition: 0.3s;">
    ← Back to Dashboard
</a>


<div class="container">
    <h2>📄 My Information</h2>

    <?php if ($message): ?>
        <div class="message <?= str_starts_with($message, '✅') ? 'success' : 'error' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Full Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($student['phone']) ?>" required>

        <label>Parent Phone</label>
        <input type="text" name="parent_phone" value="<?= htmlspecialchars($student['parent_phone']) ?>" required>

        <label>Address</label>
        <textarea name="address" rows="3" required><?= htmlspecialchars($student['address']) ?></textarea>

        <label>Change Photo</label>
        <input type="file" name="photo" accept="image/*">
        <?php if (!empty($student['photo']) && file_exists("../uploads/" . $student['photo'])): ?>
            <img src="../uploads/<?= htmlspecialchars($student['photo']) ?>" class="profile" alt="Photo">
        <?php endif; ?>

        <button type="submit">Update Information</button>
    </form>
</div>

</body>
</html>
