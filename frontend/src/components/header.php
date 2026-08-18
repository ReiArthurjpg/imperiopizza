<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 z-10 shrink-0">
  <div class="flex items-center gap-4">
    <!-- Hamburger button for mobile menu -->
    <button id="toggleMobileMenuBtn" class="hidden p-2 -ml-2 text-gray-500 hover:bg-gray-100 rounded-lg">
      <i data-lucide="menu" class="w-5 h-5"></i>
    </button>
    <h1 class="text-lg font-semibold text-[#171717] hidden sm:block">Visão Geral da Operação</h1>
    <!-- Operation status badge inside header -->
  </div>

  <div class="flex items-center gap-3 sm:gap-4">
    <!-- Seletor de Mês/Ano no Header -->
    <div class="flex items-center bg-gray-50 border border-gray-200 rounded-lg px-2.5 py-1.5 text-sm font-medium text-gray-600">
      <i data-lucide="calendar" class="w-4 h-4 mr-2 text-gray-400"></i>
      <input type="month" id="headerMonthPicker" class="bg-transparent border-none outline-none text-gray-700 font-semibold text-xs cursor-pointer p-0" style="color-scheme: light;" />
    </div>


    <!-- Notification Bell -->
    <button class="relative p-2 text-gray-300 cursor-not-allowed rounded-full transition-colors" title="Não disponível" disabled>
      <i data-lucide="bell" class="w-5 h-5"></i>
      <span id="notificationBadge" class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#B5120B] rounded-full border-2 border-white hidden"></span>
    </button>

    <!-- User Avatar -->
    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-gray-200 to-gray-300 border border-gray-200 flex items-center justify-center text-sm font-medium text-gray-400 shadow-sm cursor-not-allowed opacity-70" title="Não disponível">
      GP
    </div>
  </div>
</header>
