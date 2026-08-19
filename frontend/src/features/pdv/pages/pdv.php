    <section id="page-dispatch" class="page">
      <!-- PAGE HEADER -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
          <h2 class="text-2xl font-bold text-[#171717]">Atendimento / Despacho</h2>
          <p class="text-sm text-[#737373] mt-1">Puxe manualmente as comandas que já estão no forno, confira os complementos e sinalize a saída para entrega.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto shrink-0">
          <button id="manageTeamDispatchBtn" class="w-full sm:w-auto px-4 py-2.5 bg-white border border-[#E7E7E7] text-[#171717] text-sm font-semibold rounded-lg hover:bg-gray-50 active:scale-[0.98] transition-all duration-150 shadow-sm flex justify-center items-center gap-2">
            <i data-lucide="users" class="w-4 h-4"></i>
            Acionar equipe
          </button>
          <button id="finishDayBtn" class="w-full sm:w-auto px-4 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 active:scale-[0.98] transition-all duration-150 shadow-sm flex justify-center items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            Finalizar o dia
          </button>
        </div>
      </div>

      <div id="dispatchGate"></div>

      <div id="dispatchContent" class="hidden space-y-6">
        
        <!-- SUBTOTAIS (KPIs) -->
        <div id="dispatchSubtotals" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 lg:gap-4"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <button id="openDispatchIntakeBtn" type="button" class="relative group bg-white border border-[#E5E7EB] hover:border-[#1F6FB2] rounded-xl p-5 shadow-sm transition-all duration-300 hover:shadow-md flex items-start gap-4 text-left overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-100 rounded-full blur-2xl opacity-40 group-hover:opacity-80 transition-opacity"></div>
            <div class="relative z-10 w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
              <i data-lucide="flame" class="w-6 h-6"></i>
              <span id="ovenReadyBadge" class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-red-500 text-white text-[11px] font-bold flex items-center justify-center border-2 border-white shadow-sm">0</span>
            </div>
            <div class="relative z-10">
              <strong class="block text-base font-bold text-[#171717] mb-1">Puxar do forno</strong>
              <small class="text-[13px] text-[#737373] leading-snug block">Veja as comandas que já entraram no forno e receba manualmente no atendimento.</small>
            </div>
          </button>

          <button id="openDispatchQueueBtn" type="button" class="relative group bg-white border border-[#E5E7EB] hover:border-emerald-500 rounded-xl p-5 shadow-sm transition-all duration-300 hover:shadow-md flex items-start gap-4 text-left overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-100 rounded-full blur-2xl opacity-40 group-hover:opacity-80 transition-opacity"></div>
            <div class="relative z-10 w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
              <i data-lucide="check-square" class="w-6 h-6"></i>
              <span id="dispatchQueueBadge" class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-emerald-500 text-white text-[11px] font-bold flex items-center justify-center border-2 border-white shadow-sm">0</span>
            </div>
            <div class="relative z-10">
              <strong class="block text-base font-bold text-[#171717] mb-1">Conferir pedidos</strong>
              <small class="text-[13px] text-[#737373] leading-snug block">Atualize bebida, troco, molhos e sinalize quando saiu para o motoboy.</small>
            </div>
          </button>
        </div>

        <article id="dispatchQueuePanel" class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_4px_12px_rgba(0,0,0,0.02)] flex flex-col overflow-hidden">
          <div class="p-5 border-b border-[#E7E7E7] bg-gray-50/30">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
              <div>
                <h3 class="text-base font-semibold text-[#171717]">Fila do atendimento</h3>
                <p class="text-xs text-[#737373] mt-0.5">Recebidas do forno, conferidas e enviadas para entrega.</p>
              </div>
              <span class="px-2.5 py-1 rounded-full text-xs font-medium border bg-indigo-50 text-indigo-700 border-indigo-200 shrink-0 self-start sm:self-center">Última etapa: saiu para entrega</span>
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

          <div class="p-5">
            <div id="dispatchGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"></div>
            
            <div id="dispatchEmpty" class="hidden p-10 text-center flex-col items-center justify-center bg-gray-50/50 rounded-xl border border-dashed border-[#E7E7E7]">
              <div class="w-12 h-12 bg-white border border-[#E7E7E7] shadow-sm rounded-full flex items-center justify-center mb-3 mx-auto">
                <i data-lucide="inbox" class="w-5 h-5 text-gray-400"></i>
              </div>
              <strong class="block text-sm text-[#171717] mb-1">Nenhuma comanda recebida</strong>
              <p class="text-xs text-[#737373]">Use "Puxar do forno" para trazer uma comanda ao atendimento.</p>
            </div>
          </div>
        </article>
      </div>
    </section>

