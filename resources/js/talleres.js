document.addEventListener('DOMContentLoaded', function () {
  const section = document.getElementById('talleres');
  if (!section) return;

  const chips = section.querySelectorAll('.filter-chip');
  const cards = section.querySelectorAll('.workshop-card');

  // Filtros
  chips.forEach(chip => {
    chip.addEventListener('click', () => {
      chips.forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
      const filter = chip.getAttribute('data-filter');
      cards.forEach(card => {
        const cat = card.getAttribute('data-category') || '';
        if (filter === 'all' || cat.split(',').includes(filter)) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });

  // Modal
  const modal = document.getElementById('workshop-modal');
  const modalTitle = modal.querySelector('#modal-title');
  const modalDesc = modal.querySelector('#modal-desc');
  const modalInstructor = modal.querySelector('#modal-instructor');
  const modalSchedule = modal.querySelector('#modal-schedule');
  const modalAge = modal.querySelector('#modal-age');
  const modalImg = modal.querySelector('.modal-image img');
  const modalJoin = modal.querySelector('#modal-join');

  section.querySelectorAll('.btn-more').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const card = e.target.closest('.workshop-card');
      if (!card) return;
      
      modalTitle.textContent = card.getAttribute('data-title') || '';
      modalDesc.textContent = card.getAttribute('data-desc') || '';
      modalInstructor.textContent = card.getAttribute('data-instructor') || '';
      modalSchedule.textContent = card.getAttribute('data-schedule') || '';
      modalAge.textContent = card.getAttribute('data-age') || '';
      
      const img = card.getAttribute('data-image') || (card.querySelector('.workshop-image img')?.src || '');
      modalImg.src = img;
      modalImg.alt = modalTitle.textContent;
      
      // Crear link a WhatsApp con el nombre del taller
      modalJoin.href = 'https://wa.me/51999999999?text=' + encodeURIComponent('Hola, quiero inscribirme en: ' + modalTitle.textContent);

      modal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    });
  });

  // Cerrar modal
  modal.querySelectorAll('[data-close]').forEach(el => {
    el.addEventListener('click', () => {
      modal.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    });
  });
  
  // Cierre con ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') {
      modal.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    }
  });
});