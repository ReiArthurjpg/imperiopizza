    <section id="page-mass" class="page">
      <div class="page-head">
        <div>
          <h2>Controle de Massas</h2>
          <p>Masseiros, estoque de insumos e registro de cada batida.</p>
        </div>
        <div class="actions">
          <button id="manageTeamMassBtn" class="btn btn-soft" data-go="team">Acionar equipe</button>
          <button id="openMassBatchBtn" class="btn btn-primary">Nova batida</button>
        </div>
      </div>

      <div id="massGate"></div>

      <div id="massContent" class="hidden">
        <div id="massSubtotals" class="subtotal"></div>

        <div class="grid sidebar">
          <article class="card">
            <h3>Estoque informado do dia</h3>
            <p class="sub">Informe quanto existe disponível. O sistema calcula automaticamente o saldo após as batidas.
            </p>

            <div class="mass-stock-grid">
              <div class="field"><label>Farinha de trigo (kg)</label><input id="stockFlourKg" type="number" min="0"
                  step="0.1" inputmode="decimal"></div>
              <div class="field"><label>Açúcar (g)</label><input id="stockSugarG" type="number" min="0" step="1"
                  inputmode="numeric"></div>
              <div class="field"><label>Sal (g)</label><input id="stockSaltG" type="number" min="0" step="1"
                  inputmode="numeric"></div>
              <div class="field"><label>Ovos (un.)</label><input id="stockEggs" type="number" min="0" step="1"
                  inputmode="numeric"></div>
              <div class="field"><label>Óleo (ml)</label><input id="stockOilMl" type="number" min="0" step="1"
                  inputmode="numeric"></div>
              <div class="field"><label>Água (L)</label><input id="stockWaterL" type="number" min="0" step="0.1"
                  inputmode="decimal"></div>
              <div class="field"><label>Fermento (g)</label><input id="stockYeastG" type="number" min="0" step="1"
                  inputmode="numeric"></div>
            </div>

            <button id="saveMassStockBtn" class="btn btn-primary btn-wide">Salvar estoque do dia</button>
          </article>

          <article class="card">
            <div class="mass-balance-head">
              <div>
                <h3>Saldo estimado</h3>
                <p class="sub">Estoque informado menos o consumo registrado nas batidas.</p>
              </div>
              <span id="massStockStatus" class="chip">Aguardando estoque</span>
            </div>
            <div id="massBalanceGrid" class="mass-balance-grid"></div>
          </article>
        </div>

        <article class="card" style="margin-top:18px">
          <div class="history-card-head">
            <div>
              <h3>Histórico de batidas</h3>
              <p class="sub" style="margin-bottom:0">Cada batida registra masseiro, horário e consumo real de material.
              </p>
            </div>
            <span id="massRecipeChip" class="history-hint">Receita padrão: 10 kg de farinha</span>
          </div>
          <div id="massBatchHistory"></div>
        </article>
      </div>
    </section>

