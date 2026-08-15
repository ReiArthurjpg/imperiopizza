<section id="page-team" class="page">

  <!-- ═══════════════════════════════════════════════════
       PAGE HEADER
  ════════════════════════════════════════════════════ -->
  <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
      <h2 class="text-2xl font-bold text-[#171717]">Equipe do Dia</h2>
      <p class="text-sm text-[#737373] mt-1">
        Cadastre profissionais, marque a presença e inicie a operação.
      </p>
    </div>
    <!-- Badge de status da operação (atualizado pelo JS) -->
    <div id="teamHeaderBadge" class="flex items-center gap-2 shrink-0 mt-1"></div>
  </div>


  <!-- ═══════════════════════════════════════════════════
       BANNER DE STATUS DA OPERAÇÃO (populado pelo JS)
  ════════════════════════════════════════════════════ -->
  <div id="teamOperationNotice" class="mb-6"></div>

  <!-- ═══════════════════════════════════════════════════
       LAYOUT PRINCIPAL: 2 colunas
       Esquerda (sidebar): Cadastro + Lista de profissionais
       Direita (main): Lista de presença + Fluxo do dia
  ════════════════════════════════════════════════════ -->
  <div class="grid grid-cols-1 lg:grid-cols-[380px_1fr] gap-6 items-start">

    <!-- ─────────────────────────────────────────────
         COLUNA ESQUERDA
    ──────────────────────────────────────────────── -->
    <div class="space-y-4">

      <!-- ── Formulário de cadastro ── -->
      <div class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_4px_12px_rgba(0,0,0,0.02)] p-5">
        <div class="flex items-center justify-between pb-4 border-b border-[#E7E7E7] mb-5">
          <div>
            <h3 class="text-base font-semibold text-[#171717]">Cadastrar profissional</h3>
            <p class="text-xs text-[#737373] mt-0.5">Disponível para os próximos dias.</p>
          </div>
          <div class="p-2 rounded-lg bg-[#FDECEB] text-[#B5120B] shrink-0">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
          </div>
        </div>

        <form id="personForm" class="space-y-3">
          <!-- Nome -->
          <div>
            <label for="personName" class="block text-xs font-semibold text-[#173F69] mb-1.5">
              Nome
            </label>
            <input
              id="personName"
              type="text"
              maxlength="80"
              placeholder="Nome do colaborador"
              required
              class="w-full border border-[#E7E7E7] rounded-lg px-3 py-2.5 text-sm
                     text-[#171717] placeholder-gray-400
                     focus:outline-none focus:border-[#B5120B] focus:ring-2 focus:ring-[#FDECEB]
                     transition-all duration-150"
            >
          </div>

          <!-- Setor -->
          <div>
            <label for="personRole" class="block text-xs font-semibold text-[#173F69] mb-1.5">
              Setor
            </label>
            <select
              id="personRole"
              required
              class="w-full border border-[#E7E7E7] rounded-lg px-3 py-2.5 text-sm
                     text-[#171717] bg-white
                     focus:outline-none focus:border-[#B5120B] focus:ring-2 focus:ring-[#FDECEB]
                     transition-all duration-150"
            >
              <option value="">Selecione</option>
              <option>Montagem</option>
              <option>Massa</option>
              <option>Cozinha</option>
              <option>Forno</option>
              <option>Despacho</option>
              <option>Atendimento</option>
              <option>Estoque</option>
              <option>Liderança</option>
              <option>Outros</option>
            </select>
          </div>

          <button
            type="submit"
            class="w-full py-2.5 bg-[#B5120B] text-white text-sm font-semibold rounded-lg
                   hover:bg-[#9a0f09] active:scale-[0.98] transition-all duration-150 shadow-sm
                   flex items-center justify-center gap-2"
          >
            <i data-lucide="plus" class="w-4 h-4"></i>
            Cadastrar
          </button>
        </form>
      </div>

      <!-- ── Lista de profissionais cadastrados ── -->
      <div class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_4px_12px_rgba(0,0,0,0.02)] p-5">
        <div class="flex items-center justify-between pb-3 border-b border-[#E7E7E7] mb-4">
          <h3 class="text-base font-semibold text-[#171717]">Profissionais cadastrados</h3>
          <span id="teamPeopleCount"
                class="px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-[#737373]">
            0
          </span>
        </div>

        <!-- Busca por nome -->
        <div class="relative mb-2">
          <i data-lucide="search"
             class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
          <input
            id="teamPersonSearch"
            type="text"
            placeholder="Buscar por nome..."
            class="w-full pl-9 pr-3 py-2 text-sm border border-[#E7E7E7] rounded-lg
                   text-[#171717] placeholder-gray-400
                   focus:outline-none focus:border-[#B5120B] focus:ring-2 focus:ring-[#FDECEB]
                   transition-all duration-150"
          >
        </div>

        <!-- Filtro por setor -->
        <div class="mb-4">
          <select
            id="teamSectorFilter"
            class="w-full px-3 py-2 text-sm border border-[#E7E7E7] rounded-lg
                   text-[#737373] bg-white
                   focus:outline-none focus:border-[#B5120B] focus:ring-2 focus:ring-[#FDECEB]
                   transition-all duration-150"
          >
            <option value="">Todos os setores</option>
            <option>Montagem</option>
            <option>Massa</option>
            <option>Cozinha</option>
            <option>Forno</option>
            <option>Despacho</option>
            <option>Atendimento</option>
            <option>Estoque</option>
            <option>Liderança</option>
            <option>Outros</option>
          </select>
        </div>

        <!-- Lista (renderizada pelo JS) -->
        <div id="peopleChecklist" class="space-y-2 max-h-[340px] overflow-y-auto -mr-1 pr-1
                                          custom-scrollbar">
        </div>
      </div>

    </div><!-- /coluna esquerda -->

    <!-- ─────────────────────────────────────────────
         COLUNA DIREITA
    ──────────────────────────────────────────────── -->
    <div class="space-y-4">

      <!-- ── Lista de presença da operação ── -->
      <div class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_4px_12px_rgba(0,0,0,0.02)] p-5">
        <div class="flex items-center justify-between pb-4 border-b border-[#E7E7E7] mb-5">
          <div>
            <h3 class="text-base font-semibold text-[#171717]">Lista de Presença</h3>
            <p class="text-xs text-[#737373] mt-0.5">Será usada no dashboard e nos relatórios.</p>
          </div>
          <span id="teamSelectedBadge"
                class="px-2.5 py-0.5 rounded-full text-xs font-semibold border
                       bg-gray-100 text-[#737373] border-gray-200 transition-all">
            0 selecionados
          </span>
        </div>

        <!-- Conteúdo da lista de presença (renderizado pelo JS) -->
        <div id="dayTeamGroups" class="min-h-[80px]"></div>

        <!-- Ações principais -->
        <div class="flex flex-col sm:flex-row gap-3 mt-5 pt-4 border-t border-[#E7E7E7]">
          <button
            id="saveTeamBtn"
            class="flex-1 py-2.5 text-sm font-semibold border border-[#E7E7E7] text-[#171717]
                   rounded-lg hover:bg-gray-50 transition-colors duration-150
                   disabled:opacity-40 disabled:cursor-not-allowed
                   flex items-center justify-center gap-2"
          >
            <i data-lucide="save" class="w-4 h-4"></i>
            Salvar equipe
          </button>
          <button
            id="startOperationBtn"
            class="flex-1 py-2.5 text-sm font-semibold text-white bg-[#B5120B]
                   rounded-lg hover:bg-[#9a0f09] shadow-sm transition-all duration-150
                   disabled:opacity-40 disabled:cursor-not-allowed
                   flex items-center justify-center gap-2"
          >
            <i data-lucide="play" class="w-4 h-4"></i>
            Iniciar operação
          </button>
        </div>
      </div>


    </div><!-- /coluna direita -->

  </div><!-- /grid principal -->

</section>
