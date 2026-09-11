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
    btn.innerHTML = show
      ? '<i class="bi bi-eye-slash" aria-hidden="true"></i>'
      : '<i class="bi bi-eye" aria-hidden="true"></i>';
    btn.setAttribute('aria-pressed', show ? 'true' : 'false');
    btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
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

document.querySelectorAll('[data-preview-video]').forEach((input) => {
  input.addEventListener('change', () => {
    const file = input.files?.[0];
    if (!file || !file.type.startsWith('video/')) return;
    const preview = document.getElementById(input.dataset.previewVideo || '');
    const placeholder = document.getElementById(input.dataset.previewPlaceholder || '');
    if (preview) {
      preview.src = URL.createObjectURL(file);
      preview.classList.remove('is-hidden');
      preview.muted = true;
      preview.play()?.catch(() => {});
    }
    placeholder?.classList.add('is-hidden');
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

  sliderForm.querySelectorAll('input[name="library_image"]').forEach((radio) => {
    radio.addEventListener('change', () => {
      if (!radio.checked) return;
      const thumb = radio.closest('.admin-hero-library__item')?.querySelector('img');
      const img = document.getElementById('slider-img-preview');
      const placeholder = document.getElementById('slider-img-placeholder');
      const previewBg = preview?.querySelector('[data-slider-preview-image], .admin-slider-preview img');
      if (thumb && img) {
        img.src = thumb.src;
        img.classList.remove('is-hidden');
        placeholder?.classList.add('is-hidden');
      }
      if (thumb && previewBg) {
        previewBg.src = thumb.src;
      }
    });
  });

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

/* ═══════════════════════════════════════════════════════════════
   Section Editor — Tab Switcher
   ═══════════════════════════════════════════════════════════════ */
(function initSectionEditorTabs() {
  const tabNav = document.querySelector('.admin-tabs-nav');
  if (!tabNav) return;

  tabNav.querySelectorAll('[data-tab-target]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const targetId = btn.getAttribute('data-tab-target');
      const target = document.querySelector(targetId);
      if (!target) return;

      // Deactivate all
      tabNav.querySelectorAll('[data-tab-target]').forEach((b) => b.classList.remove('is-active'));
      document.querySelectorAll('.admin-tab-pane').forEach((p) => p.classList.remove('is-active'));

      btn.classList.add('is-active');
      target.classList.add('is-active');

      // If switching to Visual, re-render from JSON
      if (targetId === '#tab-visual') {
        visualBuilder.renderFromJson();
      }
      // If switching to JSON, sync JSON from visual
      if (targetId === '#tab-json') {
        visualBuilder.syncJsonFromVisual();
      }
    });
  });
})();

/* ═══════════════════════════════════════════════════════════════
   Section Editor — Visual Item Builder
   ═══════════════════════════════════════════════════════════════ */
const visualBuilder = (function () {
  const jsonTA     = document.getElementById('section-data');
  const container  = document.getElementById('visual-items-container');
  const addBtn     = document.getElementById('btn-add-item');
  const builderEl  = document.getElementById('visual-builder');
  if (!jsonTA || !container || !builderEl) return { renderFromJson() {}, syncJsonFromVisual() {} };

  let currentType = builderEl.getAttribute('data-detected-type') || 'cards';

  // ── Schema per type ──────────────────────────────────────────
  const SCHEMAS = {
    cards:   [{ key: 'icon',  label: 'Icon (Bootstrap icon class)', ph: 'bi-shield-check' }, { key: 'eyebrow', label: 'Eyebrow (optional)', ph: 'Purpose' }, { key: 'title', label: 'Title', ph: 'Feature title' }, { key: 'text', label: 'Description', type: 'textarea', ph: 'Short description' }],
    timeline:[{ key: 'year', label: 'Year / Date', ph: '2020' }, { key: 'text', label: 'Milestone', type: 'textarea', ph: 'What happened this year' }],
    bento:   [{ key: 'icon', label: 'Icon', ph: 'bi-house-heart' }, { key: 'title', label: 'Title', ph: 'Residential Builds' }, { key: 'text', label: 'Description', type: 'textarea', ph: 'Short description' }, { key: 'img', label: 'Image URL', ph: 'https://...' }, { key: 'href', label: 'Link', ph: '/projects' }, { key: 'mod', label: 'Layout (feature / wide)', ph: 'feature' }],
    steps:   [{ key: 'num', label: 'Step #', ph: '1' }, { key: 'title', label: 'Step Title', ph: 'Discovery & Planning' }, { key: 'text', label: 'Description', type: 'textarea', ph: 'What happens in this step' }],
    faqs:    [{ key: 'q', label: 'Question', ph: 'What is your process?' }, { key: 'a', label: 'Answer', type: 'textarea', ph: 'We begin with an on-site consultation...' }, { key: 'icon', label: 'Icon', ph: 'bi-calculator' }],
    strings: [{ key: '_val', label: 'Badge / Tag Text', ph: 'ISO Certified' }],
    facts:   [{ key: 'value', label: 'Value', ph: '500+' }, { key: 'label', label: 'Label', ph: 'Projects Completed' }],
    stats:   [{ key: 'num', label: 'Number', ph: '10' }, { key: 'suffix', label: 'Suffix', ph: '+' }, { key: 'label', label: 'Label', ph: 'Years Operating' }, { key: 'icon', label: 'Icon', ph: 'bi-award' }],
  };

  function getSchema() {
    return SCHEMAS[currentType] || SCHEMAS.cards;
  }

  function parseJson() {
    try { return JSON.parse(jsonTA.value) || []; } catch { return []; }
  }

  function buildItemEl(item, index) {
    const schema = getSchema();
    const div = document.createElement('div');
    div.className = 'admin-visual-item';
    div.setAttribute('data-vi-index', index);

    const dragHandle = `<div class="admin-visual-item__drag" title="Drag to reorder"><i class="bi bi-grip-vertical"></i></div>`;
    const fields = schema.map((field) => {
      const val = typeof item === 'string' ? item : (item[field.key] || '');
      const inputEl = field.type === 'textarea'
        ? `<textarea rows="2" data-vi-key="${field.key}" placeholder="${field.ph}">${escHtml(val)}</textarea>`
        : `<input type="text" data-vi-key="${field.key}" placeholder="${field.ph}" value="${escHtml(val)}">`;
      return `<div><label>${field.label}</label>${inputEl}</div>`;
    }).join('');
    const removeBtn = `<button type="button" class="admin-visual-item__remove" title="Remove item"><i class="bi bi-trash3"></i></button>`;

    div.innerHTML = `${dragHandle}<div class="admin-visual-item__fields">${fields}</div>${removeBtn}`;

    div.querySelector('.admin-visual-item__remove').addEventListener('click', () => {
      div.remove();
      syncJsonFromVisual();
    });

    // live sync on input
    div.querySelectorAll('[data-vi-key]').forEach((el) => {
      el.addEventListener('input', syncJsonFromVisual);
    });

    return div;
  }

  function buildMapRow(key, value) {
    const div = document.createElement('div');
    div.className = 'admin-visual-item admin-visual-item--map';
    const isComplex = value !== null && typeof value === 'object';
    const display = isComplex ? JSON.stringify(value, null, 2) : String(value ?? '');
    const inputEl = isComplex
      ? `<textarea rows="3" data-vi-map-value data-complex="1">${escHtml(display)}</textarea>`
      : `<input type="text" data-vi-map-value value="${escHtml(display)}">`;
    div.innerHTML = `<div class="admin-visual-item__fields"><div><label>Field</label><input type="text" data-vi-map-key value="${escHtml(key)}"></div><div><label>Value</label>${inputEl}</div></div><button type="button" class="admin-visual-item__remove" title="Remove field"><i class="bi bi-trash3"></i></button>`;
    div.querySelector('.admin-visual-item__remove').addEventListener('click', () => {
      div.remove();
      syncJsonFromVisual();
    });
    div.querySelectorAll('[data-vi-map-key], [data-vi-map-value]').forEach((el) => {
      el.addEventListener('input', syncJsonFromVisual);
    });
    return div;
  }

  function escHtml(str) {
    return String(str).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  }

  function renderFromJson() {
    const items = parseJson();
    container.innerHTML = '';
    if (currentType === 'map' && items && !Array.isArray(items)) {
      const keys = Object.keys(items);
      if (keys.length === 0) {
        container.innerHTML = '<p style="color:#8899aa;font-size:.8125rem;padding:.5rem 0">No extra fields yet. Click <strong>Add Item</strong> to add a label such as button_text.</p>';
        return;
      }
      keys.forEach((key) => container.appendChild(buildMapRow(key, items[key])));
      return;
    }
    if (!Array.isArray(items) || items.length === 0) {
      container.innerHTML = '<p style="color:#8899aa;font-size:.8125rem;padding:.5rem 0">No items yet. Click <strong>Add Item</strong> or choose a preset.</p>';
      return;
    }
    items.forEach((item, i) => container.appendChild(buildItemEl(item, i)));
  }

  function syncJsonFromVisual() {
    const visualRows = container.querySelectorAll('.admin-visual-item');
    if (!visualRows.length && container.querySelector('p')) {
      return;
    }
    if (currentType === 'map') {
      const map = {};
      visualRows.forEach((row) => {
        const key = row.querySelector('[data-vi-map-key]')?.value?.trim();
        if (!key) return;
        const valueEl = row.querySelector('[data-vi-map-value]');
        let value = valueEl?.value ?? '';
        if (valueEl?.getAttribute('data-complex') === '1') {
          try { value = JSON.parse(value); } catch { /* keep string */ }
        }
        map[key] = value;
      });
      jsonTA.value = JSON.stringify(map, null, 2);
      return;
    }
    const schema = getSchema();
    const original = parseJson();
    const items = [];
    visualRows.forEach((row, index) => {
      const keys = schema.map((f) => f.key);
      if (keys.length === 1 && keys[0] === '_val') {
        const val = row.querySelector('[data-vi-key="_val"]')?.value?.trim() || '';
        if (val) items.push(val);
      } else {
        const obj = (original[index] && typeof original[index] === 'object' && !Array.isArray(original[index]))
          ? { ...original[index] }
          : {};
        row.querySelectorAll('[data-vi-key]').forEach((el) => {
          obj[el.getAttribute('data-vi-key')] = el.value;
        });
        items.push(obj);
      }
    });
    jsonTA.value = JSON.stringify(items, null, 2);
  }

  function newBlankItem() {
    if (currentType === 'map') {
      return { key: 'field_name', value: '' };
    }
    const blank = {};
    getSchema().forEach((f) => { blank[f.key] = ''; });
    return blank;
  }

  // Init
  renderFromJson();

  // Add item
  addBtn?.addEventListener('click', () => {
    if (currentType === 'map') {
      const data = parseJson();
      const map = (data && !Array.isArray(data)) ? data : {};
      let key = 'field_name';
      let n = 2;
      while (Object.prototype.hasOwnProperty.call(map, key)) {
        key = 'field_name_' + n;
        n += 1;
      }
      map[key] = '';
      jsonTA.value = JSON.stringify(map, null, 2);
      if (container.querySelector('p')) container.innerHTML = '';
      container.appendChild(buildMapRow(key, ''));
      container.lastElementChild?.querySelector('input')?.focus();
      return;
    }
    const blank = newBlankItem();
    const items = Array.isArray(parseJson()) ? parseJson() : [];
    items.push(blank);
    jsonTA.value = JSON.stringify(items, null, 2);
    if (container.querySelector('p')) container.innerHTML = '';
    container.appendChild(buildItemEl(blank, items.length - 1));
    container.lastElementChild?.querySelector('input, textarea')?.focus();
  });

  // Preset template buttons
  document.querySelectorAll('[data-preset]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const type = btn.getAttribute('data-preset');
      const presets = {
        cards:   [{ icon: 'bi-shield-check', title: 'Quality Assurance', text: 'Every project meets our strict quality benchmarks.' }, { icon: 'bi-clock-history', title: 'On-Time Delivery', text: 'We deliver on schedule, every time.' }],
        steps:   [{ num: '1', title: 'Consultation', text: 'We discuss your vision and requirements.' }, { num: '2', title: 'Planning', text: 'Our team creates a detailed project plan.' }, { num: '3', title: 'Execution', text: 'We build with precision and care.' }],
        faqs:    [{ q: 'How long does a typical project take?', a: 'It depends on scope. We provide a timeline during consultation.' }, { q: 'Do you offer post-construction support?', a: 'Yes, we provide a 12-month warranty and after-service care.' }],
        strings: ['ISO Certified', 'Premium Materials', 'Licensed & Insured', '10+ Years Experience'],
        facts:   [{ value: '500+', label: 'Projects Completed' }, { value: '15+', label: 'Years Experience' }, { value: '98%', label: 'Client Satisfaction' }],
        stats:   [{ num: '10', suffix: '', label: 'Years Operating', icon: 'bi-award' }, { num: '8', suffix: '', label: 'Profiled Projects', icon: 'bi-buildings' }],
        bento:   [{ icon: 'bi-house-heart', title: 'Residential Builds', text: 'Homes designed for long-term living.', img: '', href: '/projects', mod: 'feature' }],
        timeline:[{ year: '2020', text: 'Company founded and first projects delivered.' }, { year: 'Today', text: 'Trusted partner from planning to handover.' }],
      };
      const data = presets[type] || [];
      jsonTA.value = JSON.stringify(data, null, 2);
      currentType = type;
      builderEl.setAttribute('data-detected-type', type);
      renderFromJson();
    });
  });

  return { renderFromJson, syncJsonFromVisual };
})();

