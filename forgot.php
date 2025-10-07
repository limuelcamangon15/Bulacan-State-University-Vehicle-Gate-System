 <?php
    session_start();
    include 'connection.php';

    $showAlert = false;
    $alertTitle = "";
    $alertText = "";
    $alertIcon = "";
    $redirectURL = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = trim($_POST['email']);
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $confPassword = $_POST['confpassword'];

        if ($password !== $confPassword) {
            $showAlert = true;
            $alertTitle = "Password Mismatch";
            $alertText = "The new passwords do not match.";
            $alertIcon = "warning";
            $redirectURL = "forgot.php";
        } 
        else {
            $stmt = $conn->prepare("SELECT EmailAddress FROM accounts WHERE Username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($dbEmail);
                $stmt->fetch();

                if ($dbEmail === $email) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $updateStmt = $conn->prepare("UPDATE accounts SET Password = ? WHERE Username = ?");
                    $updateStmt->bind_param("ss", $hashedPassword, $username);

                    if ($updateStmt->execute()) {
                        $showAlert = true;
                        $alertTitle = "Password Updated Successfully!";
                        $alertText = "Your password has been updated.";
                        $alertIcon = "success";
                        $redirectURL = "index.php";
                    } 
                    else {
                        $showAlert = true;
                        $alertTitle = "Update Failed";
                        $alertText = "Could not update password. Try again.";
                        $alertIcon = "error";
                        $redirectURL = "forgot.php";
                    }

                    $updateStmt->close();
                } 
                else {
                    $showAlert = true;
                    $alertTitle = "Email Mismatch";
                    $alertText = "The email does not match our records.";
                    $alertIcon = "error";
                    $redirectURL = "forgot.php";
                }
            } 
            else {
                $showAlert = true;
                $alertTitle = "User Not Found";
                $alertText = "No account found with that username.";
                $alertIcon = "error";
                $redirectURL = "forgot.php";
            }

            $stmt->close();
            $conn->close();
        }
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password - Vehicle Gate System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <style>
    * {
      font-family: 'Segoe UI', sans-serif;
      box-sizing: border-box;
    }

    body {
      margin: 0;
      background: linear-gradient(135deg, #0a0e17, #425a83);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #ffffff;
    }

    .form-container {
      background: rgba(15, 23, 42, 0.95);
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 500px;
      display: flex;
      flex-direction: column;
      transition: box-shadow 0.3s ease, transform 0.3s ease;
      animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
      0% {
        opacity: 0;
        transform: translateY(-30px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .form-container:hover {
      box-shadow: 0 0 40px rgba(0, 0, 0, 0.2);
      transform: scale(1.02);
    }

    h2 {
      margin: 0 0 10px;
      text-align: center;
      color: #fcd34d;
    }

    p {
      text-align: center;
      margin-bottom: 20px;
      color: #ffffff;
      font-weight: 500;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
      width: 100%;
    }

    .input-group {
      display: flex;
      flex-direction: column;
    }

    label {
      margin-bottom: 5px;
      color: #ffffff;
      font-size: 0.95rem;
    }

    input {
      padding: 12px;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 8px;
      width: 100%;
      transition: all 0.3s ease;
    }

    input:focus {
      outline: none;
      border-color: #60a5fa;
      background-color: #111827;
      transform: scale(1.05); 
      color: #fcd34d;
    }

    button {
      margin-top: 10px;
      padding: 12px;
      background-color: #3b82f6;
      color: #fff;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    button:hover {
      background-color: #2563eb;
      transform: scale(1.05);
    }

    .signin-link {
      margin-top: 15px;
      text-align: center;
      font-size: 0.95rem;
      color: #ffffff;
    }

    .signin-link a {
      color: #3b82f6;
      font-weight: bold;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .signin-link a:hover {
      color: #2563eb;
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .form-container {
        padding: 20px;
        width: 90%;
      }

      h2 {
        font-size: 1.5rem;
      }

      p {
        font-size: 0.95rem;
      }
    }
  </style>
</head>
<body>

  <div class="form-container">
    <h2>Forgot Password?</h2>
    <p>Let's recover your account in just a few steps</p>

    <form method="POST">
      <div class="input-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
      </div>

      <div class="input-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Your username" required>
      </div>

      <div class="input-group">
        <label for="password">New Password</label>
        <input type="password" id="password" name="password" placeholder="New password" required>
      </div>

      <div class="input-group">
        <label for="confpassword">Confirm Password</label>
        <input type="password" id="confpassword" name="confpassword" placeholder="Confirm new password" required>
      </div>

      <button type="submit">Recover</button>
    </form>

    <div class="signin-link">
      Remembered your password? <a href="index.php">Sign In</a>
    </div>
  </div>

  <?php if ($showAlert): ?>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
        Swal.fire({
          title: "<?= htmlspecialchars($alertTitle) ?>",
          text: "<?= htmlspecialchars($alertText) ?>",
          icon: "<?= $alertIcon ?>",
          allowOutsideClick: false,
          allowEscapeKey: false
        }).then(() => {
          window.location.href = "<?= $redirectURL ?>";
        });
      </script>
  <?php endif; ?>


</body>
</html>