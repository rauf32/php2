<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $assignment_id = $_POST['assignment_id'];
    $user_id = $_SESSION['user_id'];
    $target_file = "uploads/" . basename($_FILES["file"]["name"]);

    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

    $stmt = $mysqli->prepare("INSERT INTO submissions (assignment_id, user_id, file_path) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $assignment_id, $user_id, $target_file);
    $stmt->execute();
}

$assignments = $mysqli->query("SELECT * FROM assignments")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Student Panel</h2>
    <h3>Available Assignments</h3>
    <table>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Due Date</th>
            <th>Submit</th>
        </tr>
        <?php foreach ($assignments as $assignment): ?>
            <tr>
                <td><?= htmlspecialchars($assignment['title']) ?></td>
                <td><?= htmlspecialchars($assignment['description']) ?></td>
                <td><?= htmlspecialchars($assignment['due_date']) ?></td>
                <td>
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="assignment_id" value="<?= $assignment['id'] ?>">
                        Upload File: <input type="file" name="file" required>
                        <input type="submit" value="Submit">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="logout.php">Logout</a>
</body>
</html>
