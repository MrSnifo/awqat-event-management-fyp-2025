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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Events</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="dashboard.css" rel="stylesheet">
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
    <a href="dashboard.php">Users</a>
    <a href="eventsTable.php">Events</a>
    <a href="logout.php" class="text-white">Logout</a>
  </nav>

  <div class="content">
    <!-- Search form -->
    <div class="mb-4">
      <form method="GET" action="">
        <div class="input-group">
          <input type="text" name="search" class="form-control" placeholder="Search by title or location" value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
          <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
        </div>
      </form>
    </div>

    <!-- Statistics -->
    <div class="row stats-row g-3 mb-4">
      <div class="col-md-4">
        <div class="card bg-primary text-white d-flex align-items-center p-3">
          <i class="fas fa-calendar-alt fa-2x me-3"></i>
          <div>
            <h5>Total Events</h5>
            <p class="mb-0"><?php echo $total_events; ?> Events</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Events table -->
    <div class="card mb-4">
      <div class="card-header">
        <h2>Event List</h2>
      </div>
      <div class="card-body p-0">
        <table class="table table-striped mb-0 text-center">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Location</th>
              <th>Status</th>
              <th>Start Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row_event = $result_events->fetch_assoc()) { ?>
              <tr>
                <td><?php echo $row_event['id']; ?></td>
                <td><?php echo htmlspecialchars($row_event['title']); ?></td>
                <td><?php echo htmlspecialchars($row_event['location']); ?></td>
                <td><?php echo htmlspecialchars($row_event['status']); ?></td>
                <td><?php echo htmlspecialchars($row_event['start_date']); ?></td>
                <td>
                  <a href="edit_event.php?id=<?php echo $row_event['id']; ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="?delete_event_id=<?php echo $row_event['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this event?');">
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

  <script src="dashboard.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
