document.querySelectorAll('[data-copy]').forEach((btn) => {
  btn.addEventListener('click', async () => {
    const value = btn.getAttribute('data-copy') || '';
    if (!value) return;
    try {
      await navigator.clipboard.writeText(value);
      const original = btn.innerHTML;
      btn.innerHTML = '<i class="bi bi-check2"></i> Copied';
      setTimeout(() => { btn.innerHTML = original; }, 1400);
    } catch (e) {
      window.prompt('Copy URL:', value);
    }
  });
});

document.querySelectorAll('[data-confirm]').forEach((btn) => {
  btn.addEventListener('click', (event) => {
    const message = btn.getAttribute('data-confirm') || 'Are you sure?';
    if (!window.confirm(message)) {
      event.preventDefault();
    }
  });
});

document.querySelectorAll('[data-toggle-password]').forEach((btn) => {
  btn.addEventListener('click', () => {
    const sel = btn.getAttribute('data-toggle-password');
    const input = sel ? document.querySelector(sel) : null;
    if (!input) return;
    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    btn.innerHTML = show ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
  });
});

const adminToggle = document.getElementById('admin-sidebar-toggle');
const adminSidebar = document.getElementById('admin-sidebar');
const adminOverlay = document.getElementById('admin-overlay');
const adminCollapseBtn = document.querySelector('[data-admin-sidebar-collapse]');
const SIDEBAR_STORAGE_KEY = 'hq-admin-sidebar-collapsed';
const SIDEBAR_DESKTOP_BP = 1024;

function isAdminSidebarDesktop() {
  return window.innerWidth >= SIDEBAR_DESKTOP_BP;
}

function setSidebarToggleIcon(mode) {
  if (!adminToggle) return;
  const icon = adminToggle.querySelector('i');
  if (!icon) return;
  icon.className = mode === 'mobile' ? 'bi bi-list' : mode === 'collapsed' ? 'bi bi-layout-sidebar' : 'bi bi-layout-sidebar-inset';
}

function closeMobileSidebar() {
  adminSidebar?.classList.remove('open');
  adminOverlay?.classList.remove('active');
  document.body.classList.remove('admin-nav-open');
}

