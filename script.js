document.addEventListener('DOMContentLoaded', () => {
  const button = document.getElementById('mobile-menu');
  const links = document.getElementById('nav-links');
  const topbar = document.querySelector('.topbar');

  button.addEventListener('click', () => {
    const open = links.classList.toggle('active');
    button.classList.toggle('is-active', open);
    button.setAttribute('aria-expanded', open);
  });

  links.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
    links.classList.remove('active');
    button.classList.remove('is-active');
    button.setAttribute('aria-expanded', 'false');
  }));

  window.addEventListener('scroll', () => topbar.classList.toggle('scrolled', window.scrollY > 30));
});
