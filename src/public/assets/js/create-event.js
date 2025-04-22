// Image Preview Functionality
document.getElementById('coverImage').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = '';
            const img = document.createElement('img');
            img.src = e.target.result;
            preview.appendChild(img);
        }
        
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '<i class="bi bi-image image-placeholder"></i><span class="image-text">No image selected</span>';
    }
});

// Tags Functionality
// Tags Functionality
const tagsInput = document.querySelector('.tags-input');
const tagsList = document.getElementById('tagsList');
const tagsHiddenInput = document.getElementById('tagsHiddenInput');

// Get initial tags from HTML
const initialTags = Array.from(tagsList.querySelectorAll('.badge')).map(badge => {
    return badge.textContent.trim().replace('×', '').trim();
});
let tags = [...initialTags];

function renderTags() {
    tagsList.innerHTML = tags.map(tag => `
        <div class="badge">
            ${tag}
            <span class="tag-remove">×</span>
        </div>
    `).join('');
    
    // Update hidden input
    tagsHiddenInput.value = tags.join(',');
    
    // Add event listeners to remove buttons
    document.querySelectorAll('.tag-remove').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const tagToRemove = this.parentNode.textContent.trim().replace('×', '').trim();
            tags = tags.filter(t => t !== tagToRemove);
            renderTags();
        });
    });
}

tagsInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && this.value.trim()) {
        e.preventDefault();
        const tag = this.value.trim().toLowerCase();
        
        if (!tags.includes(tag)) {
            tags.push(tag);
            renderTags();
        }
        
        this.value = '';
    }
});

// Initialize tags display
renderTags();
// Form Validation
document.querySelector('form').addEventListener('submit', function(e) {
    // Check required fields
    const requiredFields = ['title', 'location', 'start_date', 'end_date', 'start_time', 'end_time', 'description'];
    let isValid = true;
    
    requiredFields.forEach(field => {
        const element = document.querySelector(`[name="${field}"]`);
        if (!element.value.trim()) {
            element.classList.add('is-invalid');
            isValid = false;
        } else {
            element.classList.remove('is-invalid');
        }
    });
    
    // Check tags
    const tags = document.getElementById('tagsHiddenInput').value;
    if (!tags) {
        document.querySelector('.tags-input-container').classList.add('is-invalid');
        isValid = false;
    } else {
        document.querySelector('.tags-input-container').classList.remove('is-invalid');
    }
    
    // Date/time validation
    const startDate = new Date(document.getElementById('startDate').value);
    const endDate = new Date(document.getElementById('endDate').value);
    const startTime = document.getElementById('startTime').value;
    const endTime = document.getElementById('endTime').value;
    
    if (endDate < startDate) {
        document.getElementById('endDate').classList.add('is-invalid');
        isValid = false;
    }
    
    if (endDate.getTime() === startDate.getTime() && endTime <= startTime) {
        document.getElementById('endTime').classList.add('is-invalid');
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    this.classList.add('was-validated');
});

// Clear validation errors when user starts typing
document.querySelectorAll('input, textarea').forEach(input => {
    input.addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });


document.getElementById('create_event').addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Prevent Enter key from submitting the form
    }
});
});