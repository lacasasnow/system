<?php
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect("localhost", "root", "", "gikonko_tss");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM Users WHERE Username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['Password'])) {
            if ($row['Role'] === 'DOS') {
                $_SESSION['user_id'] = $row['User_Id'];
                $_SESSION['role'] = $row['Role'];
                header("Location: insert.php");
                exit();
            } else {
                $error = "Access denied. Only DOS can log in.";
            }
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DOS Login</title>
    <style>
        body {
            background: #f0f4f8;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .login-container {
            width: 350px;
            margin: 80px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 30px 40px;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2d3e50;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px 10px;
            margin: 8px 0 18px 0;
            border: 1px solid #d0d7de;
            border-radius: 5px;
            font-size: 16px;
        }
        .login-container button {
            width: 100%;
            padding: 12px;
            background: #1976d2;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 17px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .login-container button:hover {
            background: #125ea2;
        }
        .error {
            color: #d32f2f;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>DOS Login</h2>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" autocomplete="off">
        <input type="text" name="username" placeholder="Username" required autofocus>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>