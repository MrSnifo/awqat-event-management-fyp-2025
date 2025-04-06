<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ouqat</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/AddEvent.css">

</head>

<body>
    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand navbar-dark sticky-top">
        <div class="container-fluid navbar-container">
            <!-- Title on the left -->
            <a class="navbar-brand" href="./">
                <span class="brand-gradient">Ouqat</span>
                <span class="brand-arabic">ما يفوتك شي</span>
            </a>

            <!-- Search bar in the middle -->
            <div class="search-container">
                <input type="search" class="form-control search-bar" placeholder="Search events...">
            </div>

            <!-- Auth buttons on the right -->
            <div class="auth-buttons">
                <a href="login" class="btn btn-outline-light">Log In</a>
                <a href="register" class="btn btn-orange">Sign Up</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-0 pt-0">
        <div class="row g-0">
            <!-- Remove gaps between columns -->
            <!-- Left Sidebar - Navigation and Filters -->
            <div class="col-lg-2 sidebar">
                <!-- Navigation Menu -->
                <div class="sidebar-section">
                    <nav class="nav flex-column gap-2 mb-4">
                        <a class="nav-link active" href="./">
                            <i class="bi bi-house-door me-2"></i>Home
                        </a>
                        <a class="nav-link" href="profile">
                            <i class="bi bi-person me-2"></i>Profile
                        </a>
                        <a class="nav-link" href="interests">
                            <i class="bi bi-star me-2"></i> My Interests
                        </a>
                        <a class="nav-link" href="the real deal/index.html">
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
                            <button id="clear-filters" class="btn btn-outline-secondary">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>

         
            <!--Not Found -->
            <!--
            <div class="col-lg-7 p-3">
               <div class="empty-events">
                  <i class="bi bi-calendar2-event empty-icon"></i>
                  <h3 class="empty-title">No Events Found</h3>
                  <p class="empty-message">Try broadening your search or check different categories</p>
               </div>
            </div>
            -->
            <!-- Main Content Area - Create Event -->
            <div class="col-lg-7 p-3" id="create-events-container">
                <div class="event-details-container">
                    <form id="eventForm">
                        <h2 style="color: #FF5733; text-align: center;">Event Details</h2>
                        <!-- Event Topic -->
                        <div class="form-group">
                            <label for="eventTopic">Event Topic:</label>
                            <input type="text" class="tags-input form-control" id="eventTopic" placeholder="What's your event?" required>
                        </div>
                        <br>
                        <!-- Start Date -->
                        <div class="form-group">
                            <label for="startDate">Start Date:</label>
                            <input type="date" class="custom-date-input form-control" id="startDate" required>
                        </div>
                        <br>
                        <!-- Start Time -->
                        <div class="form-group">
                            <label for="startTime">Start Time:</label>
                            <input type="time" class="tags-input form-control" id="startTime" required>
                        </div>
                        <br>
                        <!-- End Date -->
                        <div class="form-group">
                            <label for="endDate">End Date:</label>
                            <input type="date" class="custom-date-input form-control" id="endDate" required>
                        </div>
                        <br>
                        <!-- End Time -->
                        <div class="form-group">
                            <label for="endTime">End Time:</label>
                            <input type="time" class="tags-input form-control" id="endTime" required>
                        </div>
                        <br>
                        <!-- Event Frequency -->
                        <div class="form-group">
                            <label for="eventFrequency">Event Frequency:</label>
                            <select class="tags-input form-control" id="eventFrequency" required>
                                <option value="doesNotRepeat">Does not repeat</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                        <br>
                        <!-- Description -->
                        <div class="form-group">
                            <label for="eventDescription">Description:</label>
                            <textarea class="tags-input form-control" id="eventDescription" placeholder="Tell people a little more about your event. Markdown, new lines, and links are supported." required></textarea>
                        </div>
                        <br>
                        <!-- Cover Image -->
                        <div class="form-group">
                            <label for="coverImage">Cover Image:</label>
                            <input type="file" class="file-upload-input tags-input form-control" id="coverImage" accept="image/*" required>
                            <small>We recommend an image that’s at least 800px wide and 320px tall.</small>
                        </div><br>
                        <div class="tags-input-container mb-3">
                            <label class="form-label small mb-2">Add Tags</label>
                            <div class="tags-input-wrapper">
                                <input type="text" class="tags-input form-control" placeholder="Type and press Enter">
                                <div class="tags-list"></div>
                            </div>
                        </div>
                        <br>
                        <button type="submit" id="apply-filters" class="btn btn-orange flex-grow-1">
                            Save Event
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Sidebar - Recommended and Trending Events -->
            <div class="col-lg-3 right-sidebar">

                <!-- Trending Events Section -->
                <div class="sidebar-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold m-0"><i class="bi bi-fire me-2"></i>Trending</h5>
                    </div>
                    <div class="recommended-event">
                        <div class="d-flex gap-3">
                            <img src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" class="recommended-event-img" alt="Event">
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
                    <div class="recommended-event">
                        <div class="d-flex gap-3">
                            <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" class="recommended-event-img" alt="Event">
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
                      <!-- More tags... -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="assets/js/filters.js"></script>
    <script src="assests/js/AddEvent.js"></script>
</body>

</html>