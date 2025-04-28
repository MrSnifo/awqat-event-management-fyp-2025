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


// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'awqat';





$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete user
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

// Search users
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql = "SELECT * FROM users";
if (!empty($search_query)) {
    $sql .= " WHERE username LIKE ? OR email LIKE ?";
}
$stmt = $conn->prepare($sql);
if (!empty($search_query)) {
    $search_param = "%" . $search_query . "%";
    $stmt->bind_param("ss", $search_param, $search_param);
}
$stmt->execute();
$result = $stmt->get_result();

// Count total users
$total_users_sql = "SELECT COUNT(*) as total FROM users";
$total_users_result = $conn->query($total_users_sql);
$total_users = $total_users_result->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/dashboard/dashboard.css">
  <style>
    #theme-toggle {
      margin-top: 45px;
      margin-left: 20px;
      margin-bottom: 80px;
    }
  </style>
</head>
<body class="bg-light">

  <!-- Theme toggle button -->
  <button id="theme-toggle" class="btn btn-light">
    <i id="theme-icon" class="fas fa-sun"></i>
  </button>

  <!-- Sidebar -->
  <nav class="sidebar position-fixed">
    <h4 class="text-center text-white mt-3">Dashboard</h4>
    <a href="#">Users</a>
    <a href="eventsTable">Events</a>
    <a href="./logout" class="text-white">Logout</a>
  </nav>

  <div class="content">
    <!-- Search form -->
    <div class="mb-4">
      <form method="GET" action="">
        <div class="input-group">
          <input type="text" name="search" class="form-control" placeholder="Search by username or email" value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
          <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
        </div>
      </form>
    </div>

    <!-- Statistics -->
    <div class="row stats-row g-3 mb-4">
      <div class="col-md-4">
        <div class="card bg-primary text-white d-flex align-items-center p-3">
          <i class="fas fa-users fa-2x me-3"></i>
          <div>
            <h5>Total Users</h5>
            <p class="mb-0"><?php echo $total_users; ?> Users</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Users table -->
    <div class="card mb-4">
      <div class="card-header">
        <h2>User List</h2>
      </div>
      <div class="card-body p-0">
        <table class="table table-striped mb-0 text-center">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
              <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['role']); ?></td> <!-- Display role -->
                <td><?php echo htmlspecialchars($row['status']); ?></td> <!-- Display status -->
                <td>
                  <a href="edit_user?id=<?php echo $row['id']; ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">
                    <i class="fas fa-trash-alt"></i>
                  </a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="assets/js/dashboard/dashboard.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>