/* ═══════════════════════════════════════════════════════════════
   Section Editor — Media Picker Modal
   ═══════════════════════════════════════════════════════════════ */
(function initMediaPicker() {
  const modal       = document.getElementById('media-picker-modal');
  const openBtn     = document.getElementById('btn-open-media-modal');
  const imageInput  = document.getElementById('section-image');
  const previewImg  = document.getElementById('admin-image-preview');
  const previewWrap = document.getElementById('image-preview-container');
  const clearBtn    = document.getElementById('btn-clear-image');

  if (!modal) return;

  function openModal() {
    modal.removeAttribute('hidden');
    document.body.style.overflow = 'hidden';
    modal.querySelector('.admin-modal__close')?.focus();
  }

  function closeModal() {
    modal.setAttribute('hidden', '');
    document.body.style.overflow = '';
    modal.querySelectorAll('.admin-media-picker-item.is-selected').forEach((el) => el.classList.remove('is-selected'));
  }

  openBtn?.addEventListener('click', openModal);

  // Backdrop + close buttons
  modal.querySelectorAll('[data-close-modal]').forEach((el) => {
    el.addEventListener('click', closeModal);
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modal.hasAttribute('hidden')) closeModal();
  });

  // Media item selection
  modal.querySelectorAll('.admin-media-picker-item').forEach((item) => {
    item.addEventListener('click', () => {
      // Mark selected
      modal.querySelectorAll('.admin-media-picker-item').forEach((el) => el.classList.remove('is-selected'));
      item.classList.add('is-selected');

      const url    = item.getAttribute('data-media-url') || '';
      const fullUrl = item.getAttribute('data-full-url') || url;

      // Set image URL input
      if (imageInput) imageInput.value = url;

      // Update preview
      if (previewImg && previewWrap) {
        previewImg.src = fullUrl;
        previewWrap.style.display = '';
      }

      closeModal();
    });
  });

  // Clear image button
  clearBtn?.addEventListener('click', () => {
    if (imageInput) imageInput.value = '';
    if (previewImg) previewImg.src = '';
    if (previewWrap) previewWrap.style.display = 'none';
  });

  // Live preview from URL input
  imageInput?.addEventListener('change', () => {
    const val = imageInput.value.trim();
    if (!val) {
      if (previewWrap) previewWrap.style.display = 'none';
      return;
    }
    const src = val.match(/^https?:\/\//i) ? val : val;
    if (previewImg) previewImg.src = src;
    if (previewWrap) previewWrap.style.display = '';
  });
})();

