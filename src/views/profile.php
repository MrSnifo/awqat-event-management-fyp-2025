<?php
session_start();
$authenticated = false;
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
    <title>Profile | Ouqat</title>
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
    <style>
        /* PROFILE PAGE SPECIFIC STYLES */
        .profile-header {
            background: var(--surface);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            position: relative;
            border: 1px solid var(--border-color);
        }
        
       
        
        .profile-actions {
            position: absolute;
            right: 2rem;
            bottom: 1rem;
        }
        
        .profile-info {
            margin-top: 10px;
        }
        
        .profile-stats {
            display: flex;
            gap: 1.5rem;
            margin: 1.5rem 0;
        }
        
        
        .stat-value {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary);
        }
        
        .stat-label {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }
        
     
        
        
        .event-card {
            background: var(--surface);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
        }
    </style>
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
                        <a class="nav-link" id="homeRefresh" href="./">
                            <i class="bi bi-house-door me-2"></i>Home
                        </a>
                        <a class="nav-link active" href="profile">
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
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-7 p-3">
                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="profile-actions">
                        <button class="btn btn-outline-light me-2">
                            <i class="bi bi-pencil me-1"></i> Edit Profile
                        </button>
                        <button class="btn btn-orange">
                            <i class="bi bi-share me-1"></i> Share
                        </button>
                    </div>
                    <?php
                        // if the user authenticated 
                        if ($authenticated){
                    ?>
                    <div class="profile-info">
                        <h2 class="mb-1"><?php echo $_SESSION['username']; ?></h2>
                        <p class="text-muted mb-2">@username</p>
                        <p>Event enthusiast and organizer. Love connecting people through shared interests!</p>
                    <?php
                        }else{ 
                    ?>
                                        <div class="profile-info">
                        <h2 class="mb-1">Group14</h2>
                        <p class="text-muted mb-2">@username</p>
                        <p>Event enthusiast and organizer. Love connecting people through shared interests!</p>
                        <?php
                        }
                    ?>           
                        <div class="profile-stats">
                            <div class="stat-item">
                                <div class="stat-value">4</div>
                                <div class="stat-label">Active events</div>
                            </div>

                            <div class="stat-item">
                                <div class="stat-value">39</div>
                                <div class="stat-label">Created events</div>
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            
    
                <!-- Events List -->
                <div class="event-card">
                    <div class="d-flex gap-3">
                        <img src="https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" class="rounded" width="100" height="100" style="object-fit:cover" alt="Event">
                        <div>
                            <h5 class="mb-1">Jazz in the Park [Padding verification]</h5>
                            <div class="d-flex align-items-center text-muted small mb-1">
                                <i class="bi bi-calendar me-2"></i>
                                <span>Jul 7-9, 2024</span>
                            </div>
                            <div class="d-flex align-items-center text-muted small mb-2">
                                <i class="bi bi-geo-alt me-2"></i>
                                <span>Chicago, IL</span>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash me-1"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="event-card">
                    <div class="d-flex gap-3">
                        <img src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" class="rounded" width="100" height="100" style="object-fit:cover" alt="Event">
                        <div>
                            <h5 class="mb-1">Food Truck Festival</h5>
                            <div class="d-flex align-items-center text-muted small mb-1">
                                <i class="bi bi-calendar me-2"></i>
                                <span>Mar 28, 2024</span>
                            </div>
                            <div class="d-flex align-items-center text-muted small mb-2">
                                <i class="bi bi-geo-alt me-2"></i>
                                <span>Miami, FL</span>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash me-1"></i> Delete
                                </button>
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
                    <div class="side-event-card">
                        <div class="d-flex gap-3">
                            <img src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" class="side-event-card-img" alt="Event">
                            <div>
                                <h6 class="mb-1 fw-semibold">Food Truck Festival</h6>
                                <div class="d-flex align-items-center text-muted small mb-1">
                                    <i class="bi bi-calendar me-2"></i>
                                    <span>Mar 28</span>
                                </div>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="bi bi-geo-alt me-2"></i>
                                    <span>Miami, FL</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="side-event-card">
                        <div class="d-flex gap-3">
                            <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" class="side-event-card-img" alt="Event">
                            <div>
                                <h6 class="mb-1 fw-semibold">Blockchain Conference</h6>
                                <div class="d-flex align-items-center text-muted small mb-1">
                                    <i class="bi bi-calendar me-2"></i>
                                    <span>Apr 12-14</span>
                                </div>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="bi bi-geo-alt me-2"></i>
                                    <span>Austin, TX</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Popular Tags Section -->
                <div class="popular-tags-container">
                    <div class="popular-tags-header">
                        <h5><i class="bi bi-tags me-2"></i>Popular Tags</h5>
                    </div>
                    <div class="popular-tags-flex">
                        <span class="popular-tag" data-tag="music">
                            <i class="bi bi-music-note-beamed"></i>
                            <span>Music</span>
                        </span>
                        <span class="popular-tag" data-tag="tech">
                            <i class="bi bi-laptop"></i>
                            <span>Tech</span>
                        </span>
                        <span class="popular-tag" data-tag="sports">
                            <i class="bi bi-trophy"></i>
                            <span>Sports</span>
                        </span>
                        <span class="popular-tag" data-tag="gaming">
                            <i class="bi bi-joystick"></i>
                            <span>Gaming</span>
                        </span>
                        <span class="popular-tag" data-tag="comedy">
                            <i class="bi bi-emoji-laughing"></i>
                            <span>Comedy</span>
                        </span>
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