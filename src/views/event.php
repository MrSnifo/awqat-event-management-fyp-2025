<?php
session_start();
require_once '../config/database.php';
require_once '../controllers/Auth.php';

// Create Auth instance
$auth = new Auth();
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$username = $isLoggedIn ? $_SESSION['username'] : '';

// Redirect to login if not authenticated
if (!$isLoggedIn) {
    header("Location: login");
    exit();
}

$event = [
    'id' => 123,
    'title' => 'Tech Conference 2023',
    'description' => "Join us for the biggest tech conference of the year featuring top industry speakers, workshops, and networking opportunities.\nThis three-day event will cover the latest trends in AI, blockchain, and cloud computing with hands-on sessions and panel discussions.",
    'location' => 'Dubai World Trade Centre',
    'start_date' => '2026-12-15',
    'end_date' => '2023-12-17',
    'start_time' => '09:00:00',
    'end_time' => '18:00:00',
    'cover_image_url' => 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1600&q=80',
    'status' => 'unverified',
    'interests' => 1245,
    'tags' => json_encode(['technology', 'conference', 'networking', 'workshops', 'dubai']),
    'creator' => [
        'id' => 456,
        'name' => 'Tech Events Dubai',
        'avatar' => 'http://localhost/PFA-2024-2025/src/public/storage/uploads/profile_default.jpg'
    ]
];

// Calculate time remaining
$now = new DateTime();
$startDate = new DateTime($event['start_date'] . ' ' . $event['start_time']);
$endDate = new DateTime($event['end_date'] . ' ' . $event['end_time']);
$timeRemaining = $now->diff($startDate);
$eventStatus = ($now < $startDate) ? 'upcoming' : (($now > $endDate) ? 'ended' : 'ongoing');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ouqat</title>
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
                <span class="brand-gradient">Ouqat</span>
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

            <!-- Main Content Area -->
            <!-- Main Content Area -->
<div class="col-lg-7 p-3">
    <div class="event-detail-container">
        <div class="event-layout">
            <!-- Left Column - Timer & Info -->
            <div class="event-info-column">
                <!-- Creator Section -->
                <div class="creator-section">
                    <img src="<?php echo htmlspecialchars($event['creator']['avatar']) ?>" 
                         class="creator-avatar" 
                         alt="<?php echo htmlspecialchars($event['creator']['name']) ?>"
                         onerror="this.src='https://via.placeholder.com/50'">
                    <div class="creator-info">
                        <div class="creator-label">Created by</div>
                        <a href="../profile/<?php echo $event['creator']['id'] ?>" class="creator-name">
                            <?php echo htmlspecialchars($event['creator']['name']) ?>
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
                <div class="countdown-container" 
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
                    <?php foreach (json_decode($event['tags']) as $tag) : ?>
                        <span class="tag-badge"><?php echo htmlspecialchars($tag) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right Column - Image -->
            <div class="event-image-column">
                <img src="<?php echo htmlspecialchars($event['cover_image_url']) ?>" 
                     class="event-cover-img" 
                     alt="Event cover"
                     onerror="this.src='https://images.unsplash.com/photo-1497366811353-6870744d04b2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1600&q=80'">
                
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
                    <button class="interested-btn" id="interest-btn">
                        <i class="bi bi-star"></i>
                        <span>Interested</span>
                        <span class="count"><?php echo number_format($event['interests']) ?></span>
                    </button>
                    <a href="#" class="btn-share" id="share-btn" data-event-title="<?php echo htmlspecialchars($event['title']) ?>">
                        <i class="bi bi-share"></i> Share
                    </a>
                </div>
            </div>
        </div>
    </div>
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
                    <div class="copyright">© 2025 Ouqat. All rights reserved.</div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/global.js"></script>
    <script src="../assets/js/event.js"></script>
</body>

</html>