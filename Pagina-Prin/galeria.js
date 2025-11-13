// Funcionalidad de la galería con modal
function abrirModal(src, caption) {
  const modal = document.getElementById('galeria-modal');
  const modalImg = document.getElementById('galeria-modal-img');
  const modalCaption = document.getElementById('galeria-modal-caption');
  
  modal.style.display = 'block';
  modalImg.src = src;
  modalCaption.textContent = caption;
  
  // Prevenir scroll del body cuando el modal está abierto
  document.body.style.overflow = 'hidden';
}

// Cerrar modal al hacer clic en la X
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('galeria-modal');
  const closeBtn = document.querySelector('.galeria-cerrar');
  
  if (closeBtn) {
    closeBtn.addEventListener('click', () => {
      modal.style.display = 'none';
      document.body.style.overflow = 'auto';
    });
  }
  
  // Cerrar modal al hacer clic fuera de la imagen
  if (modal) {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
      }
    });
  }
  
  // Cerrar modal con la tecla ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.style.display === 'block') {
      modal.style.display = 'none';
      document.body.style.overflow = 'auto';
    }
  });
});

