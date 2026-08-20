    <section id="page-reports" class="page">
      <!-- PAGE HEADER -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
          <h2 class="text-2xl font-bold text-[#171717]">Relatórios e histórico</h2>
          <p class="text-sm text-[#737373] mt-1">Ranking, presença e resultado da montagem por operação.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
          <button id="backupBtn" class="px-4 py-2 bg-[#1F6FB2] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5e98] transition-colors shadow-sm flex items-center justify-center gap-2">
            <i data-lucide="download" class="w-4 h-4"></i>
            Baixar backup
          </button>
          <button id="restoreBtn" class="px-4 py-2 bg-orange-100 text-orange-700 hover:text-orange-800 text-sm font-semibold rounded-lg hover:bg-orange-200 transition-colors shadow-sm flex items-center justify-center gap-2">
            <i data-lucide="upload" class="w-4 h-4"></i>
            Restaurar backup
          </button>
          <input id="restoreFile" class="hidden" type="file" accept=".json,application/json">
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-[300px_1fr] gap-6">
        <!-- Sidebar -->
        <article class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_4px_12px_rgba(0,0,0,0.02)] p-5 h-fit">
          <h3 class="text-base font-semibold text-[#171717]">Operações registradas</h3>
          <p class="text-xs text-[#737373] mt-0.5 mb-4">Selecione uma data.</p>
          <div id="historyList" class="flex flex-col gap-2"></div>
        </article>

        <!-- Main Content -->
        <div class="flex flex-col gap-6">
          <article class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_4px_12px_rgba(0,0,0,0.02)] p-5">
            <div id="reportOverview"></div>
          </article>
          
          <article class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_4px_12px_rgba(0,0,0,0.02)] p-5">
            <h3 class="text-base font-semibold text-[#171717]">Relatórios disponíveis</h3>
            <p class="text-xs text-[#737373] mt-0.5 mb-4">A lista de presença pode ser emitida após salvar a equipe. O resultado final fica disponível ao encerrar a cozinha.</p>
            <div id="reportCards" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
          </article>
        </div>
      </div>
    </section>
