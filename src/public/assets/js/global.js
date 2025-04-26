const applyTheme = () => {
    const isDark = localStorage.getItem('user-theme') === 'dark' || 
                  (!localStorage.getItem('user-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches);
    document.documentElement.classList.toggle('dark-theme', isDark);
};

const toggleTheme = () => {
    localStorage.setItem('user-theme', document.documentElement.classList.contains('dark-theme') ? 'light' : 'dark');
    applyTheme();
};

// Handle tag filtering while preserving other parameters
const handlePopularTagClick = (tag) => {
    const params = new URLSearchParams(location.search);
    const currentTags = params.get('tags')?.split(',') || [];
    
    // Toggle the clicked tag
    const newTags = currentTags.includes(tag) 
        ? currentTags.filter(t => t !== tag)
        : [...currentTags, tag];
    
    // Update tags parameter (remove if empty)
    if (newTags.length) {
        params.set('tags', newTags.join(','));
    } else {
        params.delete('tags');
    }
    
    // Redirect with all preserved parameters
    const queryString = params.toString() ? `?${params.toString()}` : '';
    window.location.href = `./${queryString}`;
};

const init = () => {
    applyTheme();
    document.getElementById('themeToggle')?.addEventListener('click', toggleTheme);

    // Set initial active tags from URL
    const activeTags = new URLSearchParams(location.search).get('tags')?.split(',') || [];
    document.querySelectorAll('.popular-tag').forEach(el => {
        el.classList.toggle('active', activeTags.includes(el.dataset.tag));
    });

    // Event delegation
    document.body.addEventListener('click', e => {
        const eventCard = e.target.closest('.side-event-card');
        const popularTag = e.target.closest('.popular-tag');
        
        if (eventCard) {
            e.preventDefault();
            const eventId = eventCard.dataset.eventId;
            window.location.href = `event/${eventId}`;
        }
        
        if (popularTag) {
            e.preventDefault();
            handlePopularTagClick(popularTag.dataset.tag);
        }
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}