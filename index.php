 <?php
    session_start();
    include 'connection.php';

    $showAlert = false;
    $alertTitle = "";
    $alertText = "";
    $alertIcon = "";
    $redirectURL = "";


    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        $query = "SELECT * FROM accounts WHERE Username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['Password'])) {
                if (is_null($user['Role'])) {
                    $showAlert = true;
                    $alertTitle = "Role Not Set";
                    $alertText = "Role not set. Wait for admin approval.";
                    $alertIcon = "warning";
                    $redirectURL = "index.php";

                    $stmt->close();
                    $conn->close();
                } 
                else {                   
                    $_SESSION['username'] = $user['Username'];
                    $_SESSION['role'] = $user['Role'];
                    $_SESSION['account_id'] = $user['AccountID'];

                    $stmt->close();
                    $conn->close();

                    switch ($user['Role']) {
                        case 'Admin':
                            header("Location: dashboardAdmin.php");
                            exit;
                        case 'Guard':
                            header("Location: dashboardGuard.php");
                            exit;
                        case 'Vehicle Owner':
                            header("Location: dashboardOwner.php");
                            exit;
                        default:
                            $showAlert = true;
                            $alertTitle = "Role Not Set";
                            $alertText = "Role not set. Wait for admin approval.";
                            $alertIcon = "warning";
                            $redirectURL = "index.php";
                    }
                }
            } 
            else {
                $showAlert = true;
                $alertTitle = "Login Failed";
                $alertText = "Incorrect password.";
                $alertIcon = "error";
                $redirectURL = "index.php";

                $stmt->close();
                $conn->close();
            }
        } 
        else {
            $showAlert = true;
            $alertTitle = "Login Failed";
            $alertText = "Username not found.";
            $alertIcon = "error";
            $redirectURL = "index.php";

            $stmt->close();
            $conn->close();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Vehicle Gate System - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        html {
            background: linear-gradient(135deg, #0a0e17, #425a83);
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0a0e17, #425a83);
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #ffffff;
        }

        .login-container {
            background: rgba(15, 23, 42, 0.95);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 480px;
            transition: box-shadow 0.3s ease;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            0% {
                transform: translateY(-30px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .login-container:hover {
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.4);
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.2);
            transform: scale(1.02);  
        }

        .login-container h2 {
            margin-bottom: 24px;
            text-align: center;
            color: #fcd34d;
            font-size: 1.75rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .form-group label {
            margin-bottom: 8px;
            font-weight: 600;
            color: #d1d5db;
        }

        .form-group input {
            padding: 12px 14px;
            border: 1px solid #4b5563;
            border-radius: 8px;
            background-color: #1f2937;
            color: #fcd34d;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #60a5fa;
            background-color: #111827;
            transform: scale(1.05);
            color: white;
        }

        #loginBUTTON {
            background-color: #3b82f6;
            color: #ffffff;
            border: none;
            padding: 14px;
            width: 100%;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        #loginBUTTON:hover {
            background-color: #2563eb;
            transform: scale(1.05);
        }

        .link-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            margin-top: 15px;
            font-size: 14px;
        }

        .link-buttons a {
            text-decoration: none;
            color: #93c5fd;
            transition: color 0.3s ease;
        }

        .link-buttons a:hover{
            color: #60a5fa;
            transform: scale(1.1);
            transition: transform 0.5 ease-in-out;
        }

        .inner-link-buttons #createA {
            margin-right: 20px;
        }

        .inner-link-buttons #forgotA {
            margin-left: 20px;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 20px;
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login to Vehicle Gate</h2>
        <br>
        <form action="" method="POST">
            <div class="form-group">
                <label for="usernameField">Username</label>
                <input type="text" id="usernameField" name="username" required />
            </div>
            <div class="form-group">
                <label for="passwordField">Password</label>
                <input type="password" id="passwordField" name="password" required />
            </div>
            <button id="loginBUTTON" type="submit">Login</button>
        </form>
        <br>
        <div class="link-buttons">
            <div class="inner-link-buttons">
                <a href="register.php" id="createA">Create Account</a> |
                <a href="forgot.php" id="forgotA">Forgot Password?</a>
            </div>
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
</html