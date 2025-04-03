const Events = {
    events: [
        {
            id: 4,
            title: "Gourmet Food Festival",
            description: "A culinary journey featuring 50+ top chefs, cooking demonstrations, and unlimited tastings from world-class restaurants.",
            date: "May 10-12, 2024",
            location: "Chicago, IL",
            category: "food",
            tags: ["culinary", "tasting"],
            image: "https://images.unsplash.com/photo-1544025162-d76694265947?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80",
            interested: 950,
            badge: "Popular"
        },
        {
            id: 1,
            title: "Night Music Festival 2024",
            description: "Experience three nights of electronic music with top international DJs including Martin Garrix, David Guetta, and Armin van Buuren under the spectacular city lights.",
            date: "Mar 20-22, 2024",
            location: "Madison Square Garden, NY",
            category: "music",
            tags: ["electronic", "festival"],
            image: "https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80",
            interested: 1800,
            badge: "Trending"
        },

        {
            id: 5,
            title: "Urban Marathon Challenge",
            description: "Annual 42km race through the city with 10,000 participants. Different categories for professionals and amateurs with cash prizes.",
            date: "Nov 3, 2024",
            location: "Boston, MA",
            category: "sports",
            tags: ["running", "competition"],
            image: "https://images.unsplash.com/photo-1543351611-58f69d7c1781?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80",
            interested: 3200,
            badge: "Featured"
        },
        {
            id: 3,
            title: "Tech Innovators Summit 2024",
            description: "Join industry leaders and innovators as they discuss the latest trends in AI, blockchain, and quantum computing. Networking opportunities with top tech companies.",
            date: "Apr 15-17, 2024",
            location: "San Francisco, CA",
            category: "tech",
            tags: ["conference", "networking"],
            image: "https://images.unsplash.com/photo-1492684223066-81342ee5ff30?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80",
            interested: 1200,
            badge: "New"
        },
        {
            id: 2,
            title: "International Art Biennale",
            description: "Contemporary art exhibition featuring 200+ artists from 40 countries, with installations, paintings, and digital art experiences.",
            date: "Jun 5-Sep 5, 2024",
            location: "Venice, Italy",
            category: "art",
            tags: ["exhibition", "contemporary"],
            image: "https://images.unsplash.com/photo-1536922246289-88c42f957773?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80",
            interested: 750,
            badge: "International"
        }
       
    ],

    init: function() {
        this.renderEvents();
        this.setupEventHandlers();
    },

    renderEvents: function(filteredEvents = null) {
        const eventsToRender = filteredEvents || this.events;
        const container = document.getElementById('events-container');

        
        
        container.innerHTML = eventsToRender.map(event => `
            <div class="event-card" data-category="${event.category}" data-tags="${event.tags.join(',')}">
                <div class="row g-0">
                    <div class="col-md-4">
                        <div class="event-image-container">
                            <img src="${event.image}" 
                                 class="event-image img-fluid h-100" alt="${event.title}">
                            <div class="event-badge">${event.badge}</div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="event-content">
                            <div class="event-meta">
                                <span><i class="bi bi-calendar"></i> ${event.date}</span>
                                <span><i class="bi bi-geo-alt"></i> ${event.location}</span>
                            </div>
                            <h4 class="event-title">${event.title}</h4>
                            <p class="event-description">
                                ${event.description}
                            </p>
                            <div class="event-tags">
                                ${event.tags.map(tag => `<span class="badge">${tag}</span>`).join('')}
                            </div>
                            <div class="event-actions">
                                <button class="btn interested-btn">
                                    <i class="bi bi-star"></i>
                                    <span>Interested</span>
                                    <span class="count">${(event.interested/1000).toFixed(1)}k</span>
                                </button>
                                <a href="event.php?id=${event.id}" class="btn details-btn">
                                    <span>View Details</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    },

    setupEventHandlers: function() {
        // Event delegation for interested buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.interested-btn')) {
                const btn = e.target.closest('.interested-btn');
                const isActive = btn.classList.toggle('active');
                const icon = btn.querySelector('.bi');
                const countElement = btn.querySelector('.count');
                
                icon.classList.toggle('bi-star');
                icon.classList.toggle('bi-star-fill');
                
                if (countElement) {
                    const currentText = countElement.textContent;
                    const currentCount = parseFloat(currentText) * 1000;
                    const newCount = isActive ? currentCount + 200 : currentCount - 200;
                    countElement.textContent = (newCount/1000).toFixed(1) + 'k';
                }
            }
        });
    },

    filterEvents: function(tags = [], sortBy = 'popular') {
        let filteredEvents = [...this.events];
        
        // Filter by tags
        if (tags.length > 0) {
            filteredEvents = filteredEvents.filter(event => 
                tags.some(tag => event.tags.includes(tag))
            );
        }
        
        // Sort events
        switch(sortBy) {
            case 'recent':
                // Would need actual dates to implement properly
                break;
            case 'date':
                // Would need actual dates to implement properly
                break;
            case 'name':
                filteredEvents.sort((a, b) => a.title.localeCompare(b.title));
                break;
            case 'popular':
            default:
                filteredEvents.sort((a, b) => b.interested - a.interested);
        }
        
        this.renderEvents(filteredEvents);
    }
};