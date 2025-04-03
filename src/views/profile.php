<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Ouqat Meets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" style="color: var(--primary)" href="index.html">Ouqat</a>
            <div class="d-flex align-items-center gap-3">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                    <input type="search" class="form-control search-bar rounded-pill" placeholder="Search events...">
                </div>
                <button class="btn btn-orange rounded-pill">Create Event</button>
                <img src="https://randomuser.me/api/portraits/men/32.jpg" class="rounded-circle" alt="Profile" width="36" height="36">
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Left Sidebar - Navigation -->
            <div class="col-lg-2 sidebar">
                <nav class="nav flex-column gap-2 mb-4">
                    <a class="nav-link" href="index.html">
                        <i class="bi bi-house-door me-2"></i>Home
                    </a>
                    <a class="nav-link active" href="profile.html">
                        <i class="bi bi-person me-2"></i>Profile
                    </a>
                    <a class="nav-link" href="saved.html">
                        <i class="bi bi-bookmark me-2"></i>Saved
                    </a>
                </nav>

                <!-- Filters Section -->
                <div class="filter-section">
                    <h6 class="fw-bold mb-3">Filters</h6>
                    <div class="form-check text-white mb-2">
                        <input class="form-check-input" type="checkbox" id="music" checked>
                        <label class="form-check-label" for="music">Music</label>
                    </div>
                    <div class="form-check text-white mb-2">
                        <input class="form-check-input" type="checkbox" id="tech" checked>
                        <label class="form-check-label" for="tech">Technology</label>
                    </div>
                    <div class="form-check text-white mb-2">
                        <input class="form-check-input" type="checkbox" id="food" checked>
                        <label class="form-check-label" for="food">Food & Drink</label>
                    </div>
                    <div class="form-check text-white">
                        <input class="form-check-input" type="checkbox" id="art" checked>
                        <label class="form-check-label" for="art">Art</label>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-7 p-4">
                <div class="event-card">
                    <div class="p-4 text-center">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" class="rounded-circle mb-3" width="120" height="120" alt="Profile">
                        <h3>John Doe</h3>
                        <p class="text-muted">New York, NY</p>
                        <div class="d-flex justify-content-center gap-3 mb-4">
                            <div>
                                <h5>42</h5>
                                <small class="text-muted">Events</small>
                            </div>
                            <div>
                                <h5>128</h5>
                                <small class="text-muted">Following</small>
                            </div>
                            <div>
                                <h5>1.2k</h5>
                                <small class="text-muted">Followers</small>
                            </div>
                        </div>
                        <button class="btn btn-orange">Edit Profile</button>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-3 right-sidebar">
                <!-- Recommended Events -->
                <div class="trending-card">
                    <h5 class="fw-bold mb-3"><i class="bi bi-star-fill me-2"></i>Recommended Events</h5>
                    <div class="recommended-event">
                        <div class="d-flex align-items-start gap-3">
                            <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" class="rounded" width="60" height="60" alt="Event">
                            <div>
                                <h6 class="mb-1">Summer Music Fest</h6>
                                <small class="text-muted d-block"><i class="bi bi-calendar"></i> Jun 15-17</small>
                                <small class="text-muted"><i class="bi bi-geo-alt"></i> Chicago</small>
                            </div>
                        </div>
                    </div>
                    <div class="recommended-event">
                        <div class="d-flex align-items-start gap-3">
                            <img src="https://images.unsplash.com/photo-1505373877841-8d25f7d46678?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" class="rounded" width="60" height="60" alt="Event">
                            <div>
                                <h6 class="mb-1">UX Design Workshop</h6>
                                <small class="text-muted d-block"><i class="bi bi-calendar"></i> Apr 5</small>
                                <small class="text-muted"><i class="bi bi-geo-alt"></i> Online</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>