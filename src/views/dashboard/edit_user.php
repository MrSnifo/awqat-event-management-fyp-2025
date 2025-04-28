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
    die('No User ID Provided.');
}

$user_id = (int)$_GET['id'];

// Update
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
        header('Location: dashboard?message=User Updated Successfully');
        exit;
    } else {
        echo "Update failed: " . $conn->error;
    }
}

// Récupérer l'utilisateur
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
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard/edit.css">
</head>
<body class="bg-dark text-white">

<div class="container mt-5">
    <h2>Edit User</h2>
    <form method="POST" class="bg-secondary p-4 rounded">
        <div class="mb-3">
            <label class="form-label">Username:</label>
            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Role:</label>
            <select name="role" class="form-select" required>
                <option value="user" <?php if($user['role'] == 'user') echo 'selected'; ?>>User</option>
                <option value="verified" <?php if($user['role'] == 'verified') echo 'selected'; ?>>Verified</option>
                <option value="admin" <?php if($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                <option value="superadmin" <?php if($user['role'] == 'superadmin') echo 'selected'; ?>>Superadmin</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Status:</label>
            <select name="status" class="form-select" required>
                <option value="active" <?php if($user['status'] == 'active') echo 'selected'; ?>>Active</option>
                <option value="inactive" <?php if($user['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                <option value="banned" <?php if($user['status'] == 'banned') echo 'selected'; ?>>Banned</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Profile Description:</label>
            <textarea name="profile_description" class="form-control"><?php echo htmlspecialchars($user['profile_description']); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="dashboard" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>