(function initAdminCommandPalette() {
  const palette = document.getElementById('admin-command');
  const input = document.getElementById('admin-command-input');
  const list = document.getElementById('admin-command-list');
  const openers = document.querySelectorAll('[data-admin-command-open]');
  if (!palette || !input || !list) return;

  const escapeHtml = (value) => String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  let extras = [];
  try {
    extras = JSON.parse(document.getElementById('admin-command-extras')?.textContent || '[]');
  } catch (err) {
    extras = [];
  }

  const navItems = [...document.querySelectorAll('.admin-nav-link')].map((link) => ({
    label: (link.dataset.tip || link.textContent || '').replace(/\s+/g, ' ').trim(),
    href: link.getAttribute('href') || '',
    group: link.closest('.admin-nav-section')?.querySelector('.admin-nav-label')?.textContent?.trim() || 'CMS',
  })).filter((item) => item.label && item.href);

  const items = [...navItems, ...extras].filter((item, index, all) => (
    all.findIndex((other) => other.href === item.href && other.label === item.label) === index
  ));

  let activeIndex = 0;
  let visible = [];

  const render = (query = '') => {
    const q = query.toLowerCase().trim();
    visible = items.filter((item) => !q || `${item.label} ${item.group}`.toLowerCase().includes(q));
    activeIndex = 0;
    if (!visible.length) {
      list.innerHTML = '<li class="admin-command__empty">No matching pages</li>';
      return;
    }
    list.innerHTML = visible.map((item, index) => `
      <li>
        <a href="${escapeHtml(item.href)}" class="admin-command__item${index === 0 ? ' is-active' : ''}" data-index="${index}"${item.blank ? ' target="_blank" rel="noopener"' : ''}>
          <span>${escapeHtml(item.label)}</span>
          <em>${escapeHtml(item.group || 'CMS')}</em>
        </a>
      </li>
    `).join('');
  };

  const open = () => {
    palette.hidden = false;
    document.body.classList.add('admin-command-open');
    render(input.value);
    window.setTimeout(() => input.focus(), 20);
  };

  const close = () => {
    palette.hidden = true;
    document.body.classList.remove('admin-command-open');
    input.value = '';
  };

  const move = (delta) => {
    if (!visible.length) return;
    activeIndex = (activeIndex + delta + visible.length) % visible.length;
    list.querySelectorAll('.admin-command__item').forEach((el, index) => {
      el.classList.toggle('is-active', index === activeIndex);
      if (index === activeIndex) el.scrollIntoView({ block: 'nearest' });
    });
  };

  openers.forEach((btn) => btn.addEventListener('click', open));
  palette.querySelectorAll('[data-admin-command-close]').forEach((btn) => btn.addEventListener('click', close));
  input.addEventListener('input', () => render(input.value));
  input.addEventListener('keydown', (event) => {
    if (event.key === 'ArrowDown') { event.preventDefault(); move(1); }
    if (event.key === 'ArrowUp') { event.preventDefault(); move(-1); }
    if (event.key === 'Enter') {
      event.preventDefault();
      const current = list.querySelector('.admin-command__item.is-active');
      if (current) current.click();
    }
    if (event.key === 'Escape') close();
  });

  document.addEventListener('keydown', (event) => {
    if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
      event.preventDefault();
      palette.hidden ? open() : close();
    }
    if (event.key === 'Escape' && !palette.hidden) close();
  });
})();

