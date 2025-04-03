/**
 * Filters module - Handles tag filtering, URL persistence, and category tag highlighting
 */
const Filters = {
    tags: [], // Array to store currently selected tags
    
    /**
     * Initialize the filters module
     */
    init: function() {
        this.setupEventHandlers();
        this.setupCategoryTagListeners();
        this.loadFromURL(); // Load any filters from URL on page load
    },

    /**
     * Set up event listeners for category tags (predefined tags)
     */
    setupCategoryTagListeners: function() {
        // Add click handler to all category tags
        document.querySelectorAll('.category-tag').forEach((categoryTag) => {
            categoryTag.addEventListener('click', (e) => {
                this.toggleCategoryTag(categoryTag);
            });
        });
    },

    /**
     * Toggle a category tag's active state and update filters
     * @param {HTMLElement} categoryTag - The clicked category tag element
     */
    toggleCategoryTag: function(categoryTag) {
        const tag = categoryTag.dataset.tag;
        
        // Toggle visual active state
        categoryTag.classList.toggle('active');
        
        // Update tags array
        if (this.tags.includes(tag)) {
            this.removeTag(this.tags.indexOf(tag));
        } else {
            this.addTag(tag);
        }
        
        // Update URL and apply filters
        this.updateURL();
        Events.filterEvents(this.tags, document.getElementById('sort-by').value);
    },

    /**
     * Set up all event handlers for the filters
     */
    setupEventHandlers: function() {
        const tagsInput = document.querySelector('.tags-input');

        // Add tag when user presses Enter in input field
        tagsInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && tagsInput.value.trim()) {
                e.preventDefault();
                this.addTag(tagsInput.value.trim().toLowerCase());
                tagsInput.value = ''; // Clear input after adding
            }
        });

        // Apply filters button handler
        document.getElementById('apply-filters').addEventListener('click', () => {
            this.updateURL();
            Events.filterEvents(this.tags, document.getElementById('sort-by').value);
        });

        // Clear filters button handler
        document.getElementById('clear-filters').addEventListener('click', () => {
            this.clearFilters();
        });
    },

    /**
     * Add a tag to the filters
     * @param {string} tag - The tag to add
     */
    addTag: function(tag) {
        if (!this.tags.includes(tag)) {
            this.tags.push(tag);
            this.renderTags();
            this.highlightMatchingCategoryTag(tag); // Highlight matching category tag
        }
    },

    /**
     * Remove a tag from the filters
     * @param {number} index - Index of the tag to remove
     */
    removeTag: function(index) {
        const removedTag = this.tags[index];
        this.tags.splice(index, 1);
        this.renderTags();
        this.unhighlightMatchingCategoryTag(removedTag); // Unhighlight matching category tag
    },

    /**
     * Highlight a category tag that matches the given tag
     * @param {string} tag - The tag to match against category tags
     */
    highlightMatchingCategoryTag: function(tag) {
        document.querySelectorAll('.category-tag').forEach(categoryTag => {
            if (categoryTag.dataset.tag === tag) {
                categoryTag.classList.add('active');
            }
        });
    },

    /**
     * Unhighlight a category tag that matches the given tag
     * @param {string} tag - The tag to match against category tags
     */
    unhighlightMatchingCategoryTag: function(tag) {
        document.querySelectorAll('.category-tag').forEach(categoryTag => {
            if (categoryTag.dataset.tag === tag) {
                categoryTag.classList.remove('active');
            }
        });
    },

    /**
     * Render all current tags in the tags list
     */
    renderTags: function() {
        const tagsList = document.querySelector('.tags-list');
        tagsList.innerHTML = ''; // Clear current tags

        // Create a badge element for each tag
        this.tags.forEach((tag, index) => {
            const tagElement = document.createElement('div');
            tagElement.className = 'badge';
            tagElement.innerHTML = `
                ${tag}
                <span class="tag-remove" data-index="${index}">&times;</span>
            `;
            tagsList.appendChild(tagElement);

            // Add click handler to remove button
            tagElement.querySelector('.tag-remove').addEventListener('click', (e) => {
                e.stopPropagation();
                this.removeTag(index);
            });
        });
    },

    /**
     * Load filters from URL parameters
     */
    loadFromURL: function() {
        const urlParams = new URLSearchParams(window.location.search);
        const urlTags = urlParams.get('tags');
        const urlSort = urlParams.get('sort');

        // Load tags from URL if present
        if (urlTags) {
            this.tags = urlTags.split(',');
            this.renderTags();
            
            // Highlight any matching category tags
            this.tags.forEach(tag => {
                this.highlightMatchingCategoryTag(tag);
            });
        }

        // Set sort value (default to 'recent' if not specified)
        document.getElementById('sort-by').value = urlSort || 'recent';

        // Apply filters if URL has parameters
        if (urlTags || urlSort) {
            Events.filterEvents(this.tags, urlSort || 'recent');
        }
    },

    /**
     * Update the URL with current filter state
     */
    updateURL: function() {
        const params = new URLSearchParams();

        // Add tags to URL if any exist
        if (this.tags.length > 0) {
            params.set('tags', this.tags.join(','));
        }

        // Add sort value if not default
        const sortValue = document.getElementById('sort-by').value;
        if (sortValue !== 'recent') {
            params.set('sort', sortValue);
        }

        // Update URL without reloading page
        const newUrl = params.toString() ? `${window.location.pathname}?${params}` : window.location.pathname;
        window.history.pushState({}, '', newUrl);
    },

    /**
     * Clear all filters and reset to default state
     */
    clearFilters: function() {
        // Clear tags array and UI
        this.tags = [];
        this.renderTags();
        
        // Reset sort dropdown to default
        document.getElementById('sort-by').value = 'recent';
        
        // Remove highlights from all category tags
        document.querySelectorAll('.category-tag.active').forEach(tag => {
            tag.classList.remove('active');
        });
        
        // Clear URL parameters
        window.history.pushState({}, '', window.location.pathname);
        
        // Re-apply empty filters
        Events.filterEvents([], 'recent');
    }
};