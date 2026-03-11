    <?php
    session_start();

    $servername = "localhost";
    $db_username = "root";  
    $db_password = "";  // Replace if needed
    $dbname = "login_system";

    // Connect to database
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $errorMsg = "";

    // Handle login
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        // Prepared statement
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $username;
                header("Location: levelSelect.php?welcome=1"); // <-- Add ?welcome=1 here
                exit;
            }
            else {
                $errorMsg = "❌ Invalid password.";
            }
        } else {
            $errorMsg = "❌ No account found with that username.";
        }

        $stmt->close();
    }

    // Show message passed from URL (like redirect notice)
    if (isset($_GET['msg'])) {
        $errorMsg = htmlspecialchars($_GET['msg']);
    }

    $conn->close();
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Login</title>
        <link rel="stylesheet" href="styles.css" />
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

      /* Login Container */
      .login-container {
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
      .login-container::before {
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

      /* Input Groups */
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

      /* Options (e.g., Forgot Password) */
      .options {
        display: flex;
        justify-content: space-between;
        font-size: 0.95rem;
        margin-bottom: 2rem;
        position: relative;
        z-index: 5;
      }
      .options a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
      }
      .options a:hover {
        color: #4ECDC4;
        text-decoration: underline;
      }

      /* Submit Button */
      button[type="submit"] {
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
      button[type="submit"]::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
      }
      button[type="submit"]:hover::before {
        left: 100%;
      }
      button[type="submit"]:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 10px 30px rgba(78, 205, 196, 0.6);
        background: linear-gradient(145deg, #4ECDC4, #FF6B6B);
      }
      button[type="submit"]:active {
        transform: translateY(-1px) scale(1.01);
      }

      /* Register/Account Message */
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

      /* Error Message */
      .error-message {
        margin-bottom: 1.5rem;
        padding: 1rem 1.5rem;
        color: white;
        background: linear-gradient(145deg, rgba(255, 107, 107, 0.2), rgba(255, 107, 107, 0.1));
        border: 2px solid rgba(255, 107, 107, 0.3);
        border-radius: 15px;
        font-size: 1rem;
        text-align: center;
        backdrop-filter: blur(5px);
        position: relative;
        z-index: 5;
        font-weight: 500;
      }
    </style>
    </head>
    <body>
        <div class="menu-bar">
        <a href="index.php">Word Hunt</a>
        </div>
        <div class="login-container">
        <h2>Login</h2>

        <?php if (!empty($errorMsg)): ?>
            <div class="error-message"><?php echo $errorMsg; ?></div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <div class="input-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required />
            </div>
            <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required />
            </div>

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </body>
    </html>
