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

$success = "";
$error = "";

// Fetch trades for dropdown
$trades = mysqli_query($conn, "SELECT Trade_Id, Trade_Name FROM Trades");

// Fetch modules for dropdown
$modules = mysqli_query($conn, "SELECT Module_Id, Module_Name FROM Modules");

// Fetch trainees for marks entry
$trainees = mysqli_query($conn, "SELECT Trainee_Id, FirstNames, LastName FROM Trainees");

// Handle trainee insert
if (isset($_POST['add_trainee'])) {
    $fname = $_POST['firstnames'];
    $lname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $trade_id = $_POST['trade_id'];
    $sql = "INSERT INTO Trainees (FirstNames, LastName, Gender, Trade_Id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $fname, $lname, $gender, $trade_id);
    if ($stmt->execute()) {
        $success = "Trainee added successfully!";
    } else {
        $error = "Error adding trainee: " . $conn->error;
    }
    $stmt->close();
}

// Handle trade insert
if (isset($_POST['add_trade'])) {
    $trade_name = $_POST['trade_name'];
    $sql = "INSERT INTO Trades (Trade_Name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $trade_name);
    if ($stmt->execute()) {
        $success = "Trade added successfully!";
    } else {
        $error = "Error adding trade: " . $conn->error;
    }
    $stmt->close();
}

// Handle module insert
if (isset($_POST['add_module'])) {
    $module_name = $_POST['module_name'];
    $trade_id = $_POST['module_trade_id'];
    $sql = "INSERT INTO Modules (Module_Name, Trade_Id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $module_name, $trade_id);
    if ($stmt->execute()) {
        $success = "Module added successfully!";
    } else {
        $error = "Error adding module: " . $conn->error;
    }
    $stmt->close();
}

// Handle marks insert
if (isset($_POST['add_marks'])) {
    $trainee_id = $_POST['marks_trainee_id'];
    $module_id = $_POST['marks_module_id'];
    $formative = $_POST['formative'];
    $summative = $_POST['summative'];
    if ($formative < 0 || $formative > 50 || $summative < 0 || $summative > 50) {
        $error = "Assessments must be between 0 and 50.";
    } else {
        $sql = "INSERT INTO Marks (Trainee_Id, Module_Id, Formative_Assessment, Summative_Assessment)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE Formative_Assessment=VALUES(Formative_Assessment), Summative_Assessment=VALUES(Summative_Assessment)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $trainee_id, $module_id, $formative, $summative);
        if ($stmt->execute()) {
            $success = "Marks added/updated successfully!";
        } else {
            $error = "Error adding marks: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert Data</title>
    <style>
        body { background: #f0f4f8; font-family: 'Segoe UI', Arial, sans-serif; }
        .container { width: 600px; margin: 30px auto; background: #fff; border-radius: 10px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 30px 40px; }
        h2 { color: #1976d2; margin-bottom: 10px; }
        form { margin-bottom: 30px; }
        label { display: block; margin-bottom: 6px; color: #333; }
        input[type="text"], input[type="number"], select { width: 100%; padding: 10px; margin-bottom: 18px; border: 1px solid #d0d7de; border-radius: 5px; font-size: 16px; }
        .btn { padding: 10px 20px; background: #1976d2; color: #fff; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        .btn:hover { background: #125ea2; }
        .success { color: #388e3c; text-align: center; margin-bottom: 10px; }
        .error { color: #d32f2f; text-align: center; margin-bottom: 10px; }
        .section { margin-bottom: 40px; }
        .link { display:inline-block; margin:20px 0; padding:10px 20px; background:#1976d2; color:#fff; text-decoration:none; border-radius:5px; }
        .back-btn { display: inline-block; margin: 20px 0; padding: 10px 20px; background: #1976d2; color: #fff; text-decoration: none; border-radius: 5px; }
        .back-btn:hover { background: #125ea2; }
    </style>
</head>
<body>
<div class="container">
    <h2>Insert Data</h2>
    <?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="section">
        <h3>Add Trainee</h3>
        <form method="post">
            <label>First Names</label>
            <input type="text" name="firstnames" required>
            <label>Last Name</label>
            <input type="text" name="lastname" required>
            <label>Gender</label>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            <label>Trade</label>
            <select name="trade_id" required>
                <option value="">Select Trade</option>
                <?php mysqli_data_seek($trades, 0); while ($t = mysqli_fetch_assoc($trades)): ?>
                    <option value="<?= $t['Trade_Id'] ?>"><?= htmlspecialchars($t['Trade_Name']) ?></option>
                <?php endwhile; ?>
            </select>
            <button class="btn" type="submit" name="add_trainee">Add Trainee</button>
        </form>
    </div>

    <div class="section">
        <h3>Add Trade</h3>
        <form method="post">
            <label>Trade Name</label>
            <input type="text" name="trade_name" required>
            <button class="btn" type="submit" name="add_trade">Add Trade</button>
        </form>
    </div>

    <div class="section">
        <h3>Add Module</h3>
        <form method="post">
            <label>Module Name</label>
            <input type="text" name="module_name" required>
            <label>Trade</label>
            <select name="module_trade_id" required>
                <option value="">Select Trade</option>
                <?php mysqli_data_seek($trades, 0); while ($t = mysqli_fetch_assoc($trades)): ?>
                    <option value="<?= $t['Trade_Id'] ?>"><?= htmlspecialchars($t['Trade_Name']) ?></option>
                <?php endwhile; ?>
            </select>
            <button class="btn" type="submit" name="add_module">Add Module</button>
        </form>
    </div>

    <div class="section">
        <h3>Add/Update Marks</h3>
        <form method="post">
            <label>Trainee</label>
            <select name="marks_trainee_id" required>
                <option value="">Select Trainee</option>
                <?php mysqli_data_seek($trainees, 0); while ($tr = mysqli_fetch_assoc($trainees)): ?>
                    <option value="<?= $tr['Trainee_Id'] ?>"><?= htmlspecialchars($tr['FirstNames'] . ' ' . $tr['LastName']) ?></option>
                <?php endwhile; ?>
            </select>
            <label>Module</label>
            <select name="marks_module_id" required>
                <option value="">Select Module</option>
                <?php mysqli_data_seek($modules, 0); while ($m = mysqli_fetch_assoc($modules)): ?>
                    <option value="<?= $m['Module_Id'] ?>"><?= htmlspecialchars($m['Module_Name']) ?></option>
                <?php endwhile; ?>
            </select>
            <label>Formative Assessment (/50)</label>
            <input type="number" name="formative" min="0" max="50" required>
            <label>Summative Assessment (/50)</label>
            <input type="number" name="summative" min="0" max="50" required>
            <button class="btn" type="submit" name="add_marks">Add/Update Marks</button>
        </form>
    </div>

    <div style="text-align:center;">
        <a class="link" href="select_all.php">View All Trainee Records</a>
    </div>
    
    <a class="back-btn" href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>
