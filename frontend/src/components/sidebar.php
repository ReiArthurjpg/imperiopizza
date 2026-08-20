<aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 bg-white border-r border-[#E7E7E7] flex flex-col transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out w-64 lg:w-[72px] lg:hover:w-64 group shrink-0 overflow-hidden">
  <!-- Logo Area -->
  <div class="h-16 flex items-center px-5 border-b border-gray-100 overflow-hidden whitespace-nowrap">
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 shrink-0 rounded bg-[#B5120B] flex items-center justify-center shadow-inner">
        <span class="text-white font-bold font-serif text-lg leading-none mt-1">IP</span>
      </div>
      <div class="flex flex-col opacity-0 group-hover:opacity-100 transition-all duration-300 overflow-hidden whitespace-nowrap">
        <span class="text-sm font-bold tracking-tight text-[#171717]">Imperial Pizza</span>
        <span class="text-[10px] uppercase font-semibold text-gray-400 tracking-wider">Operações</span>
      </div>
    </div>
  </div>

  <!-- Navigation -->
  <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto overflow-x-hidden">
    <div class="px-3 text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-2 opacity-0 group-hover:opacity-100 transition-all duration-300 overflow-hidden whitespace-nowrap">
      Menu Principal
    </div>
    
    <button class="sidebar-nav-btn w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 gap-3 text-gray-600 hover:bg-[#FDECEB] hover:text-[#B5120B] group active" data-page="dashboard">
      <i data-lucide="layout-dashboard" class="w-5 h-5 shrink-0 text-gray-400 group-hover:text-[#B5120B]"></i>
      <span class="opacity-0 group-hover:opacity-100 transition-all duration-300 overflow-hidden whitespace-nowrap">Dashboard</span>
    </button>
    
    <button class="sidebar-nav-btn w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 gap-3 text-gray-600 hover:bg-[#FDECEB] hover:text-[#B5120B] group" data-page="team">
      <i data-lucide="users" class="w-5 h-5 shrink-0 text-gray-400 group-hover:text-[#B5120B]"></i>
      <span class="opacity-0 group-hover:opacity-100 transition-all duration-300 overflow-hidden whitespace-nowrap">Equipe</span>
    </button>
    
    <button class="sidebar-nav-btn w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 gap-3 text-gray-600 hover:bg-[#FDECEB] hover:text-[#B5120B] group" data-page="production">
      <i data-lucide="chef-hat" class="w-5 h-5 shrink-0 text-gray-400 group-hover:text-[#B5120B]"></i>
      <span class="opacity-0 group-hover:opacity-100 transition-all duration-300 overflow-hidden whitespace-nowrap">Produção</span>
    </button>
    
    <button class="sidebar-nav-btn w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 gap-3 text-gray-600 hover:bg-[#FDECEB] hover:text-[#B5120B] group" data-page="mass">
      <i data-lucide="soup" class="w-5 h-5 shrink-0 text-gray-400 group-hover:text-[#B5120B]"></i>
      <span class="opacity-0 group-hover:opacity-100 transition-all duration-300 overflow-hidden whitespace-nowrap">Massas</span>
    </button>
    
    <button class="sidebar-nav-btn w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 gap-3 text-gray-600 hover:bg-[#FDECEB] hover:text-[#B5120B] group" data-page="dispatch">
      <i data-lucide="truck" class="w-5 h-5 shrink-0 text-gray-400 group-hover:text-[#B5120B]"></i>
      <span class="opacity-0 group-hover:opacity-100 transition-all duration-300 overflow-hidden whitespace-nowrap">Despacho</span>
    </button>
    
    <button class="sidebar-nav-btn w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 gap-3 text-gray-600 hover:bg-[#FDECEB] hover:text-[#B5120B] group" data-page="reports">
      <i data-lucide="bar-chart-2" class="w-5 h-5 shrink-0 text-gray-400 group-hover:text-[#B5120B]"></i>
      <span class="opacity-0 group-hover:opacity-100 transition-all duration-300 overflow-hidden whitespace-nowrap">Relatórios</span>
    </button>
  </nav>

  <!-- Footer/Settings -->
  <div class="p-4 border-t border-gray-100">
    <button id="settingsBtn" class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-[#171717] transition-all duration-200 gap-3">
      <i data-lucide="settings" class="w-5 h-5 shrink-0 text-gray-400"></i>
      <span class="opacity-0 group-hover:opacity-100 transition-all duration-300 overflow-hidden whitespace-nowrap">Configurações</span>
    </button>
  </div>
</aside>
