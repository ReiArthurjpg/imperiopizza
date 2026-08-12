<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta name="theme-color" content="#090a0b" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
  <title><?= $title ?? 'Imperial Pizza | Operação Integrada v4.0' ?></title>
  
  <!-- Tailwind CSS via CDN conforme solicitado -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Estilos -> A compilação do Tailwind + CSS legado -->
  <link rel="stylesheet" href="/assets/css/app.css" />
</head>
<body>

  <!-- Componente de Header Global -->
  <?php require_once __DIR__ . '/../components/header.php'; ?>

  <main>
    <?= $content ?>
  </main>

  <!-- Componente de Modais Globais -->
  <?php require_once __DIR__ . '/../components/modals.php'; ?>
  
  <!-- Navegação Mobile -->
  <?php require_once __DIR__ . '/../components/mobile_nav.php'; ?>

  <!-- Scripts -->
  <script src="/assets/js/app.js"></script>
</body>
</html>
