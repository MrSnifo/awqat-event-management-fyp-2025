
const Filters = {

    
    init: function() {
        this.tags = [];
        this.setupEventHandlers();
        this.setupCategoryTagListeners();
        this.loadFromURL();
    },


    setupCategoryTagListeners: function() {
        const categoryTags = document.querySelectorAll('.category-tag');

    
        categoryTags.forEach((categoryTag) => {
            categoryTag.addEventListener('click', (e) => {

                const tag = categoryTag.getAttribute('data-tag');
                
                // Toggle tag existence
                if (this.tags.includes(tag)) {
                    const index = this.tags.indexOf(tag);
                    this.removeTag(index);
                } else {
                    this.clearFilters();
                    this.addTag(tag);
                }
                
                this.updateURL();
                Events.filterEvents(this.tags, document.getElementById('sort-by').value);
            });
        });
    },

    setupEventHandlers: function() {
        const tagsInput = document.querySelector('.tags-input');

        // Add tag on Enter
        tagsInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && tagsInput.value.trim() !== '') {
                e.preventDefault();
                this.addTag(tagsInput.value.trim().toLowerCase());
                tagsInput.value = '';
            }
        });

        // Apply filters button (updates URL)
        document.getElementById('apply-filters').addEventListener('click', () => {
            this.updateURL();
            const sortValue = document.getElementById('sort-by').value;
            Events.filterEvents(this.tags, sortValue);
        });

        // Clear filters button (does not update URL)
        document.getElementById('clear-filters').addEventListener('click', () => {
            this.clearFilters();
        });
    },
    

    addTag: function(tag) {
        if (!this.tags.includes(tag)) {
            this.tags.push(tag);
            this.renderTags();
        }
    },

    removeTag: function(index) {
        this.tags.splice(index, 1);
        this.renderTags();
    },

    renderTags: function() {
        const tagsList = document.querySelector('.tags-list');
        tagsList.innerHTML = '';

        this.tags.forEach((tag, index) => {
            const tagElement = document.createElement('div');
            tagElement.className = 'badge';
            tagElement.innerHTML = `
                ${tag}
                <span class="tag-remove" data-index="${index}">&times;</span>
            `;
            tagsList.appendChild(tagElement);

            tagElement.querySelector('.tag-remove').addEventListener('click', (e) => {
                e.stopPropagation();
                this.removeTag(index);
            });
        });
    },

    loadFromURL: function() {
        const urlParams = new URLSearchParams(window.location.search);
        const urlTags = urlParams.get('tags');
        const urlSort = urlParams.get('sort');

        // Load tags
        if (urlTags) {
            this.tags = urlTags.split(',');
            this.renderTags();
        }

        // Set sort value (default to 'recent' if not specified)
        document.getElementById('sort-by').value = urlSort || 'recent';

        // Apply filters if URL has parameters
        if (urlTags || urlSort) {
            Events.filterEvents(this.tags, urlSort || 'recent');
        }
    },

    updateURL: function() {
        const params = new URLSearchParams();

        if (this.tags.length > 0) {
            params.set('tags', this.tags.join(','));
        }

        const sortValue = document.getElementById('sort-by').value;
        if (sortValue !== 'recent') {
            params.set('sort', sortValue);
        }

        const newUrl = params.toString() ? `${window.location.pathname}?${params}` : window.location.pathname;
        window.history.pushState({}, '', newUrl);
    },

    clearFilters: function() {
        this.tags = [];
        this.renderTags();
        document.getElementById('sort-by').value = 'recent'; // Reset dropdown
    }
};