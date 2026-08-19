    <section id="page-dispatch" class="page">
      <!-- PAGE HEADER -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
          <h2 class="text-2xl font-bold text-[#171717]">Atendimento / Despacho</h2>
          <p class="text-sm text-[#737373] mt-1">Puxe manualmente as comandas que já estão no forno, confira os complementos e sinalize a saída para entrega.</p>
        </div>
      </div>

      <div id="dispatchGate"></div>

      <!-- Atendimento/Despacho Content -->
      <div id="dispatchContent" class="hidden">
        
        <!-- SUBTOTAIS (KPIs) -->
        <div id="dispatchSubtotals" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 lg:gap-4 mb-6"></div>

        <!-- FILTERS PANEL -->
        <article class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_4px_12px_rgba(0,0,0,0.02)] mb-4">
          <div class="p-5 border-b border-[#E7E7E7] bg-gray-50/30">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
              <div>
                <h3 class="text-base font-semibold text-[#171717]">Fila do atendimento</h3>
                <p class="text-xs text-[#737373] mt-0.5">Recebidas do forno, conferidas e enviadas para entrega.</p>
              </div>
              <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto shrink-0">
                <div class="max-sm:hidden flex items-center bg-[#F3F4F6] p-1 rounded-lg border border-[#E7E7E7]">
                  <button type="button" id="viewListDispatchBtn" class="p-1.5 text-[#9CA3AF] hover:text-[#4B5563] hover:bg-[#E5E7EB] rounded-md transition-colors" title="Ver em lista">
                    <i data-lucide="list" class="w-4 h-4"></i>
                  </button>
                  <button type="button" id="viewGridDispatchBtn" class="p-1.5 bg-white shadow-sm rounded-md text-[#1F6FB2] transition-colors" title="Ver em cards">
                    <i data-lucide="layout-grid" class="w-4 h-4"></i>
                  </button>
                </div>
                <button id="openDispatchIntakeBtn" class="px-4 py-2.5 bg-[#1F6FB2] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5e98] active:scale-[0.98] transition-all duration-150 shadow-[0_2px_8px_rgba(31,111,178,0.25)] border border-transparent flex justify-center items-center gap-2">
                  <div class="relative flex items-center justify-center">
                    <i data-lucide="flame" class="w-4 h-4"></i>
                    <span id="ovenReadyBadge" class="absolute -top-2 -right-2 w-4.5 h-4.5 rounded-full bg-red-500 text-white text-[9px] font-bold flex items-center justify-center border border-white shadow-sm">0</span>
                  </div>
                  Puxar do forno
                </button>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-[1fr_210px_auto] gap-3">
              <div class="relative">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                <input id="dispatchSearch" type="text" placeholder="Buscar comanda ou montador" class="w-full pl-9 pr-3 py-2 text-sm border border-[#E7E7E7] rounded-lg text-[#171717] bg-white focus:outline-none focus:border-[#1F6FB2] focus:ring-2 focus:ring-blue-50 transition-all duration-150">
              </div>
              <div class="relative">
                <select id="dispatchFilter" class="w-full px-3 py-2 text-sm border border-[#E7E7E7] rounded-lg text-[#171717] bg-white focus:outline-none focus:border-[#1F6FB2] focus:ring-2 focus:ring-blue-50 transition-all duration-150 appearance-none">
                  <option value="">Todos os status</option>
                  <option value="aguardando">Recebido / aguardando</option>
                  <option value="conferido">Conferido</option>
                  <option value="entrega">Saiu para entrega</option>
                </select>
                <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
              </div>
              <button id="clearDispatchFilters" class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-[#E7E7E7] rounded-lg hover:bg-gray-50 active:bg-gray-100 transition-colors shadow-sm flex items-center justify-center gap-2">
                <i data-lucide="x" class="w-4 h-4"></i>
                Limpar
              </button>
            </div>
          </div>
        </article>

        <!-- RESULTS PANEL -->
        <div id="dispatchQueuePanel" class="space-y-4">
          <!-- Grid mode -->
          <div id="dispatchGridContainer">
            <div id="dispatchGrid" class="grid grid-cols-1 gap-4"></div>
          </div>

          <!-- List/Table mode -->
          <div id="dispatchTableContainer" class="hidden overflow-x-auto w-full pb-4">
            <table class="w-full text-left min-w-[600px] table-spaced" style="border-spacing: 0 1rem !important;">
              <tbody id="dispatchTableBody"></tbody>
            </table>
          </div>
          
          <div id="dispatchEmpty" class="hidden p-10 text-center flex-col items-center justify-center bg-white rounded-xl border border-dashed border-[#E7E7E7] shadow-[0_4px_12px_rgba(0,0,0,0.02)]">
            <div class="w-12 h-12 bg-white border border-[#E7E7E7] shadow-sm rounded-full flex items-center justify-center mb-3 mx-auto">
              <i data-lucide="inbox" class="w-5 h-5 text-gray-400"></i>
            </div>
            <strong class="block text-sm text-[#171717] mb-1">Nenhuma comanda recebida</strong>
            <p class="text-xs text-[#737373]">Use "Puxar do forno" para trazer uma comanda ao atendimento.</p>
          </div>

          <div id="dispatchPagination"></div>
        </div>
      </div>
    </section>

