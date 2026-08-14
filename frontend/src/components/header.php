<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 z-10 shrink-0">
  <div class="flex items-center gap-4">
    <!-- Hamburger button for mobile menu -->
    <button id="toggleMobileMenuBtn" class="lg:hidden p-2 -ml-2 text-gray-500 hover:bg-gray-100 rounded-lg">
      <i data-lucide="menu" class="w-5 h-5"></i>
    </button>
    <h1 class="text-lg font-semibold text-[#171717] hidden sm:block">Visão Geral da Operação</h1>
    <!-- Operation status badge inside header -->
    <span id="phasePill" class="phase-pill">Sem operação</span>
  </div>

  <div class="flex items-center gap-3 sm:gap-4">
    <!-- Date picker or selector (represented as read-only badge here) -->
    <div class="hidden sm:flex items-center bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 text-sm font-medium text-gray-600">
      <i data-lucide="calendar" class="w-[14px] h-[14px] mr-2 text-gray-400"></i>
      <input id="globalDate" type="date" class="bg-transparent border-none outline-none text-gray-600 font-medium text-sm p-0 cursor-pointer" style="color-scheme: light;" />
    </div>
    
    <div class="w-px h-6 bg-gray-200 hidden sm:block"></div>

    <!-- Notification Bell -->
    <button class="relative p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
      <i data-lucide="bell" class="w-5 h-5"></i>
      <span id="notificationBadge" class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#B5120B] rounded-full border-2 border-white hidden"></span>
    </button>

    <!-- User Avatar -->
    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-gray-200 to-gray-300 border border-gray-200 flex items-center justify-center text-sm font-medium text-gray-600 shadow-sm cursor-pointer" title="Configurações do Usuário">
      GP
    </div>
  </div>
</header>
