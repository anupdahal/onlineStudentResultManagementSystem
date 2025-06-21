<?php include '../includes/config.php'; session_start(); ?>
<?php
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Static admin login (can be made dynamic)
    if ($email == "admin@gmail.com" && $password == "admin123") {
        $_SESSION['admin'] = $email;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "❌ Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Modern admin login styling */
        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }

        body {
          background: linear-gradient(135deg, #0f3460, #16213e);
          font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
          color: white;
          height: 100vh;
          display: flex;
          align-items: center;
          justify-content: center;
          animation: bgShift 20s ease infinite;
        }

        @keyframes bgShift {
          0% { background-position: 0% 50%; }
          50% { background-position: 100% 50%; }
          100% { background-position: 0% 50%; }
        }

        .form-box {
          background-color: rgba(255, 255, 255, 0.05);
          backdrop-filter: blur(10px);
          padding: 40px 30px;
          border-radius: 20px;
          text-align: center;
          box-shadow: 0 10px 25px rgba(0,0,0,0.4);
          width: 90%;
          max-width: 400px;
          animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
          from { opacity: 0; transform: scale(0.9); }
          to   { opacity: 1; transform: scale(1); }
        }

        h2 {
          font-size: 28px;
          margin-bottom: 25px;
          color: #f8f8f8;
        }

        input[type="email"],
        input[type="password"] {
          width: 100%;
          padding: 12px;
          margin: 12px 0;
          border: none;
          border-radius: 8px;
          outline: none;
          background-color: rgba(255, 255, 255, 0.2);
          color: white;
          font-size: 16px;
          transition: 0.3s;
        }

        input::placeholder {
          color: #ccc;
        }

        input:focus {
          background-color: rgba(255, 255, 255, 0.25);
          box-shadow: 0 0 10px #3282b8;
        }

        button {
          margin-top: 20px;
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
          box-shadow: 0 6px 15px rgba(0,0,0,0.3);
        }

        .error {
          color: #ff6363;
          margin-top: 15px;
          font-weight: bold;
        }

        @media (max-width: 500px) {
          h2 {
            font-size: 22px;
          }

          button {
            font-size: 16px;
          }
        }
    </style>
</head>
<body>
    <form method="post" class="form-box">
        <h2>Admin Login</h2>
        <input type="email" name="email" placeholder="Admin Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="login">Login</button>

        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
    </form>
</body>
</html>
