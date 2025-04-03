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
      <link rel="stylesheet" href="assets/css/style.css">
   </head>
   <body>
      <!-- Main Navigation Bar -->
      <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
         <div class="container-fluid">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
               <span class="brand-gradient">Ouqat</span>
               <span class="brand-arabic">ما يفوتك شي</span>
            </a>
            
            <div class="mx-auto" style="max-width: 500px; width: 100%;">
               <div class="position-relative w-100">
                  <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3" style="color: var(--primary)"></i>
                  <input type="search" class="form-control search-bar rounded-pill w-100" placeholder="Search events...">
               </div>
            </div>
            
            <div class="d-flex align-items-center gap-3">
               <a href="login.php" class="btn btn-outline-light rounded-pill">Log In</a>
               <a href="register.php" class="btn btn-orange rounded-pill">Sign Up</a>
            </div>
         </div>
      </nav>
     
      <!-- Main Content Container -->
      <div class="container-fluid mt-0 pt-0">
         <div class="row g-0"> <!-- Remove gaps between columns -->
            <!-- Left Sidebar - Navigation and Filters -->
            <div class="col-lg-2 sidebar">
               <!-- Navigation Menu -->
               <div class="sidebar-section">
                  <nav class="nav flex-column gap-2 mb-4">
                     <a class="nav-link active" href="index.php">
                     <i class="bi bi-house-door me-2"></i>Home
                     </a>
                     <a class="nav-link" href="profile.php">
                     <i class="bi bi-person me-2"></i>Profile
                     </a>
                     <a class="nav-link" href="interests.php">
                     <i class="bi bi-star me-2"></i> My Interests
                     </a>
                     <a class="nav-link" href="create-event.php">
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
                           <option value="recent">Most Recent</option>
                           <option value="recommended">Recommended for you</option>
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
            
            <!-- Main Content Area - Events Listing -->
            <div class="col-lg-7 p-4" id="events-container">
               <!-- Events will be loaded here -->
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
                        <img src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" 
                           class="recommended-event-img" alt="Event">
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
                        <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" 
                           class="recommended-event-img" alt="Event">
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
               <!-- Recommended Events Section -->
               <div class="sidebar-card">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                     <h5 class="fw-bold m-0"><i class="bi bi-star-fill me-2"></i>Recommended</h5>
                  </div>
                  <div class="recommended-event">
                     <div class="d-flex gap-3">
                        <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" 
                           class="recommended-event-img" alt="Event">
                        <div>
                           <h6 class="mb-1 fw-semibold">Summer Music Fest</h6>
                           <div class="d-flex align-items-center text-muted small mb-1">
                              <i class="bi bi-calendar me-2"></i>
                              <span>Jun 15-17</span>
                           </div>
                           <div class="d-flex align-items-center text-muted small">
                              <i class="bi bi-geo-alt me-2"></i>
                              <span>Chicago, IL</span>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="recommended-event">
                     <div class="d-flex gap-3">
                        <img src="https://images.unsplash.com/photo-1505373877841-8d25f7d46678?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80" 
                           class="recommended-event-img" alt="Event">
                        <div>
                           <h6 class="mb-1 fw-semibold">UX Design Workshop</h6>
                           <div class="d-flex align-items-center text-muted small mb-1">
                              <i class="bi bi-calendar me-2"></i>
                              <span>Apr 5</span>
                           </div>
                           <div class="d-flex align-items-center text-muted small">
                              <i class="bi bi-geo-alt me-2"></i>
                              <span>Online Event</span>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Bootstrap JS Bundle -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
      <!-- Custom JavaScript -->
      <script src="assets/js/events.js"></script>
      <script src="assets/js/filters.js"></script>
      <script src="assets/js/main.js"></script>
   </body>
</html>