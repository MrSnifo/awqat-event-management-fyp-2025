<?php
require_once "../controllers/auth.php";

$auth = new AuthController();
if (!$auth->verifySession()) {
    header("Location: ./login");
    exit();
}

if(!$auth->hasRole("superadmin") && !$auth->hasRole("admin")){
    header("Location: ./");
    exit();
}

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'awqat';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check ID
if (!isset($_GET['id'])) {
    die('No Event ID Provided.');
}

$event_id = (int)$_GET['id'];

// Update event
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $location = $conn->real_escape_string($_POST['location']);
    $start_date = $conn->real_escape_string($_POST['start_date']);
    $start_time = $conn->real_escape_string($_POST['start_time']);

    // Status is optional, set default if empty
    $status = isset($_POST['status']) && in_array($_POST['status'], ['blocked', 'unverified', 'verified']) 
        ? $conn->real_escape_string($_POST['status']) 
        : 'unverified';

    $sql_update = "UPDATE events 
                   SET title='$title', location='$location', status='$status', start_date='$start_date', start_time='$start_time' 
                   WHERE id=$event_id";
    if ($conn->query($sql_update)) {
        header('Location: ./events');
        exit;
    } else {
        echo "Update failed: " . $conn->error;
    }
}

// Get event
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard/edit-event.css">
</head>
<body class="">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <span>Dashboard</span>
        </div>
        
        <a href="dashboard" class="sidebar-link">
            <i class="fas fa-users"></i>
            <span>Users</span>
        </a>
        
        <a href="events" class="sidebar-link active">
            <i class="fas fa-calendar-alt"></i>
            <span>Events</span>
        </a>
        
        <div class="mt-auto">
            <a href="./logout" class="sidebar-link">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="edit-container">
            <h1 class="edit-title">Edit Event</h1>
            
            <form method="POST">
                <div class="mb-4">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($event['title']); ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($event['location']); ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="unverified" <?php echo $event['status'] == 'unverified' ? 'selected' : ''; ?>>
                            <span class="status-badge badge-unverified">Unverified</span>
                        </option>
                        <option value="verified" <?php echo $event['status'] == 'verified' ? 'selected' : ''; ?>>
                            <span class="status-badge badge-verified">Verified</span>
                        </option>
                        <option value="blocked" <?php echo $event['status'] == 'blocked' ? 'selected' : ''; ?>>
                            <span class="status-badge badge-blocked">Blocked</span>
                        </option>
                    </select>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($event['start_date']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Start Time</label>
                        <input type="time" name="start_time" class="form-control" value="<?php echo htmlspecialchars($event['start_time']); ?>" required>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Update Event
                    </button>
                    <a href="events" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Theme detection and application
        const currentTheme = localStorage.getItem('theme');
        if (currentTheme === 'dark') {
            document.body.classList.add('dark-theme');
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>