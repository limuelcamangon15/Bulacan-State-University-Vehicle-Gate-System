 <?php
  include 'connection.php';

  $showAlert = false;
  $alertTitle = "";
  $alertText = "";
  $alertIcon = "";
  $redirectURL = "";

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $firstName = $_POST['first_name'] ?? '';
      $lastName = $_POST['last_name'] ?? '';
      $emailAddress = $_POST['email'] ?? '';
      $contactNumber = $_POST['contact'] ?? '';
      $username = $_POST['username'] ?? '';
      $password = $_POST['password'] ?? '';
      $confPassword = $_POST['confpassword'] ?? '';

      if ($password !== $confPassword) {
          $showAlert = true;
          $alertTitle = "Password Mismatch";
          $alertText = "Passwords do not match.";
          $alertIcon = "error";
          $redirectURL = "register.php";
      } 
      else {
          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

          $stmt = $conn->prepare("INSERT INTO accounts (FirstName, LastName, EmailAddress, ContactNumber, Username, Password, Role) VALUES (?, ?, ?, ?, ?, ?, 'Vehicle Owner')");

          if ($stmt) {
              $stmt->bind_param("ssssss", $firstName, $lastName, $emailAddress, $contactNumber, $username, $hashedPassword);

              if ($stmt->execute()) {
                  $showAlert = true;
                  $alertTitle = "Account Created";
                  $alertText = "Your account has been registered successfully.";
                  $alertIcon = "success";
                  $redirectURL = "index.php";
              } 
              else {
                  $showAlert = true;
                  $alertTitle = "Registration Error";
                  $alertText = "Something went wrong. Please try again.";
                  $alertIcon = "error";
                  $redirectURL = "register.php";
              }

              $stmt->close();
          } 
          else {
              $showAlert = true;
              $alertTitle = "Database Error";
              $alertText = "Failed to prepare SQL statement.";
              $alertIcon = "error";
              $redirectURL = "register.php";
          }
      }

      $conn->close();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Account - Vehicle Gate System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
      max-width: 800px;
      width: 100%;
      animation: fadeIn 0.5s ease-out;
    }

    .form-container:hover {
      box-shadow: 0 0 40px rgba(0, 0, 0, 0.2);
      transform: scale(1.02);
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

    h2 {
      text-align: center;
      margin-bottom: 10px;
      color: #fcd34d;
    }

    p {
      text-align: center;
      margin-bottom: 25px;
      color: #ffffff;
      font-weight: 500;
    }

    form {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: space-between;
    }

    .input-group {
      display: flex;
      flex-direction: column;
      width: 48%;
    }

    .full-width {
      width: 100%;
    }

    label {
      margin-bottom: 5px;
      font-size: 0.95rem;
    }

    input {
      padding: 10px;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    input:focus {
      outline: none;
      border-color: #60a5fa;
      background-color: #111827;
      transform: scale(1.03);
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
      width: 100%;
    }

    button:hover {
      background-color: #2563eb;
      transform: scale(1.05);
    }

    .signin-link {
      margin-top: 15px;
      text-align: center;
      font-size: 0.95rem;
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

    .error-message {
      color: red;
      font-weight: bold;
      font-size: 13px;
      display: none;
    }

    .cancel-btn {
      margin-top: 10px;
      padding: 12px;
      background-color: #6b7280;
      color: #fff;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.3s ease;
      width: 100%;
    }

    .cancel-btn:hover {
      background-color: #4b5563;
      transform: scale(1.05);
    }

    @media (max-width: 768px) {
      .input-group {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Create an Account</h2>
    <p>Register for access to the Vehicle Gate System</p>

    <form action="" method="POST">
      <div class="input-group">
        <label for="firstName">First Name</label>
        <input type="text" id="firstName" name="first_name" required placeholder="First Name">
      </div>

      <div class="input-group">
        <label for="lastName">Last Name</label>
        <input type="text" id="lastName" name="last_name" required placeholder="Last Name">
      </div>

      <div class="input-group">
        <label for="contact">Contact Number</label>
        <input type="tel" id="contact" name="contact" required placeholder="Contact Number">
      </div>

      <div class="input-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required placeholder="Email">    
      </div>

      <div class="input-group full-width">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required placeholder="Username">
        <span id="username-error" class="error-message"><i class="fas fa-exclamation-triangle" style="color: #facc15; margin-right: 5px;"></i> Username already exists</span>
      </div>

      <div class="input-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required placeholder="Password">
      </div>

      <div class="input-group">
        <label for="confpassword">Confirm Password</label>
        <input type="password" id="confpassword" name="confpassword" required placeholder="Confirm Password">
        <span id="password-error" class="error-message"><i class="fas fa-exclamation-triangle" style="color: #facc15; margin-right: 5px;"></i> Password do not match</span>
      </div>

      <div class="input-group full-width">
        <button type="submit">Sign Up</button>
      </div>
    </form>

    <div class="signin-link">
      Already have an account? <a href="index.php">Sign In</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php if ($showAlert): ?>
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

  <script>
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const confPasswordInput = document.getElementById('confpassword');
    const submitButton = document.querySelector('button[type="submit"]');
  
    let isUsernameValid = false;
    let doPasswordsMatch = false;
  
    usernameInput.addEventListener('blur', checkUsername);
    passwordInput.addEventListener('input', validateForm);
    confPasswordInput.addEventListener('input', validateForm);
  
    function checkUsername() {
      const username = usernameInput.value.trim();
   
  
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'checkUsername.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      
      xhr.onload = function () {
        if (xhr.status === 200) {
          if (xhr.responseText === "exists") {
            document.getElementById('username-error').style.display = "block";
            isUsernameValid = false;
          } else {
            document.getElementById('username-error').style.display = "none";
            isUsernameValid = true;
          }
          validateForm();
        }
      };
  
      xhr.send('username=' + encodeURIComponent(username));
    }
  
    function validateForm() {
      const pass = passwordInput.value;
      const confPass = confPasswordInput.value;
  
      if (pass !== confPass) {
        document.getElementById('password-error').style.display = "block";
        doPasswordsMatch = false;
      } else {
        document.getElementById('password-error').style.display = "none";
        doPasswordsMatch = true;
      }
  
      submitButton.disabled = !(isUsernameValid && doPasswordsMatch);
    }
  </script>
  

</body>
</html>