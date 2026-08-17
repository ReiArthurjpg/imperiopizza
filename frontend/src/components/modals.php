  <!-- REGISTER COMMAND MODAL — Premium Redesign -->
  <div id="registerCommandModal" class="modal-bg">
    <div class="modal" style="max-width:540px; border-radius:20px; padding:0; overflow:hidden;">

      <!-- Header -->
      <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div>
          <h3 class="text-[15px] font-bold text-gray-900 leading-none">Registrar nova comanda</h3>
          <p class="text-[12px] text-gray-400 mt-0.5">A janela fica aberta para registros em sequência.</p>
        </div>
        <button type="button" data-close-v4="registerCommand"
          class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <!-- Body -->
      <div class="register-modal-body px-6 py-5 space-y-5 overflow-y-auto" style="max-height:70vh;">

        <div class="space-y-1.5">
          <label class="text-[12px] font-semibold text-gray-500 uppercase tracking-wider">Montador responsável</label>
          <div class="relative">
            <select id="assemblerId" class="w-full appearance-none px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 focus:bg-white focus:border-[#1F6FB2] focus:ring-2 focus:ring-[#1F6FB2]/20 transition-all text-[14px] font-semibold text-gray-700 outline-none cursor-pointer">
              <option value="">Selecione o montador</option>
            </select>
            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </div>
          </div>
          <div id="assemblerSuggestions" class="hidden"></div>
        </div>

        <!-- Number + Qty -->
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-1.5">
            <label for="commandNumber" class="text-[12px] font-semibold text-gray-500 uppercase tracking-wider">Nº da comanda</label>
            <input id="commandNumber" type="number" min="1" max="1000" inputmode="numeric" placeholder="Ex.: 7"
              class="w-full px-4 py-3 text-[15px] font-bold text-gray-900 bg-gray-50 border border-gray-200 rounded-xl placeholder-gray-300 focus:outline-none focus:border-[#1F6FB2] focus:ring-2 focus:ring-blue-100 transition-all">
          </div>
          <div class="space-y-1.5">
            <label class="text-[12px] font-semibold text-gray-500 uppercase tracking-wider">Qtd. de pizzas</label>
            <div class="flex items-center gap-2">
              <button type="button" data-qty="minus"
                class="w-10 h-10 flex items-center justify-center bg-gray-100 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-200 transition-colors font-bold text-lg shrink-0">−</button>
              <input id="pizzaQty" type="number" min="0" max="50" value="1" inputmode="numeric"
                class="flex-1 px-2 py-3 text-[15px] font-bold text-gray-900 bg-gray-50 border border-gray-200 rounded-xl text-center focus:outline-none focus:border-[#1F6FB2] focus:ring-2 focus:ring-blue-100 transition-all min-w-0">
              <button type="button" data-qty="plus"
                class="w-10 h-10 flex items-center justify-center bg-gray-100 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-200 transition-colors font-bold text-lg shrink-0">+</button>
            </div>
          </div>
        </div>

        <!-- Command Suggestions -->
        <div id="commandSuggestions" class="suggest-box"></div>

        <!-- Note -->
        <div class="space-y-1.5">
          <label for="commandNote" class="text-[12px] font-semibold text-gray-500 uppercase tracking-wider">Observação <span class="font-normal normal-case text-gray-400">(opcional)</span></label>
          <textarea id="commandNote" maxlength="220" placeholder="Ex.: sem cebola, prioridade, pizza dividida..."
            class="w-full px-4 py-3 text-[13px] text-gray-700 bg-gray-50 border border-gray-200 rounded-xl placeholder-gray-300 focus:outline-none focus:border-[#1F6FB2] focus:ring-2 focus:ring-blue-100 transition-all resize-none"
            rows="2"></textarea>
        </div>

        <!-- Initial Oven Toggle -->
        <label class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50 cursor-pointer hover:bg-orange-50/50 hover:border-orange-200 transition-all group has-[:checked]:bg-orange-50 has-[:checked]:border-orange-200">
          <input id="initialOven" type="checkbox" class="hidden">
          <div class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 rounded-[10px] shadow-sm group-hover:border-orange-300 group-has-[:checked]:border-orange-400 group-has-[:checked]:bg-orange-50 transition-colors">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-500 group-has-[:checked]:text-orange-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 7 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>
            </svg>
          </div>
          <div>
            <p class="text-[13px] font-semibold text-gray-700 group-has-[:checked]:text-orange-700 transition-colors leading-none">A comanda já entrou no forno</p>
            <p class="text-[11px] text-gray-400 mt-0.5">Marque se a pizza já está assando</p>
          </div>
        </label>

        <!-- Special Products -->
        <div class="space-y-3">
          <div class="flex items-center justify-between">
            <div>
              <h4 class="text-[13px] font-bold text-gray-800">Produtos especiais</h4>
              <p class="text-[11px] text-gray-400 mt-0.5">Equivalência aplicada automaticamente ao ranking</p>
            </div>
            <span id="equivalentPreview" class="inline-flex items-center px-2.5 py-1 rounded-lg text-[12px] font-bold bg-blue-50 text-[#1F6FB2] border border-blue-100">1,0 equiv.</span>
          </div>

          <div class="grid grid-cols-1 gap-2.5">
            <!-- Volcano -->
            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-200 bg-gray-50 cursor-pointer hover:border-orange-200 hover:bg-orange-50/30 transition-all group has-[:checked]:border-orange-300 has-[:checked]:bg-orange-50">
              <input id="volcanoCheck" type="checkbox" class="hidden">
              <div class="w-8 h-8 flex items-center justify-center rounded-[8px] border border-gray-200 bg-white group-has-[:checked]:bg-orange-100 group-has-[:checked]:border-orange-300 transition-colors shrink-0">
                <svg class="w-4 h-4 text-gray-400 group-has-[:checked]:text-orange-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-[13px] font-semibold text-gray-700 group-has-[:checked]:text-orange-700 transition-colors leading-none">Pizza vulcão</p>
                <p id="volcanoRuleText" class="text-[11px] text-gray-400 mt-0.5">Cada unidade vale 2 pizzas</p>
              </div>
              <input id="volcanoQty" class="w-14 px-2 py-1.5 text-[13px] font-bold text-center bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition-all" type="number" min="0" max="50" value="1" inputmode="numeric" disabled>
            </label>

            <!-- Esfiha -->
            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-200 bg-gray-50 cursor-pointer hover:border-amber-200 hover:bg-amber-50/30 transition-all group has-[:checked]:border-amber-300 has-[:checked]:bg-amber-50">
              <input id="esfihaCheck" type="checkbox" class="hidden">
              <div class="w-8 h-8 flex items-center justify-center rounded-[8px] border border-gray-200 bg-white group-has-[:checked]:bg-amber-100 group-has-[:checked]:border-amber-300 transition-colors shrink-0">
                <svg class="w-4 h-4 text-gray-400 group-has-[:checked]:text-amber-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-[13px] font-semibold text-gray-700 group-has-[:checked]:text-amber-700 transition-colors leading-none">Esfirras</p>
                <p id="esfihaRuleText" class="text-[11px] text-gray-400 mt-0.5">5 esfirras = 2 pizzas</p>
              </div>
              <input id="esfihaQty" class="w-14 px-2 py-1.5 text-[13px] font-bold text-center bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition-all" type="number" min="0" max="200" value="5" inputmode="numeric" disabled>
            </label>

            <!-- Sweet -->
            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-200 bg-gray-50 cursor-pointer hover:border-pink-200 hover:bg-pink-50/30 transition-all group has-[:checked]:border-pink-300 has-[:checked]:bg-pink-50">
              <input id="sweetCheck" type="checkbox" class="hidden">
              <div class="w-8 h-8 flex items-center justify-center rounded-[8px] border border-gray-200 bg-white group-has-[:checked]:bg-pink-100 group-has-[:checked]:border-pink-300 transition-colors shrink-0">
                <svg class="w-4 h-4 text-gray-400 group-has-[:checked]:text-pink-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-[13px] font-semibold text-gray-700 group-has-[:checked]:text-pink-700 transition-colors leading-none">Pizza doce</p>
                <p class="text-[11px] text-gray-400 mt-0.5">Mapeia a produção de pizzas doces</p>
              </div>
              <input id="sweetQty" class="w-14 px-2 py-1.5 text-[13px] font-bold text-center bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-100 transition-all" type="number" min="0" max="50" value="1" inputmode="numeric" disabled>
            </label>
          </div>
        </div>

      </div>

      <!-- Footer Actions -->
      <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50/80 border-t border-gray-100">
        <button type="button" data-close="registerCommand"
          class="inline-flex items-center px-4 py-2.5 text-[13px] font-semibold text-gray-700 bg-gray-100 border border-transparent rounded-xl hover:bg-gray-200 transition-colors">
          <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" stroke-width="2"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9l-6 6M9 9l6 6"/>
          </svg>
          Cancelar
        </button>
        <button id="addCommandBtn" type="button"
          class="inline-flex items-center px-5 py-2.5 text-[13px] font-bold text-white bg-[#2f9e64] rounded-xl hover:bg-[#248150] active:scale-[0.98] transition-all shadow-sm">
          <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-8H7v8M7 3v5h8"/>
          </svg>
          Salvar comanda
        </button>
      </div>
    </div>
  </div>

  <div id="settingsModal" class="modal-bg">
    <div class="modal settings-modal">
      <div class="modal-head">
        <div>
          <h3>Configurações de comandas</h3>
          <p class="sub" style="margin:4px 0 0">Regras usadas na produção e no ranking.</p>
        </div>
        <button class="close" type="button" data-close-v4="settings">×</button>
      </div>

      <div class="settings-grid">
        <div class="field"><label for="settingCommandMax">Numeração máxima da comanda</label><input
            id="settingCommandMax" type="number" min="100" max="5000"></div>
        <div class="field"><label for="settingDefaultPizzaQty">Quantidade padrão de pizzas</label><input
            id="settingDefaultPizzaQty" type="number" min="0" max="20"></div>
        <div class="field"><label for="settingVolcanoEq">1 pizza vulcão equivale a</label>
          <div class="unit-input"><input id="settingVolcanoEq" type="number" min="1" max="10"
              step="0.1"><span>pizzas</span></div>
        </div>
        <div class="field"><label>Regra de esfirras</label>
          <div class="settings-ratio"><input id="settingEsfihaGroup" type="number" min="1" max="50"><span>esfirras
              =</span><input id="settingEsfihaEq" type="number" min="0.1" max="20" step="0.1"><span>pizzas</span></div>
        </div>
        <div class="field"><label for="settingRecentCommands">Últimas comandas na janela</label><input
            id="settingRecentCommands" type="number" min="3" max="15"></div>
      </div>

      <div class="settings-note">
        <strong>Como a equivalência funciona:</strong>
        pizzas físicas entram normalmente; a vulcão recebe o adicional configurado; as esfirras são convertidas pela
        proporção definida. Pizza doce é apenas mapeada e não altera a equivalência.
      </div>

      <div class="actions end mobile-stack">
        <button class="btn btn-ghost" type="button" data-close-v4="settings">Cancelar</button>
        <button id="saveSettingsBtn" class="btn btn-primary" type="button">Salvar configurações</button>
      </div>
    </div>
  </div>

  <div id="dispatchCommandModal" class="modal-bg register-command-modal">
    <div class="modal">
      <div class="register-modal-head">
        <div>
          <h3 id="dispatchModalTitle">Puxar comanda do forno</h3>
          <p id="dispatchModalSubtitle">Selecione uma comanda que já entrou no forno.</p>
        </div>
        <button class="close" type="button" data-close-v4="dispatchCommand">×</button>
      </div>

      <div class="register-modal-body">
        <input id="dispatchCommandId" type="hidden">

        <div id="ovenCommandPickerBlock">
          <div class="field"><label for="ovenCommandSearch">Comandas disponíveis no forno</label><input
              id="ovenCommandSearch" type="text" inputmode="numeric" placeholder="Buscar número ou montador"></div>
          <div id="ovenCommandList" class="oven-command-list"></div>
        </div>

        <div id="dispatchSelectedSummary" class="dispatch-selected-summary"></div>

        <div class="dispatch-options dispatch-modal-options">
          <label class="option"><input id="dispatchBeverage" type="checkbox"> Tem bebida</label>
          <label class="option"><input id="dispatchChange" type="checkbox"> Precisa de troco</label>
          <label class="option"><input id="dispatchKetchup" type="checkbox"> Ketchup</label>
          <label class="option"><input id="dispatchMayonnaise" type="checkbox"> Maionese</label>
        </div>

        <div class="inline">
          <div class="field"><label for="dispatchChangeAmount">Troco</label><input id="dispatchChangeAmount" type="text"
              placeholder="Ex.: troco para R$ 100" disabled></div>
          <div class="field"><label for="dispatchNote">Observação do atendimento</label><input id="dispatchNote"
              type="text" maxlength="180" placeholder="Opcional"></div>
        </div>
      </div>

      <div class="register-modal-actions dispatch-modal-actions">
        <button id="receiveDispatchBtn" type="button" class="btn btn-primary">Receber no atendimento</button>
        <button id="checkDispatchBtn" type="button" class="btn btn-soft hidden">Marcar conferido</button>
        <button id="outForDeliveryBtn" type="button" class="btn btn-green hidden">Saiu para entrega</button>
      </div>
    </div>
  </div>

  <div id="massBatchModal" class="modal-bg register-command-modal">
    <div class="modal">
      <div class="register-modal-head">
        <div>
          <h3>Registrar batida de massa</h3>
          <p>Os campos já vêm preenchidos com a receita padrão e podem ser ajustados para o consumo real.</p>
        </div>
        <button class="close" type="button" data-close-v4="massBatch">×</button>
      </div>

      <div class="register-modal-body">
        <div class="register-flow-tip">Receita padrão: 10 kg farinha · 500 g açúcar · 120 g sal · 10 ovos · 900 ml óleo
          · 3 L água · 100 g fermento.</div>

        <div class="field"><label for="massWorkerSelect">Masseiro responsável</label><select
            id="massWorkerSelect"></select></div>

        <div class="mass-recipe-grid">
          <div class="field"><label>Farinha (kg)</label><input id="batchFlourKg" type="number" min="0" step="0.1"
              inputmode="decimal"></div>
          <div class="field"><label>Açúcar (g)</label><input id="batchSugarG" type="number" min="0" step="1"
              inputmode="numeric"></div>
          <div class="field"><label>Sal (g)</label><input id="batchSaltG" type="number" min="0" step="1"
              inputmode="numeric"></div>
          <div class="field"><label>Ovos (un.)</label><input id="batchEggs" type="number" min="0" step="1"
              inputmode="numeric"></div>
          <div class="field"><label>Óleo (ml)</label><input id="batchOilMl" type="number" min="0" step="1"
              inputmode="numeric"></div>
          <div class="field"><label>Água (L)</label><input id="batchWaterL" type="number" min="0" step="0.1"
              inputmode="decimal"></div>
          <div class="field"><label>Fermento (g)</label><input id="batchYeastG" type="number" min="0" step="1"
              inputmode="numeric"></div>
        </div>

        <div class="field"><label for="batchNote">Observação opcional</label><textarea id="batchNote" maxlength="180"
            placeholder="Ex.: ajuste de água, massa mais firme..."></textarea></div>
      </div>

      <div class="register-modal-actions">
        <button type="button" class="btn btn-ghost" data-close-v4="massBatch">Cancelar</button>
        <button id="saveMassBatchBtn" type="button" class="btn btn-primary">Registrar batida</button>
      </div>
    </div>
  </div>





  <div id="editModal" class="modal-bg">
    <div class="modal">
      <div class="modal-head">
        <h3>Editar comanda</h3><button class="close" data-close="edit">×</button>
      </div>
      <form id="editForm"><input id="editId" type="hidden">
        <div class="inline">
          <div class="field"><label for="editNumber">Comanda</label><input id="editNumber" type="number" min="1"
              max="1000" required></div>
          <div class="field"><label for="editQty">Pizzas</label><input id="editQty" type="number" min="0" max="50"
              required></div>
        </div>
        <div class="special-edit-grid">
          <div class="field"><label for="editVolcanoQty">Vulcão</label><input id="editVolcanoQty" type="number" min="0"
              max="50" value="0"></div>
          <div class="field"><label for="editEsfihaQty">Esfirras</label><input id="editEsfihaQty" type="number" min="0"
              max="200" value="0"></div>
          <div class="field"><label for="editSweetQty">Doces</label><input id="editSweetQty" type="number" min="0"
              max="50" value="0"></div>
        </div>
        <div class="field"><label for="editAssembler">Montador</label><select id="editAssembler" required></select></div>
        <div class="field" id="editSweetAssemblerField" style="display:none;">
          <label for="editSweetAssembler">Montador (Doces)</label>
          <select id="editSweetAssembler"></select>
        </div>
        <div class="field"><label for="editStatus">Status</label><select id="editStatus">
            <option value="cozinha">Na cozinha</option>
            <option value="forno">No forno</option>
            <option value="pronto">Aguardando atendimento</option>
            <option value="despacho">Saiu para o despacho</option>
          </select></div>
        <div class="field"><label for="editNote">Observação</label><textarea id="editNote" maxlength="220"></textarea>
        </div>
        <div class="actions end mobile-stack"><button id="deleteCommandBtn" type="button"
            class="btn btn-soft-red">Excluir</button><button type="button" class="btn btn-ghost"
            data-close="edit">Cancelar</button><button class="btn btn-primary" type="submit">Salvar</button></div>
      </form>
    </div>
  </div>
  <div id="errorModal" class="modal-bg">
    <div class="modal">
      <div class="modal-head">
        <h3>Sinalizar erro</h3><button class="close" data-close="error">×</button>
      </div>
      <form id="errorForm"><input id="errorId" type="hidden">
        <div class="field"><label for="errorType">Tipo</label><select id="errorType" required>
            <option value="">Selecione</option>
            <option>Montagem incorreta</option>
            <option>Ingrediente faltando</option>
            <option>Pizza queimada</option>
            <option>Atraso</option>
            <option>Divergência da comanda</option>
            <option>Embalagem ou despacho</option>
            <option>Outro</option>
          </select></div>
        <div class="field"><label for="errorNote">Descrição opcional</label><textarea id="errorNote"
            maxlength="220"></textarea></div>
        <div class="actions end mobile-stack"><button id="clearErrorBtn" type="button" class="btn btn-soft">Retirar
            erro</button><button type="button" class="btn btn-ghost" data-close="error">Cancelar</button><button
            class="btn btn-red" type="submit">Registrar erro</button></div>
      </form>
    </div>
  </div>
  <div id="sweetAssemblerModal" class="modal-bg">
    <div class="modal" style="max-width:440px; border-radius:24px; padding:0; overflow:hidden; border: 1px solid rgba(0,0,0,0.06); shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
      <!-- Header -->
      <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50/40 via-white to-pink-50/40">
        <div>
          <h3 class="text-[16px] font-bold text-gray-900 leading-snug">Adicionar 2º Montador</h3>
          <p class="text-[12px] text-gray-500">Vincular montador responsável pelos doces</p>
        </div>
        <button type="button" data-close-v4="sweetAssembler"
          class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <!-- Body -->
      <form id="sweetAssemblerForm">
        <div class="px-6 py-5 space-y-4">
          <input id="sweetAssemblerCmdId" type="hidden">
          
          <!-- Summary Info Card -->
          <div class="bg-gradient-to-br from-slate-50 via-gray-50 to-pink-50/30 rounded-2xl p-4 border border-pink-100/70 shadow-sm relative overflow-hidden space-y-3">
            <!-- Top Row: Comanda # Badge + Sweet Pill -->
            <div class="flex items-center justify-between pb-3 border-b border-gray-200/50">
              <div class="flex items-center gap-2">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Comanda</span>
                <span id="sweetAssemblerCmdNumber" class="text-base font-black text-gray-900 bg-white px-3 py-1 rounded-xl border border-gray-200 shadow-xs">-</span>
              </div>
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[12px] font-extrabold bg-pink-100 text-pink-700 border border-pink-200/60 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span id="sweetAssemblerCmdSweetQty">0</span> doces
              </span>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-2 gap-2.5">
              <div class="bg-white/90 p-3 rounded-xl border border-gray-100 shadow-2xs">
                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Total Pizzas</span>
                <div class="flex items-center gap-1 text-sm font-bold text-gray-800">
                  <span>🍕</span>
                  <span id="sweetAssemblerCmdQty">0</span>
                </div>
              </div>
              <div class="bg-white/90 p-3 rounded-xl border border-gray-100 shadow-2xs">
                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">1º Montador</span>
                <span id="sweetAssemblerCmdMainName" class="text-sm font-bold text-gray-800 truncate block">-</span>
              </div>
            </div>
          </div>

          <!-- Select Field -->
          <div class="space-y-1.5 pt-1">
            <label class="flex items-center gap-1.5 text-[12px] font-bold text-gray-600 uppercase tracking-wider">
              <svg class="w-4 h-4 text-[#1F6FB2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Montador de Doces (2º Montador)
            </label>
            <div class="relative">
              <select id="sweetAssemblerSelect" required class="w-full appearance-none px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 focus:bg-white focus:border-[#1F6FB2] focus:ring-2 focus:ring-[#1F6FB2]/20 transition-all text-[14px] font-semibold text-gray-800 outline-none cursor-pointer">
                <option value="">Selecione o montador</option>
              </select>
              <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer Actions -->
        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50/80 border-t border-gray-100">
          <button type="button" data-close-v4="sweetAssembler"
            class="inline-flex items-center px-4 py-2.5 text-[13px] font-semibold text-gray-700 bg-gray-100 border border-transparent rounded-xl hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10" stroke-width="2"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9l-6 6M9 9l6 6"/>
            </svg>
            Cancelar
          </button>
          <button type="submit"
            class="inline-flex items-center px-5 py-2.5 text-[13px] font-bold text-white bg-[#2f9e64] rounded-xl hover:bg-[#248150] active:scale-[0.98] transition-all shadow-sm cursor-pointer">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-8H7v8M7 3v5h8"/>
            </svg>
            Salvar comanda
          </button>
        </div>
      </form>
    </div>
  </div>
  <div id="toast" class="toast"></div>

  <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-database-compat.js"></script>
