  <nav class="mobile-nav">
    <a href="/" class="nav-btn <?= ($_SERVER['REQUEST_URI'] ?? '/') === '/' ? 'active' : '' ?>"><span>📊</span>Resumo</a>
    <a href="/equipe" class="nav-btn <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/equipe') !== false ? 'active' : '' ?>"><span>👥</span>Equipe</a>
    <a href="/cozinha" class="nav-btn <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/cozinha') !== false ? 'active' : '' ?>"><span>🍕</span>Produção</a>
    <a href="/estoque" class="nav-btn <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/estoque') !== false ? 'active' : '' ?>"><span>🥣</span>Massas</a>
    <a href="/comandas" class="nav-btn <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/comandas') !== false ? 'active' : '' ?>"><span>📦</span>Atend./Desp.</a>
    <a href="/relatorios" class="nav-btn <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/relatorios') !== false ? 'active' : '' ?>"><span>🧾</span>Relatórios</a>
  </nav>
