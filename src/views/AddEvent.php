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

  <style>
        :root {
            --primary: #7C4DFF;
            --primary-light: #EDE7F6;
            --primary-dark: #5E35B1;
            --surface: #ffffff;
            --surface-secondary: #f8f9fa;
            --border-color: #e0e0e0;
            --text-primary: #212121;
            --text-secondary: #757575;
            --text-tertiary: #bdbdbd;
            --success: #4CAF50;
            --success-light: #E8F5E9;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f5f5;
            color: var(--text-primary);
        }

        /* NAVBAR STYLES */
        .navbar {
            background-color: var(--surface);
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 0.8rem 1.5rem;
        }

        .navbar-brand {
            font-weight: 700;
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .brand-gradient {
            background: linear-gradient(135deg, #7C4DFF, #448AFF);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-size: 1.2rem;
        }

        .brand-arabic {
            font-family: 'Noto Sans Arabic', sans-serif;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        /* PROFILE SECTION */
        .profile-header {
            background: var(--surface);
            border-radius: 16px;
            padding: 0;
            margin-bottom: 2.5rem;
            position: relative;
            border: 1px solid var(--border-color);
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .profile-banner {
            height: 200px;
            width: 100%;
            background: linear-gradient(135deg, #7C4DFF, #448AFF);
            position: relative;
            overflow: hidden;
        }

        .profile-banner::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to top, rgba(255,255,255,0.9), transparent);
        }

        .profile-avatar-container {
            display: flex;
            justify-content: center;
            margin-top: -80px;
            position: relative;
            z-index: 2;
        }

        .profile-avatar {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            border: 4px solid var(--surface);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            background: linear-gradient(135deg, #7C4DFF, #448AFF);
            color: white;
            font-weight: bold;
            font-size: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .profile-avatar:hover {
            transform: scale(1.05) rotate(5deg);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .profile-content {
            padding: 2rem 2.5rem;
            text-align: center;
        }

        .profile-name {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
            letter-spacing: -0.5px;
        }

        .profile-username {
            color: var(--primary);
            font-weight: 500;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .profile-bio {
            font-size: 1.05rem;
            line-height: 1.7;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto 1.5rem;
        }

        .profile-stats {
            display: flex;
            gap: 2.5rem;
            margin: 2rem 0;
            justify-content: center;
        }

        .stat-item {
            text-align: center;
            padding: 0 1rem;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
        }

        .stat-item:not(:last-child)::after {
            content: "";
            position: absolute;
            right: -1.25rem;
            top: 50%;
            transform: translateY(-50%);
            height: 40px;
            width: 1px;
            background-color: var(--border-color);
            opacity: 0.6;
        }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--primary);
            transition: all 0.3s ease;
        }

        .stat-item:hover .stat-value {
            color: var(--primary-dark);
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
        }

        .profile-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        /* BUTTONS */
        .btn-profile {
            border-radius: 12px;
            padding: 0.7rem 1.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            border: none;
            font-size: 0.95rem;
        }

        .btn-edit {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(124, 77, 255, 0.25);
        }

        .btn-edit:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(124, 77, 255, 0.35);
        }

        .btn-share {
            background-color: var(--surface);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-share:hover {
            background-color: var(--surface-secondary);
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
        }

        /* EVENT CARDS */
        .event-card {
            background: var(--surface);
            border-radius: 14px;
            padding: 1.75rem;
            margin-bottom: 1.75rem;
            border: 1px solid var(--border-color);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: var(--primary-light);
        }

        .event-card-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            transition: all 0.4s ease;
        }

        .event-card:hover .event-card-img {
            transform: scale(1.07) rotate(1deg);
        }

        .event-title {
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .event-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .event-meta i {
            font-size: 0.9rem;
            color: var(--primary);
        }

        .event-description {
            font-size: 0.95rem;
            line-height: 1.6;
            color: var(--text-secondary);
            margin-bottom: 1.25rem;
        }

        .event-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .btn-event {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* ACTIVE BADGE */
        .active-badge {
            background-color: var(--success-light);
            color: var(--success);
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            margin-left: 0.5rem;
        }

        .active-badge i {
            font-size: 0.7rem;
        }

        /* SECTION TITLE */
        .section-title {
            font-weight: 700;
            margin-bottom: 1.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.35rem;
            color: var(--text-primary);
            position: relative;
            padding-bottom: 0.75rem;
        }

        .section-title::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(to right, var(--primary), var(--primary-light));
            border-radius: 3px;
        }

        .section-title i {
            color: var(--primary);
        }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 3.5rem 2rem;
            color: var(--text-secondary);
            background: var(--surface);
            border-radius: 16px;
            border: 2px dashed var(--border-color);
            margin-top: 1.5rem;
        }

        .empty-state i {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            color: var(--text-tertiary);
            opacity: 0.7;
        }

        .empty-state h5 {
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 1.25rem;
        }

        .empty-state p {
            margin-bottom: 1.75rem;
            font-size: 0.95rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-orange {
            background: linear-gradient(135deg, #FF6B35, #FFA62B);
            color: white;
            border: none;
            font-weight: 500;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-orange:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(255, 107, 53, 0.3);
            color: white;
        }

        /* SIDEBAR STYLES */
        .sidebar {
            background-color: var(--surface);
            border-right: 1px solid var(--border-color);
            min-height: 100vh;
            padding: 1.5rem 1rem;
        }

        .nav-link {
            color: var(--text-secondary);
            font-weight: 500;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: var(--primary);
            background-color: var(--primary-light);
        }

        .nav-link.active {
            color: var(--primary);
            background-color: var(--primary-light);
            font-weight: 600;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        .right-sidebar {
            background-color: var(--surface);
            border-left: 1px solid var(--border-color);
        }

        /* RESPONSIVE ADJUSTMENTS */
        @media (max-width: 992px) {
            .profile-banner {
                height: 180px;
            }
            
            .profile-avatar {
                width: 140px;
                height: 140px;
                font-size: 2.5rem;
            }
            
            .profile-avatar-container {
                margin-top: -70px;
            }
            
            .profile-name {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 768px) {
            .profile-banner {
                height: 160px;
            }
            
            .profile-avatar {
                width: 120px;
                height: 120px;
                font-size: 2rem;
            }
            
            .profile-avatar-container {
                margin-top: -60px;
            }
            
            .profile-content {
                padding: 1.75rem 1.5rem;
            }
            
            .profile-stats {
                flex-wrap: wrap;
                gap: 1.5rem;
            }
            
            .stat-item {
                flex: 1 0 calc(50% - 1.5rem);
            }
            
            .stat-item:not(:last-child)::after {
                display: none;
            }
            
            .profile-actions {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .btn-profile {
                width: 100%;
                justify-content: center;
            }
            
            .event-card {
                flex-direction: column;
            }
            
            .event-card-img {
                width: 100%;
                height: 180px;
                margin-bottom: 1.25rem;
            }
        }

        @media (max-width: 576px) {
            .profile-banner {
                height: 140px;
            }
            
            .profile-avatar {
                width: 100px;
                height: 100px;
                font-size: 1.75rem;
            }
            
            .profile-avatar-container {
                margin-top: -50px;
            }
            
            .profile-name {
                font-size: 1.5rem;
            }
            
            .profile-bio {
                font-size: 0.95rem;
            }
        }
    </style>

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
            <div class="auth-buttons">
                <a href="login" class="btn btn-outline-light">Log In</a>
                <a href="register" class="btn btn-orange">Sign Up</a>
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
            <div class="col-lg-7 p-4">
                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="profile-banner"></div>
                    <div class="profile-avatar-container">
                        <div class="profile-avatar">G14</div>
                    </div>
                    
                    <div class="profile-content">
                        <h1 class="profile-name">Groupe 14</h1>
                        <p class="profile-username">@event_organizers</p>
                        <p class="profile-bio">We create unforgettable experiences and bring people together through amazing events. Passionate about music, tech, and community building!</p>
                        
                        <div class="profile-stats">
                            <div class="stat-item" title="Total events created">
                                <div class="stat-value">42</div>
                                <div class="stat-label">Events</div>
                            </div>

                            <div class="stat-item" title="Currently active events">
                                <div class="stat-value">5</div>
                                <div class="stat-label">Active</div>
                            </div>
                        </div>
                        
                        <div class="profile-actions">
                            <button class="btn btn-profile btn-edit">
                                <i class="bi bi-pencil"></i> Edit Profile
                            </button>
                            <button class="btn btn-profile btn-share">
                                <i class="bi bi-share"></i> Share Profile
                            </button>
                        </div>
                    </div>
                </div>
                
                <h4 class="section-title">
                    <i class="bi bi-calendar-event"></i>
                    <span>My Events</span>
                </h4>
                
                <!-- Events List -->
                <div class="event-card">
                    <div class="d-flex gap-3">
                        <img src="https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" class="event-card-img" alt="Jazz Festival">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center">
                                <h5 class="event-title">Jazz in the Park</h5>
                                <span class="active-badge">
                                    <i class="bi bi-check-circle-fill"></i> Active
                                </span>
                            </div>
                            <div class="event-meta">
                                <i class="bi bi-calendar"></i>
                                <span>Jul 7-9, 2024 • 3 days</span>
                            </div>
                            <div class="event-meta">
                                <i class="bi bi-geo-alt"></i>
                                <span>Chicago, IL • Grant Park</span>
                            </div>
                            <p class="event-description">Join us for three days of incredible jazz performances in the heart of Chicago. Featuring international artists and local talents across multiple stages.</p>
                            <div class="event-actions">
                                <button class="btn btn-sm btn-outline-secondary btn-event">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-event">
                                    <i class="bi bi-trash me-1"></i> Delete
                                </button>
                                <button class="btn btn-sm btn-primary btn-event ms-auto">
                                    <i class="bi bi-eye me-1"></i> View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="event-card">
                    <div class="d-flex gap-3">
                        <img src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" class="event-card-img" alt="Food Truck Festival">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center">
                                <h5 class="event-title">Food Truck Festival</h5>
                                <span class="active-badge">
                                    <i class="bi bi-check-circle-fill"></i> Active
                                </span>
                            </div>
                            <div class="event-meta">
                                <i class="bi bi-calendar"></i>
                                <span>Mar 28, 2024 • One day</span>
                            </div>
                            <div class="event-meta">
                                <i class="bi bi-geo-alt"></i>
                                <span>Miami, FL • Bayfront Park</span>
                            </div>
                            <p class="event-description">The biggest gathering of food trucks in Florida with diverse cuisines from around the world. Vegetarian and vegan options available.</p>
                            <div class="event-actions">
                                <button class="btn btn-sm btn-outline-secondary btn-event">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-event">
                                    <i class="bi bi-trash me-1"></i> Delete
                                </button>
                                <button class="btn btn-sm btn-primary btn-event ms-auto">
                                    <i class="bi bi-eye me-1"></i> View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="event-card">
                    <div class="d-flex gap-3">
                        <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" class="event-card-img" alt="Tech Conference">
                        <div class="flex-grow-1">
                            <h5 class="event-title">Tech Innovators Summit</h5>
                            <div class="event-meta">
                                <i class="bi bi-calendar"></i>
                                <span>Apr 12-14, 2024 • 3 days</span>
                            </div>
                            <div class="event-meta">
                                <i class="bi bi-geo-alt"></i>
                                <span>Austin, TX • Convention Center</span>
                            </div>
                            <p class="event-description">Annual gathering of tech leaders, startups, and innovators showcasing the latest in AI, blockchain, and emerging technologies.</p>
                            <div class="event-actions">
                                <button class="btn btn-sm btn-outline-secondary btn-event">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-event">
                                    <i class="bi bi-trash me-1"></i> Delete
                                </button>
                                <button class="btn btn-sm btn-primary btn-event ms-auto">
                                    <i class="bi bi-eye me-1"></i> View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h5>No more events to show</h5>
                    <p>You've reached the end of your event list. Create a new event to get started!</p>
                    <a href="create-event" class="btn btn-orange mt-2">
                        <i class="bi bi-plus-circle me-1"></i> Create New Event
                    </a>
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