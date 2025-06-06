<?php
$conn = mysqli_connect("localhost", "root", "", "gikonko_tss");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM Trainees";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error executing query: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Trainee Records</title>
    <style>
        /* Base styles */
        *, *::before, *::after {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            background: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #6b7280;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            padding: 3rem 1rem;
        }
        .container {
            max-width: 900px;
            width: 100%;
            background: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 4px 16px rgba(25, 118, 210, 0.1);
            padding: 2.5rem 3rem;
            display: flex;
            flex-direction: column;
        }
        h1 {
            font-weight: 700;
            font-size: 3rem;
            color: #111827;
            margin-bottom: 2rem;
            text-align: center;
            user-select: none;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.75rem;
            margin-bottom: 2rem;
        }
        thead tr {
            background-color: #1976d2;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.875rem;
            user-select: none;
            border-radius: 0.75rem;
        }
        thead th {
            padding: 1rem 1.5rem;
        }
        tbody tr {
            background: #f9fafb;
            box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
            border-radius: 0.75rem;
            transition: background-color 0.3s ease;
        }
        tbody tr:hover {
            background: #e3f2fd;
        }
        tbody td {
            padding: 1rem 1.5rem;
            color: #374151;
            font-weight: 500;
            border-bottom: none;
        }
        tbody td:first-child {
            border-top-left-radius: 0.75rem;
            border-bottom-left-radius: 0.75rem;
        }
        tbody td:last-child {
            border-top-right-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            white-space: nowrap;
        }
        .btn-edit {
            display: inline-block;
            padding: 0.5rem 1.25rem;
            font-weight: 700;
            font-size: 0.875rem;
            color: #111827;
            background-color: transparent;
            border: 2px solid #f59e0b; /* Amber-500 */
            border-radius: 0.5rem;
            cursor: pointer;
            text-decoration: none;
            user-select: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .btn-edit:hover,
        .btn-edit:focus-visible {
            background-color: #fbbf24; /* Amber-400 */
            color: #1a202c; /* Gray-900 */
            outline: none;
        }
        .btn-back {
            align-self: center;
            padding: 0.75rem 2.5rem;
            font-weight: 700;
            font-size: 1rem;
            color: #fff;
            background-color: #111827; /* Gray-900 */
            border-radius: 0.75rem;
            text-decoration: none;
            box-shadow: 0 10px 24px rgba(17, 24, 39, 0.3);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            user-select: none;
        }
        .btn-back:hover,
        .btn-back:focus-visible {
            background-color: #374151; /* Gray-700 */
            box-shadow: 0 14px 40px rgba(55, 65, 81, 0.6);
            outline: none;
        }
        @media (max-width: 640px) {
            body {
                padding: 2rem 1rem;
            }
            h1 {
                font-size: 2rem;
                margin-bottom: 1.5rem;
            }
            .container {
                padding: 1.5rem 1.5rem;
            }
            thead th, tbody td {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }
            .btn-edit {
                padding: 0.4rem 1rem;
                font-size: 0.8rem;
            }
            .btn-back {
                width: 100%;
                text-align: center;
                padding: 1rem;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
<div class="container" role="main" aria-label="Trainee Records Table">
    <h1>Trainee Records</h1>
    <table>
        <thead>
            <tr>
                <th scope="col">Trainee ID</th>
                <th scope="col">First Names</th>
                <th scope="col">Last Name</th>
                <th scope="col">Gender</th>
                <th scope="col">Trade ID</th>
                <th scope="col">Edit</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['Trainee_Id']) ?></td>
                <td><?= htmlspecialchars($row['FirstNames']) ?></td>
                <td><?= htmlspecialchars($row['LastName']) ?></td>
                <td><?= htmlspecialchars($row['Gender']) ?></td>
                <td><?= htmlspecialchars($row['Trade_Id']) ?></td>
                <td>
                    <a
                        href="insert.php?id=<?= urlencode($row['Trainee_Id']) ?>"
                        class="btn-edit"
                        aria-label="Edit trainee <?= htmlspecialchars($row['FirstNames'] . ' ' . $row['LastName']) ?>"
                    >
                        Edit
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="btn-back" aria-label="Back to Dashboard">Back to Dashboard</a>
</div>
<?php
mysqli_close($conn);
?>
</body>
</html>
