<?php
session_start();
require_once '../controllers/auth.php';
require_once '../controllers/event.php';


// Create Auth instance
$auth = new Auth();
$eventController = new EventController();
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$username = $isLoggedIn ? $_SESSION['username'] : '';

// Initialize variables
$errors = [];
$formData = [
    'title' => '',
    'location' => '',
    'start_date' => '',
    'end_date' => '',
    'start_time' => '',
    'end_time' => '',
    'description' => '',
    'cover_image_url' => '',
    'tags' => []
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $formData = [
        'user_id' => $_SESSION['user_id'],
        'title' => htmlspecialchars(trim($_POST['title'] ?? '')),
        'location' => htmlspecialchars(trim($_POST['location'] ?? '')),
        'start_date' => htmlspecialchars(trim($_POST['start_date'] ?? '')),
        'end_date' => htmlspecialchars(trim($_POST['end_date'] ?? '')),
        'start_time' => htmlspecialchars(trim($_POST['start_time'] ?? '')),
        'end_time' => htmlspecialchars(trim($_POST['end_time'] ?? '')),
        'description' => htmlspecialchars(trim($_POST['description'] ?? '')),
        'tags' => isset($_POST['tags']) ? explode(',', $_POST['tags']) : [],
        'status' => 'unverified'
    ];

    // Handle file upload if provided
    if (isset($_FILES['cover_image'])) {
        if ($_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../storage/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = uniqid() . '_' . basename($_FILES['cover_image']['name']);
            $targetPath = $uploadDir . $fileName;

            // Validate image
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($_FILES['cover_image']['tmp_name']);

            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $targetPath)) {
                    $formData['cover_image_url'] = 'storage/uploads/' . $fileName;
                } else {
                    $errors[] = 'Failed to upload image';
                }
            } else {
                $errors[] = 'Invalid file type. Only JPG, PNG, and GIF are allowed.';
            }
        } elseif ($_FILES['cover_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $errors[] = 'Error uploading image';
        }
    }

    if (empty($errors)) {
        $result = $eventController->createEvent($formData);
        if ($result['success']) {
            // Reset form data
            $formData = [
                'title' => '',
                'location' => '',
                'start_date' => '',
                'end_date' => '',
                'start_time' => '',
                'end_time' => '',
                'description' => '',
                'cover_image_url' => '',
                'tags' => []
            ];

            header("Location: ./event/" . $result['event_id']);
            exit();
        } else {
            $errors[] = $result['message'];
        }
    }
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
    <link rel="stylesheet" href="assets/css/create-event.css">
</head>

<body>
    <!-- Improved Navigation Bar -->
    <nav class="navbar navbar-expand navbar-dark sticky-top">
        <div class="container-fluid navbar-container">
            <a class="navbar-brand" href="./">
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
                            <a href="profile" class="username-link">
                                <span class="username"><?php echo htmlspecialchars($username); ?></span>
                            </a>
                            <a href="logout" class="logout-btn">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </a>
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
                        <a class="nav-link" href="interests">
                            <i class="bi bi-star me-2"></i> My Interests
                        </a>
                        <a class="nav-link active" href="create-event">
                            <i class="bi bi-plus-circle me-2"></i> Create Event
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-7 p-3">
                <div class="create-event-container">
                    <h3 class="create-event-title mb-4"><i class="bi bi-plus-circle me-2"></i>Create New Event</h3>
                    
                    <div class="error-message-container">
                    <?php if (!empty($errors)): ?>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <div class="error-message-content">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <span><?php echo htmlspecialchars($error); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    </div>
                    
                    <form method="POST" id="create_event" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <!-- Hidden input for tags -->
                        <input type="hidden" name="tags" id="tagsHiddenInput" value="<?php echo htmlspecialchars(implode(',', $formData['tags'])); ?>">
                        
                        <!-- Event Title -->
                        <div class="mb-4">
                            <label for="eventTitle" class="form-label">Event Title *</label>
                            <input type="text" class="form-control" id="eventTitle" name="title" 
                                   value="<?php echo htmlspecialchars($formData['title']); ?>" required>
                            <div class="invalid-feedback">Please provide a title for your event.</div>
                        </div>
                        
                        <!-- Location -->
                        <div class="mb-4">
                            <label for="eventLocation" class="form-label">Location *</label>
                            <input type="text" class="form-control" id="eventLocation" name="location" 
                                   value="<?php echo htmlspecialchars($formData['location']); ?>" required>
                            <div class="invalid-feedback">Please provide a location for your event.</div>
                        </div>
                        
                        <!-- Date Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="startDate" class="form-label">Start Date *</label>
                                <input type="date" class="form-control" id="startDate" name="start_date" 
                                       value="<?php echo htmlspecialchars($formData['start_date']); ?>" required>
                                <div class="invalid-feedback">Please select a start date.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="endDate" class="form-label">End Date *</label>
                                <input type="date" class="form-control" id="endDate" name="end_date" 
                                       value="<?php echo htmlspecialchars($formData['end_date']); ?>" required>
                                <div class="invalid-feedback">Please select an end date.</div>
                            </div>
                        </div>
                        
                        <!-- Time Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="startTime" class="form-label">Start Time *</label>
                                <input type="time" class="form-control" id="startTime" name="start_time" 
                                       value="<?php echo htmlspecialchars($formData['start_time']); ?>" required>
                                <div class="invalid-feedback">Please select a start time.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="endTime" class="form-label">End Time *</label>
                                <input type="time" class="form-control" id="endTime" name="end_time" 
                                       value="<?php echo htmlspecialchars($formData['end_time']); ?>" required>
                                <div class="invalid-feedback">Please select an end time.</div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-4">
                            <label for="eventDescription" class="form-label">Description *</label>
                            <textarea class="form-control" id="eventDescription" name="description" rows="5" 
                                      required><?php echo htmlspecialchars($formData['description']); ?></textarea>
                            <div class="invalid-feedback">Please provide a description for your event.</div>
                        </div>
                        
                        <!-- Tags Section -->
                        <div class="mb-4">
                            <label class="form-label">Tags *</label>
                            <div class="tags-input-container">
                                <input type="text" class="tags-input form-control" placeholder="Type tag and press Enter">
                                <div class="tags-list" id="tagsList">
                                    <?php foreach ($formData['tags'] as $tag): ?>
                                        <div class="badge">
                                            <?php echo htmlspecialchars($tag); ?>
                                            <span class="tag-remove">×</span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="invalid-feedback">Please add at least one tag.</div>
                        </div>
                        
                        <!-- Cover Image Upload -->
                        <div class="mb-4">
                            <label for="coverImage" class="form-label">Cover Image</label>
                            <div class="image-upload-container">
                                <div class="image-preview" id="imagePreview">
                                    <?php if (!empty($formData['cover_image_url'])): ?>
                                        <img src="<?php echo htmlspecialchars($formData['cover_image_url']); ?>" alt="Cover Preview">
                                    <?php else: ?>
                                        <i class="bi bi-image image-placeholder"></i>
                                        <span class="image-text">No image selected</span>
                                    <?php endif; ?>
                                </div>
                                <input type="file" class="form-control d-none" id="coverImage" name="cover_image" accept="image/*">
                                <button type="button" class="btn btn-outline-secondary mt-2" onclick="document.getElementById('coverImage').click()">
                                    <i class="bi bi-upload me-1"></i> Upload Image
                                </button>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <a href="./" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-orange">Create Event</button>
                        </div>
                    </form>
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
    <script src="assets/js/global.js"></script>
    <script src="assets/js/create-event.js"></script>
</body>

</html>