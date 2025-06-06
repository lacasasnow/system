<?php
$conn = mysqli_connect("localhost", "root", "", "gikonko_tss");

if (!$conn) {
    die("<div class='error'>Database connection failed: " . mysqli_connect_error() . "</div>");
}

// Select Competent trainees (Total Marks ≥ 70)
$sql = "
SELECT 
    t.Trainee_Id,
    t.FirstNames,
    t.LastName,
    t.Gender,
    tr.Trade_Name,
    m.Module_Name,
    mk.Total_Marks
FROM Trainees t
LEFT JOIN Trades tr ON t.Trade_Id = tr.Trade_Id
LEFT JOIN Marks mk ON t.Trainee_Id = mk.Trainee_Id
LEFT JOIN Modules m ON mk.Module_Id = m.Module_Id
WHERE mk.Total_Marks >= 70
ORDER BY mk.Total_Marks DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("<div class='error'>Error executing query: " . mysqli_error($conn) . "</div>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Competent Trainees</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f7fc;
            text-align: center;
            margin: 20px;
        }
        .container {
            width: 80%;
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
            margin-top: 20px;
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
        .competent {
            color: #388e3c;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        .back-btn {
            display: inline-block;
            margin: 20px auto;
            padding: 10px 20px;
            background: #1976d2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .back-btn:hover {
            background: #125ea2;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Competent Trainees (Total Marks ≥ 70)</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Gender</th>
            <th>Trade</th>
            <th>Module</th>
            <th>Total Marks</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= htmlspecialchars($row['Trainee_Id']) ?></td>
            <td><?= htmlspecialchars($row['FirstNames']) ?></td>
            <td><?= htmlspecialchars($row['LastName']) ?></td>
            <td><?= htmlspecialchars($row['Gender']) ?></td>
            <td><?= htmlspecialchars($row['Trade_Name']) ?></td>
            <td><?= htmlspecialchars($row['Module_Name']) ?></td>
            <td class="competent"><?= htmlspecialchars($row['Total_Marks']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a class="back-btn" href="dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>

<?php
mysqli_close($conn);
?>
