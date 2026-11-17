/* ==========================================================================
   Zaira Lite JS (Upgraded & Unified Interactive Engine)
   Supports Public Editorial Portal & Admin/Author/User Portals without bloat
   ========================================================================== */
(function () {
  const html = document.documentElement;
  const savedTheme = localStorage.getItem('zaira_theme') || 'light';
  const savedDir = localStorage.getItem('zaira_dir') || html.getAttribute('dir') || 'ltr';

  function applyTheme(theme) {
    html.setAttribute('tg-theme', theme);
    html.setAttribute('data-bs-theme', theme);
    localStorage.setItem('zaira_theme', theme);
    document.querySelectorAll('.modeSwitch input[type="checkbox"]').forEach(cb => {
      cb.checked = (theme === 'dark');
    });
  }

  applyTheme(savedTheme);
  html.setAttribute('dir', savedDir);

  document.querySelectorAll('.modeSwitch input[type="checkbox"]').forEach(cb => {
    cb.addEventListener('change', () => {
      applyTheme(cb.checked ? 'dark' : 'light');
    });
  });

  // Remove preloader immediately if present
  const pre = document.getElementById('preloader');
  if (pre) pre.style.display = 'none';

  // Apply data-background attributes without jQuery
  document.querySelectorAll('[data-background]').forEach(el => {
    el.style.backgroundImage = `url(${el.getAttribute('data-background')})`;
  });

  // Auto-clone primary desktop menu into mobile menu if empty
  const primaryMenu = document.getElementById('menu-primary-menu');
  const mobileMenuOuter = document.querySelector('.mobile-menu .menu-outer, .tgmobile__menu .tgmobile__menu-outer');
  if (primaryMenu && mobileMenuOuter && mobileMenuOuter.children.length === 0) {
    const cloned = primaryMenu.cloneNode(true);
    cloned.removeAttribute('id');
    mobileMenuOuter.appendChild(cloned);
  }

  // Reading Progress Bar (if #reading-progress-bar exists on article detail page)
  const progressBar = document.getElementById('reading-progress-bar');

  // Sticky header & scroll top
  window.addEventListener('scroll', () => {
    const sh = document.getElementById('sticky-header');
    if (sh) sh.classList.toggle('sticky-menu', window.scrollY > 120);
    const st = document.querySelector('.scroll-top');
    if (st) st.classList.toggle('open', window.scrollY > 250);

    if (progressBar) {
      const docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
      const pct = docHeight > 0 ? Math.min(100, Math.round((window.scrollY / docHeight) * 100)) : 0;
      progressBar.style.width = pct + '%';
    }
  });

  // Global click handlers for Public & Portal UI
  document.addEventListener('click', (e) => {
    if (e.target.closest('.scroll-top')) {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    if (e.target.closest('.menu-tigger')) {
      document.querySelector('.offCanvas-menu, .offCanvas-wrap')?.classList.add('active');
      document.querySelector('.offCanvas-overlay')?.classList.add('active');
    }
    if (e.target.closest('.offCanvas-close, .offCanvas-toggle, .offCanvas-overlay')) {
      document.querySelector('.offCanvas-menu, .offCanvas-wrap')?.classList.remove('active');
      document.querySelector('.offCanvas-overlay')?.classList.remove('active');
    }
    if (e.target.closest('.mobile-nav-toggler')) {
      document.body.classList.add('mobile-menu-visible');
    }
    if (e.target.closest('.mobile-menu .close-btn, .mobile-menu .menu-backdrop, .tgmobile__menu .close-btn, .tgmobile__menu-backdrop')) {
      document.body.classList.remove('mobile-menu-visible');
    }
    if (e.target.closest('.search-open-btn')) {
      document.querySelector('.search__popup')?.classList.add('search-opened');
    }
    if (e.target.closest('.search-close-btn')) {
      document.querySelector('.search__popup')?.classList.remove('search-opened');
    }

    // Portal Sidebar Toggler (#sidebar-toggler, #sidebar-toggler-2, #sidebar-toggler-3)
    if (e.target.closest('#sidebar-toggler, #sidebar-toggler-2, #sidebar-toggler-3')) {
      const sidebar = document.getElementById('main-sidebar');
      if (sidebar) {
        sidebar.classList.toggle('collapse-sidebar');
        sidebar.classList.toggle('-left-[266px]');
        sidebar.classList.toggle('left-0');
      }
    }

    // Portal Sidebar Dropdowns (.have-dropdown .dropdown-btn)
    const dropdownBtn = e.target.closest('.have-dropdown .dropdown-btn');
    if (dropdownBtn) {
      const parentDropdown = dropdownBtn.closest('.dropdown');
      const dataEl = parentDropdown?.querySelector('.dropdown-data');
      if (parentDropdown && dataEl) {
        parentDropdown.classList.toggle('open');
        dataEl.classList.toggle('height-0');
      }
    }
  });

  // Video blog checkbox toggle (#is_video_blog -> #video_url)
  const videoCheckbox = document.getElementById('is_video_blog');
  const videoUrlWrap = document.getElementById('video_url');
  if (videoCheckbox && videoUrlWrap) {
    const syncVideoVisibility = () => {
      videoUrlWrap.classList.toggle('hidden', !videoCheckbox.checked);
      videoUrlWrap.classList.toggle('d-none', !videoCheckbox.checked);
    };
    videoCheckbox.addEventListener('change', syncVideoVisibility);
    syncVideoVisibility();
  }

  // Featured image file preview (#cover-image)
  const coverInput = document.getElementById('cover-image');
  if (coverInput) {
    coverInput.addEventListener('change', (ev) => {
      const file = ev.target.files?.[0];
      const previewImg = document.getElementById('cover-image-preview');
      if (file && previewImg) {
        const reader = new FileReader();
        reader.onload = (loadEv) => {
          previewImg.src = loadEv.target.result;
          previewImg.classList.remove('d-none', 'hidden');
        };
        reader.readAsDataURL(file);
      }
    });
  }

  // Auto-generate Table of Contents (TOC) on article detail page if #article-toc-list exists
  const articleBody = document.getElementById('article-content-body');
  const tocContainer = document.getElementById('article-toc-box');
  const tocList = document.getElementById('article-toc-list');
  if (articleBody && tocContainer && tocList) {
    const headings = articleBody.querySelectorAll('h2, h3');
    if (headings.length > 0) {
      tocContainer.classList.remove('d-none');
      headings.forEach((h, idx) => {
        if (!h.id) h.id = 'section-' + (idx + 1);
        const li = document.createElement('li');
        li.className = h.tagName === 'H3' ? 'ms-3 my-1' : 'my-1 fw-semibold';
        const a = document.createElement('a');
        a.href = '#' + h.id;
        a.textContent = h.textContent;
        a.className = 'text-decoration-none';
        li.appendChild(a);
        tocList.appendChild(li);
      });
    }
  }
})();

// Global helper for Password visibility toggle across User, Author, and Admin forms
window.togglePassword = function (fieldId = 'password') {
  const input = document.getElementById(fieldId) || document.querySelector('input[name="password"]');
  if (!input) return;
  const isPassword = input.getAttribute('type') === 'password';
  input.setAttribute('type', isPassword ? 'text' : 'password');

  const eyeIcon = document.getElementById('eyeIcon-' + fieldId) || document.getElementById('eyeIcon');
  const eyeOffIcon = document.getElementById('eyeOffIcon-' + fieldId) || document.getElementById('eyeOffIcon');
  if (eyeIcon && eyeOffIcon) {
    eyeIcon.style.display = isPassword ? 'none' : 'block';
    eyeOffIcon.style.display = isPassword ? 'block' : 'none';
  }
};

// Global helper for 1-Click Demo Credentials Fill on Login pages
window.fillDemoCredentials = function (email, password = 'password') {
  const emailInput = document.querySelector('input[name="email"]');
  const passInput = document.querySelector('input[name="password"]');
  if (emailInput) emailInput.value = email;
  if (passInput) passInput.value = password;
};

// Global helper for Web Speech API (Listen to Article)
window.toggleArticleSpeech = function (btnEl) {
  if (!('speechSynthesis' in window)) {
    alert('Your browser does not support Text-to-Speech.');
    return;
  }
  const synth = window.speechSynthesis;
  if (synth.speaking) {
    synth.cancel();
    if (btnEl) btnEl.innerHTML = '<i class="fas fa-headphones me-1"></i> Listen to Article';
    return;
  }
  const articleBody = document.getElementById('article-content-body');
  if (!articleBody) return;
  const utterance = new SpeechSynthesisUtterance(articleBody.innerText);
  utterance.rate = 1.0;
  utterance.onend = () => {
    if (btnEl) btnEl.innerHTML = '<i class="fas fa-headphones me-1"></i> Listen to Article';
  };
  if (btnEl) btnEl.innerHTML = '<i class="fas fa-stop-circle me-1"></i> Stop Audio';
  synth.speak(utterance);
};
