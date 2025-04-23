document.addEventListener('DOMContentLoaded', function() {
    // Countdown Timer
    function updateCountdown() {
        const countdownContainer = document.getElementById('countdown-timer');
        if (!countdownContainer) return;

        const eventStart = countdownContainer.dataset.eventStart;
        const eventEnd = countdownContainer.dataset.eventEnd;
        
        const eventDate = new Date(eventStart).getTime();
        const endDate = new Date(eventEnd).getTime();
        const now = new Date().getTime();
        
        const statusElement = document.querySelector('.countdown-status');
        
        function update() {
            const now = new Date().getTime();
            let distance, status;
            
            if (now < eventDate) {
                distance = eventDate - now;
                status = 'upcoming';
                if (statusElement) {
                    statusElement.textContent = 'EVENT STARTS SOON';
                    statusElement.style.color = 'var(--primary)';
                }
            } else if (now > endDate) {
                // Event has ended
                if (statusElement) {
                    statusElement.textContent = 'EVENT HAS ENDED';
                    statusElement.style.color = 'var(--secondary)';
                }
                if (document.getElementById("countdown-days")) {
                    document.getElementById("countdown-days").textContent = '00';
                    document.getElementById("countdown-hours").textContent = '00';
                    document.getElementById("countdown-minutes").textContent = '00';
                    document.getElementById("countdown-seconds").textContent = '00';
                }
                return;
            } else {
                distance = endDate - now;
                status = 'ongoing';
                if (statusElement) {
                    statusElement.textContent = 'EVENT IS HAPPENING NOW';
                    statusElement.style.color = 'var(--primary)';
                }
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            if (document.getElementById("countdown-days")) {
                document.getElementById("countdown-days").textContent = days.toString().padStart(2, '0');
                document.getElementById("countdown-hours").textContent = hours.toString().padStart(2, '0');
                document.getElementById("countdown-minutes").textContent = minutes.toString().padStart(2, '0');
                document.getElementById("countdown-seconds").textContent = seconds.toString().padStart(2, '0');
            }
        }
        
        update();
        setInterval(update, 1000);
    }

    // Toggle Interest Button
    const interestBtn = document.getElementById("interest-btn");
    if (interestBtn) {
        interestBtn.addEventListener("click", function() {
            const btn = this;
            const icon = btn.querySelector("i");
            const countSpan = btn.querySelector(".count");
            let currentCount = parseInt(countSpan.textContent.replace(/,/g, ''));
            let isInterested = icon.classList.contains("bi-star-fill");
            
            // Toggle state
            if (isInterested) {
                icon.classList.remove("bi-star-fill");
                icon.classList.add("bi-star");
                currentCount--;
                btn.classList.remove("active");
            } else {
                icon.classList.remove("bi-star");
                icon.classList.add("bi-star-fill");
                currentCount++;
                btn.classList.add("active");
            }
            
            // Update count with formatting
            countSpan.textContent = currentCount.toLocaleString();
        });
    }
    
    // Share Button
    const shareBtn = document.getElementById("share-btn");
    if (shareBtn) {
        shareBtn.addEventListener("click", function(e) {
            e.preventDefault();
            const eventTitle = this.dataset.eventTitle || 'Event';
            
            if (navigator.share) {
                navigator.share({
                    title: eventTitle,
                    text: 'Check out this event on Ouqat',
                    url: window.location.href
                }).catch(err => {
                    console.log('Error sharing:', err);
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                const shareUrl = `mailto:?subject=${encodeURIComponent('Event: ' + eventTitle)}&body=${encodeURIComponent('Check out this event: ' + window.location.href)}`;
                window.location.href = shareUrl;
            }
        });
    }

    // Initialize countdown
    updateCountdown();
});