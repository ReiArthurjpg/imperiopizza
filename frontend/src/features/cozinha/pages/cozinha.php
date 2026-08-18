<section id="page-production" class="page">
  <!-- PAGE HEADER -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
      <h2 class="text-2xl font-bold text-[#171717]">Produção</h2>
      <p class="text-sm text-[#737373] mt-1">
        Registre novas comandas e acompanhe as etapas da produção em tempo real.
      </p>
    </div>
    
    <div class="shrink-0 w-full sm:w-auto">
      <button id="reopenKitchenBtn" class="hidden w-full sm:w-auto px-4 py-2.5 bg-amber-100 text-amber-800 text-sm font-semibold rounded-lg hover:bg-amber-200 active:scale-[0.98] transition-all duration-150 shadow-[0_2px_8px_rgba(251,191,36,0.25)] flex justify-center items-center gap-2 border border-transparent">
        <i data-lucide="power" class="w-4 h-4"></i>
        Reabrir cozinha
      </button>
      <button id="closeKitchenBtn" class="w-full sm:w-auto px-4 py-2.5 bg-[#B5120B] text-white text-sm font-semibold rounded-lg hover:bg-[#9a0f09] active:scale-[0.98] transition-all duration-150 shadow-[0_2px_8px_rgba(181,18,11,0.25)] flex justify-center items-center gap-2 border border-transparent">
        <i data-lucide="power-off" class="w-4 h-4"></i>
        Encerrar cozinha
      </button>
    </div>
  </div>

  <div id="productionGate"></div>

  <div id="productionContent" class="hidden space-y-4">

    <!-- SUBTOTAIS (KPIs) -->
    <div id="productionSubtotals" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3"></div>

    <!-- Sweet Pending Alert Panel -->
    <div id="sweetPendingPanel" class="hidden">
      <div class="rounded-xl border border-pink-200 bg-pink-50 p-4 shadow-[0_4px_12px_rgba(0,0,0,0.02)]">
        <div class="flex items-center gap-2 mb-3">
          <svg class="w-4 h-4 text-pink-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
          </svg>
          <span class="text-[12px] font-bold text-pink-700 uppercase tracking-wide">Doces aguardando o 2ª montador</span>
        </div>
        <div id="sweetPendingList" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-[115px] overflow-y-auto pr-1"></div>
      </div>
    </div>




    <!-- CONTROLS & FILTERS -->
    <div class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_4px_12px_rgba(0,0,0,0.02)] mb-4">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-5 border-b border-[#E7E7E7] gap-4">
        <div class="w-full text-center sm:text-left sm:w-auto">
          <h3 class="text-base font-semibold text-[#171717]">Comandas da operação</h3>
          <p class="text-xs text-[#737373] mt-0.5">No forno aparecem primeiro · clique na ação principal para avançar.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto shrink-0">
          <div class="hidden sm:flex items-center bg-[#F3F4F6] p-1 rounded-lg border border-[#E7E7E7]">
            <button type="button" id="viewListBtn" class="p-1.5 bg-white shadow-sm rounded-md text-[#1F6FB2] transition-colors" title="Ver em lista">
              <i data-lucide="list" class="w-4 h-4"></i>
            </button>
            <button type="button" id="viewGridBtn" class="p-1.5 text-[#9CA3AF] hover:text-[#4B5563] hover:bg-[#E5E7EB] rounded-md transition-colors" title="Ver em cards">
              <i data-lucide="layout-grid" class="w-4 h-4"></i>
            </button>
          </div>

          <button id="openRegisterCommandBtn" type="button"
            class="w-full sm:w-auto justify-center px-4 py-3 bg-[#1F6FB2] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5e98] active:scale-[0.98] transition-all duration-150 shadow-[0_2px_8px_rgba(31,111,178,0.25)] border border-transparent flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            Adicionar comanda
          </button>
        </div>
      </div>

      <!-- Filters -->
      <div class="p-4 bg-gray-50/50 rounded-b-xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
          <div class="relative">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
            <input id="prodSearch" type="text" placeholder="Buscar comanda, montador..." class="w-full pl-9 pr-3 py-2 text-sm border border-[#E7E7E7] rounded-lg text-[#171717] placeholder-gray-400 focus:outline-none focus:border-[#B5120B] focus:ring-2 focus:ring-[#FDECEB] transition-all duration-150">
          </div>
          <select id="prodStatus" class="w-full px-3 py-2 text-sm border border-[#E7E7E7] rounded-lg text-[#737373] bg-white focus:outline-none focus:border-[#B5120B] focus:ring-2 focus:ring-[#FDECEB] transition-all duration-150">
            <option value="">Todos os status</option>
            <option value="cozinha">Na cozinha</option>
            <option value="forno">No forno</option>
            <option value="pronto">Aguardando atendimento</option>
            <option value="despacho">Saiu para o despacho</option>
          </select>
          <select id="prodAssembler" class="w-full px-3 py-2 text-sm border border-[#E7E7E7] rounded-lg text-[#737373] bg-white focus:outline-none focus:border-[#B5120B] focus:ring-2 focus:ring-[#FDECEB] transition-all duration-150">
            <option value="">Todos os montadores</option>
          </select>
        </div>
      </div>
    </div>

    <!-- HISTORY / TABLE -->
    <div id="commandHistoryPanel">
      <div id="productionTableContainer" class="max-sm:!hidden overflow-x-auto w-full custom-scrollbar pb-4">
        <table class="w-full text-left min-w-[600px] table-spaced">
          <tbody id="productionBody"></tbody>
        </table>
      </div>
      
      <div id="productionGridContainer" class="max-sm:!grid hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pb-4"></div>

      <div id="productionMobileList" class="!hidden"></div>

      <div id="productionEmpty" class="hidden p-10 text-center flex flex-col items-center justify-center">
        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
          <i data-lucide="search-x" class="w-6 h-6 text-gray-400"></i>
        </div>
        <strong class="text-base text-[#171717] mb-1">Nenhuma comanda encontrada</strong>
        <p class="text-sm text-[#737373]">Registre a primeira comanda ou ajuste os filtros.</p>
      </div>

      <!-- Pagination -->
      <div id="prodPagination"></div>

    </div>
  </div>
</section>
