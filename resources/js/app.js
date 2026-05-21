document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebar-overlay');
  const toggle = document.getElementById('sidebar-toggle');

  if (!sidebar || !overlay || !toggle) {
    return;
  }

  const openSidebar = () => {
    sidebar.classList.remove('translate-x-full');
    sidebar.classList.add('translate-x-0');
    overlay.classList.remove('opacity-0', 'pointer-events-none');
    overlay.classList.add('opacity-100', 'pointer-events-auto');
  };

  const closeSidebar = () => {
    sidebar.classList.add('translate-x-full');
    sidebar.classList.remove('translate-x-0');
    overlay.classList.add('opacity-0', 'pointer-events-none');
    overlay.classList.remove('opacity-100', 'pointer-events-auto');
  };

  toggle.addEventListener('click', () => {
    if (sidebar.classList.contains('translate-x-full')) {
      openSidebar();
    } else {
      closeSidebar();
    }
  });

  overlay.addEventListener('click', closeSidebar);

  window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
      overlay.classList.add('opacity-0', 'pointer-events-none');
      overlay.classList.remove('opacity-100', 'pointer-events-auto');
    } else if (!overlay.classList.contains('opacity-100')) {
      sidebar.classList.add('translate-x-full');
      sidebar.classList.remove('translate-x-0');
    }
  });
});
