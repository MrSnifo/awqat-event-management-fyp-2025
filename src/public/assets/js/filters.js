const Filters = {
    tags: [],
    sortBy: 'recommended', // Default sort value

    init() {
        // Load initial state from URL
        const params = new URLSearchParams(location.search);
        this.tags = params.get('tags')?.split(',').filter(Boolean) || [];
        this.sortBy = params.get('sort') || 'recommended';

        this.render();
        this.setupEventListeners();
    },

    setupEventListeners() {
        // Tag input
        document.querySelector('.tags-input')?.addEventListener('keydown', e => {
            if (e.key === 'Enter' && e.target.value.trim()) {
                this.tags.push(e.target.value.trim().toLowerCase());
                e.target.value = '';
                this.render();
            }
        });

        // Sort select
        document.getElementById('sort-by')?.addEventListener('change', e => {
            this.sortBy = e.target.value;
        });

        // Apply filters
        document.getElementById('apply-filters')?.addEventListener('click', () => {
            this.updateURL();
        });

        // Clear filters
        document.getElementById('clear-filters')?.addEventListener('click', () => {
            this.tags = [];
            this.sortBy = 'recommended';
            document.getElementById('sort-by').value = 'recommended';
            this.render();
        });

        // Remove tag
        document.querySelector('.tags-list')?.addEventListener('click', e => {
            if (e.target.classList.contains('tag-remove')) {
                this.tags.splice(e.target.dataset.index, 1);
                this.render();
            }
        });
    },

    updateURL() {
        const params = new URLSearchParams();
        if (this.tags.length) params.set('tags', this.tags.join(','));
        if (this.sortBy !== 'recommended') params.set('sort', this.sortBy);

        const newUrl = params.toString() ? `${location.pathname}?${params}` : location.pathname;

        window.location.href = newUrl;
    },

    render() {
        // Render tags
        const tagsList = document.querySelector('.tags-list');
        if (tagsList) {
            tagsList.innerHTML = this.tags.map((tag, i) => `
          <div class="badge">
            ${tag}
            <span class="tag-remove" data-index="${i}">×</span>
          </div>
        `).join('');
        }

        // Set sort dropdown value
        const sortSelect = document.getElementById('sort-by');
        if (sortSelect) {
            sortSelect.value = this.sortBy;
        }
    }
};

document.addEventListener('DOMContentLoaded', () => Filters.init());