<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta name="theme-color" content="#B5120B" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
  <title><?= $title ?? 'Imperial Pizza | Operação Integrada v4.0' ?></title>
  
  <!-- Tailwind CSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
          }
        }
      }
    }
  </script>
  
  <!-- Google Fonts Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  
  <!-- Estilos base do projeto -->
  <link rel="stylesheet" href="/assets/css/app.css" />
  
  <!-- Lucide Icons CDN -->
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#F7F7F5] overflow-hidden">

  <!-- Main flex layout representing Sidebar and Main content side-by-side -->
  <div class="flex h-[100dvh] w-screen overflow-hidden font-sans selection:bg-[#B5120B] selection:text-white">
    
    <!-- Sidebar component -->
    <?php require_once __DIR__ . '/../components/sidebar.php'; ?>

    <!-- Content area -->
    <main class="flex-1 flex flex-col min-w-0 overflow-hidden !p-0 !m-0 !max-w-none">
      
      <!-- Top Header -->
      <?php require_once __DIR__ . '/../components/header.php'; ?>

      <!-- Main Scrollable Content -->
      <div id="mainScrollContainer" class="flex-1 overflow-auto p-4 pb-32 sm:p-6 sm:pb-32 lg:p-8 lg:pb-8">
        <div class="max-w-7xl mx-auto min-h-full">
          <?= $content ?>
        </div>
      </div>
    </main>

  <!-- Modais Globais -->
  <?php require_once __DIR__ . '/../components/modals.php'; ?>
  
  <!-- Navegação Mobile -->
  <?php require_once __DIR__ . '/../components/mobile_nav.php'; ?>
  
  <!-- Navegação Mobile (Representa o backdrop do menu lateral) -->
  <div id="mobileSidebarOverlay" class="fixed inset-0 bg-black/20 z-40 hidden backdrop-blur-sm transition-opacity"></div>

  <!-- Scripts -->
  <script src="/assets/js/app.js?v=<?= filemtime(__DIR__ . '/../../public/assets/js/app.js') ?>"></script>
  <script>
    // Initialize Lucide icons on page load
    document.addEventListener('DOMContentLoaded', function() {
      lucide.createIcons();
    });
  </script>
</body>
</html>
