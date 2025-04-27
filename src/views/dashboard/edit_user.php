<?php
// Connexion à la base
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'awqat';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifie si ID est passé dans l'URL
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    
    // Vérifier si l'ID est un nombre valide
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
    $phone = $conn->real_escape_string($_POST['phone']);
    $genre = $conn->real_escape_string($_POST['genre']);
    $role = $conn->real_escape_string($_POST['role']);
    $status = $conn->real_escape_string($_POST['status']);
    
    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email invalide.";
        exit;
    }

    // Mise à jour dans la base de données
    $updateQuery = "UPDATE users 
                    SET username='$username', email='$email', phone='$phone', genre='$genre', role='$role', status='$status' 
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
            <label for="phone" class="form-label">Phone:</label>
            <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="genre" class="form-label">Gender:</label>
            <select name="genre" id="genre" class="form-select" required>
                <option value="Male" <?php if($user['genre'] == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if($user['genre'] == 'Female') echo 'selected'; ?>>Female</option>
            </select>
        </div>

        <!-- Rôle de l'utilisateur -->
        <div class="mb-3">
            <label for="role" class="form-label">Role:</label>
            <select name="role" id="role" class="form-select" required>
                <option value="admin" <?php if($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                <option value="user" <?php if($user['role'] == 'user') echo 'selected'; ?>>User</option>
            </select>
        </div>

        <!-- Statut de l'utilisateur -->
        <div class="mb-3">
            <label for="status" class="form-label">Status:</label>
            <select name="status" id="status" class="form-select" required>
                <option value="active" <?php if($user['status'] == 'active') echo 'selected'; ?>>Active</option>
                <option value="inactive" <?php if($user['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                <option value="banned" <?php if($user['status'] == 'banned') echo 'selected'; ?>>Banned</option>
            </select>
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