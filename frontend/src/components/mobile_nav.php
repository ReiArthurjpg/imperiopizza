  <style>
  .more-menu-dropdown.hidden {
    display: none !important;
  }
  .mobile-nav .nav-btn:hover,
  .more-menu-item:hover {
    background-color: #FDECEB !important;
    color: #B5120B !important;
  }
  .mobile-nav .nav-btn.active,
  .more-menu-item.active {
    background-color: #FDECEB !important;
    color: #B5120B !important;
  }
  </style>

  <nav class="mobile-nav" style="overflow: visible;">
    <button type="button" class="nav-btn" data-page="dashboard"><span><i data-lucide="layout-dashboard" class="w-5 h-5"></i></span>Resumo</button>
    <button type="button" class="nav-btn" data-page="team"><span><i data-lucide="users" class="w-5 h-5"></i></span>Equipe</button>
    <button type="button" class="nav-btn" data-page="production"><span><i data-lucide="chef-hat" class="w-5 h-5"></i></span>Produção</button>
    <button type="button" class="nav-btn" data-page="stock"><span><i data-lucide="soup" class="w-5 h-5"></i></span>Massas</button>
    
    <div class="nav-btn-more-container" style="position: relative; flex: 1; display: flex; flex-direction: column;">
      <button type="button" class="nav-btn" id="moreMenuBtn" style="border: none; background: transparent; cursor: pointer; width: 100%; height: 100%; display: grid; place-items: center; gap: 2px;">
        <span><i data-lucide="plus" class="w-5 h-5"></i></span>Mais
      </button>
      <div class="more-menu-dropdown hidden" id="moreMenuDropdown" style="position: absolute; bottom: calc(100% + 10px); right: 0; background: #fff; border: 1px solid var(--line, #E7E7E7); border-radius: 12px; box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.15); display: flex; flex-direction: column; min-width: 140px; z-index: 210; overflow: hidden; padding: 4px;">
        <button type="button" class="more-menu-item nav-btn-mobile-spa" data-page="dispatch" style="display: flex; align-items: center; gap: 8px; padding: 10px 12px; border-radius: 8px; text-decoration: none; color: var(--muted, #737373); font-size: 12px; font-weight: 500; transition: background 0.15s; border: none; background: transparent; cursor: pointer;">
          <span style="font-size: 16px; line-height: 1; display: flex; align-items: center;"><i data-lucide="truck" class="w-4 h-4"></i></span>Atend./Desp.
        </button>
        <button type="button" class="more-menu-item nav-btn-mobile-spa" data-page="reports" style="display: flex; align-items: center; gap: 8px; padding: 10px 12px; border-radius: 8px; text-decoration: none; color: var(--muted, #737373); font-size: 12px; font-weight: 500; transition: background 0.15s; border: none; background: transparent; cursor: pointer;">
          <span style="font-size: 16px; line-height: 1; display: flex; align-items: center;"><i data-lucide="bar-chart-2" class="w-4 h-4"></i></span>Relatórios
        </button>
      </div>
    </div>
  </nav>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('moreMenuBtn');
    const dropdown = document.getElementById('moreMenuDropdown');
    if (btn && dropdown) {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
      });
      document.addEventListener('click', (e) => {
        if (!dropdown.classList.contains('hidden') && !e.target.closest('.nav-btn-more-container')) {
          dropdown.classList.add('hidden');
        }
      });
    }
  });
  </script>
