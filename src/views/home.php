<?php
session_start();
require_once "../controllers/auth.php";
require_once "../controllers/event.php";
require_once "../controllers/interest.php";

// Create Auth instance
$auth = new Auth();
$isLoggedIn = isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true;
$username = $isLoggedIn ? $_SESSION["username"] : "";

// Get filter parameters from URL
$sortBy = isset($_GET["sort"]) ? $_GET["sort"] : "recommended";

$tags = isset($_GET["tags"]) ? explode(",", $_GET["tags"]) : [];
// Validate sort parameter
$validSorts = ["recommended", "recent", "interests_high", "interests_low"];
if (!in_array($sortBy, $validSorts)) {
    $sortBy = "recommended";
}

$eventController = new EventController();

// Database query would go here - this is just mock data
$events = $eventController->getevents();

$eventInterestController = new EventInterestController();

foreach ($events as $key => $event) {
    // Get interest count for the event
    $event["interests"] = $eventInterestController->getEventInterestCount(
        $event["id"]
    );

    // Check if the user is interested in the event
    $event["isInterested"] = false;
    if ($isLoggedIn) {
        $event["isInterested"] = $eventInterestController->hasUserInterest(
            $_SESSION["user_id"],
            $event["id"]
        );
    }

$today = new DateTime();
    $startDate = new DateTime($event['start_date']);
    $endDate = new DateTime($event['end_date'] ?? $event['start_date']);
    
    $isPast = $endDate < $today;
    $isUpcoming = $startDate > $today;
    $isActiveNow = (!$isPast && !$isUpcoming);
    
    if ($isActiveNow) {
        $event['status_text'] = 'Happening Now';
    } elseif ($isUpcoming) {
        $event['status_text'] = 'Upcoming';
    } else {
        $event['status_text'] = 'Past';
    }

    $events[$key] = $event;
}

$filteredEvents = $events;
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
      <link rel="stylesheet" href="assets/css/global.css">
      <link rel="stylesheet" href="assets/css/home.css">
   </head>
   <body>
      <!-- Navigation Bar -->
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
                     <a class="nav-link active" id="homeRefresh" href="./">
                     <i class="bi bi-house-door me-2"></i>Home
                     </a>
                     <a class="nav-link" href="profile">
                     <i class="bi bi-person me-2"></i>Profile
                     </a>
                     <a class="nav-link" href="interests">
                     <i class="bi bi-star me-2"></i> My Interests
                     </a>
                     <a class="nav-link" href="create-event">
                     <i class="bi bi-plus-circle me-2"></i> Create Event
                     </a>
                  </nav>
               </div>
               <hr class="sidebar-divider">
               <!-- Filter Section -->
               <div class="sidebar-section">
                  <h6 class="sidebar-header">Filters</h6>
                  <div class="filter-section">
                     <!-- Sort Dropdown -->
                     <div class="mb-3">
                        <label for="sort-by" class="form-label small mb-2">Sort by</label>
                        <select id="sort-by" class="form-select">
                           <option value="recommended" <?php echo $sortBy === 'recommended' ? 'selected' : ''; ?>>Recommended for you</option>
                           <option value="recent" <?php echo $sortBy === 'recent' ? 'selected' : ''; ?>>Most Recent</option>
                           <option value="interests_high" <?php echo $sortBy === 'interests_high' ? 'selected' : ''; ?>>Interests (High to Low)</option>
                           <option value="interests_low" <?php echo $sortBy === 'interests_low' ? 'selected' : ''; ?>>Interests (Low to High)</option>
                        </select>
                     </div>
                     <!-- Tags Input -->
                     <div class="tags-input-container mb-3">
                        <label class="form-label small mb-2">Add Tags</label>
                        <div class="tags-input-wrapper">
                           <input type="text" class="tags-input form-control" placeholder="Type and press Enter">
                           <div class="tags-list">
                              <?php foreach ($tags as $tag): ?>
                              <span class="tag-badge">
                              <?php echo htmlspecialchars($tag); ?>
                              <input type="hidden" name="tags[]" value="<?php echo htmlspecialchars($tag); ?>">
                              <button class="tag-remove-btn">&times;</button>
                              </span>
                              <?php endforeach; ?>
                           </div>
                        </div>
                     </div>
                     <!-- Action Buttons -->
                     <div class="d-flex gap-2">
                        <button id="apply-filters" class="btn btn-orange flex-grow-1">
                        <i class="bi bi-funnel me-1"></i> Apply
                        </button>
                        <button id="clear-filters" class="btn btn-outline-secondary btn-mini">
                        Clear
                        </button>
                     </div>
                  </div>
               </div>
            </div>
            <!-- Main Content Area -->
            <div class="col-lg-7 p-3">
               <?php if (empty($filteredEvents)): ?>
               <div class="empty-events">
                  <i class="bi bi-calendar2-event empty-icon"></i>
                  <h3 class="empty-title">No Events Found</h3>
                  <p class="empty-message">Oops, no events found! Try again later.</p>
                  <?php if (!empty($tags)): ?>
                  <a href="./" class="btn btn-orange">Clear Filters</a>
                  <?php endif; ?>
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
                  <?php
                     $trendingEvents = [
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
                     
                     foreach ($trendingEvents as $event) {
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
                            $isActive = in_array($tag['tag'], $tags);
                            echo '<span class="popular-tag ' . ($isActive ? 'active' : '') . '" data-tag="' . htmlspecialchars($tag['tag']) . '">
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