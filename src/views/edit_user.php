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
    $id = (int) $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        die('User not found.');
    }
} else {
    die('No user ID specified.');
}

// Update user data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $profile_description = $conn->real_escape_string($_POST['profile_description']);
    $profile_picture_url = $conn->real_escape_string($_POST['profile_picture_url']);
    $role = $conn->real_escape_string($_POST['role']);
    $status = $conn->real_escape_string($_POST['status']);

    $sql = "UPDATE users SET 
        username = '$username', 
        email = '$email', 
        profile_description = '$profile_description', 
        profile_picture_url = '$profile_picture_url', 
        role = '$role', 
        status = '$status'
        WHERE id = $id";

    if ($conn->query($sql)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
