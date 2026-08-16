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
    <!-- Badge de status da operação (atualizado pelo JS) e botão de cadastrar -->
    <div class="flex items-center gap-3 shrink-0 mt-1">
      <div id="teamHeaderBadge" class="flex items-center gap-2"></div>
      <button
        id="openAddPersonModalBtn"
        type="button"
        class="px-4 py-3 bg-[#1F6FB2] text-white text-xs font-semibold rounded-lg
               hover:bg-[#1a5e98] active:scale-[0.98] transition-all duration-150 shadow-sm
               flex items-center gap-1.5"
      >
        <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
        Adicionar Colaborador
      </button>
    </div>
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
  <div class="grid grid-cols-1 lg:grid-cols-[380px_1fr] gap-6 items-stretch">

    <!-- ─────────────────────────────────────────────
         COLUNA ESQUERDA
    ──────────────────────────────────────────────── -->
    <div class="space-y-4">

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
    <div class="h-full">

      <!-- ── Lista de presença da operação ── -->
      <div class="bg-white rounded-xl border border-[#E7E7E7] shadow-[0_4px_12px_rgba(0,0,0,0.02)] p-5 h-full flex flex-col">
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
        <div id="dayTeamGroups" class="flex-1 max-h-[310px] min-h-[80px] overflow-y-auto pr-2 custom-scrollbar"></div>

        <!-- Ações principais -->
        <div class="flex flex-col sm:flex-row gap-3 mt-auto pt-4 border-t border-[#E7E7E7]">
          <button
            id="saveTeamBtn"
            class="flex-1 py-2.5 text-sm font-semibold text-white bg-[#2f9e64]
                   rounded-lg hover:bg-[#257f4f] transition-all duration-150 shadow-sm
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
  <!-- ═══════════════════════════════════════════════════
       MODAL: ADICIONAR COLABORADOR (Design System Moderno)
  ════════════════════════════════════════════════════ -->
  <div id="addPersonModal" class="modal-bg">
    <!-- Caixa do modal (dialog) -->
    <div class="bg-white rounded-2xl border border-[#E7E7E7] shadow-[0_8px_32px_rgba(0,0,0,0.08)] w-[95%] max-w-[500px] overflow-hidden transform transition-all duration-200">
      
      <!-- Cabeçalho -->
      <div class="flex items-center justify-between p-5 border-b border-[#E7E7E7] bg-gray-50/50">
        <div>
          <h3 class="text-lg font-bold text-[#171717]">Adicionar Colaborador</h3>
          <p class="text-xs text-[#737373] mt-0.5">Cadastre um profissional na base do sistema.</p>
        </div>
        <button 
          class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"
          type="button" 
          data-close="addPerson"
        >
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      </div>

      <!-- Corpo -->
      <div class="p-5">
        <form id="personForm" class="space-y-4">
          <!-- Nome -->
          <div>
            <label for="personName" class="block text-xs font-semibold text-[#173F69] mb-1.5">
              Nome Completo
            </label>
            <input
              id="personName"
              type="text"
              maxlength="80"
              placeholder="Ex: João Silva"
              required
              class="w-full border border-[#E7E7E7] rounded-lg px-3 py-2.5 text-sm
                     text-[#171717] placeholder-gray-400
                     focus:outline-none focus:border-[#1F6FB2] focus:ring-2 focus:ring-[#eaf5ff]
                     transition-all duration-150"
            >
          </div>

          <!-- Setor -->
          <div>
            <label for="personRole" class="block text-xs font-semibold text-[#173F69] mb-1.5">
              Setor de Atuação
            </label>
            <select
              id="personRole"
              required
              class="w-full border border-[#E7E7E7] rounded-lg px-3 py-2.5 text-sm
                     text-[#171717] bg-white
                     focus:outline-none focus:border-[#1F6FB2] focus:ring-2 focus:ring-[#eaf5ff]
                     transition-all duration-150"
            >
              <option value="">Selecione o setor...</option>
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

          <!-- Ações (Rodapé) -->
          <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#E7E7E7] mt-6">
            <button 
              type="button" 
              class="px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-transparent rounded-lg
                     hover:bg-gray-200 active:scale-[0.98] transition-all duration-150
                     flex items-center gap-1.5" 
              data-close="addPerson"
            >
              <i data-lucide="x-circle" class="w-4 h-4"></i>
              Cancelar
            </button>
            <button 
              type="submit" 
              class="px-6 py-2.5 text-sm font-semibold text-white bg-[#2f9e64] rounded-lg
                     hover:bg-[#257f4f] active:scale-[0.98] transition-all duration-150 shadow-sm
                     flex items-center gap-1.5"
            >
              <i data-lucide="save" class="w-4 h-4"></i>
              Salvar
            </button>
          </div>
        </form>
      </div>

    </div>
  <!-- ═══════════════════════════════════════════════════
       MODAL: EDITAR COLABORADOR (Design System Moderno)
  ════════════════════════════════════════════════════ -->
  <div id="editPersonModal" class="modal-bg">
    <!-- Caixa do modal (dialog) -->
    <div class="bg-white rounded-2xl border border-[#E7E7E7] shadow-[0_8px_32px_rgba(0,0,0,0.08)] w-[95%] max-w-[500px] overflow-hidden transform transition-all duration-200">
      
      <!-- Cabeçalho -->
      <div class="flex items-center justify-between p-5 border-b border-[#E7E7E7] bg-gray-50/50">
        <div>
          <h3 class="text-lg font-bold text-[#171717]">Editar Colaborador</h3>
          <p class="text-xs text-[#737373] mt-0.5">Altere os dados do profissional na base do sistema.</p>
        </div>
        <button 
          class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"
          type="button" 
          data-close="editPerson"
        >
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      </div>

      <!-- Corpo -->
      <div class="p-5">
        <form id="editPersonForm" class="space-y-4">
          <input type="hidden" id="editPersonId">
          <!-- Nome -->
          <div>
            <label for="editPersonName" class="block text-xs font-semibold text-[#173F69] mb-1.5">
              Nome Completo
            </label>
            <input
              id="editPersonName"
              type="text"
              maxlength="80"
              placeholder="Ex: João Silva"
              required
              class="w-full border border-[#E7E7E7] rounded-lg px-3 py-2.5 text-sm
                     text-[#171717] placeholder-gray-400
                     focus:outline-none focus:border-[#1F6FB2] focus:ring-2 focus:ring-[#eaf5ff]
                     transition-all duration-150"
            >
          </div>

          <!-- Setor -->
          <div>
            <label for="editPersonRole" class="block text-xs font-semibold text-[#173F69] mb-1.5">
              Setor de Atuação
            </label>
            <select
              id="editPersonRole"
              required
              class="w-full border border-[#E7E7E7] rounded-lg px-3 py-2.5 text-sm
                     text-[#171717] bg-white
                     focus:outline-none focus:border-[#1F6FB2] focus:ring-2 focus:ring-[#eaf5ff]
                     transition-all duration-150"
            >
              <option value="">Selecione o setor...</option>
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

          <!-- Ações (Rodapé) -->
          <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#E7E7E7] mt-6">
            <button 
              type="button" 
              class="px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-transparent rounded-lg
                     hover:bg-gray-200 active:scale-[0.98] transition-all duration-150
                     flex items-center gap-1.5" 
              data-close="editPerson"
            >
              <i data-lucide="x-circle" class="w-4 h-4"></i>
              Cancelar
            </button>
            <button 
              type="submit" 
              class="px-6 py-2.5 text-sm font-semibold text-white bg-[#2f9e64] rounded-lg
                     hover:bg-[#257f4f] active:scale-[0.98] transition-all duration-150 shadow-sm
                     flex items-center gap-1.5"
            >
              <i data-lucide="save" class="w-4 h-4"></i>
              Salvar Alterações
            </button>
          </div>
        </form>
      </div>

  </div>

  <!-- ═══════════════════════════════════════════════════
       MODAL: CONFIRMAR EXCLUSÃO DE COLABORADOR (Design System Moderno)
  ════════════════════════════════════════════════════ -->
  <div id="confirmDeleteModal" class="modal-bg">
    <!-- Caixa do modal (dialog) -->
    <div class="bg-white rounded-2xl border border-[#E7E7E7] shadow-[0_8px_32px_rgba(0,0,0,0.08)] w-[95%] max-w-[500px] overflow-hidden transform transition-all duration-200">
      
      <!-- Cabeçalho -->
      <div class="flex items-center justify-between p-5 border-b border-[#E7E7E7] bg-red-50/50">
        <div class="flex items-center gap-2">
          <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
          <div>
            <h3 class="text-lg font-bold text-red-700">Confirmar Exclusão</h3>
            <p class="text-xs text-[#737373] mt-0.5">Esta ação é irreversível.</p>
          </div>
        </div>
        <button 
          class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"
          type="button" 
          data-close="confirmDelete"
          id="closeConfirmDeleteBtn"
        >
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      </div>

      <!-- Corpo -->
      <div class="p-5">
        <p class="text-sm text-[#171717] mb-4">
          Você está prestes a excluir o profissional <strong id="deletePersonNameLabel"></strong>.
        </p>
        
        <div class="p-4 bg-red-50 rounded-xl border border-red-100 text-red-800 text-xs space-y-2 mb-6">
          <p class="font-semibold flex items-center gap-1.5 text-red-950">
            <i data-lucide="info" class="w-4 h-4 shrink-0"></i>
            Atenção: Todos os registros vinculados serão apagados!
          </p>
          <p class="leading-relaxed text-red-900">
            Ao confirmar a exclusão, <strong>todos os registros de operação desta pessoa serão excluídos permanentemente do banco de dados</strong>. Isso inclui presenças, lotes de massas produzidas e comandas montadas vinculadas a ela.
          </p>
        </div>

        <!-- Ações (Rodapé) -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#E7E7E7]">
          <button 
            type="button" 
            class="px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-transparent rounded-lg
                   hover:bg-gray-200 active:scale-[0.98] transition-all duration-150
                   flex items-center gap-1.5" 
            data-close="confirmDelete"
            id="cancelDeleteBtn"
          >
            <i data-lucide="x-circle" class="w-4 h-4"></i>
            Cancelar
          </button>
          <button 
            type="button" 
            id="confirmDeletePersonBtn"
            class="px-6 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-lg
                   hover:bg-red-700 active:scale-[0.98] transition-all duration-150 shadow-sm
                   flex items-center gap-1.5"
          >
            <i data-lucide="trash-2" class="w-4 h-4"></i>
            Excluir Tudo e Confirmar
          </button>
        </div>
      </div>

    </div>
  </div>

</section>
