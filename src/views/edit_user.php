<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'ouqat';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
} else {
    die('No user ID specified.');
}

// Update user data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $profile_description = trim($_POST['profile_description']);
    $profile_picture_url = trim($_POST['profile_picture_url']);
    $role = $_POST['role'];
    $status = $_POST['status'];

    $sql = "UPDATE users SET username = ?, email = ?, profile_description = ?, profile_picture_url = ?, role = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $username, $email, $profile_description, $profile_picture_url, $role, $status, $id);
    
    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-primary text-white">
      <h2>Edit User</h2>
    </div>
    <div class="card-body">
      <form method="POST" action="">
        <div class="mb-3">
          <label for="username" class="form-label">Name</label>
          <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3">
          <label for="profile_description" class="form-label">Profile Description</label>
          <textarea class="form-control" id="profile_description" name="profile_description" rows="3"><?php echo htmlspecialchars($user['profile_description']); ?></textarea>
        </div>
        <div class="mb-3">
          <label for="profile_picture_url" class="form-label">Profile Picture URL</label>
          <input type="text" class="form-control" id="profile_picture_url" name="profile_picture_url" value="<?php echo htmlspecialchars($user['profile_picture_url']); ?>">
        </div>
        <div class="mb-3">
          <label for="role" class="form-label">Role</label>
          <select class="form-select" id="role" name="role" required>
            <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
            <option value="verified" <?php if ($user['role'] == 'verified') echo 'selected'; ?>>Verified</option>
            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
            <option value="superadmin" <?php if ($user['role'] == 'superadmin') echo 'selected'; ?>>Superadmin</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="status" class="form-label">Status</label>
          <select class="form-select" id="status" name="status" required>
            <option value="active" <?php if ($user['status'] == 'active') echo 'selected'; ?>>Active</option>
            <option value="inactive" <?php if ($user['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
            <option value="banned" <?php if ($user['status'] == 'banned') echo 'selected'; ?>>Banned</option>
          </select>
        </div>
        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
      </form>
    </div>
  </div>
</div>

</body>
</html>

<?php
$conn->close();
?>
