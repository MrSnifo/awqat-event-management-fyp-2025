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

// Delete event
if (isset($_GET['delete_event_id'])) {
    $delete_event_id = intval($_GET['delete_event_id']);
    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_event_id);
    $stmt->execute();
    $stmt->close();
}

// Search events
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql_events = "SELECT * FROM events";
if (!empty($search_query)) {
    $sql_events .= " WHERE title LIKE ? OR location LIKE ?";
}
$stmt = $conn->prepare($sql_events);
if (!empty($search_query)) {
    $search_param = "%" . $search_query . "%";
    $stmt->bind_param("ss", $search_param, $search_param);
}
$stmt->execute();
$result_events = $stmt->get_result();

// Count total events
$total_events_sql = "SELECT COUNT(*) as total FROM events";
$total_events_result = $conn->query($total_events_sql);
$total_events = $total_events_result->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Management | Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/dashboard/events.css">
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
      <h1 class="h3 mb-0">Event Management</h1>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="stat-card">
          <div class="d-flex align-items-center">
            <div class="stat-icon me-3">
              <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
              <div class="stat-number"><?php echo $total_events; ?></div>
              <div class="stat-label">Total Events</div>
            </div>
          </div>
          
        </div>
        
      </div>
      

      
    </div>


    <div class="search-box">
          <i class="fas fa-search search-icon"></i>
          <form method="GET" action="">
            <input type="text" name="search" class="form-control" placeholder="Search events..." value="<?php echo htmlspecialchars($search_query); ?>">
          </form>
        </div>

    

    <!-- Events Table -->
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Event List</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Location</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>Start Time</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row_event = $result_events->fetch_assoc()) { ?>
                <tr>
                  <td><?php echo $row_event['id']; ?></td>
                  <td><?php echo htmlspecialchars($row_event['title']); ?></td>
                  <td><?php echo htmlspecialchars($row_event['location']); ?></td>
                  <td>
                    <span class="badge bg-<?php echo $row_event['status'] === 'verified' ? 'success' : 'warning'; ?>">
                      <?php echo htmlspecialchars($row_event['status']); ?>
                    </span>
                  </td>
                  <td><?php echo htmlspecialchars($row_event['start_date']); ?></td>
                  <td><?php echo htmlspecialchars($row_event['start_time']); ?></td>
                  <td>
                    <div class="action-buttons">
                      <a href="edit_event?id=<?php echo $row_event['id']; ?>" class="btn-action edit" title="Edit">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="?delete_event_id=<?php echo $row_event['id']; ?>" class="btn-action delete" title="Delete" onclick="return confirm('Are you sure you want to delete this event?');">
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
  </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>