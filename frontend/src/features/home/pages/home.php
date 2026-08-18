<section id="page-dashboard" class="page active">
  <!-- Alertas Ativos -->
  <div id="alertsContainer" class="flex flex-col gap-2 mb-4">
    <!-- Será preenchido dinamicamente pelo JS -->
  </div>

  <!-- Status Banner (dashboardBanner) -->
  <div id="dashboardBanner" class="mb-6">
    <!-- Será preenchido dinamicamente pelo JS (StatusBanner) -->
  </div>

  <!-- Unified KPI & Period Section -->
  <div class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_4px_12px_rgba(0,0,0,0.02)] p-5 mb-6">
    <!-- Top header of the section (Period selector) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-[#E7E7E7] mb-5">
      <div>
        <h3 class="text-base font-semibold text-[#173F69]">Período de Análise</h3>
        <p class="text-xs text-gray-400 mt-0.5">Visão consolidada do mês selecionado no cabeçalho.</p>
      </div>
    </div>

    <!-- KPI Grid (2 colunas) inside the same section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
      <!-- Card Comandas -->
      <div class="relative overflow-hidden bg-gray-50/50 p-5 rounded-xl border border-[#E7E7E7] transition-all duration-200 hover:shadow-[0_2px_8px_rgb(0,0,0,0.03)] border-l-4 border-l-[#B5120B]">
        <div class="flex justify-between items-start mb-4">
          <h3 class="text-sm font-semibold text-[#B5120B]">Comandas</h3>
          <div class="p-2 rounded-lg bg-[#FDECEB] text-[#B5120B]">
            <i data-lucide="receipt" class="w-[18px] h-[18px]"></i>
          </div>
        </div>
        <div class="flex items-baseline gap-2">
          <span id="dashCommands" class="text-3xl font-bold tracking-tight text-[#171717]">0</span>
        </div>
        <p class="mt-2 text-xs font-medium text-[#737373]">Total de pedidos</p>
      </div>

      <!-- Card Pizzas feitas -->
      <div class="relative overflow-hidden bg-gray-50/50 p-5 rounded-xl border border-[#E7E7E7] transition-all duration-200 hover:shadow-[0_2px_8px_rgb(0,0,0,0.03)] border-l-4 border-l-emerald-600">
        <div class="flex justify-between items-start mb-4">
          <h3 class="text-sm font-semibold text-emerald-700">Pizzas feitas</h3>
          <div class="p-2 rounded-lg bg-emerald-50 text-emerald-700">
            <i data-lucide="chef-hat" class="w-[18px] h-[18px]"></i>
          </div>
        </div>
        <div class="flex items-baseline gap-2">
          <span id="dashPizzas" class="text-3xl font-bold tracking-tight text-[#171717]">0</span>
        </div>
        <p class="mt-2 text-xs font-medium text-[#737373]">Pizzas finalizadas</p>
      </div>
    </div>
  </div>

  <!-- Complex Grids -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column (takes 2/3 space on large screens) -->
    <div class="lg:col-span-2 space-y-6">
      
      <!-- Produção em Tempo Real -->
      <div class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-5">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-base font-semibold text-[#171717]">Produção em Tempo Real</h2>
          <span id="dashboardPipelineBadge" class="px-2.5 py-0.5 rounded-full text-xs font-medium border bg-[#FDECEB] text-[#B5120B] border-[#FCA5A5]">Fluxo Atual</span>
        </div>
        
        <div id="pipelineContainer" class="flex items-center gap-3 overflow-x-auto pb-4 snap-x snap-mandatory" style="scrollbar-width: none; -ms-overflow-style: none;">
          <!-- Será populado pelo JS para mostrar o fluxo real -->
        </div>
        <style>
          #pipelineContainer::-webkit-scrollbar {
            display: none;
          }
        </style>
      </div>

      <!-- Movimento Recente -->
      <div class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-5">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-base font-semibold text-[#171717]">Movimento Recente</h2>
          <button class="text-xs font-medium text-[#B5120B] px-3 py-1.5 rounded-lg transition-colors hover:bg-[#FDECEB]" onclick="showPage('production')">Ver tudo</button>
        </div>
        <div id="dashboardLive" class="relative pl-2">
          <!-- Populado pelo JS (Timeline) -->
        </div>
      </div>
    </div>

    <!-- Right Column (takes 1/3 space) -->
    <div class="space-y-6">
      <!-- Top Montadores -->
      <div class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-5">
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-base font-semibold text-[#171717] flex items-center gap-2">
            Top Montadores
          </h2>
          <span class="text-xs text-gray-400 font-medium">Este Mês</span>
        </div>
        <div id="dashboardRankMensal">
          <!-- Populado pelo JS (Ranking Mensal Automático) -->
        </div>
      </div>

      <!-- Equipe da Operação -->
      <div class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-5">
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-base font-semibold text-[#171717]">Equipe da Operação</h2>
          <span id="dashboardTeamOnlineBadge" class="px-2.5 py-0.5 rounded-full text-xs font-medium border bg-[#E7F8F0] text-[#10B981] border-[#A7F3D0]">0 Online</span>
        </div>
        <div id="dashboardTeam">
          <!-- Populado pelo JS (Equipe) -->
        </div>
      </div>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/../../equipe/pages/equipe.php'; ?>
<?php require_once __DIR__ . '/../../cozinha/pages/cozinha.php'; ?>
<?php require_once __DIR__ . '/../../estoque/pages/estoque.php'; ?>
<?php require_once __DIR__ . '/../../pdv/pages/pdv.php'; ?>
<?php require_once __DIR__ . '/../../relatorios/pages/relatorios.php'; ?>
