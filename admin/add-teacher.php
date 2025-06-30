<?php include '../includes/config.php'; ?>
<?php
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $photo = $_FILES['photo']['name'];
    move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/$photo");

    $conn->query("INSERT INTO teachers (name, email, phone, photo, password) 
                  VALUES ('$name', '$email', '$phone', '$photo', '$password')");
    $success = "✅ Teacher added successfully.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Teacher</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Consistent Modern UI */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0f3460, #16213e);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-box {
            background-color: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            width: 90%;
            max-width: 500px;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #f1f1f1;
        }

        input[type="text"],
        input[type="email"],
        input[type="file"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            font-size: 16px;
        }

        input::placeholder {
            color: #ccc;
        }

        input:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.25);
            box-shadow: 0 0 10px #3282b8;
        }

        button {
            margin-top: 15px;
            width: 100%;
            padding: 12px;
            background: linear-gradient(to right, #1a508b, #3282b8);
            color: white;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }

        button:hover {
            background: linear-gradient(to right, #3282b8, #1a508b);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        .message {
            margin-top: 15px;
            font-size: 16px;
            font-weight: bold;
        }

        @media (max-width: 500px) {
            h2 { font-size: 22px; }
            button { font-size: 16px; }
        }
    </style>
</head>
<body>
<a href="dashboard.php" style="position: absolute; top: 20px; left: 20px; background-color: #3282b8; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.3); transition: 0.3s;">
    ← Back to Dashboard
</a>

    <form method="post" enctype="multipart/form-data" class="form-box">
        <h2>Add New Teacher</h2>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="file" name="photo" required>
        <input type="password" name="password" placeholder="Create Password" required>
        <button name="add">Add Teacher</button>
        
        <?php if (isset($success)): ?>
            <div class="message" style="color: #4aff7a"><?= $success ?></div>
            <?php endif; ?>
        </form>
    </body>
    </html>
