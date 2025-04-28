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
    die('No User ID Provided.');
}

$user_id = (int)$_GET['id'];

// Update user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);
    $status = $conn->real_escape_string($_POST['status']);
    $profile_description = $conn->real_escape_string($_POST['profile_description']);

    $sql_update = "UPDATE users 
                   SET username='$username', email='$email', role='$role', status='$status', profile_description='$profile_description' 
                   WHERE id=$user_id";

    if ($conn->query($sql_update)) {
        header('Location: dashboard?message=User+Updated+Successfully');
        exit;
    } else {
        echo "Update failed: " . $conn->error;
    }
}

// Get user
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
if ($result->num_rows != 1) {
    die('User Not Found.');
}
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard/edit-user.css">
</head>
<body class="">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <span>Dashboard</span>
        </div>
        
        <a href="dashboard" class="sidebar-link active">
            <i class="fas fa-users"></i>
            <span>Users</span>
        </a>
        
        <a href="events" class="sidebar-link">
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
            <h1 class="edit-title">Edit User</h1>
            
            <form method="POST">
                <div class="mb-4">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>
                            <span class="role-badge badge-user">User</span>
                        </option>
                        <option value="verified" <?php echo $user['role'] == 'verified' ? 'selected' : ''; ?>>
                            <span class="role-badge badge-verified">Verified</span>
                        </option>
                        <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>
                            <span class="role-badge badge-admin">Admin</span>
                        </option>
                        <option value="superadmin" <?php echo $user['role'] == 'superadmin' ? 'selected' : ''; ?>>
                            <span class="role-badge badge-superadmin">Superadmin</span>
                        </option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="active" <?php echo $user['status'] == 'active' ? 'selected' : ''; ?>>
                            <span class="status-badge badge-active">Active</span>
                        </option>
                        <option value="inactive" <?php echo $user['status'] == 'inactive' ? 'selected' : ''; ?>>
                            <span class="status-badge badge-inactive">Inactive</span>
                        </option>
                        <option value="banned" <?php echo $user['status'] == 'banned' ? 'selected' : ''; ?>>
                            <span class="status-badge badge-banned">Banned</span>
                        </option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Profile Description</label>
                    <textarea name="profile_description" class="form-control form-textarea" rows="4"><?php echo htmlspecialchars($user['profile_description']); ?></textarea>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Update User
                    </button>
                    <a href="dashboard" class="btn btn-secondary">
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