<?php
session_start();
require_once "../controllers/Auth.php";
require_once "../controllers/event.php";
require_once "../controllers/interest.php";

// Create Auth instance
$auth = new Auth();
$eventController = new EventController();
$isLoggedIn = isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true;
$username = $isLoggedIn ? $_SESSION["username"] : "";

$user = $auth->getUserInfo($userId);

if ($user["success"]) {
    // Handle POST request for event deletion
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["event_id"])) {
        if (!$isLoggedIn) {
            header("Location: ../login");
            exit();
        }

        $eventId = (int) $_POST["event_id"];
        $eventData = $eventController->getEvent($eventId);

        if ($eventData["success"]) {
            // Verify event ownership
            if ($_SESSION["user_id"] == $eventData["data"]["user_id"]) {
                $deleteResult = $eventController->deleteEvent($eventId);

                if ($deleteResult["success"]) {
                    // Delete associated cover image if it exists
                    if (!empty($eventData["data"]["cover_image_url"])) {
                        $uploadDir = "../storage/uploads/";
                        $imagePath =
                            $uploadDir .
                            basename($eventData["data"]["cover_image_url"]);

                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                }
            }
        }
    }

    // Get user events
    $data = $eventController->getUserEvent($userId);
    $events = $data["data"] ?? [];
}
?>


