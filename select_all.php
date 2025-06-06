<?php
function connectToDatabase() {
    $conn = mysqli_connect("localhost", "root", "", "gikonko_tss");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

function fetchTrainees($conn) {
    $sql = "
    SELECT DISTINCT
        t.Trainee_Id,
        t.FirstNames,
        t.LastName,
        t.Gender,
        tr.Trade_Name,
        m.Module_Name,
        mk.Formative_Assessment,
        mk.Summative_Assessment,
        mk.Total_Marks
    FROM Trainees t
    LEFT JOIN Trades tr ON t.Trade_Id = tr.Trade_Id
    LEFT JOIN Marks mk ON t.Trainee_Id = mk.Trainee_Id
    LEFT JOIN Modules m ON mk.Module_Id = m.Module_Id
    ORDER BY t.Trainee_Id ASC
    ";

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        error_log("Error executing query: " . mysqli_error($conn)); // Log the error
        die("An error occurred while fetching data. Please try again later."); // User-friendly message
    }
    return $result;
}

function renderTable($result) {
    echo "<table border='1' cellpadding='8' cellspacing='0'>
            <tr style='background-color: #f2f2f2;'>
                <th>Trainee_Id</th>
                <th>FirstNames</th>
                <th>LastName</th>
                <th>Gender</th>
                <th>Trade</th>
                <th>Module</th>
                <th>Formative</th>
                <th>Summative</th>
                <th>Total Marks</th>
                <th>Status</th>
            </tr>";

    $competent = [];
    $nyc = [];

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Calculate status
            $status = "-";
            if (is_numeric($row['Total_Marks'])) {
                $percent = ($row['Total_Marks'] / 100) * 100;
                if ($percent >= 70) {
                    $status = "Competent";
                    $competent[] = $row;
                } else {
                    $status = "NYC";
                    $nyc[] = $row;
                }
            }

            echo "<tr>
                    <td>" . htmlspecialchars($row['Trainee_Id']) . "</td>
                    <td>" . htmlspecialchars($row['FirstNames']) . "</td>
                    <td>" . htmlspecialchars($row['LastName']) . "</td>
                    <td>" . htmlspecialchars($row['Gender']) . "</td>
                    <td>" . htmlspecialchars($row['Trade_Name']) . "</td>
                    <td>" . htmlspecialchars($row['Module_Name']) . "</td>
                    <td>" . htmlspecialchars($row['Formative_Assessment']) . "</td>
                    <td>" . htmlspecialchars($row['Summative_Assessment']) . "</td>
                    <td>" . htmlspecialchars($row['Total_Marks']) . "</td>
                    <td>" . $status . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No records found.</td></tr>";
    }
    echo "</table>";

    return [$competent, $nyc];
}

function renderCompetentTrainees($competent) {
    echo "<h3 style='color:#1976d2;margin-top:40px;'>Competent Trainees (Total Marks ≥ 70)</h3>";
    if (count($competent) > 0) {
        echo "<table border='1' cellpadding='8' cellspacing='0'>
                <tr style='background-color: #e8f5e9;'>
                    <th>Trainee_Id</th>
                    <th>FirstNames</th>
                    <th>LastName</th>
                    <th>Trade</th>
                    <th>Module</th>
                    <th>Total Marks</th>
                </tr>";
        foreach ($competent as $row) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['Trainee_Id']) . "</td>
                    <td>" . htmlspecialchars($row['FirstNames']) . "</td>
                    <td>" . htmlspecialchars($row['LastName']) . "</td>
                    <td>" . htmlspecialchars($row['Trade_Name']) . "</td>
                    <td>" . htmlspecialchars($row['Module_Name']) . "</td>
                    <td>" . htmlspecialchars($row['Total_Marks']) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<div style='color:#388e3c;'>No Competent trainees found.</div>";
    }
}

function renderNYCTrainees($nyc) {
    echo "<h3 style='color:#d32f2f;margin-top:40px;'>Not Yet Competent (NYC) Trainees (Total Marks &lt; 70)</h3>";
    if (count($nyc) > 0) {
        echo "<table border='1' cellpadding='8' cellspacing='0'>
                <tr style='background-color: #ffebee;'>
                    <th>Trainee_Id</th>
                    <th>FirstNames</th>
                    <th>LastName</th>
                    <th>Trade</th>
                    <th>Module</th>
                    <th>Total Marks</th>
                </tr>";
        foreach ($nyc as $row) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['Trainee_Id']) . "</td>
                    <td>" . htmlspecialchars($row['FirstNames']) . "</td>
                    <td>" . htmlspecialchars($row['LastName']) . "</td>
                    <td>" . htmlspecialchars($row['Trade_Name']) . "</td>
                    <td>" . htmlspecialchars($row['Module_Name']) . "</td>
                    <td>" . htmlspecialchars($row['Total_Marks']) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<div style='color:#d32f2f;'>No NYC trainees found.</div>";
    }
}

$conn = connectToDatabase();
$result = fetchTrainees($conn);

echo "<div style='margin: 20px 0; text-align: center;'>
        <a href='insert.php' style='display:inline-block;padding:10px 20px;background:#1976d2;color:#fff;text-decoration:none;border-radius:5px;'>Back</a>
      </div>";

list($competent, $nyc) = renderTable($result);
renderCompetentTrainees($competent);
renderNYCTrainees($nyc);

mysqli_close($conn);
?>
