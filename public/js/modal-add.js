
function openModal() {
    const overlay = document.getElementById('modalOverlay');
    const container = document.getElementById('modalContainer');
    
    overlay.classList.remove('hidden');
    overlay.classList.add('flex');
    
    setTimeout(() => {
        container.classList.remove('scale-95', 'opacity-0');
        container.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeModal() {
    const overlay = document.getElementById('modalOverlay');
    const container = document.getElementById('modalContainer');
    
    container.classList.remove('scale-100', 'opacity-100');
    container.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        overlay.classList.remove('flex');
        overlay.classList.add('hidden');
    }, 300);
}

// Close modal when clicking outside
document.getElementById('modalOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
