// Toggle del menú con el botón de 3 líneas
document.addEventListener('DOMContentLoaded', () => {
  const menuBtn = document.getElementById('menu-btn');
  const dropdownMenu = document.getElementById('dropdown-menu');

  if (!menuBtn || !dropdownMenu) return;

  menuBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    const isOpen = dropdownMenu.style.display === 'flex' || dropdownMenu.style.display === 'block';
    dropdownMenu.style.display = isOpen ? 'none' : 'flex';
    if (!isOpen) {
      dropdownMenu.style.flexDirection = 'column';
    }
  });

  // Cerrar al hacer clic fuera
  window.addEventListener('click', () => {
    dropdownMenu.style.display = 'none';
  });

  // Evitar cierre al clicar dentro del menú
  dropdownMenu.addEventListener('click', (e) => e.stopPropagation());

  // Scroll suave para enlaces de anclaje
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const href = this.getAttribute('href');
      if (href !== '#' && href.length > 1) {
        const target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
          // Cerrar el menú después de hacer clic
          if (dropdownMenu) {
            dropdownMenu.style.display = 'none';
          }
        }
      }
    });
  });
});
