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

// Handle deletion
if (isset($_POST['delete_trainee'])) {
    $trainee_id = $_POST['delete_trainee'];
    $sql = "DELETE FROM Trainees WHERE Trainee_Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $trainee_id);
    if ($stmt->execute()) {
        $success = "Trainee deleted successfully!";
    } else {
        $error = "Error deleting trainee: " . $conn->error;
    }
    $stmt->close();
}

if (isset($_POST['delete_trade'])) {
    $trade_id = $_POST['delete_trade'];
    $sql = "DELETE FROM Trades WHERE Trade_Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $trade_id);
    if ($stmt->execute()) {
        $success = "Trade deleted successfully!";
    } else {
        $error = "Error deleting trade: " . $conn->error;
    }
    $stmt->close();
}

if (isset($_POST['delete_module'])) {
    $module_id = $_POST['delete_module'];
    $sql = "DELETE FROM Modules WHERE Module_Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $module_id);
    if ($stmt->execute()) {
        $success = "Module deleted successfully!";
    } else {
        $error = "Error deleting module: " . $conn->error;
    }
    $stmt->close();
}

if (isset($_POST['delete_marks'])) {
    $marks_id = $_POST['delete_marks'];
    $sql = "DELETE FROM Marks WHERE Mark_Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $marks_id);
    if ($stmt->execute()) {
        $success = "Marks deleted successfully!";
    } else {
        $error = "Error deleting marks: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Data</title>
    <style>
        body { background: #f0f4f8; font-family: 'Segoe UI', Arial, sans-serif; }
        .container { width: 600px; margin: 30px auto; background: #fff; border-radius: 10px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 30px 40px; }
        h2 { color: #1976d2; margin-bottom: 10px; }
        form { margin-bottom: 30px; }
        label { display: block; margin-bottom: 6px; color: #333; }
        input[type="text"], input[type="number"], select { width: 100%; padding: 10px; margin-bottom: 18px; border: 1px solid #d0d7de; border-radius: 5px; font-size: 16px; }
        .btn { padding: 10px 20px; background: #1976d2; color: #fff; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        .btn:hover { background: #125ea2; }
        .delete-btn { background: #d32f2f; color: #fff; padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer; }
        .delete-btn:hover { background: #b71c1c; }
        .success { color: #388e3c; text-align: center; margin-bottom: 10px; }
        .error { color: #d32f2f; text-align: center; margin-bottom: 10px; }
        .section { margin-bottom: 40px; }
        .back-btn { background: #1976d2; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-align: center; display: block; margin: 20px auto; }
        .back-btn:hover { background: #125ea2; }
    </style>
</head>
<body>
<div class="container">
    <h2>Manage Data</h2>
    <?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="section">
        <h3>Delete Trainee</h3>
        <form method="post">
            <label>Select Trainee</label>
            <select name="delete_trainee" required>
                <option value="">Select Trainee</option>
                <?php $trainees = mysqli_query($conn, "SELECT Trainee_Id, FirstNames, LastName FROM Trainees"); while ($t = mysqli_fetch_assoc($trainees)): ?>
                    <option value="<?= $t['Trainee_Id'] ?>"><?= htmlspecialchars($t['FirstNames'] . " " . $t['LastName']) ?></option>
                <?php endwhile; ?>
            </select>
            <button class="delete-btn" type="submit">Delete Trainee</button>
        </form>
    </div>

    <div class="section">
        <h3>Delete Trade</h3>
        <form method="post">
            <label>Select Trade</label>
            <select name="delete_trade" required>
                <option value="">Select Trade</option>
                <?php $trades = mysqli_query($conn, "SELECT Trade_Id, Trade_Name FROM Trades"); while ($t = mysqli_fetch_assoc($trades)): ?>
                    <option value="<?= $t['Trade_Id'] ?>"><?= htmlspecialchars($t['Trade_Name']) ?></option>
                <?php endwhile; ?>
            </select>
            <button class="delete-btn" type="submit">Delete Trade</button>
        </form>
    </div>

    <div class="section">
        <h3>Delete Module</h3>
        <form method="post">
            <label>Select Module</label>
            <select name="delete_module" required>
                <option value="">Select Module</option>
                <?php $modules = mysqli_query($conn, "SELECT Module_Id, Module_Name FROM Modules"); while ($m = mysqli_fetch_assoc($modules)): ?>
                    <option value="<?= $m['Module_Id'] ?>"><?= htmlspecialchars($m['Module_Name']) ?></option>
                <?php endwhile; ?>
            </select>
            <button class="delete-btn" type="submit">Delete Module</button>
        </form>
    </div>

    <div class="section">
        <h3>Delete Marks</h3>
        <form method="post">
            <label>Select Marks</label>
            <select name="delete_marks" required>
                <option value="">Select Marks Entry</option>
                <?php $marks = mysqli_query($conn, "SELECT Mark_Id FROM Marks"); while ($m = mysqli_fetch_assoc($marks)): ?>
                    <option value="<?= $m['Mark_Id'] ?>">Marks ID <?= htmlspecialchars($m['Mark_Id']) ?></option>
                <?php endwhile; ?>
            </select>
            <button class="delete-btn" type="submit">Delete Marks</button>
        </form>
    </div>

    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>

</div>
</body>
</html>

<?php mysqli_close($conn); ?>
