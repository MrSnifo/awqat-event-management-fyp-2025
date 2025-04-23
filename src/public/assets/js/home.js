const Home = {
  tags: [],
  sortBy: 'recommended',
  
  init() {
      // Initialize filters from URL
      const params = new URLSearchParams(location.search);
      this.tags = params.get('tags')?.split(',').filter(Boolean) || [];
      this.sortBy = params.get('sort') || 'recommended';
      
      this.render();
      this.setupFilterEvents();
      this.initInterestButton();
  },

  setupFilterEvents() {
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
  
initInterestButton() {
    const interestBtns = document.querySelectorAll(".interest-btn");

    interestBtns.forEach(function (interestBtn) {
        const interestIcon = interestBtn.querySelector(".interest-icon");
        const interestLabel = interestBtn.querySelector(".interest-label");
        const interestCount = interestBtn.querySelector(".interest-count");

        if (interestBtn && interestIcon && interestLabel && interestCount) {
            interestBtn.addEventListener("click", async function () {
                const eventId = interestBtn.dataset.eventId;
                const isInterested = interestIcon.classList.contains("bi-star-fill");

                try {
                    const response = await fetch('http://localhost/PFA-2024-2025/src/public/api/interest', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ event_id: eventId }),
                        credentials: 'include'
                    });

                    const data = await response.json();

                    if (data.success) {
                        const added = data.action === 'added';
                        interestIcon.className = added ? "interest-icon bi-star-fill" : "interest-icon bi-star";
                        interestBtn.classList.toggle("active", added);
                        interestLabel.textContent = added ? "Interested" : "Show Interest";
                        interestCount.textContent = data.interestCount.toLocaleString();
                    } else if (data.message === "Unauthorized") {
                        window.location.href = "../login";
                    }
                } catch (error) {
                    interestIcon.className = isInterested ? "interest-icon bi-star-fill" : "interest-icon bi-star";
                    interestLabel.textContent = isInterested ? "Interested" : "Show Interest";
                }
            });
        }
    });
},

  updateURL() {
      const params = new URLSearchParams();
      if (this.tags.length) params.set('tags', this.tags.join(','));
      if (this.sortBy !== 'recommended') params.set('sort', this.sortBy);
      
      const newUrl = params.toString() 
          ? `${location.pathname}?${params}`
          : location.pathname;
      
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

// Initialize everything when DOM loads
document.addEventListener('DOMContentLoaded', () => Home.init());