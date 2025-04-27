<?php
// Connexion à la base de données
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'ouqat';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Suppression d'un intérêt d'événement
if (isset($_GET['delete_interest_id'])) {
    $delete_interest_id = $_GET['delete_interest_id'];
    $sql = "DELETE FROM event_interests WHERE id = $delete_interest_id";
    $conn->query($sql);
}




// Récupérer tous les intérêts d'événements
$sql_interests = "SELECT * FROM event_interests";
$result_interests = $conn->query($sql_interests);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Events Interests</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<link href="dashboard.css" rel="stylesheet">
</head>
<button id="theme-toggle" class="btn btn-light">
  <i id="theme-icon" class="fas fa-sun"></i>
</button>
</head>
<body class="bg-light">


  <div class="content">
    <div class="mb-4">
      <input type="text" class="form-control" placeholder="Search...">
    </div>

    

    
  <!-- Liste des intérêts d'événements -->
  <div class="card mb-4">
      <div class="card-header">
        <h2>Event Interests</h2>
      </div>
      <div class="card-body p-0">
        <table class="table table-striped mb-0 text-center">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>User ID</th>
              <th>Event ID</th>
              <th>Created At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row_interest = $result_interests->fetch_assoc()) { ?>
              <tr>
                <td><?php echo $row_interest['id']; ?></td>
                <td><?php echo $row_interest['user_id']; ?></td>
                <td><?php echo $row_interest['event_id']; ?></td>
                <td><?php echo $row_interest['created_at']; ?></td>
                <td>
                  <a href="?delete_interest_id=<?php echo $row_interest['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this interest?');">
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
$conn->close();
?>
