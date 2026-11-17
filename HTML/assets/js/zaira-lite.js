/* Zaira Lite JS: Minimal interactions without heavy vendor bloat */
(function () {
  const html = document.documentElement;
  const savedTheme = localStorage.getItem('zaira_theme') || 'light';
  const savedDir = localStorage.getItem('zaira_dir') || 'ltr';
  html.setAttribute('tg-theme', savedTheme);
  html.setAttribute('dir', savedDir);

  document.querySelectorAll('.modeSwitch input[type="checkbox"]').forEach(cb => {
    cb.checked = (savedTheme === 'dark');
    cb.addEventListener('change', () => {
      const next = cb.checked ? 'dark' : 'light';
      html.setAttribute('tg-theme', next);
      localStorage.setItem('zaira_theme', next);
    });
  });

  // Remove preloader immediately if present
  const pre = document.getElementById('preloader');
  if (pre) pre.style.display = 'none';

  // Apply data-background attributes without jQuery
  document.querySelectorAll('[data-background]').forEach(el => {
    el.style.backgroundImage = `url(${el.getAttribute('data-background')})`;
  });

  // Sticky header & scroll top
  window.addEventListener('scroll', () => {
    const sh = document.getElementById('sticky-header');
    if (sh) sh.classList.toggle('sticky-menu', window.scrollY > 120);
    const st = document.querySelector('.scroll-top');
    if (st) st.classList.toggle('open', window.scrollY > 250);
  });

  document.addEventListener('click', (e) => {
    if (e.target.closest('.scroll-top')) window.scrollTo({ top: 0, behavior: 'smooth' });
    if (e.target.closest('.menu-tigger')) {
      document.querySelector('.offCanvas-menu')?.classList.add('active');
      document.querySelector('.offCanvas-overlay')?.classList.add('active');
    }
    if (e.target.closest('.offCanvas-close, .offCanvas-overlay')) {
      document.querySelector('.offCanvas-menu')?.classList.remove('active');
      document.querySelector('.offCanvas-overlay')?.classList.remove('active');
    }
    if (e.target.closest('.mobile-nav-toggler')) document.body.classList.add('mobile-menu-visible');
    if (e.target.closest('.tgmobile__menu .close-btn, .tgmobile__menu-backdrop')) document.body.classList.remove('mobile-menu-visible');
    if (e.target.closest('.search-open-btn')) document.querySelector('.search__popup')?.classList.add('search-opened');
    if (e.target.closest('.search-close-btn')) document.querySelector('.search__popup')?.classList.remove('search-opened');
    const langLink = e.target.closest('a[href*="language/switch/"]');
    if (langLink) {
      e.preventDefault();
      const dir = langLink.getAttribute('href').includes('/ar') ? 'rtl' : 'ltr';
      html.setAttribute('dir', dir);
      localStorage.setItem('zaira_dir', dir);
    }
  });
})();
