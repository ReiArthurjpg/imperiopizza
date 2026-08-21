<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta name="theme-color" content="#B5120B" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
  <title><?= $title ?? 'Login | Imperial Pizza' ?></title>
  
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
  <link rel="stylesheet" href="/assets/css/app.css?v=<?= filemtime(__DIR__ . '/../../public/assets/css/app.css') ?>" />
  
  <!-- Lucide Icons CDN -->
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#F7F7F5] min-h-screen overflow-x-hidden font-sans selection:bg-[#B5120B] selection:text-white flex flex-col">

  <main class="flex-1 w-full flex flex-col !p-0 !m-0">
    <?= $content ?>
  </main>

  <!-- Scripts -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }
    });
  </script>
</body>
</html>
