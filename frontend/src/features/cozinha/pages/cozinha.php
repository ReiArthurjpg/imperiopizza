<section id="page-production" class="page">
  <!-- PAGE HEADER -->
  <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
      <h2 class="text-2xl font-bold text-[#171717]">Produção</h2>
      <p class="text-sm text-[#737373] mt-1">
        Registre novas comandas e acompanhe as etapas da produção em tempo real.
      </p>
    </div>
    <div class="flex items-center gap-3 shrink-0 mt-1 flex-wrap">
      <button id="manageTeamBtn" class="px-4 py-3 bg-[#E7E9EB] text-[#171717] text-xs font-semibold rounded-lg hover:bg-[#d8dadc] active:scale-[0.98] transition-all duration-150 shadow-sm flex items-center gap-1.5">
        <i data-lucide="users" class="w-3.5 h-3.5"></i>
        Acionar equipe
      </button>
      <button id="reopenKitchenBtn" class="hidden px-4 py-3 bg-amber-100 text-amber-800 text-xs font-semibold rounded-lg hover:bg-amber-200 active:scale-[0.98] transition-all duration-150 shadow-sm flex items-center gap-1.5">
        <i data-lucide="power" class="w-3.5 h-3.5"></i>
        Reabrir cozinha
      </button>
      <button id="closeKitchenBtn" class="px-4 py-3 bg-[#B5120B] text-white text-xs font-semibold rounded-lg hover:bg-[#9a0f09] active:scale-[0.98] transition-all duration-150 shadow-sm flex items-center gap-1.5">
        <i data-lucide="power-off" class="w-3.5 h-3.5"></i>
        Encerrar cozinha
      </button>
    </div>
  </div>

  <div id="productionGate"></div>

  <div id="productionContent" class="hidden space-y-4">

    <!-- SUBTOTAIS (KPIs) -->
    <div id="productionSubtotals" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3"></div>




    <!-- CONTROLS & FILTERS -->
    <div class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_4px_12px_rgba(0,0,0,0.02)] mb-4">
      <div class="flex items-center justify-between p-5 border-b border-[#E7E7E7] flex-wrap gap-4">
        <div>
          <h3 class="text-base font-semibold text-[#171717]">Comandas da operação</h3>
          <p class="text-xs text-[#737373] mt-0.5">No forno aparecem primeiro · clique na ação principal para avançar.</p>
        </div>
        <button id="openRegisterCommandBtn" type="button"
          class="px-4 py-3 bg-[#B5120B] text-white text-xs font-semibold rounded-lg hover:bg-[#9a0f09] active:scale-[0.98] transition-all duration-150 shadow-sm flex items-center gap-1.5">
          <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
          Nova comanda
        </button>
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
      <div class="overflow-x-auto w-full custom-scrollbar pb-4">
        <table class="w-full text-left min-w-[600px] table-spaced">
          <tbody id="productionBody"></tbody>
        </table>
      </div>
      
      <div id="productionMobileList" class="mobile-command-list p-4"></div>

      <div id="productionEmpty" class="hidden p-10 text-center flex flex-col items-center justify-center">
        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
          <i data-lucide="search-x" class="w-6 h-6 text-gray-400"></i>
        </div>
        <strong class="text-base text-[#171717] mb-1">Nenhuma comanda encontrada</strong>
        <p class="text-sm text-[#737373]">Registre a primeira comanda ou ajuste os filtros.</p>
      </div>
    </div>
  </div>
</section>
