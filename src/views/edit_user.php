<?php
// Connexion à la base
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'ouqat';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifie si ID est passé dans l'URL
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    
    if ($userId <= 0) {
        echo "ID invalide.";
        exit;
    }

    // Récupérer l'utilisateur
    $sql = "SELECT * FROM users WHERE id = $userId";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    } else {
        echo "Utilisateur non trouvé.";
        exit;
    }
} else {
    echo "ID non fourni.";
    exit;
}

// Mise à jour du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password_hash = $conn->real_escape_string($_POST['password_hash']);
    $profile_description = $conn->real_escape_string($_POST['profile_description']);
    $profile_picture_url = $conn->real_escape_string($_POST['profile_picture_url']);
    $social_links = $conn->real_escape_string($_POST['social_links']);
    $role = $conn->real_escape_string($_POST['role']);
    $status = $conn->real_escape_string($_POST['status']);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email invalide.";
        exit;
    }

    // Mise à jour dans la base de données
    $updateQuery = "UPDATE users 
                    SET username='$username', email='$email', password_hash='$password_hash',
                        profile_description='$profile_description', profile_picture_url='$profile_picture_url',
                        social_links='$social_links', role='$role', status='$status'
                    WHERE id=$userId";
    
    if ($conn->query($updateQuery)) {
        echo "<script>alert('Utilisateur mis à jour avec succès.'); window.location.href='dashboard.php';</script>";
    } else {
        echo "Erreur lors de la mise à jour: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Edit User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="edit_event.css" rel="stylesheet">
</head>
<body class="bg-light p-5">

<div class="container">
    <h2>Edit User</h2>
    <form method="post" class="mt-4">
        <div class="mb-3">
            <label for="username" class="form-label">Name:</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="password_hash" class="form-label">Password Hash:</label>
            <input type="text" name="password_hash" id="password_hash" class="form-control" value="<?php echo htmlspecialchars($user['password_hash']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="profile_description" class="form-label">Profile Description:</label>
            <textarea name="profile_description" id="profile_description" class="form-control" required><?php echo htmlspecialchars($user['profile_description']); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="profile_picture_url" class="form-label">Profile Picture URL:</label>
            <input type="text" name="profile_picture_url" id="profile_picture_url" class="form-control" value="<?php echo htmlspecialchars($user['profile_picture_url']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="social_links" class="form-label">Social Links:</label>
            <input type="text" name="social_links" id="social_links" class="form-control" value="<?php echo htmlspecialchars($user['social_links']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role:</label>
            <input type="text" name="role" id="role" class="form-control" value="<?php echo htmlspecialchars($user['role']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status:</label>
            <input type="text" name="status" id="status" class="form-control" value="<?php echo htmlspecialchars($user['status']); ?>" required>
        </div>

        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>
