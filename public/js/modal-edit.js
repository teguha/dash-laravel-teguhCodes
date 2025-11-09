// function openModalEdit() {
//     const overlay = document.getElementById('modalOverlayEdit');
//     const container = document.getElementById('modalContainerEdit');
    
//     overlay.classList.remove('hidden');
//     overlay.classList.add('flex');
    
//     setTimeout(() => {
//         container.classList.remove('scale-95', 'opacity-0');
//         container.classList.add('scale-100', 'opacity-100');
//     }, 10);
// }

function closeModalEdit() {
    const overlay = document.getElementById('modalOverlayEdit');
    const container = document.getElementById('modalContainerEdit');
    
    container.classList.remove('scale-100', 'opacity-100');
    container.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        overlay.classList.remove('flex');
        overlay.classList.add('hidden');
    }, 300);
}

// Close modal when clicking outside
document.getElementById('modalOverlayEdit').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModalEdit();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModalEdit();
    }
});