function setSidebarCollapsed(collapsed, persist = true) {
  if (!adminSidebar) return;
  adminSidebar.classList.toggle('collapsed', collapsed);
  document.body.classList.toggle('admin-sidebar-collapsed', collapsed);
  if (adminCollapseBtn) {
    adminCollapseBtn.setAttribute('aria-label', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
    adminCollapseBtn.title = collapsed ? 'Expand sidebar' : 'Collapse sidebar';
  }
  if (adminToggle && isAdminSidebarDesktop()) {
    adminToggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
    setSidebarToggleIcon(collapsed ? 'collapsed' : 'expanded');
  }
  if (persist && isAdminSidebarDesktop()) {
    try {
      localStorage.setItem(SIDEBAR_STORAGE_KEY, collapsed ? '1' : '0');
    } catch (e) {}
  }
}

function toggleSidebarCollapsed() {
  if (!adminSidebar) return;
  setSidebarCollapsed(!adminSidebar.classList.contains('collapsed'));
}

function syncAdminSidebarMode() {
  if (!adminSidebar) return;

  if (isAdminSidebarDesktop()) {
    closeMobileSidebar();
    let collapsed = false;
    try {
      collapsed = localStorage.getItem(SIDEBAR_STORAGE_KEY) === '1';
    } catch (e) {}
    setSidebarCollapsed(collapsed, false);
    setSidebarToggleIcon(collapsed ? 'collapsed' : 'expanded');
    adminToggle?.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
  } else {
    adminSidebar.classList.remove('collapsed');
    document.body.classList.remove('admin-sidebar-collapsed');
    setSidebarToggleIcon('mobile');
    adminToggle?.setAttribute('aria-expanded', adminSidebar.classList.contains('open') ? 'true' : 'false');
  }
}

document.querySelectorAll('.admin-nav-link').forEach((link) => {
  const label = link.querySelector('.sidebar-label');
  if (label) {
    link.dataset.tip = label.textContent.trim();
  }
});

if (adminToggle && adminSidebar) {
  adminToggle.addEventListener('click', () => {
    if (isAdminSidebarDesktop()) {
      toggleSidebarCollapsed();
      return;
    }
    const isOpen = adminSidebar.classList.toggle('open');
    adminOverlay?.classList.toggle('active', isOpen);
    document.body.classList.toggle('admin-nav-open', isOpen);
    adminToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });

  adminOverlay?.addEventListener('click', closeMobileSidebar);

  adminCollapseBtn?.addEventListener('click', (event) => {
    event.stopPropagation();
    toggleSidebarCollapsed();
  });

  adminSidebar.querySelectorAll('.admin-nav-link').forEach((link) => {
    link.addEventListener('click', () => {
      if (!isAdminSidebarDesktop()) {
        closeMobileSidebar();
      }
    });
  });

  syncAdminSidebarMode();
  window.addEventListener('resize', syncAdminSidebarMode);
}

document.querySelectorAll('[data-flash]').forEach((el) => {
  window.setTimeout(() => {
    el.classList.add('is-hiding');
    window.setTimeout(() => el.remove(), 300);
  }, 5000);
});

document.querySelectorAll('[data-preview-target]').forEach((input) => {
  input.addEventListener('change', () => {
    const file = input.files?.[0];
    if (!file) return;
    const preview = document.getElementById(input.dataset.previewTarget || '');
    const placeholder = document.getElementById(input.dataset.previewPlaceholder || '');
    const reader = new FileReader();
    reader.onload = () => {
      if (preview) {
        preview.src = reader.result;
        preview.classList.remove('is-hidden');
      }
      placeholder?.classList.add('is-hidden');
      document.querySelector('[data-slider-preview-img]')?.setAttribute('src', reader.result);
    };
    reader.readAsDataURL(file);
  });
});

const sliderForm = document.querySelector('[data-slider-form]');
if (sliderForm) {
  const preview = sliderForm.querySelector('[data-slider-preview]');
  const previewContent = preview?.querySelector('[data-slider-preview-content]');
  const previewOverlay = preview?.querySelector('[data-slider-preview-overlay]');
  const alignSelect = sliderForm.querySelector('[data-slider-align]');
  const opacityRange = sliderForm.querySelector('[data-slider-opacity-range]');
  const opacityInput = sliderForm.querySelector('[data-slider-opacity-input]');

  const fieldMap = {
    title: '[data-slider-preview-title]',
    subtitle: '[data-slider-preview-subtitle]',
    description: '[data-slider-preview-desc]',
    button_text: '[data-slider-preview-btn1]',
    button_text_2: '[data-slider-preview-btn2]',
  };

  const syncPreview = () => {
    Object.entries(fieldMap).forEach(([name, selector]) => {
      const input = sliderForm.querySelector(`[data-slider-field="${name}"], [name="${name}"]`);
      const target = preview?.querySelector(selector);
      if (input && target) {
        target.textContent = input.value || target.dataset.default || target.textContent;
      }
    });
  };

  sliderForm.querySelectorAll('[data-slider-field], [name="title"], [name="subtitle"], [name="description"], [name="button_text"], [name="button_text_2"]').forEach((el) => {
    el.addEventListener('input', syncPreview);
  });

  alignSelect?.addEventListener('change', () => {
    const align = alignSelect.value;
    previewContent?.classList.remove('is-left', 'is-center', 'is-right');
    previewContent?.classList.add(align === 'left' ? 'is-left' : align === 'right' ? 'is-right' : 'is-center');
    preview?.setAttribute('data-align', align);
  });

  const syncOpacity = (value) => {
    const num = Math.min(1, Math.max(0, parseFloat(value) || 0));
    if (opacityRange) opacityRange.value = String(num);
    if (opacityInput) opacityInput.value = String(num);
    if (previewOverlay) previewOverlay.style.opacity = String(num);
  };

  opacityRange?.addEventListener('input', () => syncOpacity(opacityRange.value));
  opacityInput?.addEventListener('input', () => syncOpacity(opacityInput.value));

  syncPreview();
  alignSelect?.dispatchEvent(new Event('change'));
}

document.querySelectorAll('[data-user-menu]').forEach((menu) => {
  const toggle = menu.querySelector('.admin-user-menu__toggle');
  const panel = menu.querySelector('.admin-user-menu__panel');
  if (!toggle || !panel) return;

  const close = () => {
    toggle.setAttribute('aria-expanded', 'false');
    panel.hidden = true;
    menu.classList.remove('is-open');
  };

  const open = () => {
    document.querySelectorAll('[data-user-menu].is-open').forEach((other) => {
      if (other === menu) return;
      other.querySelector('.admin-user-menu__toggle')?.setAttribute('aria-expanded', 'false');
      const otherPanel = other.querySelector('.admin-user-menu__panel');
      if (otherPanel) otherPanel.hidden = true;
      other.classList.remove('is-open');
    });
    toggle.setAttribute('aria-expanded', 'true');
    panel.hidden = false;
    menu.classList.add('is-open');
  };

  toggle.addEventListener('click', (event) => {
    event.stopPropagation();
    if (menu.classList.contains('is-open')) {
      close();
    } else {
      open();
    }
  });

  panel.addEventListener('click', (event) => event.stopPropagation());
});

document.addEventListener('click', () => {
  document.querySelectorAll('[data-user-menu].is-open').forEach((menu) => {
    menu.querySelector('.admin-user-menu__toggle')?.setAttribute('aria-expanded', 'false');
    const panel = menu.querySelector('.admin-user-menu__panel');
    if (panel) panel.hidden = true;
    menu.classList.remove('is-open');
  });
});

document.addEventListener('keydown', (event) => {
  if (event.key !== 'Escape') return;
  document.querySelectorAll('[data-user-menu].is-open').forEach((menu) => {
    menu.querySelector('.admin-user-menu__toggle')?.setAttribute('aria-expanded', 'false');
    const panel = menu.querySelector('.admin-user-menu__panel');
    if (panel) panel.hidden = true;
    menu.classList.remove('is-open');
  });
  closeUserDrawer();
});

function closeUserDrawer() {
  const drawer = document.getElementById('admin-user-drawer');
  const backdrop = document.getElementById('admin-user-drawer-backdrop');
  if (!drawer || !backdrop) return;
  drawer.classList.remove('is-open');
  drawer.setAttribute('aria-hidden', 'true');
  backdrop.classList.remove('is-visible');
  window.setTimeout(() => {
    backdrop.hidden = true;
  }, 250);
  document.body.classList.remove('admin-user-drawer-open');
}

function openUserDrawer(button) {
  const drawer = document.getElementById('admin-user-drawer');
  const backdrop = document.getElementById('admin-user-drawer-backdrop');
  const form = document.getElementById('admin-user-edit-form');
  if (!drawer || !backdrop || !form || !button) return;

  form.action = button.getAttribute('data-action') || '';
  const nameInput = form.querySelector('#edit-name');
  const emailInput = form.querySelector('#edit-email');
  const roleInput = form.querySelector('#edit-role');
  const activeInput = form.querySelector('#edit-active');
  const passwordInput = form.querySelector('#edit-password');
  const title = document.getElementById('admin-user-drawer-title');

  if (nameInput) nameInput.value = button.getAttribute('data-name') || '';
  if (emailInput) emailInput.value = button.getAttribute('data-email') || '';
  if (roleInput) roleInput.value = button.getAttribute('data-role') || '';
  if (activeInput) activeInput.checked = button.getAttribute('data-active') === '1';
  if (passwordInput) passwordInput.value = '';
  if (title) title.textContent = button.getAttribute('data-name') || 'User details';

  backdrop.hidden = false;
  requestAnimationFrame(() => {
    backdrop.classList.add('is-visible');
    drawer.classList.add('is-open');
  });
  drawer.setAttribute('aria-hidden', 'false');
  document.body.classList.add('admin-user-drawer-open');
  nameInput?.focus();
}

document.querySelectorAll('[data-user-edit]').forEach((button) => {
  button.addEventListener('click', () => openUserDrawer(button));
});

document.querySelectorAll('[data-user-drawer-close]').forEach((button) => {
  button.addEventListener('click', closeUserDrawer);
});

document.getElementById('admin-user-drawer-backdrop')?.addEventListener('click', closeUserDrawer);

(function initUsersFilters() {
  const searchInput = document.querySelector('[data-users-search]');
  const roleFilter = document.querySelector('[data-users-role-filter]');
  const statusFilter = document.querySelector('[data-users-status-filter]');
  const rows = document.querySelectorAll('[data-user-row]');
  const emptyState = document.querySelector('[data-users-empty-filter]');
  const countLabel = document.querySelector('[data-users-count-label]');
  if (!rows.length || !searchInput) return;

  const applyFilters = () => {
    const query = searchInput.value.trim().toLowerCase();
    const role = roleFilter?.value || '';
    const status = statusFilter?.value || '';
    let visible = 0;

    rows.forEach((row) => {
      const haystack = row.getAttribute('data-search') || '';
      const rowRole = row.getAttribute('data-role') || '';
      const rowStatus = row.getAttribute('data-status') || '';
      const matches =
        (!query || haystack.includes(query)) &&
        (!role || rowRole === role) &&
        (!status || rowStatus === status);
      row.classList.toggle('is-hidden', !matches);
      if (matches) visible += 1;
    });

    if (countLabel) {
      countLabel.textContent = visible === 1 ? '1 account shown' : `${visible} accounts shown`;
    }
    if (emptyState) {
      emptyState.hidden = visible > 0;
    }
  };

  searchInput.addEventListener('input', applyFilters);
  roleFilter?.addEventListener('change', applyFilters);
  statusFilter?.addEventListener('change', applyFilters);
})();
