<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'DOS') {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "gikonko_tss");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch 7 students
$result = mysqli_query($conn, "SELECT Trainee_Id, FirstNames, LastName, Gender FROM Trainees LIMIT 7");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student List</title>
    <style>
        body {
            background: #f0f4f8;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .container {
            width: 500px;
            margin: 50px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 30px 40px;
        }
        h2 {
            text-align: center;
            color: #1976d2;
            margin-bottom: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #d0d7de;
            text-align: left;
        }
        th {
            background: #e3f2fd;
            color: #1976d2;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .logout {
            display: block;
            text-align: right;
            margin-bottom: 10px;
        }
        .logout a {
            color: #1976d2;
            text-decoration: none;
            font-weight: bold;
        }
        .logout a:hover {
            text-decoration: underline;
        }
        .back-btn {
            display: block;
            text-align: center;
            margin: 20px 0;
            padding: 10px 20px;
            background: #1976d2;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-btn:hover {
            background: #125ea2;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
    <h2>Student List (First 7)</h2>
    <table>
        <tr>
            <th>#</th>
            <th>First Names</th>
            <th>Last Name</th>
            <th>Gender</th>
        </tr>
        <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['FirstNames']) ?></td>
            <td><?= htmlspecialchars($row['LastName']) ?></td>
            <td><?= htmlspecialchars($row['Gender']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
</div>
</body>
</html>

<?php mysqli_close($conn); ?>
