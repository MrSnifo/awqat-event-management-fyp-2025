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

</head>

<body>
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
                <!-- You can cook here -->
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/global.js"></script>
</body>

</html>