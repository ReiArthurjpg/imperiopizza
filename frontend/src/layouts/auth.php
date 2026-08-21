<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imperial OS - Autenticação</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        // Uma paleta mais sóbria, focada no vermelho imperial
                        imperial: {
                            red: '#D92D20', // Vermelho principal vibrante mas sólido
                            dark: '#111111', // Preto rico para contrastes
                            light: '#F9FAFB', // Fundo quase branco
                            border: '#E5E7EB'
                        }
                    }
                }
            }
        }
    </script>

</head>
<body class="bg-imperial-light min-h-screen flex text-gray-900">
    <?= $content ?>
</body>
</html>
