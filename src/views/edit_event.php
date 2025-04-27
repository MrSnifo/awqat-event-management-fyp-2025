<?php
// Connexion
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'ouqat';

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
    $status = $conn->real_escape_string($_POST['status']);
    $start_date = $conn->real_escape_string($_POST['start_date']);

    $sql_update = "UPDATE events SET title='$title', location='$location', status='$status', start_date='$start_date' WHERE id=$event_id";
    if ($conn->query($sql_update)) {
        header('Location: dashboard.php?message=Event Updated Successfully');
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
    <link href="edit_event.css" rel="stylesheet"> <!-- Liens CSS externe -->
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
                <option value="active" <?php if($event['status'] == 'active') echo 'selected'; ?>>Active</option>
                <option value="inactive" <?php if($event['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Start Date:</label>
            <input type="date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($event['start_date']); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Event</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>
