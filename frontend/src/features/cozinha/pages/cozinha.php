<section id="page-production" class="page">
  <!-- PAGE HEADER -->
  <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
      <h2 class="text-2xl font-bold text-[#171717]">Produção</h2>
      <p class="text-sm text-[#737373] mt-1">
        Registre novas comandas em uma janela rápida e atualize separadamente as etapas da produção.
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

  <div id="productionContent" class="hidden">
    <!-- SUBTOTAIS -->
    <div id="productionSubtotals" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6"></div>

    <!-- COMMAND CENTER -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
      <button id="openRegisterCommandBtn" type="button" class="command-center-btn new-command">
        <span class="center-action-icon">
          <i data-lucide="plus" class="w-6 h-6 text-[#1F6FB2]"></i>
        </span>
        <span class="center-action-copy">
          <strong>Nova comanda</strong>
          <small>Abra a janela para informar montador, comanda e quantidade de pizzas.</small>
        </span>
      </button>

      <button id="openUpdateCommandsBtn" type="button" class="command-center-btn update-command">
        <span id="updatePendingBadge" class="center-action-badge">0</span>
        <span class="center-action-icon">
          <i data-lucide="refresh-cw" class="w-6 h-6 text-[#9a5200]"></i>
        </span>
        <span class="center-action-copy">
          <strong>Atualizar comandas</strong>
          <small>Volte à lista e dê baixa nas pizzas que foram ao forno ou ao despacho.</small>
        </span>
      </button>
    </div>

    <!-- RECENT COMMANDS -->
    <div id="productionRecent" class="bg-white rounded-xl border border-[#E7E7E7] p-4 mb-6 flex items-center justify-between gap-3 flex-wrap"></div>

    <!-- HISTORY / TABLE -->
    <article id="commandHistoryPanel" class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_4px_12px_rgba(0,0,0,0.02)]">
      <div class="flex items-center justify-between p-5 border-b border-[#E7E7E7] flex-wrap gap-4">
        <div>
          <h3 class="text-base font-semibold text-[#171717]">Histórico e atualização das comandas</h3>
          <p class="text-xs text-[#737373] mt-0.5">As comandas no forno aparecem primeiro para facilitar a baixa até o despacho.</p>
        </div>
        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border bg-orange-50 text-[#8c4b00] border-orange-200 transition-all flex items-center gap-1.5">
          <i data-lucide="pointer" class="w-3.5 h-3.5"></i>
          Toque na ação principal para avançar
        </span>
      </div>

      <!-- Filters -->
      <div class="p-5 border-b border-[#E7E7E7] bg-gray-50/50">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[1.5fr_1fr_1fr_auto] gap-3">
          <div class="relative">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
            <input id="prodSearch" type="text" placeholder="Buscar número, montador ou observação" class="w-full pl-9 pr-3 py-2 text-sm border border-[#E7E7E7] rounded-lg text-[#171717] placeholder-gray-400 focus:outline-none focus:border-[#B5120B] focus:ring-2 focus:ring-[#FDECEB] transition-all duration-150">
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
          <button id="clearProdFilters" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 border border-transparent rounded-lg hover:bg-gray-200 active:scale-[0.98] transition-all duration-150 flex items-center justify-center gap-1.5">
            <i data-lucide="x" class="w-4 h-4"></i>
            Limpar
          </button>
        </div>
      </div>

      <div class="overflow-x-auto w-full custom-scrollbar">
        <table class="w-full text-left border-collapse min-w-[900px]">
          <thead>
            <tr class="bg-gray-50/50 border-b border-[#E7E7E7]">
              <th class="px-4 py-3 text-xs font-semibold text-[#173F69] uppercase tracking-wider">Comanda</th>
              <th class="px-4 py-3 text-xs font-semibold text-[#173F69] uppercase tracking-wider">Pizzas</th>
              <th class="px-4 py-3 text-xs font-semibold text-[#173F69] uppercase tracking-wider">Montador</th>
              <th class="px-4 py-3 text-xs font-semibold text-[#173F69] uppercase tracking-wider">Status</th>
              <th class="px-4 py-3 text-xs font-semibold text-[#173F69] uppercase tracking-wider">Horários</th>
              <th class="px-4 py-3 text-xs font-semibold text-[#173F69] uppercase tracking-wider">Observação</th>
              <th class="px-4 py-3 text-xs font-semibold text-[#173F69] uppercase tracking-wider">Erro</th>
              <th class="px-4 py-3 text-xs font-semibold text-[#173F69] uppercase tracking-wider">Ações</th>
            </tr>
          </thead>
          <tbody id="productionBody" class="divide-y divide-[#E7E7E7]"></tbody>
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
    </article>
  </div>
</section>


