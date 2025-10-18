<?php

session_start();
        
        $error_message = ""; 
        
        // Only try to connect to database if the form was submitted
        if($_POST){
            try {
                include("Database/connection.php");

            $username = $_POST['username'];
            $password = $_POST['password'];

            // Use parameterized query to prevent SQL injection
            $query = 'SELECT * FROM users WHERE email = ? AND password = ?';
            $stmt = $conn->prepare($query);
            $stmt->execute([$username, $password]);
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Store user data in session
                $_SESSION['user_id'] = $user['id'];
                
                // Admin users
                if ($user['id'] <= 4) {
                    header('Location: test inventario ELOG.php');
                } 
                // Transformador Vladimir
                elseif ($user['id'] == 6) {
                    header('Location: Transformador.php');
                } 
                else {
                    // All other users go to Bodega
                    header('Location: Bodega Inventario.php');
                }
                exit();
            }

            else {
                $error_message = "Invalid username or password";
            }
        } catch (Exception $e) {
            $error_message = $e->getMessage();
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELOG S.A. Login</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .loginHeader {
            text-align: center;
            margin-bottom: 30px;
        }

        .loginHeader h1 {
            color: #667eea;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .loginHeader h3 {
            color: #666;
            font-size: 1rem;
            font-weight: 400;
        }

        .loginBody form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        button {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            width: 100%;
            margin-top: 10px;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px;
            }

            .loginHeader h1 {
                font-size: 2rem;
            }
        }
        #errorMessage {
            text-align: center;
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="login-container">

        <?php if (!empty($error_message)) {   ?>
        <div id="errorMessage">
            <p><?= $error_message ?></p>
        </div>
        <?php } ?>

        <!--Header-->
        <div class="loginHeader">
            <h1>ELOG S.A.</h1>
            <h3>Inventory Management</h3>
        </div>
        <!--Body-->
        <div class="loginBody">
            <form action="Index.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required />
                </div> 
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>

