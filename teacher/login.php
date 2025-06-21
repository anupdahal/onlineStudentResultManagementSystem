<?php 
include '../includes/config.php'; 
session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM teachers WHERE email='$email'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $teacher = $result->fetch_assoc();
        if (password_verify($password, $teacher['password'])) {
            $_SESSION['teacher_id'] = $teacher['id'];
            $_SESSION['teacher_name'] = $teacher['name'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "No teacher found with this email";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: linear-gradient(to right, #0f3460, #16213e);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }

        .login-box {
            background-color: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #80ffdb;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border: none;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 16px;
        }

        input::placeholder {
            color: #ccc;
        }

        input:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 8px #3282b8;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: linear-gradient(to right, #1a508b, #3282b8);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button:hover {
            background: linear-gradient(to right, #3282b8, #1a508b);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .error {
            background-color: rgba(255, 0, 0, 0.1);
            color: #ff6b6b;
            padding: 10px;
            margin-top: 10px;
            text-align: center;
            border-radius: 6px;
            font-weight: bold;
        }

        @media (max-width: 500px) {
            .login-box {
                padding: 25px;
            }

            input, button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Teacher Login</h2>
    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="email" name="email" placeholder="Teacher Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="login">Login</button>
    </form>
</div>

</body>
</html>
