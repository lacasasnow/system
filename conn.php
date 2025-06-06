<?php
$conn = mysqli_connect("localhost", "root", "");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "CREATE DATABASE IF NOT EXISTS gikonko_tss";
if (mysqli_query($conn, $sql)) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn) . "<br>";
}

// Select the database
mysqli_select_db($conn, "gikonko_tss");

// Create Trades table
$tradesTable = "CREATE TABLE IF NOT EXISTS Trades (
    Trade_Id INT AUTO_INCREMENT PRIMARY KEY,
    Trade_Name VARCHAR(100) NOT NULL
)";

if (mysqli_query($conn, $tradesTable)) {
    echo "Trades table created successfully<br>";
} else {
    echo "Error creating Trades table: " . mysqli_error($conn) . "<br>";
}

// Create Trainees table
$traineesTable = "CREATE TABLE IF NOT EXISTS Trainees (
    Trainee_Id INT AUTO_INCREMENT PRIMARY KEY,
    FirstNames VARCHAR(100) NOT NULL,
    LastName VARCHAR(100) NOT NULL,
    Gender ENUM('Male', 'Female', 'Other') NOT NULL,
    Trade_Id INT,
    FOREIGN KEY (Trade_Id) REFERENCES Trades(Trade_Id)
)";

if (mysqli_query($conn, $traineesTable)) {
    echo "Trainees table created successfully<br>";
} else {
    echo "Error creating Trainees table: " . mysqli_error($conn) . "<br>";
}

// Create Modules table
$modulesTable = "CREATE TABLE IF NOT EXISTS Modules (
    Module_Id INT AUTO_INCREMENT PRIMARY KEY,
    Module_Name VARCHAR(100) NOT NULL,
    Trade_Id INT,
    FOREIGN KEY (Trade_Id) REFERENCES Trades(Trade_Id)
)";

if (mysqli_query($conn, $modulesTable)) {
    echo "Modules table created successfully<br>";
} else {
    echo "Error creating Modules table: " . mysqli_error($conn) . "<br>";
}

// Create Marks table
$marksTable = "CREATE TABLE IF NOT EXISTS Marks (
    Mark_Id INT AUTO_INCREMENT PRIMARY KEY,
    Trainee_Id INT,
    Module_Id INT,
    Formative_Assessment INT CHECK (Formative_Assessment BETWEEN 0 AND 50),
    Summative_Assessment INT CHECK (Summative_Assessment BETWEEN 0 AND 50),
    Total_Marks INT GENERATED ALWAYS AS (Formative_Assessment + Summative_Assessment) STORED,
    FOREIGN KEY (Trainee_Id) REFERENCES Trainees(Trainee_Id),
    FOREIGN KEY (Module_Id) REFERENCES Modules(Module_Id)
)";

if (mysqli_query($conn, $marksTable)) {
    echo "Marks table created successfully<br>";
} else {
    echo "Error creating Marks table: " . mysqli_error($conn) . "<br>";
}

// Create Users table
$usersTable = "CREATE TABLE IF NOT EXISTS Users (
    User_Id INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(100) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Role ENUM('DOS', 'Admin', 'Teacher') NOT NULL
)";

if (mysqli_query($conn, $usersTable)) {
    echo "Users table created successfully<br>";
} else {
    echo "Error creating Users table: " . mysqli_error($conn) . "<br>";
}

// Insert default DOS account if not exists
$dos_username = 'dos';
$dos_password = password_hash('dos123', PASSWORD_DEFAULT); // Default password: dos123
$dos_role = 'DOS';

