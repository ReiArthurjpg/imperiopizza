    <section id="page-production" class="page active">
      <div class="page-head">
        <div>
          <h2>Produção</h2>
          <p>Registre novas comandas em uma janela rápida e atualize separadamente as etapas da produção.</p>
        </div>
        <div class="actions">
          <button id="manageTeamBtn" class="btn btn-soft">Acionar equipe</button>
          <button id="reopenKitchenBtn" class="btn btn-soft-orange hidden">Reabrir cozinha</button>
          <button id="closeKitchenBtn" class="btn btn-red">Encerrar cozinha</button>
        </div>
      </div>

      <div id="productionGate"></div>

      <div id="productionContent" class="hidden">
        <div id="productionSubtotals" class="subtotal"></div>

        <div class="production-command-center">
          <button id="openRegisterCommandBtn" type="button" class="command-center-btn new-command">
            <span class="center-action-icon">＋</span>
            <span class="center-action-copy">
              <strong>Nova comanda</strong>
              <small>Abra a janela para informar montador, comanda e quantidade de pizzas.</small>
            </span>
          </button>

          <button id="openUpdateCommandsBtn" type="button" class="command-center-btn update-command">
            <span id="updatePendingBadge" class="center-action-badge">0</span>
            <span class="center-action-icon">↻</span>
            <span class="center-action-copy">
              <strong>Atualizar comandas</strong>
              <small>Volte à lista e dê baixa nas pizzas que foram ao forno ou ao despacho.</small>
            </span>
          </button>
        </div>

        <div id="productionRecent" class="production-recent"></div>

        <article id="commandHistoryPanel" class="card command-history-card">
          <div class="history-card-head">
            <div>
              <h3>Histórico e atualização das comandas</h3>
              <p class="sub" style="margin-bottom:0">As comandas no forno aparecem primeiro para facilitar a baixa até o
                despacho.</p>
            </div>
            <span class="history-hint">Toque na ação principal para avançar</span>
          </div>

          <div class="filters">
            <input id="prodSearch" type="text" placeholder="Buscar número, montador ou observação">
            <select id="prodStatus">
              <option value="">Todos os status</option>
              <option value="cozinha">Na cozinha</option>
              <option value="forno">No forno</option>
              <option value="despacho">Saiu para o despacho</option>
            </select>
            <select id="prodAssembler">
              <option value="">Todos os montadores</option>
            </select>
            <button id="clearProdFilters" class="btn btn-ghost">Limpar</button>
          </div>

          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Comanda</th>
                  <th>Pizzas</th>
                  <th>Montador</th>
                  <th>Status</th>
                  <th>Horários</th>
                  <th>Observação</th>
                  <th>Erro</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody id="productionBody"></tbody>
            </table>
          </div>

          <div id="productionMobileList" class="mobile-command-list"></div>

          <div id="productionEmpty" class="empty hidden">
            <strong>Nenhuma comanda encontrada</strong>
            Registre a primeira comanda ou ajuste os filtros.
          </div>
        </article>
      </div>
    </section>



