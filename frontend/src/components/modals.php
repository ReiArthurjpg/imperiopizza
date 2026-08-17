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
  <div id="toast" class="toast"></div>

  <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-database-compat.js"></script>