// Check if DOS user exists
$checkUser = mysqli_query($conn, "SELECT * FROM Users WHERE Username='$dos_username'");
if (mysqli_num_rows($checkUser) == 0) {
    $stmt = $conn->prepare("INSERT INTO Users (Username, Password, Role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $dos_username, $dos_password, $dos_role);
    if ($stmt->execute()) {
        echo "Default DOS account created (username: dos, password: dos123)<br>";
    } else {
        echo "Error creating DOS account: " . $conn->error . "<br>";
    }
    $stmt->close();
} else {
    echo "DOS account already exists<br>";
}

// Insert sample trades
$trades = [
    ['ICT'],
    ['Multimedia'],
    ['Accounting'],
    ['Carpentry'],
    ['Electrical'],
    ['Tailoring'],
    ['Welding']
];
foreach ($trades as $trade) {
    mysqli_query($conn, "INSERT IGNORE INTO Trades (Trade_Name) VALUES ('{$trade[0]}')");
}

// Get Trade_Ids
$trade_ids = [];
$result = mysqli_query($conn, "SELECT Trade_Id, Trade_Name FROM Trades");
while ($row = mysqli_fetch_assoc($result)) {
    $trade_ids[$row['Trade_Name']] = $row['Trade_Id'];
}

// Insert sample modules
$modules = [
    ['ICT Basics', $trade_ids['ICT']],
    ['Graphic Design', $trade_ids['Multimedia']],
    ['Bookkeeping', $trade_ids['Accounting']],
    ['Woodwork', $trade_ids['Carpentry']],
    ['Pipe Fitting', $trade_ids['Plumbing']],
    ['Sewing', $trade_ids['Tailoring']],
    ['Metalwork', $trade_ids['Welding']]
];
foreach ($modules as $module) {
    mysqli_query($conn, "INSERT IGNORE INTO Modules (Module_Name, Trade_Id) VALUES ('{$module[0]}', {$module[1]})");
}

// Get Module_Ids
$module_ids = [];
$result = mysqli_query($conn, "SELECT Module_Id, Module_Name FROM Modules");
while ($row = mysqli_fetch_assoc($result)) {
    $module_ids[$row['Module_Name']] = $row['Module_Id'];
}

// Insert 7 trainees
$trainees = [
    ['Alice', 'Smith', 'Female', $trade_ids['ICT']],
    ['Bob', 'Johnson', 'Male', $trade_ids['Multimedia']],
    ['Carol', 'Williams', 'Female', $trade_ids['Accounting']],
    ['David', 'Brown', 'Male', $trade_ids['Carpentry']],
    ['Eve', 'Jones', 'Female', $trade_ids['Plumbing']],
    ['Frank', 'Garcia', 'Male', $trade_ids['Tailoring']],
    ['Grace', 'Martinez', 'Female', $trade_ids['Welding']]
];
foreach ($trainees as $t) {
    mysqli_query($conn, "INSERT IGNORE INTO Trainees (FirstNames, LastName, Gender, Trade_Id) VALUES ('{$t[0]}', '{$t[1]}', '{$t[2]}', {$t[3]})");
}

// Get Trainee_Ids
$trainee_ids = [];
$result = mysqli_query($conn, "SELECT Trainee_Id, FirstNames FROM Trainees");
while ($row = mysqli_fetch_assoc($result)) {
    $trainee_ids[$row['FirstNames']] = $row['Trainee_Id'];
}

// Insert marks for each trainee in their module
$marks = [
    ['Alice', 'ICT Basics', 45, 48],
    ['Bob', 'Graphic Design', 40, 42],
    ['Carol', 'Bookkeeping', 38, 44],
    ['David', 'Woodwork', 35, 39],
    ['Eve', 'Pipe Fitting', 41, 40],
    ['Frank', 'Sewing', 37, 36],
    ['Grace', 'Metalwork', 44, 45]
];
foreach ($marks as $m) {
    $trainee_id = $trainee_ids[$m[0]];
    $module_id = $module_ids[$m[1]];
    $formative = $m[2];
    $summative = $m[3];
    mysqli_query($conn, "INSERT IGNORE INTO Marks (Trainee_Id, Module_Id, Formative_Assessment, Summative_Assessment) VALUES ($trainee_id, $module_id, $formative, $summative)");
}

mysqli_close($conn);
?>