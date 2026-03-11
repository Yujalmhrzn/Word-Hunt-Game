<?php
session_start(); 

$servername = "localhost";
$db_username = "root";
$db_password = ""; 
$dbname = "login_system";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = ""; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        $message = "<span class='error-message'>All fields are required.</span>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<span class='error-message'>Invalid email format.</span>";
    } elseif (strlen($password) < 6) {
        $message = "<span class='error-message'>Password must be at least 6 characters long.</span>";
    } else {
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $message = "<span class='error-message'>Username or email already exists.</span>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt_insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt_insert->execute()) {
                header("Location: login.php?msg=" . urlencode("✅ Registration successful! Please log in."));
                exit();
            } else {
                $message = "<span class='error-message'>Error registering user. Please try again.</span>";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    <style>
      /* Base styles for consistency */
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }

      body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        flex-direction: column;
        color: white;
        position: relative;
        overflow: hidden;
      }

      /* Menu Bar (Top Left Button) */
      .menu-bar {
        position: absolute;
        top: 20px;
        left: 20px;
      }
      .menu-bar a {
        text-decoration: none;
        font-size: 1.2rem;
        font-weight: 600;
        color: white;
        background: linear-gradient(145deg, #FF6B6B, #4ECDC4);
        padding: 12px 20px;
        border-radius: 30px;
        box-shadow: 0 4px 20px rgba(78, 205, 196, 0.4);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        border: 2px solid rgba(255, 255, 255, 0.2);
      }
      .menu-bar a:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 30px rgba(78, 205, 196, 0.6);
        background: linear-gradient(145deg, #4ECDC4, #FF6B6B);
        border-color: rgba(255, 255, 255, 0.4);
      }

      /* Glassmorphic Container */
      .register-container {
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.15), rgba(240, 248, 255, 0.1));
        backdrop-filter: blur(15px);
        padding: 3rem;
        border-radius: 25px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3), 0 5px 25px rgba(78, 205, 196, 0.2);
        text-align: center;
        width: 420px;
        max-width: 90%;
        border: 2px solid rgba(255, 255, 255, 0.25);
        position: relative;
        overflow: hidden;
      }
      .register-container::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.05), transparent);
        transform: rotate(45deg);
        pointer-events: none;
      }

      h2 {
        margin-bottom: 2rem;
        color: white;
        font-size: 2.5rem;
        font-weight: 800;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        letter-spacing: 2px;
        text-transform: uppercase;
        position: relative;
        z-index: 5;
      }
      h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #FF6B6B, #4ECDC4);
        border-radius: 2px;
      }

      .input-group {
        margin-bottom: 1.5rem;
        text-align: left;
        position: relative;
        z-index: 5;
      }

      .input-group label {
        display: block;
        margin-bottom: 0.8rem;
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        font-weight: 500;
        letter-spacing: 0.5px;
      }

      .input-group input {
        width: 100%;
        padding: 1rem;
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        outline: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        font-size: 1rem;
        backdrop-filter: blur(5px);
      }
      .input-group input::placeholder {
        color: rgba(255, 255, 255, 0.6);
      }
      .input-group input:focus {
        border-color: #4ECDC4;
        background: rgba(255, 255, 255, 0.15);
        box-shadow: 0 0 20px rgba(78, 205, 196, 0.3);
        transform: translateY(-2px);
      }

      button {
        width: 100%;
        padding: 1.2rem;
        border: none;
        border-radius: 15px;
        background: linear-gradient(145deg, #FF6B6B, #4ECDC4);
        color: white;
        font-size: 1.2rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 6px 20px rgba(78, 205, 196, 0.4);
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        overflow: hidden;
        z-index: 5;
      }
      button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
      }
      button:hover::before {
        left: 100%;
      }
      button:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 10px 30px rgba(78, 205, 196, 0.6);
        background: linear-gradient(145deg, #4ECDC4, #FF6B6B);
      }
      button:active {
        transform: translateY(-1px) scale(1.01);
      }

      p {
        margin-top: 2rem;
        color: rgba(255, 255, 255, 0.8);
        font-size: 1rem;
        position: relative;
        z-index: 5;
      }
      p a {
        color: #4ECDC4;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
      }
      p a:hover {
        color: #FF6B6B;
        text-decoration: underline;
      }

      /* Message Styling */
      .error-message, .success-message {
        display: block;
        margin-bottom: 1.5rem;
        padding: 1rem 1.5rem;
        border-radius: 15px;
        font-size: 1rem;
        text-align: center;
        backdrop-filter: blur(5px);
        position: relative;
        z-index: 5;
        font-weight: 500;
      }
      .error-message {
        color: white;
        background: linear-gradient(145deg, rgba(255, 107, 107, 0.2), rgba(255, 107, 107, 0.1));
        border: 2px solid rgba(255, 107, 107, 0.3);
      }
      .success-message {
        color: white;
        background: linear-gradient(145deg, rgba(76, 175, 80, 0.2), rgba(76, 175, 80, 0.1));
        border: 2px solid rgba(76, 175, 80, 0.3);
      }
    </style>
  </head>
  <body>
    <div class="menu-bar">
        <a href="index.php">Word Hunt</a>
    </div>
    <div class="register-container">
      <h2>Register</h2>

      <?php if (!empty($message)): ?>
        <?php echo $message; ?>
      <?php endif; ?>

      <form action="register.php" method="post">
        <div class="input-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required />
        </div>
        <div class="input-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required />
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required />
        </div>
        <button type="submit">Register</button>
      </form>
      <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
  </body>
</html>