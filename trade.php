<?php
$conn = mysqli_connect("localhost", "root", "", "gikonko_tss");

if (!$conn) {
    die("<div class='error'>Database connection failed: " . mysqli_connect_error() . "</div>");
}

// Fetch Trades data
$sql = "SELECT * FROM Trades";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("<div class='error'>Error fetching Trades data: " . mysqli_error($conn) . "</div>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trades List</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f7fc;
            text-align: center;
        }
        .container {
            width: 60%;
            margin: 40px auto;
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #1976d2;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #1976d2;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9fafc;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        .back-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background: #1976d2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            width: fit-content;
        }
        .back-btn:hover {
            background: #125ea2;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Trades List</h2>
    <table>
        <tr>
            <th>Trade ID</th>
            <th>Trade Name</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= htmlspecialchars($row['Trade_Id']) ?></td>
            <td><?= htmlspecialchars($row['Trade_Name']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
</div>

</body>
</html>

<?php
mysqli_close($conn);
?>
