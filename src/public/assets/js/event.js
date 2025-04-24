

function initCountdown() {
    const countdown = document.getElementById('countdown-timer');
    if (!countdown) return;

    const start = new Date(countdown.dataset.eventStart).getTime();
    const end = new Date(countdown.dataset.eventEnd).getTime();
    const statusText = document.querySelector('.countdown-status');

    function update() {
        const now = Date.now();
        let distance, label;

        if (now < start) {
            distance = start - now;
            label = 'EVENT STARTS SOON';
        } else if (now > end) {
            label = 'EVENT HAS ENDED';
            updateDisplay(0, 0, 0, 0);
            return statusText && (statusText.textContent = label);
        } else {
            distance = end - now;
            label = 'EVENT IS HAPPENING NOW';
        }

        const days = Math.floor(distance / 86400000);
        const hours = Math.floor((distance % 86400000) / 3600000);
        const minutes = Math.floor((distance % 3600000) / 60000);
        const seconds = Math.floor((distance % 60000) / 1000);

        updateDisplay(days, hours, minutes, seconds);
        if (statusText) {
            statusText.textContent = label;
            statusText.style.color = 'var(--primary)';
        }
    }

    function updateDisplay(d, h, m, s) {
        const set = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.textContent = val.toString().padStart(2, '0');
        };
        set("countdown-days", d);
        set("countdown-hours", h);
        set("countdown-minutes", m);
        set("countdown-seconds", s);
    }

    update();
    setInterval(update, 1000);
}


function initInterestButton() {
    const interestBtn = document.getElementById("interest-btn");
    const interestIcon = document.getElementById("interest-icon");
    const interestLabel = document.getElementById("interest-label");
    const interestCount = document.getElementById("interest-count");

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
                    interestIcon.className = added ? "bi-star-fill" : "bi-star";
                    interestBtn.classList.toggle("active", added);
                    interestLabel.textContent = added ? "Interested" : "Show Interest";
                    interestCount.textContent = data.interestCount.toLocaleString();
                } else if (data.message === "Unauthorized") {
                        const currentUrl = window.location.pathname + window.location.search;
                        const redirect = btoa(currentUrl);
                        window.location.href = `../login?redirect=${redirect}`;
                        return;
                }
            } catch (error) {
                interestIcon.className = isInterested ? "bi-star-fill" : "bi-star";
                interestLabel.textContent = isInterested ? "Interested" : "Show Interest";
            }
        });
    }}

function initShareButton() {
    const shareBtn = document.getElementById("share-btn");
    if (!shareBtn) return;

    shareBtn.addEventListener("click", e => {
        e.preventDefault();
        const title = shareBtn.dataset.eventTitle || 'Event';
        const url = window.location.href;

        if (navigator.share) {
            navigator.share({
                title,
                text: 'Check out this event on Ouqat',
                url
            }).catch(console.error);
        } else {
            window.location.href = `mailto:?subject=${encodeURIComponent("Event: " + title)}&body=${encodeURIComponent("Check this out: " + url)}`;
        }
    });
}


document.addEventListener('DOMContentLoaded', () => {
    initCountdown();
    initInterestButton();
    initShareButton();
});
