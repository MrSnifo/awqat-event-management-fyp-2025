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
const tagsInput = document.querySelector('.tags-input');
const tagsList = document.querySelector('.tags-list');
let tags = [];

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

function renderTags() {
  tagsList.innerHTML = tags.map((tag, index) => `
      <div class="badge">
          ${tag}
          <span class="tag-remove" data-index="${index}">×</span>
      </div>
  `).join('');
  
  // Add event listeners to remove buttons
  document.querySelectorAll('.tag-remove').forEach(btn => {
      btn.addEventListener('click', function() {
          tags.splice(parseInt(this.dataset.index), 1);
          renderTags();
      });
  });
}

// Form Validation
document.getElementById('eventForm').addEventListener('submit', function(e) {
  e.preventDefault();
  
  if (this.checkValidity()) {
      // Form is valid - proceed with submission
      const formData = {
          title: document.getElementById('eventTitle').value,
          startDate: document.getElementById('startDate').value,
          endDate: document.getElementById('endDate').value || null,
          startTime: document.getElementById('startTime').value,
          endTime: document.getElementById('endTime').value || null,
          description: document.getElementById('eventDescription').value,
          tags: tags,
          coverImage: document.getElementById('coverImage').files[0]
      };
      
      // Here you would typically send formData to your server
      console.log('Form data:', formData);
      
      // Show success message
      alert('Event created successfully!');
      this.reset();
      tags = [];
      renderTags();
      document.getElementById('imagePreview').innerHTML = '<i class="bi bi-image image-placeholder"></i><span class="image-text">No image selected</span>';
  }
  
  this.classList.add('was-validated');
});