<?php
session_start();
require_once "../config/database.php";
require_once "../controllers/Auth.php";
require_once "../controllers/interest.php";
require_once "../controllers/filters.php";

// Create Auth instance
$auth = new AuthController();
$isLoggedIn = isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true;
$username = $isLoggedIn ? $_SESSION["username"] : "";
$redirect = base64_encode($_SERVER['REQUEST_URI']);

// Redirect to login if not authenticated
// Sofiene
if (!$isLoggedIn) {
   $redirect = base64_encode($_SERVER['REQUEST_URI']);
   header("Location: ./login?redirect=$redirect");
    exit();
}


$interestController = new InterestController();
$filter = new FilterController();


$user_inersets = $interestController->getUserInterests($_SESSION['user_id']);
// Sofiene
$filteredEvents = $filter->getSpesificEvents($user_inersets, $_SESSION['user_id']);


$trendingEvents = array_slice($filter->filter("interests_high", [], null), 0, 3);
?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="icon" href="../storage/uploads/icon.png" type="image/x-icon" />
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
      <link rel="stylesheet" href="assets/css/global.css">
      <link rel="stylesheet" href="assets/css/home.css">
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
                     <a href="profile" class="username-link">
                     <span class="username"><?php echo htmlspecialchars($username); ?></span>
                     </a>
                     <a href="./logout" class="logout-btn">Logout</a>
                  </div>
               </div>
               <?php else : ?>
               <a href="login" class="btn btn-outline-light">Log In</a>
               <a href="register" class="btn btn-orange">Sign Up</a>
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
                     <a class="nav-link" id="homeRefresh" href="./">
                     <i class="bi bi-house-door me-2"></i>Home
                     </a>
                     <a class="nav-link" href="profile">
                     <i class="bi bi-person me-2"></i>Profile
                     </a>
                     <a class="nav-link active" href="interests">
                     <i class="bi bi-star me-2"></i> My Interests
                     </a>
                     <a class="nav-link" href="create-event">
                     <i class="bi bi-plus-circle me-2"></i> Create Event
                     </a>
                  </nav>
               </div>
            </div>
            <!-- Main Content Area -->
            <div class="col-lg-7 p-3">
            <?php if (empty($filteredEvents)): ?>
               <div class="empty-events">
                  <i class="bi bi-calendar2-event empty-icon"></i>
                  <h3 class="empty-title">No Events Found</h3>
                  <p class="empty-message">Oops, no events found! Try again later.</p>
               </div>
               <?php else: ?>
               <?php foreach ($filteredEvents as $event): ?>
               <?php
                  $tags_html = '';
                  foreach ($event['tags'] as $tag) {
                      $tags_html .= '<span class="badge">' . htmlspecialchars($tag) . '</span>';
                  }
                  ?>
               <div class="event-card" data-tags="<?php echo htmlspecialchars(implode(',', $event['tags'])); ?>">
                  <div class="row g-0">
                     <div class="col-md-4">
                        <div class="event-image-container">
                           <img src="<?php echo htmlspecialchars($event['cover_image_url'] ?? ''); ?>" 
                              class="event-image img-fluid h-100" 
                              alt="<?php echo htmlspecialchars($event['title']); ?>" 
                              onerror="this.src='../storage/uploads/event_default.jpg'">
                              <div class="event-badge"><?php echo htmlspecialchars($event['status_text']); ?></div>
                        </div>
                     </div>
                     <div class="col-md-8">
                        <div class="event-content">
                           <div class="event-meta">
                              <span><i class="bi bi-calendar"></i> <?php echo htmlspecialchars($event['start_date']); ?></span>
                              <span><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($event['location']); ?></span>
                           </div>
                           <h4 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h4>
                           <p class="event-description">
                              <?php echo nl2br(htmlspecialchars($event['description'])) ?>
                           </p>
                           <div class="event-tags">
                              <?php echo $tags_html; ?>
                           </div>
                           <div class="event-actions">
                              <button class="interest-btn <?php echo $event['isInterested'] ? 'active' : '' ?>" 
                                 data-event-id="<?php echo htmlspecialchars($event['id']) ?>">
                              <i class="interest-icon bi-star<?php echo $event['isInterested'] ? '-fill' : '' ?>"></i>
                              <span class="interest-label"><?php echo $event['isInterested'] ? 'Interested' : 'Show Interest' ?></span>
                              <span class="interest-count count"><?php echo number_format($event['interests']) ?></span>
                              </button>
                              </button>
                              <a href="event/<?php echo htmlspecialchars($event['id']); ?>" class="btn details-btn">
                              <span>View Details</span>
                              <i class="bi bi-arrow-right"></i>
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <?php endforeach; ?>
               <?php endif; ?>
            </div>
            <!-- Right Sidebar -->
            <div class="col-lg-3 right-sidebar">
               <!-- Trending Events Section -->
               <div class="sidebar-card">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                     <h5 class="fw-bold m-0"><i class="bi bi-fire me-2"></i>Trending</h5>
                  </div>
                  <?php if (empty($trendingEvents)): ?>
                     <div class="empty-events">
                           <h3 class="empty-title">No Trending Events</h3>
                           <p class="empty-message">Check back later for trending events.</p>
                     </div>
                  <?php else: ?>
                     <?php foreach ($trendingEvents as $event): ?>
                           <div class="side-event-card" data-event-id="<?php echo htmlspecialchars($event['id']); ?>">
                              <div class="d-flex gap-3">
                                 <img src="<?php echo htmlspecialchars($event['cover_image_url'] ?? ''); ?>" 
                                       class="side-event-card-img" 
                                       alt="<?php echo htmlspecialchars($event['title']); ?>"
                                       onerror="this.src='../storage/uploads/event_default.jpg'">
                                 <div>
                                       <h6 class="mb-1 fw-semibold"><?php echo htmlspecialchars($event['title']); ?></h6>
                                       <div class="d-flex align-items-center text-muted small mb-1">
                                          <i class="bi bi-calendar me-2"></i>
                                          <span><?php echo htmlspecialchars($event['start_date']); ?></span>
                                       </div>
                                       <div class="d-flex align-items-center text-muted small">
                                          <i class="bi bi-geo-alt me-2"></i>
                                          <span><?php echo htmlspecialchars($event['location']); ?></span>
                                       </div>
                                 </div>
                              </div>
                           </div>
                     <?php endforeach; ?>
                  <?php endif; ?>
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
      <script src="assets/js/global.js"></script>
      <script src="assets/js/home.js"></script>
   </body>
</html>