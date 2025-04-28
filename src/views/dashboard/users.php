<?php
require_once "../controllers/auth.php";

$auth = new AuthController();
if (!$auth->verifySession()) {
    header("Location: ./login");
    exit();
}

if(!$auth->hasRole("superadmin") && ! $auth->hasRole("admin")){
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Management | Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/dashboard/users.css">
</head>
<body class="">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header">
      <span>Dashboard</span>
    </div>
    
    <a href="#" class="sidebar-link active">
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
    <!-- Simplified Navbar -->
    <nav class="navbar">
      <div class="container-fluid">
        <div class="search-box">
         
        </div>
        
        <button class="profile-icon" data-bs-toggle="dropdown">
          <i class="fas fa-user"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
          <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="./logout"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
        </ul>
      </div>
    </nav>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3 mb-0">User Management</h1>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="stat-card">
          <div class="d-flex align-items-center">
            <div class="stat-icon me-3">
              <i class="fas fa-users"></i>
            </div>
            <div>
              <div class="stat-number"><?php echo $total_users; ?></div>
              <div class="stat-label">Total Users</div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="stat-card">
          <div class="d-flex align-items-center">
            <div class="stat-icon me-3">
              <i class="fas fa-user-check"></i>
            </div>
            <div>
              <div class="stat-number">0</div>
              <div class="stat-label">Active Today</div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="stat-card">
          <div class="d-flex align-items-center">
            <div class="stat-icon me-3">
              <i class="fas fa-user-plus"></i>
            </div>
            <div>
              <div class="stat-number">0</div>
              <div class="stat-label">New This Week</div>
            </div>
          </div>
        </div>
      </div>

      <div class="search-box">
          <i class="fas fa-search search-icon"></i>
          <form method="GET" action="">
            <input type="text" name="search" class="form-control" placeholder="Search users..." value="<?php echo htmlspecialchars($search_query); ?>">
          </form>
        </div>
    </div>
    

    <!-- Users Table -->
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">User List</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
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
                  <td>
                    <span class="badge bg-primary">
                      <?php echo htmlspecialchars($row['role']); ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge bg-<?php echo $row['status'] === 'active' ? 'success' : 'warning'; ?>">
                      <?php echo htmlspecialchars($row['status']); ?>
                    </span>
                  </td>
                  <td>
                    <div class="action-buttons">
                      <a href="edit_user?id=<?php echo $row['id']; ?>" class="btn-action edit" title="Edit">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="?delete_id=<?php echo $row['id']; ?>" class="btn-action delete" title="Delete" onclick="return confirm('Are you sure you want to delete this user?');">
                        <i class="fas fa-trash-alt"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Theme Toggle Button -->
  <button class="theme-toggle">
    <i id="theme-icon" class="fas fa-moon"></i>
  </button>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Theme toggle functionality
    const themeToggle = document.querySelector('.theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const body = document.body;
    
    // Check for saved theme preference
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'dark') {
      body.classList.add('dark-theme');
      themeIcon.classList.replace('fa-moon', 'fa-sun');
    }
    
    themeToggle.addEventListener('click', () => {
      body.classList.toggle('dark-theme');
      
      if (body.classList.contains('dark-theme')) {
        localStorage.setItem('theme', 'dark');
        themeIcon.classList.replace('fa-moon', 'fa-sun');
      } else {
        localStorage.setItem('theme', 'light');
        themeIcon.classList.replace('fa-sun', 'fa-moon');
      }
    });
    
    // Mobile sidebar toggle
    const sidebar = document.querySelector('.sidebar');
    const navbarToggler = document.querySelector('.navbar-toggler');
    
    if (navbarToggler) {
      navbarToggler.addEventListener('click', () => {
        sidebar.classList.toggle('active');
      });
    }
  </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>