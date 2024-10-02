<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    $stmt = $mysqli->prepare("INSERT INTO assignments (title, description, due_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $description, $due_date);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Teacher Panel</h2>
    <h3>Create Assignment</h3>
    <form method="post">
        Title: <input type="text" name="title" required><br>
        Description: <textarea name="description" required></textarea><br>
        Due Date: <input type="datetime-local" name="due_date" required><br>
        <input type="submit" value="Create Assignment">
    </form>
    <a href="logout.php">Logout</a>
</body>
</html>
