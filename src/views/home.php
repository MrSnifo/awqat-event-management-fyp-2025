<?php
session_start();
// define authenticated var : 
$authenticated = false;
// checkif the user is authenticated :

if (isset($_SESSION['email'])) {
    //echo "logged in.";
    $authenticated = true;
}
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
  <link rel="stylesheet" href="assets/css/global.css">
  <link rel="stylesheet" href="assets/css/home.css">

</head>

<body>
    <!-- Main Navigation Bar -->
<!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand navbar-dark sticky-top">
        <div class="container-fluid navbar-container">
            <a class="navbar-brand" href="./">
                <span class="brand-gradient">Ouqat</span>
                <span class="brand-arabic">ما يفوتك شي</span>
            </a>

            <div class="search-container">
                <input type="search" class="form-control search-bar" placeholder="Search events...">
            </div>
            <?php
            // if the user authenticated 
            if ($authenticated){
            ?>
            <!-- Dropdown wrapper -->
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                   <?php echo $_SESSION['username']; ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href='profile'>Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout">Logout</a></li>
                </ul>
            </div>
            <?php
            }else {
            ?>
            <div class="auth-buttons">
                <a href="login" class="btn btn-outline-light">Log In</a>
                <a href="register" class="btn btn-orange">Sign Up</a>
            </div>
            <?php
            }
            ?>
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
                                <option value="recommended">Recommended for you</option>
                                <option value="recent">Most Recent</option>
                                <option value="interests_high">Interests (High to Low)</option>
                                <option value="interests_low">Interests (Low to High)</option>
                            </select>
                        </div>
                        <!-- Tags Input -->
                        <div class="tags-input-container mb-3">
                            <label class="form-label small mb-2">Add Tags</label>
                            <div class="tags-input-wrapper">
                                <input type="text" class="tags-input form-control" placeholder="Type and press Enter">
                                <div class="tags-list"></div>
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
            <?php
                // Hethi bech tkoun mn DATABASE ou bch na3mlou filter ou houni bech ykoun oma el recommend..
                // $events = [];
                $events = [
                    [
                        'id' => 1,
                        'title' => "International Art Biennale",
                        'description' => "Contemporary art exhibition featuring 200+ artists from 40 countries.",
                        'date' => "Jun 5-Sep 5, 2024",
                        'location' => "Venice, Italy",
                        'category' => "art",
                        'tags' => ["exhibition", "contemporary"],
                        'image' => "https://images.unsplash.com/photo-1536922246289-88c42f957773?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80",
                        'interested' => 750,
                        'badge' => "International"
                    ],
                    [
                        'id' => 2,
                        'title' => "Tech Summit 2024",
                        'description' => "Annual technology conference showcasing the latest innovations in AI, blockchain and IoT.",
                        'date' => "Aug 15-18, 2024",
                        'location' => "San Francisco, CA",
                        'category' => "tech",
                        'tags' => ["conference", "innovation"],
                        'image' => "https://images.unsplash.com/photo-1511578314322-379afb476865?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80",
                        'interested' => 1200,
                        'badge' => "Featured"
                    ],
                    [
                        'id' => 3,
                        'title' => "Jazz in the Park",
                        'description' => "Outdoor jazz festival with performances from world-renowned musicians.",
                        'date' => "Jul 7-9, 2024",
                        'location' => "Chicago, IL",
                        'category' => "music",
                        'tags' => ["festival", "outdoor"],
                        'image' => "https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80",
                        'interested' => 980,
                        'badge' => "Popular"
                    ],
                    [
                        'id' => 4,
                        'title' => "Food & Wine Festival",
                        'description' => "Celebration of culinary arts featuring top chefs and winemakers.",
                        'date' => "Sep 12-15, 2024",
                        'location' => "Napa Valley, CA",
                        'category' => "food",
                        'tags' => ["culinary", "tasting"],
                        'image' => "https://images.unsplash.com/photo-1547592180-85f173990554?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80",
                        'interested' => 650,
                        'badge' => "New"
                    ],
                    [
                        'id' => 5,
                        'title' => "Marathon Weekend",
                        'description' => "Annual city marathon with routes through historic downtown areas.",
                        'date' => "Oct 5, 2024",
                        'location' => "Boston, MA",
                        'category' => "sports",
                        'tags' => ["running", "fitness"],
                        'image' => "https://images.unsplash.com/photo-1552674605-db6ffd4facb5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80",
                        'interested' => 1500,
                        'badge' => "Featured"
                    ]
                ];

                if (empty($events)) {
                    echo '<div class="col-lg-7 p-3">
                        <div class="empty-events">
                            <i class="bi bi-calendar2-event empty-icon"></i>
                            <h3 class="empty-title">No Events Found</h3>
                            <p class="empty-message">Try broadening your search or check different categories</p>
                        </div>
                    </div>';
                }else{
                    foreach ($events as $event) {
                        $tags_html = '';

                        foreach ($event['tags'] as $tag) {
                            $tags_html .= '<span class="badge">' . htmlspecialchars($tag) . '</span>';
                        }

                        echo '
                        <div class="event-card" data-category="' . htmlspecialchars($event['category']) . '" data-tags="' . htmlspecialchars(implode(',', $event['tags'])) . '">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <div class="event-image-container">
                                        <img src="' . htmlspecialchars($event['image']) . '" 
                                            class="event-image img-fluid h-100" alt="' . htmlspecialchars($event['title']) . '">
                                        <div class="event-badge">' . htmlspecialchars($event['badge']) . '</div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="event-content">
                                        <div class="event-meta">
                                            <span><i class="bi bi-calendar"></i> ' . htmlspecialchars($event['date']) . '</span>
                                            <span><i class="bi bi-geo-alt"></i> ' . htmlspecialchars($event['location']) . '</span>
                                        </div>
                                        <h4 class="event-title">' . htmlspecialchars($event['title']) . '</h4>
                                        <p class="event-description">
                                            ' . htmlspecialchars($event['description']) . '
                                        </p>
                                        <div class="event-tags">
                                            ' . $tags_html . '
                                        </div>
                                        <div class="event-actions">
                                            <button class="btn interested-btn">
                                                <i class="bi bi-star"></i>
                                                <span>Interested</span>
                                                <span class="count">' . number_format($event['interested']/1000, 1) . 'k</span>
                                            </button>
                                            <a href="event/' . htmlspecialchars($event['id']) . '" class="btn details-btn">
                                                <span>View Details</span>
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    }
                }
                
            ?>
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
                <div class="footer-container mt-4 pt-3 border-top">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/global.js"></script>
    <script src="assets/js/home.js"></script>
</body>

</html>