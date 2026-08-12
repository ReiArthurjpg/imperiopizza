    <section id="page-dashboard" class="page active">
      <div class="page-head">
        <div>
          <h2>Dashboard</h2>
          <p>Visão geral da operação selecionada.</p>
        </div>
        <div class="actions"><button class="btn btn-soft" data-go="team">Organizar equipe</button><button
            class="btn btn-primary" data-go="production">Abrir produção</button></div>
      </div>
      <div id="dashboardBanner" class="banner"></div>
      <div class="grid grid-4" style="margin-bottom:18px">
        <article class="card summary"><small>Comandas</small><strong
            id="dashCommands">0</strong><span>registradas</span></article>
        <article class="card summary"><small>Pizzas</small><strong id="dashPizzas">0</strong><span>total
            produzido</span></article>
        <article class="card summary"><small>Na cozinha</small><strong id="dashKitchen">0</strong><span>em
            preparação</span></article>
        <article class="card summary"><small>No forno</small><strong id="dashOven">0</strong><span>em
            processamento</span></article>
        <article class="card summary"><small>Pendentes no despacho</small><strong
            id="dashDispatch">0</strong><span>aguardando liberação</span></article>
        <article class="card summary"><small>Erros</small><strong id="dashErrors">0</strong><span>ocorrências
            sinalizadas</span></article>
      </div>
      <div class="grid dashboard-insights">
        <article class="card">
          <h3>Top 5 da montagem</h3>
          <p class="sub">Amostra ao vivo dos cinco montadores com maior quantidade de pizzas no dia.</p>
          <div id="dashboardRank"></div>
        </article>
        <article class="card">
          <h3>Movimento recente</h3>
          <p class="sub">As cinco últimas comandas registradas ou atualizadas na operação.</p>
          <div id="dashboardLive"></div>
        </article>
        <article class="card">
          <h3>Equipe online do dia</h3>
          <p class="sub">Profissionais selecionados para a operação.</p>
          <div id="dashboardTeam"></div>
        </article>
      </div>
    </section>

    <?php require_once __DIR__ . '/../../equipe/pages/equipe.php'; ?>
    <?php require_once __DIR__ . '/../../cozinha/pages/cozinha.php'; ?>
    <?php require_once __DIR__ . '/../../estoque/pages/estoque.php'; ?>
    <?php require_once __DIR__ . '/../../pdv/pages/pdv.php'; ?>
    <?php require_once __DIR__ . '/../../relatorios/pages/relatorios.php'; ?>
