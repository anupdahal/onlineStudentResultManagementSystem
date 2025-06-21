<?php include '../includes/config.php'; ?>
<?php
if (isset($_POST['create'])) {
    $class_name = $_POST['class_name'];
    $conn->query("INSERT INTO classes (class_name) VALUES ('$class_name')");
    $success = "✅ Class/Faculty created successfully.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Class or Faculty</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
          height: 100vh;
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

        input[type="text"] {
          width: 100%;
          padding: 12px;
          margin: 15px 0;
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

        .message {
          margin-top: 15px;
          font-size: 16px;
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
        <h2>Create Class / Faculty</h2>
        <input type="text" name="class_name" placeholder="e.g. BCA 1st Sem" required>
        <button name="create">Create</button>
        <?php if (isset($success)) { ?>
            <div class="message" style="color:#4aff7a"><?= $success ?></div>
        <?php } ?>
    </form>
</body>
</html>