<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Awqat</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
      <!-- Google Fonts -->
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;500;600&display=swap" rel="stylesheet">
      <script>
         (function() {
           const savedTheme = localStorage.getItem('user-theme');
           const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
           document.documentElement.classList.toggle(
             'dark-theme', 
             savedTheme ? savedTheme === 'dark' : prefersDark
           );
         })();
      </script>
      <link rel="stylesheet" href="../assets/css/global.css">
      <link rel="stylesheet" href="../assets/css/profile.css">
   </head>
   <body>
      <!-- Improved Navigation Bar -->
      <nav class="navbar navbar-expand navbar-dark sticky-top">
         <div class="container-fluid navbar-container">
            <a class="navbar-brand" href="./">
            <span class="brand-gradient">Awqat</span>
            <span class="brand-arabic">ما يفوتك شي</span>
            </a>
            <div class="search-container">
               <i class="bi bi-search search-icon"></i>
               <input type="search" class="search-bar" placeholder="Search events...">
            </div>
            <div class="auth-buttons">
               <?php if ($isLoggedIn) : ?>
               <div class="user-section">
                  <div class="user-info">
                     <a href="../profile" class="username-link">
                     <span class="username"><?php echo htmlspecialchars($username); ?></span>
                     </a>
                     <a href="../logout" class="logout-btn">
                     <i class="bi bi-box-arrow-right"></i>
                     <span>Logout</span>
                     </a>
                  </div>
               </div>
               <?php else : ?>
               <a href="../login" class="btn btn-outline-light">Log In</a>
               <a href="../register" class="btn btn-orange">Sign Up</a>
               <?php endif; ?>
            </div>
         </div>
      </nav>
      <div class="container-fluid mt-0 pt-0">
         <div class="row g-0">
            <!-- Left Sidebar -->
            <div class="col-lg-2 sidebar">
               <!-- Navigation Menu -->
               <div class="sidebar-section">
                  <nav class="nav flex-column gap-2 mb-4">
                     <a class="nav-link" id="homeRefresh" href="../">
                     <i class="bi bi-house-door me-2"></i>Home
                     </a>
                     <a class="nav-link <?php if (!empty($user['success']) && $userId == $user['data']['id']) echo 'active'; ?>" href="../profile">
                     <i class="bi bi-person me-2"></i>Profile
                     </a>
                     <a class="nav-link" href="../interests">
                     <i class="bi bi-star me-2"></i> My Interests
                     </a>
                     <a class="nav-link" href="../create-event">
                     <i class="bi bi-plus-circle me-2"></i> Create Event
                     </a>
                  </nav>
               </div>
            </div>
            <!-- Main Content Area -->
            <div class="col-lg-7 p-3">
               <?php if ($user['success']) :?>
               <!-- Profile Header -->
               <div class="profile-header">
                  <div class="profile-picture-container">
                     <img src="<?php echo htmlspecialchars('../' . $user['data']['profile_picture_url'] ?? '') ?>" 
                        class="profile-picture" 
                        alt="<?php echo htmlspecialchars($user['data']['username']) ?>" onerror="this.src='../storage/uploads/profile_default.jpg'">
                     <button class="edit-picture-btn">
                     <i class="bi bi-camera-fill"></i>
                     </button>
                  </div>
                  <div class="profile-info">
                     <div class="profile-name-container">
                        <h1 class="profile-name"><?php echo htmlspecialchars($user['data']['username']); ?></h1>
                        <button class="edit-profile-btn">
                        Edit Profile
                        </button>
                     </div>
                     <p class="profile-username">@<?php echo htmlspecialchars($user['data']['username']); ?></p>
                     <p class="profile-bio">
                        <?php echo htmlspecialchars($user['data']['description'] ?? "We don't know much about them, but we're sure " . $user['data']['username'] . " is great."); ?>
                     </p>
                     <div class="profile-meta">
                        <span><i class="bi bi-calendar"></i> Joined <?php echo date('M j, Y', strtotime($user['data']['created_at'])) ?></span>
                     </div>
                  </div>
               </div>
               <!-- Events Section -->
               <div class="events-section">
                  <h2 class="section-title">
                     <i class="bi bi-calendar-event"></i> Created events
                  </h2>
                  <div class="events-grid">
                     <?php if (!empty($events)) : ?>
                     <?php foreach ($events as $event): 
                        // Determine if event is upcoming or past
                        $today = new DateTime();
                        $endDate = new DateTime($event['end_date'] ?? $event['start_date']);
                        $isPast = $endDate < $today;
                        $badgeClass = $isPast ? 'past' : 'active';
                        $badgeText = $isPast ? 'Past' : 'Upcoming';
                        
                        // Format dates
                        $startDateObj = new DateTime($event['start_date']);
                        $endDateObj = new DateTime($event['end_date'] ?? $event['start_date']);
                        
                        // Single day event
                        if ($event['start_date'] == ($event['end_date'] ?? null)) {
                            $dateDisplay = $startDateObj->format('M j, Y');
                        } 
                        // Multi-day event in same month
                        elseif ($startDateObj->format('M') == $endDateObj->format('M')) {
                            $dateDisplay = $startDateObj->format('M j').'-'.$endDateObj->format('j, Y');
                        }
                        // Multi-day event across months
                        else {
                            $dateDisplay = $startDateObj->format('M j').'-'.$endDateObj->format('M j, Y');
                        }
                        ?>
                     <!-- Event Card -->
                     <div class="event-card">
                        <span class="event-badge <?= $badgeClass ?>"><?= $badgeText ?></span>
                        <div class="event-image-container">
                           <img src="<?php echo htmlspecialchars('../' . $event['cover_image_url'] ?? ''); ?>" 
                              class="event-image img-fluid h-100" 
                              alt="<?php echo htmlspecialchars($event['title']); ?>" 
                              onerror="this.src='../storage/uploads/event_default.jpg'">
                        </div>
                        <div class="event-content">
                           <h3 class="event-title"><?= htmlspecialchars($event['title']) ?></h3>
                           <div class="event-meta">
                              <span><i class="bi bi-calendar"></i> <?= $dateDisplay ?></span>
                              <span><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($event['location']) ?></span>
                           </div>
                           <div class="event-actions">
                              <button class="details-btn" onclick="window.location.href='../event/<?= $event['id'] ?>'">
                              View Details
                              </button>
                              <?php if ($isLoggedIn && $_SESSION['user_id'] == $userId) : ?>
                              <form method="POST" style="display: inline;">
                                 <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                                 <button type="submit" class="delete-btn">
                                 Delete
                                 </button>
                              </form>
                              <?php endif; ?>
                           </div>
                        </div>
                     </div>
                     <?php endforeach; ?>
                     <?php else : ?>
                     <p class="event-meta">There are currently no events.</p>
                     <?php endif; ?>
                  </div>
               </div>
               <script>
                  function confirmDelete(eventId) {
                      if (confirm('Are you sure you want to delete this event?')) {
                          window.location.href = '/delete-event?id=' + eventId;
                      }
                  }
               </script>
               <?php else : ?>
               <p class="event-meta">User not found.</p>
               <?php endif; ?>
            </div>
            <!-- Right Sidebar -->
            <div class="col-lg-3 right-sidebar">
               <!-- Trending Events Section -->
               <div class="sidebar-card">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                     <h5 class="fw-bold m-0"><i class="bi bi-fire me-2"></i>Trending</h5>
                  </div>
                  <?php
                     $events = [
                         [
                             'title' => 'Food Truck Festival',
                             'image' => 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80',
                             'date' => 'Mar 28',
                             'location' => 'Miami, FL'
                         ],
                         [
                             'title' => 'Blockchain Conference',
                             'image' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80',
                             'date' => 'Apr 12-14',
                             'location' => 'Austin, TX'
                         ],
                     ];
                     
                     foreach ($events as $event) {
                         echo '<div class="side-event-card">
                             <div class="d-flex gap-3">
                                 <img src="' . htmlspecialchars($event['image']) . '" class="side-event-card-img" alt="Event">
                                 <div>
                                     <h6 class="mb-1 fw-semibold">' . htmlspecialchars($event['title']) . '</h6>
                                     <div class="d-flex align-items-center text-muted small mb-1">
                                         <i class="bi bi-calendar me-2"></i>
                                         <span>' . htmlspecialchars($event['date']) . '</span>
                                     </div>
                                     <div class="d-flex align-items-center text-muted small">
                                         <i class="bi bi-geo-alt me-2"></i>
                                         <span>' . htmlspecialchars($event['location']) . '</span>
                                     </div>
                                 </div>
                             </div>
                         </div>';
                     }
                     ?>
               </div>
               <!-- Popular Tags Section -->
               <div class="popular-tags-container">
                  <div class="popular-tags-header">
                     <h5><i class="bi bi-tags me-2"></i>Popular Tags</h5>
                  </div>
                  <div class="popular-tags-flex">
                     <?php
                        $popularTags = [
                            ['tag' => 'music', 'icon' => 'music-note-beamed', 'label' => 'Music'],
                            ['tag' => 'tech', 'icon' => 'laptop', 'label' => 'Tech'],
                            ['tag' => 'sports', 'icon' => 'trophy', 'label' => 'Sports'],
                            ['tag' => 'gaming', 'icon' => 'joystick', 'label' => 'Gaming'],
                            ['tag' => 'comedy', 'icon' => 'emoji-laughing', 'label' => 'Comedy'],
                        ];
                        
                        foreach ($popularTags as $tag) {
                            echo '<span class="popular-tag" data-tag="' . htmlspecialchars($tag['tag']) . '">
                                <i class="bi bi-' . htmlspecialchars($tag['icon']) . '"></i>
                                <span>' . htmlspecialchars($tag['label']) . '</span>
                            </span>';
                        }
                        ?>
                  </div>
               </div>
               <!-- Footer Section -->
               <div class="footer-container mt-4 pt-3">
                  <div class="footer-links d-flex flex-wrap align-items-center gap-3 mb-2">
                     <a href="#" class="footer-link">Terms of Service</a>
                     <a href="#" class="footer-link">Privacy Policy</a>
                     <a href="#" class="footer-link">Cookie Policy</a>
                     <button class="theme-toggle-btn" id="themeToggle">Switch Appearance</button>
                  </div>
                  <div class="copyright">© 2025 Awqat. All rights reserved.</div>
               </div>
            </div>
         </div>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
      <script src="../assets/js/global.js"></script>
   </body>
</html>