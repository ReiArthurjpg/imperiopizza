    <section id="page-dispatch" class="page">
      <div class="page-head">
        <div>
          <h2>Atendimento / Despacho</h2>
          <p>Puxe manualmente as comandas que já estão no forno, confira os complementos e sinalize a saída para
            entrega.</p>
        </div>
        <div class="actions">
          <button id="manageTeamDispatchBtn" class="btn btn-soft">Acionar equipe</button>
          <button id="finishDayBtn" class="btn btn-green">Finalizar o dia</button>
        </div>
      </div>

      <div id="dispatchGate"></div>

      <div id="dispatchContent" class="hidden">
        <div id="dispatchSubtotals" class="subtotal"></div>

        <div class="production-command-center dispatch-command-center">
          <button id="openDispatchIntakeBtn" type="button" class="command-center-btn new-command">
            <span id="ovenReadyBadge" class="center-action-badge">0</span>
            <span class="center-action-icon">🔥</span>
            <span class="center-action-copy">
              <strong>Puxar do forno</strong>
              <small>Veja as comandas que já entraram no forno e receba manualmente no atendimento.</small>
            </span>
          </button>

          <button id="openDispatchQueueBtn" type="button" class="command-center-btn update-command">
            <span id="dispatchQueueBadge" class="center-action-badge">0</span>
            <span class="center-action-icon">✓</span>
            <span class="center-action-copy">
              <strong>Conferir pedidos</strong>
              <small>Atualize bebida, troco, molhos e sinalize quando saiu para o motoboy.</small>
            </span>
          </button>
        </div>

        <article id="dispatchQueuePanel" class="card">
          <div class="history-card-head">
            <div>
              <h3>Fila do atendimento</h3>
              <p class="sub" style="margin-bottom:0">Recebidas do forno, conferidas e enviadas para entrega.</p>
            </div>
            <span class="history-hint">Última etapa: saiu para entrega</span>
          </div>

          <div class="filters" style="grid-template-columns:1fr 210px auto">
            <input id="dispatchSearch" type="text" placeholder="Buscar comanda ou montador">
            <select id="dispatchFilter">
              <option value="">Todos</option>
              <option value="aguardando">Recebido / aguardando</option>
              <option value="conferido">Conferido</option>
              <option value="entrega">Saiu para entrega</option>
            </select>
            <button id="clearDispatchFilters" class="btn btn-ghost">Limpar</button>
          </div>

          <div id="dispatchGrid" class="dispatch-grid"></div>
          <div id="dispatchEmpty" class="empty hidden"><strong>Nenhuma comanda recebida</strong>Use “Puxar do forno”
            para trazer uma comanda ao atendimento.</div>
        </article>
      </div>
    </section>

