<?php include 'includes/config.php'; ?>
<?php
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $parent_phone = $_POST['parent_phone'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $photo = $_FILES['photo']['name'];
    $target = "uploads/" . basename($photo);
    move_uploaded_file($_FILES['photo']['tmp_name'], $target);

    $sql = "INSERT INTO students (name, email, phone, parent_phone, address, photo, password) 
            VALUES ('$name', '$email', '$phone', '$parent_phone', '$address', '$photo', '$password')";
    if ($conn->query($sql)) {
        $success = "✅ Registration submitted. Please wait for admin approval.";
    } else {
        $error = "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Insert full modern CSS */
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
          animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
          from { opacity: 0; transform: scale(0.95); }
          to   { opacity: 1; transform: scale(1); }
        }

        h2 {
          font-size: 28px;
          margin-bottom: 20px;
        }

        input, textarea {
          width: 100%;
          padding: 12px;
          margin: 10px 0;
          border: none;
          border-radius: 8px;
          background-color: rgba(255, 255, 255, 0.15);
          color: white;
          font-size: 16px;
        }

        input[type="file"] {
          padding: 8px;
          background-color: rgba(255, 255, 255, 0.1);
        }

        input::placeholder,
        textarea::placeholder {
          color: #ccc;
        }

        input:focus, textarea:focus {
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
    <form method="post" enctype="multipart/form-data" class="form-box">
        <h2>Student Registration</h2>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="text" name="parent_phone" placeholder="Parent's Phone Number" required>
        <textarea name="address" placeholder="Permanent Address" required></textarea>
        <input type="file" name="photo" required>
        <input type="password" name="password" placeholder="Create Password" required>
        <button name="register">Register</button>

        <?php if (isset($success)): ?>
            <div class="message" style="color: #4aff7a"><?= $success ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="message" style="color: #ff6666"><?= $error ?></div>
        <?php endif; ?>
    </form>
</body>
</html>