document.addEventListener('keydown', (event) => {
  if (!(event.ctrlKey || event.metaKey) || event.key.toLowerCase() !== 's') return;
  const form = document.getElementById('section-form') || document.querySelector('form[data-admin-save], form.admin-form');
  if (!form || event.target.closest('textarea, [contenteditable="true"]')) return;
  event.preventDefault();
  if (typeof form.requestSubmit === 'function') form.requestSubmit();
  else form.submit();
});

(function initAdminListFilters() {
  document.querySelectorAll('[data-admin-list-filter]').forEach((input) => {
    const scope = input.closest('[data-admin-list-scope]') || input.closest('.admin-content') || document;
    const count = scope.querySelector('[data-admin-list-count]');
    const apply = () => {
      const q = input.value.toLowerCase().trim();
      const items = scope.querySelectorAll('[data-admin-list-item]');
      let shown = 0;
      items.forEach((item) => {
        const haystack = (item.getAttribute('data-keywords') || item.textContent || '').toLowerCase();
        const match = !q || haystack.includes(q);
        item.hidden = !match;
        if (match) shown += 1;
      });
      if (count) {
        const noun = count.getAttribute('data-noun') || 'item';
        count.textContent = `${shown} ${shown === 1 ? noun : noun + 's'}`;
      }
    };
    input.addEventListener('input', apply);
  });
})();

