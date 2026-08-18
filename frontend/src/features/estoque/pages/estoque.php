<section id="page-mass" class="page">
  <!-- PAGE HEADER -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
      <h2 class="text-2xl font-bold text-[#171717]">Controle de Massas</h2>
      <p class="text-sm text-[#737373] mt-1">
        Masseiros, estoque de insumos e registro de cada batida.
      </p>
    </div>
    
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto shrink-0">
      <button id="manageTeamMassBtn" class="w-full sm:w-auto px-4 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 active:scale-[0.98] transition-all duration-150 shadow-sm border border-[#E7E7E7] flex justify-center items-center gap-2" data-go="team">
        <i data-lucide="users" class="w-4 h-4"></i>
        Acionar equipe
      </button>
      <button id="openMassBatchBtn" class="w-full sm:w-auto px-4 py-2.5 bg-[#1F6FB2] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5e98] active:scale-[0.98] transition-all duration-150 shadow-[0_2px_8px_rgba(31,111,178,0.25)] border border-transparent flex justify-center items-center gap-2">
        <i data-lucide="plus-circle" class="w-4 h-4"></i>
        Nova batida
      </button>
    </div>
  </div>

  <div id="massGate"></div>

  <div id="massContent" class="hidden space-y-6">

    <!-- SUBTOTAIS (KPIs) -->
    <div id="massSubtotals" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 lg:gap-4"></div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      
      <!-- Estoque informado do dia -->
      <article class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_4px_12px_rgba(0,0,0,0.02)] p-5 flex flex-col h-full transition-all duration-200 hover:shadow-[0_2px_8px_rgb(0,0,0,0.03)]">
        <div class="mb-5">
          <h3 class="text-base font-semibold text-[#171717]">Atualizar Estoque do Mês</h3>
          <p class="text-xs text-[#737373] mt-0.5">Informe o estoque disponível. O saldo será atualizado imediatamente.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 flex-1 mb-6">
          <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-700">Farinha de trigo (kg)</label>
            <div class="relative">
              <input id="stockFlourKg" type="number" min="0" step="0.1" inputmode="decimal" class="w-full px-3 py-2 text-sm border border-[#E7E7E7] rounded-lg text-[#171717] bg-white focus:outline-none focus:border-[#B5120B] focus:ring-2 focus:ring-[#FDECEB] transition-all duration-150">
            </div>
          </div>
          <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-700">Açúcar (g)</label>
            <input id="stockSugarG" type="number" min="0" step="1" inputmode="numeric" class="w-full px-3 py-2 text-sm border border-[#E7E7E7] rounded-lg text-[#171717] bg-white focus:outline-none focus:border-[#B5120B] focus:ring-2 focus:ring-[#FDECEB] transition-all duration-150">
          </div>
          <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-700">Sal (g)</label>
            <input id="stockSaltG" type="number" min="0" step="1" inputmode="numeric" class="w-full px-3 py-2 text-sm border border-[#E7E7E7] rounded-lg text-[#171717] bg-white focus:outline-none focus:border-[#B5120B] focus:ring-2 focus:ring-[#FDECEB] transition-all duration-150">
          </div>
          <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-700">Ovos (un.)</label>
            <input id="stockEggs" type="number" min="0" step="1" inputmode="numeric" class="w-full px-3 py-2 text-sm border border-[#E7E7E7] rounded-lg text-[#171717] bg-white focus:outline-none focus:border-[#B5120B] focus:ring-2 focus:ring-[#FDECEB] transition-all duration-150">
          </div>
          <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-700">Óleo (ml)</label>
            <input id="stockOilMl" type="number" min="0" step="1" inputmode="numeric" class="w-full px-3 py-2 text-sm border border-[#E7E7E7] rounded-lg text-[#171717] bg-white focus:outline-none focus:border-[#B5120B] focus:ring-2 focus:ring-[#FDECEB] transition-all duration-150">
          </div>
          <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-700">Água (L)</label>
            <input id="stockWaterL" type="number" min="0" step="0.1" inputmode="decimal" class="w-full px-3 py-2 text-sm border border-[#E7E7E7] rounded-lg text-[#171717] bg-white focus:outline-none focus:border-[#B5120B] focus:ring-2 focus:ring-[#FDECEB] transition-all duration-150">
          </div>
          <div class="space-y-1">
            <label class="block text-xs font-medium text-gray-700">Fermento (g)</label>
            <input id="stockYeastG" type="number" min="0" step="1" inputmode="numeric" class="w-full px-3 py-2 text-sm border border-[#E7E7E7] rounded-lg text-[#171717] bg-white focus:outline-none focus:border-[#B5120B] focus:ring-2 focus:ring-[#FDECEB] transition-all duration-150">
          </div>
        </div>

        <button id="saveMassStockBtn" class="w-full px-4 py-2.5 bg-[#171717] text-white text-sm font-semibold rounded-lg hover:bg-[#333333] active:scale-[0.98] transition-all duration-150 shadow-sm flex justify-center items-center gap-2 mt-auto">
          <i data-lucide="save" class="w-4 h-4"></i>
          Salvar estoque do mês
        </button>
      </article>

      <!-- Saldo estimado -->
      <article class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_4px_12px_rgba(0,0,0,0.02)] p-5 flex flex-col h-full transition-all duration-200 hover:shadow-[0_2px_8px_rgb(0,0,0,0.03)]">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 border-b border-[#E7E7E7] pb-4">
          <div>
            <h3 class="text-base font-semibold text-[#171717]">Estoque Atual</h3>
            <p class="text-xs text-[#737373] mt-0.5">Saldo disponível para uso.</p>
          </div>
          <span id="massStockStatus" class="px-2.5 py-1 rounded-full text-xs font-medium border bg-gray-50 text-gray-600 border-gray-200 shrink-0 self-start sm:self-center">Aguardando estoque</span>
        </div>
        <div id="massBalanceGrid" class="grid grid-cols-2 sm:grid-cols-3 gap-3 flex-1 content-start"></div>
      </article>

    </div>

    <!-- Histórico de batidas -->
    <article class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_4px_12px_rgba(0,0,0,0.02)] p-5">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 pb-4 border-b border-[#E7E7E7]">
        <div>
          <h3 class="text-base font-semibold text-[#171717]">Histórico de batidas</h3>
          <p class="text-xs text-[#737373] mt-0.5">Cada batida registra masseiro, horário e consumo real de material.</p>
        </div>
        <span id="massRecipeChip" class="px-2.5 py-1 rounded-full text-xs font-medium border bg-indigo-50 text-indigo-700 border-indigo-200 shrink-0 self-start sm:self-center">Receita padrão: 10 kg de farinha</span>
      </div>
      <div id="massBatchHistory" class="space-y-3"></div>
    </article>

  </div>
</section>
