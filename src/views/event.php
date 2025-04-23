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

$data = $eventController->getEvent($eventId);

$event = $data["data"] ?? null;

if ($event) {
    $now = new DateTime();
    $startDate = new DateTime(
        $event["start_date"] . " " . $event["start_time"]
    );
    $endDate = new DateTime($event["end_date"] . " " . $event["end_time"]);
    $timeRemaining = $now->diff($startDate);
    $eventStatus =
        $now < $startDate
            ? "upcoming"
            : ($now > $endDate
                ? "ended"
                : "ongoing");

    $creator = $auth->getUserInfo($data["data"]["user_id"])["data"];

    $eventInterestController = new EventInterestController();
    $isInterested = false;
    if ($isLoggedIn) {
        $isInterested = $eventInterestController->hasUserInterest(
            $_SESSION["user_id"],
            $eventId
        );
    }
    $interestCount = $eventInterestController->getEventInterestCount($eventId);
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
      <link rel="stylesheet" href="../assets/css/event.css">
   </head>
   <body>
      <!-- Improved Navigation Bar -->
      <nav class="navbar navbar-expand navbar-dark sticky-top">
         <div class="container-fluid navbar-container">
            <a class="navbar-brand" href="../">
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
                     <a class="nav-link" href="../profile">
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
            <div class="col-lg-7 p-3">
               <?php if ($event) : ?>
               <div class="event-detail-container">
                  <div class="event-layout">
                     <!-- Left Column - Timer & Info -->
                     <div class="event-info-column">
                        <!-- Creator Section -->
                        <div class="creator-section">
                           <img src="<?php echo htmlspecialchars($creator['profile_picture_url'] ?? '') ?>" 
                              class="creator-avatar" 
                              alt="<?php echo htmlspecialchars($creator['username']) ?>"
                              onerror="this.src='../storage/uploads/profile_default.jpg'">
                           <div class="creator-info">
                              <div class="creator-label">Created by</div>
                              <a href="../profile/<?php echo $creator['id'] ?>" class="creator-name">
                              <?php echo htmlspecialchars($creator['username']) ?>
                              </a>
                           </div>
                        </div>
                        <!-- Event Header -->
                        <div class="event-header">
                           <h1 class="event-title"><?php echo htmlspecialchars($event['title']) ?></h1>
                           <div class="event-meta">
                              <span class="date">
                              <i class="bi bi-calendar-event"></i>
                              <?php echo date('M j, Y', strtotime($event['start_date'])) ?>
                              <?php if ($event['end_date']) : ?>
                              - <?php echo date('M j, Y', strtotime($event['end_date'])) ?>
                              <?php endif; ?>
                              </span>
                              <span class="time">
                              <i class="bi bi-clock"></i>
                              <?php echo date('g:i a', strtotime($event['start_time'])) ?>
                              <?php if ($event['end_time']) : ?>
                              - <?php echo date('g:i a', strtotime($event['end_time'])) ?>
                              <?php endif; ?>
                              </span>
                              <span class="location">
                              <i class="bi bi-geo-alt"></i>
                              <?php echo htmlspecialchars($event['location']) ?>
                              </span>
                           </div>
                        </div>
                        <!-- Countdown Timer or Ended Message -->
                        <div class="countdown-container" id="countdown-timer"
                           data-event-start="<?php echo $event['start_date'] ?>T<?php echo $event['start_time'] ?>" 
                           data-event-end="<?php echo $event['end_date'] ?>T<?php echo $event['end_time'] ?>">
                           <?php if ($eventStatus !== 'ended') : ?>
                           <div class="countdown-card">
                              <div class="time-unit">
                                 <div class="number" id="countdown-days">00</div>
                                 <div class="label">DAYS</div>
                              </div>
                              <div class="time-unit">
                                 <div class="number" id="countdown-hours">00</div>
                                 <div class="label">HOURS</div>
                              </div>
                              <div class="time-unit">
                                 <div class="number" id="countdown-minutes">00</div>
                                 <div class="label">MINUTES</div>
                              </div>
                              <div class="time-unit">
                                 <div class="number" id="countdown-seconds">00</div>
                                 <div class="label">SECONDS</div>
                              </div>
                           </div>
                           <div class="countdown-status">
                              <?php echo match($eventStatus) {
                                 'upcoming' => 'EVENT STARTS SOON',
                                 'ongoing' => 'EVENT IS HAPPENING NOW',
                                 default => ''
                                 }; ?>
                           </div>
                           <?php else : ?>
                           <div class="event-ended-message">
                              EVENT HAS ENDED
                           </div>
                           <?php endif; ?>
                        </div>
                        <!-- Event Description -->
                        <div class="event-description">
                           <h3>About the Event</h3>
                           <p><?php echo nl2br(htmlspecialchars($event['description'])) ?></p>
                        </div>
                        <!-- Event Tags -->
                        <div class="event-tags">
                           <?php foreach ($event['tags'] as $tag) : ?>
                           <span class="tag-badge"><?php echo htmlspecialchars($tag) ?></span>
                           <?php endforeach; ?>
                        </div>
                     </div>
                     <!-- Right Column - Image -->
                     <div class="event-image-column">
                        <img src="<?php echo htmlspecialchars('../' . $event['cover_image_url'] ?? '') ?>" 
                           class="event-cover-img" 
                           alt="Event cover"
                           alt="<?php echo htmlspecialchars($event['title']) ?>"
                           onerror="this.src='../storage/uploads/event_default.jpg'">
                        <?php if ($event['status'] === 'verified') : ?>
                        <div class="verified-badge">
                           <i class="bi bi-patch-check-fill"></i>
                           Verified Event
                        </div>
                        <?php elseif ($event['status'] === 'unverified') : ?>
                        <div class="unverified-badge">
                           <i class="bi bi-exclamation-triangle-fill"></i>
                           Unverified
                        </div>
                        <?php endif; ?>
                        <div class="action-text">
                           <h4>Ready to join this event?</h4>
                           <p>Show your interest or share with friends</p>
                        </div>
                        <!-- Action Buttons -->
                        <div class="action-buttons">
                           <button class="interested-btn <?php echo $isInterested ? 'active' : '' ?>" 
                              id="interest-btn" 
                              data-event-id="<?php echo htmlspecialchars($eventId) ?>">
                           <i class="bi-star<?php echo $isInterested ? '-fill' : '' ?>" id="interest-icon"></i>
                           <span id="interest-label"><?php echo $isInterested ? 'Interested' : 'Show Interest' ?></span>
                           <span id="interest-count" class="count"><?php echo number_format($interestCount) ?></span>
                           </button>
                           <a href="#" class="btn-share" id="share-btn" data-event-title="<?php echo htmlspecialchars($event['title']) ?>">
                           <i class="bi bi-share"></i> Share
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
               <?php else : ?>
               Not Found
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
      <script src="../assets/js/event.js"></script>
   </body>
</html>