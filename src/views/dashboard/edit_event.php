<?php

require_once "../controllers/auth.php";


$auth = new AuthController();
if (!$auth->verifySession()) {
    header("Location: ./login");
    exit();
}


if(!$auth->hasRole("admin")){
    header("Location: ./");
    exit();
}

// Connexion
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'awqat';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier l'ID
if (!isset($_GET['id'])) {
    die('No Event ID Provided.');
}

$event_id = (int)$_GET['id'];

// Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $location = $conn->real_escape_string($_POST['location']);
    $start_date = $conn->real_escape_string($_POST['start_date']);
    $start_time = $conn->real_escape_string($_POST['start_time']);

    // Status is optional, set default if empty
    $status = isset($_POST['status']) && in_array($_POST['status'], ['blocked', 'unverified', 'verified']) 
        ? $conn->real_escape_string($_POST['status']) 
        : 'unverified'; // Default fallback if status invalid or missing

    // Mise à jour de l'événement
    $sql_update = "UPDATE events 
                   SET title='$title', location='$location', status='$status', start_date='$start_date', start_time='$start_time' 
                   WHERE id=$event_id";
    if ($conn->query($sql_update)) {
        header('Location: ./eventsTable');
        exit;
    } else {
        echo "Update failed: " . $conn->error;
    }
}

// Récupérer l'event
$sql = "SELECT * FROM events WHERE id = $event_id";
$result = $conn->query($sql);
if ($result->num_rows != 1) {
    die('Event Not Found.');
}
$event = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard/edit.css">
</head>
<body class="bg-dark text-white">

<div class="container mt-5">
    <h2>Edit Event</h2>
    <form method="POST" class="bg-secondary p-4 rounded">
        <div class="mb-3">
            <label class="form-label">Title:</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($event['title']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Location:</label>
            <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($event['location']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status:</label>
            <select name="status" class="form-select" required>
                <option value="unverified" <?php if($event['status'] == 'unverified') echo 'selected'; ?>>Unverified</option>
                <option value="verified" <?php if($event['status'] == 'verified') echo 'selected'; ?>>Verified</option>
                <option value="blocked" <?php if($event['status'] == 'blocked') echo 'selected'; ?>>Blocked</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Start Date:</label>
            <input type="date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($event['start_date']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Start Time:</label>
            <input type="time" name="start_time" class="form-control" value="<?php echo htmlspecialchars($event['start_time']); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Event</button>
        <a href="dashboard" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>
