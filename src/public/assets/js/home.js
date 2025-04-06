// Theme management
function applyTheme() {
    const isDark = localStorage.getItem('user-theme') === 'dark' || 
                   (localStorage.getItem('user-theme') === null && 
                    window.matchMedia('(prefers-color-scheme: dark)').matches);
    
    document.documentElement.classList.toggle('dark-theme', isDark);
  }
  
  function toggleTheme() {
    const isDark = !document.documentElement.classList.contains('dark-theme');
    localStorage.setItem('user-theme', isDark ? 'dark' : 'light');
    applyTheme();
  }

  
  // Initialize everything
  function init() {
    applyTheme();

    document.getElementById('themeToggle')?.addEventListener('click', function() {
        toggleTheme();
      });
    
    // Initialize other modules
    Events.init();
    Filters.init();
    
    // Event delegation instead of individual listeners
    document.body.addEventListener('click', e => {
      if (e.target.closest('.recommended-event')) {
        e.preventDefault();
        window.location.href = `event/${Math.floor(1000 + Math.random() * 9000)}`;
      }
    });
  }
  
  // Start when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {init();}