<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 z-10 shrink-0">
  <div class="flex items-center gap-4">
    <!-- Hamburger button for mobile menu -->
    <button id="toggleMobileMenuBtn" class="hidden p-2 -ml-2 text-gray-500 hover:bg-gray-100 rounded-lg">
      <i data-lucide="menu" class="w-5 h-5"></i>
    </button>
    <h1 class="text-lg font-bold text-[#B5120B] block sm:hidden tracking-tight">Imperial Pizza</h1>
    <h1 class="text-lg font-semibold text-[#171717] hidden sm:block">Visão Geral da Operação</h1>
    <!-- Operation status badge inside header -->
  </div>

  <div class="flex items-center gap-3 sm:gap-4">
    <!-- Seletor de Mês/Ano no Header -->
    <div class="relative flex items-center bg-gray-50 border border-gray-200 hover:border-gray-300 transition-colors rounded-lg px-2.5 sm:px-3 py-1.5 text-sm font-medium text-gray-600 shadow-sm overflow-hidden">
      <i data-lucide="calendar" class="w-4 h-4 mr-2 text-[#B5120B] pointer-events-none"></i>
      <input type="month" id="headerMonthPicker" class="bg-transparent border-none outline-none text-gray-700 font-semibold text-xs sm:text-sm cursor-pointer p-0 w-[115px] sm:w-[130px] relative z-10" style="color-scheme: light;" />
      <style>
        #headerMonthPicker::-webkit-calendar-picker-indicator {
          position: absolute;
          top: 0; left: 0; right: 0; bottom: 0;
          width: 100%; height: 100%;
          opacity: 0;
          cursor: pointer;
        }
      </style>
    </div>

    <!-- Notification Bell -->
    <button class="hidden sm:flex relative p-2 text-gray-300 cursor-not-allowed rounded-full transition-colors" title="Não disponível" disabled>
      <i data-lucide="bell" class="w-5 h-5"></i>
      <span id="notificationBadge" class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#B5120B] rounded-full border-2 border-white hidden"></span>
    </button>

    <!-- User Avatar -->
    <div class="hidden sm:flex w-8 h-8 rounded-full bg-gradient-to-tr from-gray-200 to-gray-300 border border-gray-200 items-center justify-center text-sm font-medium text-gray-400 shadow-sm cursor-not-allowed opacity-70" title="Não disponível">
      GP
    </div>
  </div>
</header>
