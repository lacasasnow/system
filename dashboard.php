<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'DOS') {
    header("Location: login.php");
    exit();
}
$userRole = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Welcome DOS - Dashboard</title>
  <style>
    /* Reset and base */
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
      flex-direction: column;
    }

    /* Sticky top navigation */
    header {
      position: sticky;
      top: 0;
      background: #fff;
      border-bottom: 1px solid #e5e7eb;
      z-index: 1000;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 3rem;
      box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
    }
    header .logo {
      font-size: 1.75rem;
      font-weight: 700;
      color: #111827;
      user-select: none;
    }
    nav {
      display: flex;
      gap: 2rem;
      align-items: center;
    }
    nav a {
      font-weight: 600;
      color: #6b7280;
      font-size: 1rem;
      text-decoration: none;
      padding: 0.25rem 0.5rem;
      border-radius: 0.375rem;
      transition: color 0.3s ease, background-color 0.3s ease;
      user-select: none;
    }
    nav a[aria-current="page"],
    nav a:hover,
    nav a:focus-visible {
      color: #111827;
      background-color: #f3f4f6;
      outline: none;
    }

    /* Main content container */
    main {
      flex: 1;
      max-width: 1200px;
      margin: 4rem auto 6rem;
      padding: 0 2rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    /* Hero Headline */
    main h1 {
      font-weight: 800;
      font-size: 3.5rem;
      color: #111827;
      margin-bottom: 0.5rem;
      max-width: 600px;
      line-height: 1.1;
    }
    main p.subtext {
      margin-top: 0;
      font-size: 1.125rem;
      color: #6b7280;
      max-width: 500px;
      margin-bottom: 3rem;
    }

    /* Primary Call to Action button */
    .btn-primary {
      background: #111827;
      color: #fff;
      font-weight: 700;
      font-size: 1.25rem;
      padding: 1rem 3.5rem;
      border: none;
      border-radius: 0.75rem;
      cursor: pointer;
      text-decoration: none;
      user-select: none;
      box-shadow: 0 6px 16px rgb(17 24 39 / 0.25);
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }
    .btn-primary:hover, .btn-primary:focus-visible {
      background-color: #374151;
      box-shadow: 0 8px 24px rgb(55 65 81 / 0.4);
      outline: none;
    }

    /* Footer */
    footer {
      text-align: center;
      padding: 1.5rem 2rem;
      font-size: 0.875rem;
      color: #9ca3af;
      user-select: none;
      border-top: 1px solid #e5e7eb;
    }

    /* Responsive */
    @media (max-width: 640px) {
      main h1 {
        font-size: 2.25rem;
      }
      nav {
        gap: 1rem;
      }
      .btn-primary {
        width: 100%;
        padding: 1rem;
        font-size: 1.125rem;
      }
    }
  </style>
</head>
<body>
  <header role="banner">
    <div class="logo" aria-label="Gikonko TSS Logo">Gikonko TSS</div>
    <nav role="navigation" aria-label="Primary Navigation">
      <a href="dashboard.php" aria-current="page">Home</a>
      <a href="insert.php">Insert Data</a>
      <a href="select_all.php">Trainees</a>
       <a href="module.php">module</a>
        <a href="trade.php">Trade</a>
        <a href="mark.php">mark</a>
      <a href="competent.php">Competent</a>
      <a href="not competent.php">Not Competent</a>
      <a href="classfication.php">Reports</a>
      <a href="logout.php" style="color:#d32f2f;">Logout</a>
    </nav>
  </header>

  <main role="main">
    <h1>Welcome, <span aria-label="User role"><?= htmlspecialchars($userRole) ?></span></h1>
    <p class="subtext">
      This is your DOS dashboard —  to manage trainees, trades, marks, and view reports.
    </p>
    <a href="insert.php" class="btn-primary" role="button" aria-label="Navigate to Insert Data">Get Started</a>
  </main>

  <footer>
    &copy; <?= date('Y') ?> Gikonko TSS. All rights reserved.
  </footer>
</body>
</html>
