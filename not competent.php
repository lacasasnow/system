<?php
$conn = mysqli_connect("localhost", "root", "", "gikonko_tss");

if (!$conn) {
    die("<div class='error'>Database connection failed: " . mysqli_connect_error() . "</div>");
}

// Select NYC trainees (Total Marks < 70)
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
WHERE mk.Total_Marks < 70
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
    <title>NYC Trainees</title>
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
            color: #d32f2f;
            margin-bottom: 15px;
        }
        .back-button {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background-color: #1976d2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
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
            background: #d32f2f;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9fafc;
        }
        .nyc {
            color: #d32f2f;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" class="back-button">Back to Dashboard</a>
    
    <h2>Not Yet Competent (NYC) Trainees (Total Marks < 70)</h2>
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
            <td class="nyc"><?= htmlspecialchars($row['Total_Marks']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>

<?php
mysqli_close($conn);
?>
