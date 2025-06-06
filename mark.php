<?php
$conn = mysqli_connect("localhost", "root", "", "gikonko_tss");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query to select marks along with trainee and module information
$sql = "
SELECT 
    t.Trainee_Id,
    t.FirstNames,
    t.LastName,
    m.Module_Name,
    mk.Formative_Assessment,
    mk.Summative_Assessment,
    mk.Total_Marks
FROM Marks mk
JOIN Trainees t ON mk.Trainee_Id = t.Trainee_Id
JOIN Modules m ON mk.Module_Id = m.Module_Id
ORDER BY t.Trainee_Id ASC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error executing query: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trainee Marks</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 20px;
            color: #333;
            display: flex;
            justify-content: center;
        }
        .container {
            width: 90%;
            max-width: 900px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(25, 118, 210, 0.15);
            padding: 30px 40px;
        }
        h1 {
            color: #1976d2;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 700;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
        }
        thead tr {
            background-color: #1976d2;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 14px;
        }
        th, td {
            padding: 14px 18px;
            text-align: left;
        }
        tbody tr {
            border-bottom: 1px solid #e0e0e0;
            transition: background-color 0.3s ease;
        }
        tbody tr:hover {
            background-color: #e3f2fd;
        }
        tbody tr:last-child {
            border-bottom: none;
        }
        .back-btn {
            display: inline-block;
            padding: 12px 28px;
            background-color: #1976d2;
            color: #fff;
            font-weight: 600;
            font-size: 16px;
            border-radius: 6px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            user-select: none;
        }
        .back-btn:hover,
        .back-btn:focus {
            background-color: #125ea2;
            box-shadow: 0 6px 18px rgba(18, 94, 162, 0.5);
        }
        @media (max-width: 600px) {
            .container {
                padding: 20px 15px;
            }
            th, td {
                padding: 10px 8px;
                font-size: 14px;
            }
            .back-btn {
                width: 100%;
                text-align: center;
                padding: 14px 0;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Trainee Marks</h1>
    <table>
        <thead>
        <tr>
            <th>Trainee ID</th>
            <th>First Names</th>
            <th>Last Name</th>
            <th>Module Name</th>
            <th>Formative Assessment</th>
            <th>Summative Assessment</th>
            <th>Total Marks</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['Trainee_Id']) ?></td>
                <td><?= htmlspecialchars($row['FirstNames']) ?></td>
                <td><?= htmlspecialchars($row['LastName']) ?></td>
                <td><?= htmlspecialchars($row['Module_Name']) ?></td>
                <td><?= htmlspecialchars($row['Formative_Assessment']) ?></td>
                <td><?= htmlspecialchars($row['Summative_Assessment']) ?></td>
                <td><?= htmlspecialchars($row['Total_Marks']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a class="back-btn" href="dashboard.php" aria-label="Back to Dashboard">Back to Dashboard</a>
</div>
</body>
</html>

<?php
mysqli_close($conn);
?>
