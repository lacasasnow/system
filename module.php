<?php
$conn = mysqli_connect("localhost", "root", "", "gikonko_tss");

if (!$conn) {
    die("<div class='error'>Database connection failed: " . mysqli_connect_error() . "</div>");
}

// Fetch unique module names but exclude "ICT Basics"
$sql = "SELECT DISTINCT Module_Name, Module_Id, Trade_Id FROM Modules WHERE Module_Name NOT LIKE 'ICT Basics'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("<div class='error'>Error fetching Modules data: " . mysqli_error($conn) . "</div>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Modules List</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f7fc;
            text-align: center;
        }
        .container {
            width: 70%;
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
        .back-button {
            display: inline-block;
            margin: 20px;
            padding: 10px 20px;
            background-color: #1976d2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" class="back-button">Back to Dashboard</a>
    
    <h2>Modules List (Filtered)</h2>
    <table>
        <tr>
            <th>Module ID</th>
            <th>Module Name</th>
            <th>Trade ID</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= htmlspecialchars($row['Module_Id']) ?></td>
            <td><?= htmlspecialchars($row['Module_Name']) ?></td>
            <td><?= htmlspecialchars($row['Trade_Id']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>

<?php
mysqli_close($conn);
?>
