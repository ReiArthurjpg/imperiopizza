    <section id="page-reports" class="page active">
      <div class="page-head">
        <div>
          <h2>Relatórios e histórico</h2>
          <p>Ranking, presença e resultado da montagem por operação.</p>
        </div>
        <div class="actions"><button id="backupBtn" class="btn btn-primary">Baixar backup</button><button
            id="restoreBtn" class="btn btn-soft-orange">Restaurar backup</button><input id="restoreFile" class="hidden"
            type="file" accept=".json,application/json"></div>
      </div>
      <div class="grid sidebar">
        <article class="card">
          <h3>Operações registradas</h3>
          <p class="sub">Selecione uma data.</p>
          <div id="historyList" class="history-list"></div>
        </article>
        <div class="grid">
          <article class="card">
            <div id="reportOverview"></div>
          </article>
          <article class="card">
            <h3>Relatórios disponíveis</h3>
            <p class="sub">A lista de presença pode ser emitida após salvar a equipe. O resultado final fica disponível
              ao encerrar a cozinha.</p>
            <div id="reportCards" class="report-cards"></div>
          </article>
        </div>
      </div>
    </section>