(function initAdminNavFilter() {
  const input = document.getElementById('admin-nav-filter');
  if (!input) return;
  const links = document.querySelectorAll('.admin-nav-link');
  const sections = document.querySelectorAll('.admin-nav-section');
  input.addEventListener('input', () => {
    const q = input.value.toLowerCase().trim();
    links.forEach((link) => {
      const label = (link.textContent || '').toLowerCase();
      link.hidden = q !== '' && !label.includes(q);
    });
    sections.forEach((section) => {
      const visible = [...section.querySelectorAll('.admin-nav-link')].some((link) => !link.hidden);
      section.hidden = q !== '' && !visible;
    });
  });
})();

(function initHeroSlideSortable() {
  const body = document.querySelector('[data-hero-sortable]');
  const endpoint = body?.getAttribute('data-reorder-url');
  if (!body || !endpoint) return;

  const csrfInput = () => document.querySelector('input[name="_csrf_token"]');
  const setCsrf = (token) => {
    if (!token) return;
    document.querySelectorAll('input[name="_csrf_token"]').forEach((input) => {
      input.value = token;
    });
  };

  let dragRow = null;

  body.querySelectorAll('tr[data-hero-id]').forEach((row) => {
    const handle = row.querySelector('[data-hero-handle]');
    handle?.addEventListener('mousedown', () => {
      row.setAttribute('draggable', 'true');
    });
    row.addEventListener('dragstart', (event) => {
      dragRow = row;
      row.classList.add('is-dragging');
      event.dataTransfer.effectAllowed = 'move';
      event.dataTransfer.setData('text/plain', row.dataset.heroId || '');
    });
    row.addEventListener('dragend', () => {
      row.classList.remove('is-dragging');
      row.removeAttribute('draggable');
      dragRow = null;
      persist();
    });
  });

  body.addEventListener('dragover', (event) => {
    event.preventDefault();
    const over = event.target.closest('tr[data-hero-id]');
    if (!over || !dragRow || over === dragRow) return;
    const rect = over.getBoundingClientRect();
    const before = event.clientY < rect.top + rect.height / 2;
    body.insertBefore(dragRow, before ? over : over.nextSibling);
  });

  async function persist() {
    const rows = [...body.querySelectorAll('tr[data-hero-id]')];
    rows.forEach((row, index) => {
      const badge = row.querySelector('[data-hero-order]');
      if (badge) badge.textContent = String(index + 1);
    });
    const data = new FormData();
    data.append('_csrf_token', csrfInput()?.value || '');
    rows.forEach((row) => data.append('ids[]', row.dataset.heroId || ''));
    try {
      const res = await fetch(endpoint, {
        method: 'POST',
        body: data,
        headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
      });
      const json = await res.json();
      if (json?.csrf) setCsrf(json.csrf);
    } catch (err) {
      window.location.reload();
    }
  }
})();

