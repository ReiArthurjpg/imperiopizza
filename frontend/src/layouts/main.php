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
  <div class="flex h-screen w-screen overflow-hidden font-sans selection:bg-[#B5120B] selection:text-white">
    
    <!-- Sidebar component -->
    <?php require_once __DIR__ . '/../components/sidebar.php'; ?>

    <!-- Content area -->
    <main class="flex-1 flex flex-col min-w-0 overflow-hidden !p-0 !m-0 !max-w-none">
      
      <!-- Top Header -->
      <?php require_once __DIR__ . '/../components/header.php'; ?>

      <!-- Wrapper for content + right panel -->
      <div class="flex-1 flex flex-row min-h-0 overflow-hidden">
        
        <!-- Main Scrollable Content -->
        <div class="flex-1 overflow-auto p-4 sm:p-6 lg:p-8">
          <div class="max-w-7xl mx-auto">
            <?= $content ?>
          </div>
        </div>

        <!-- RIGHT FIXED PANEL: REGISTER COMMAND MODAL -->
        <aside id="registerCommandModal" class="hidden fixed inset-y-0 right-0 z-50 w-full sm:w-[380px] bg-white border-l border-gray-200 flex-col shadow-2xl shrink-0 transition-transform duration-300 translate-x-full lg:static lg:flex lg:w-[380px] lg:z-10 lg:shadow-[-4px_0_24px_rgba(0,0,0,0.03)] lg:translate-x-0">
          
          <!-- Header -->
          <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 shrink-0">
            <div>
              <h3 class="text-[15px] font-bold text-gray-900 leading-none">Registrar nova comanda</h3>
              <p class="text-[12px] text-gray-400 mt-0.5">Janela fixa para registros rápidos em sequência.</p>
            </div>
            <!-- Botão fechar apenas para mobile -->
            <button type="button" data-close="registerCommand" class="lg:hidden p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Body -->
          <div class="register-modal-body px-6 py-5 space-y-5 overflow-y-auto flex-1">

            <!-- Sweet Pending Alert Panel (inside scroll body) -->
            <div id="sweetPendingPanel" class="hidden">
              <div class="rounded-xl border border-pink-200 bg-pink-50 p-3.5">
                <div class="flex items-center gap-2 mb-2.5">
                  <svg class="w-4 h-4 text-pink-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                  </svg>
                  <span class="text-[12px] font-bold text-pink-700 uppercase tracking-wide">Doces aguardando 2ª entrega</span>
                </div>
                <div id="sweetPendingList" class="space-y-1.5 overflow-y-auto" style="max-height:108px;"></div>
              </div>
            </div>

            <div class="space-y-1.5">
              <label class="text-[12px] font-semibold text-gray-500 uppercase tracking-wider">Montador responsável</label>
              <div class="relative">
                <select id="assemblerId" class="w-full appearance-none px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 focus:bg-white focus:border-[#1F6FB2] focus:ring-2 focus:ring-[#1F6FB2]/20 transition-all text-[14px] font-semibold text-gray-700 outline-none cursor-pointer">
                  <option value="">Selecione o montador</option>
                </select>
                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                  </svg>
                </div>
              </div>
              <div id="assemblerSuggestions" class="hidden"></div>
            </div>

            <!-- Number + Qty -->
            <div class="grid grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label for="commandNumber" class="text-[12px] font-semibold text-gray-500 uppercase tracking-wider">Nº da comanda</label>
                <input id="commandNumber" type="number" min="1" max="1000" inputmode="numeric" placeholder="Ex.: 7"
                  class="w-full px-4 py-3 text-[15px] font-bold text-gray-900 bg-gray-50 border border-gray-200 rounded-xl placeholder-gray-300 focus:outline-none focus:border-[#1F6FB2] focus:ring-2 focus:ring-blue-100 transition-all">
              </div>
              <div class="space-y-1.5">
                <label class="text-[12px] font-semibold text-gray-500 uppercase tracking-wider">Qtd. de pizzas</label>
                <div class="flex items-center gap-2">
                  <button type="button" data-qty="minus"
                    class="w-10 h-10 flex items-center justify-center bg-gray-100 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-200 transition-colors font-bold text-lg shrink-0">−</button>
                  <input id="pizzaQty" type="number" min="0" max="50" value="1" inputmode="numeric"
                    class="flex-1 px-2 py-3 text-[15px] font-bold text-gray-900 bg-gray-50 border border-gray-200 rounded-xl text-center focus:outline-none focus:border-[#1F6FB2] focus:ring-2 focus:ring-blue-100 transition-all min-w-0">
                  <button type="button" data-qty="plus"
                    class="w-10 h-10 flex items-center justify-center bg-gray-100 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-200 transition-colors font-bold text-lg shrink-0">+</button>
                </div>
              </div>
            </div>

            <!-- Command Suggestions -->
            <div id="commandSuggestions" class="suggest-box"></div>

            <!-- Note -->
            <div class="space-y-1.5">
              <label for="commandNote" class="text-[12px] font-semibold text-gray-500 uppercase tracking-wider">Observação <span class="font-normal normal-case text-gray-400">(opcional)</span></label>
              <textarea id="commandNote" maxlength="220" placeholder="Ex.: sem cebola, prioridade, pizza dividida..."
                class="w-full px-4 py-3 text-[13px] text-gray-700 bg-gray-50 border border-gray-200 rounded-xl placeholder-gray-300 focus:outline-none focus:border-[#1F6FB2] focus:ring-2 focus:ring-blue-100 transition-all resize-none"
                rows="2"></textarea>
            </div>

            <!-- Initial Oven Toggle -->
            <label class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50 cursor-pointer hover:bg-orange-50/50 hover:border-orange-200 transition-all group has-[:checked]:bg-orange-50 has-[:checked]:border-orange-200">
              <input id="initialOven" type="checkbox" class="hidden">
              <div class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 rounded-[10px] shadow-sm group-hover:border-orange-300 group-has-[:checked]:border-orange-400 group-has-[:checked]:bg-orange-50 transition-colors">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-500 group-has-[:checked]:text-orange-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 7 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>
                </svg>
              </div>
              <div>
                <p class="text-[13px] font-semibold text-gray-700 group-has-[:checked]:text-orange-700 transition-colors leading-none">A comanda já entrou no forno</p>
                <p class="text-[11px] text-gray-400 mt-0.5">Marque se a pizza já está assando</p>
              </div>
            </label>

            <!-- Special Products -->
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="text-[13px] font-bold text-gray-800">Produtos especiais</h4>
                  <p class="text-[11px] text-gray-400 mt-0.5">Equivalência aplicada automaticamente ao ranking</p>
                </div>
                <span id="equivalentPreview" class="inline-flex items-center px-2.5 py-1 rounded-lg text-[12px] font-bold bg-blue-50 text-[#1F6FB2] border border-blue-100">1,0 equiv.</span>
              </div>

              <div class="grid grid-cols-1 gap-2.5">
                <!-- Volcano -->
                <label class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-200 bg-gray-50 cursor-pointer hover:border-orange-200 hover:bg-orange-50/30 transition-all group has-[:checked]:border-orange-300 has-[:checked]:bg-orange-50">
                  <input id="volcanoCheck" type="checkbox" class="hidden">
                  <div class="w-8 h-8 flex items-center justify-center rounded-[8px] border border-gray-200 bg-white group-has-[:checked]:bg-orange-100 group-has-[:checked]:border-orange-300 transition-colors shrink-0">
                    <svg class="w-4 h-4 text-gray-400 group-has-[:checked]:text-orange-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-semibold text-gray-700 group-has-[:checked]:text-orange-700 transition-colors leading-none">Pizza vulcão</p>
                    <p id="volcanoRuleText" class="text-[11px] text-gray-400 mt-0.5">Cada unidade vale 2 pizzas</p>
                  </div>
                  <input id="volcanoQty" class="w-14 px-2 py-1.5 text-[13px] font-bold text-center bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition-all" type="number" min="0" max="50" value="1" inputmode="numeric" disabled>
                </label>

                <!-- Esfiha -->
                <label class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-200 bg-gray-50 cursor-pointer hover:border-amber-200 hover:bg-amber-50/30 transition-all group has-[:checked]:border-amber-300 has-[:checked]:bg-amber-50">
                  <input id="esfihaCheck" type="checkbox" class="hidden">
                  <div class="w-8 h-8 flex items-center justify-center rounded-[8px] border border-gray-200 bg-white group-has-[:checked]:bg-amber-100 group-has-[:checked]:border-amber-300 transition-colors shrink-0">
                    <svg class="w-4 h-4 text-gray-400 group-has-[:checked]:text-amber-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-semibold text-gray-700 group-has-[:checked]:text-amber-700 transition-colors leading-none">Esfirras</p>
                    <p id="esfihaRuleText" class="text-[11px] text-gray-400 mt-0.5">5 esfirras = 2 pizzas</p>
                  </div>
                  <input id="esfihaQty" class="w-14 px-2 py-1.5 text-[13px] font-bold text-center bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition-all" type="number" min="0" max="200" value="5" inputmode="numeric" disabled>
                </label>

                <!-- Sweet -->
                <label class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-200 bg-gray-50 cursor-pointer hover:border-pink-200 hover:bg-pink-50/30 transition-all group has-[:checked]:border-pink-300 has-[:checked]:bg-pink-50">
                  <input id="sweetCheck" type="checkbox" class="hidden">
                  <div class="w-8 h-8 flex items-center justify-center rounded-[8px] border border-gray-200 bg-white group-has-[:checked]:bg-pink-100 group-has-[:checked]:border-pink-300 transition-colors shrink-0">
                    <svg class="w-4 h-4 text-gray-400 group-has-[:checked]:text-pink-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-semibold text-gray-700 group-has-[:checked]:text-pink-700 transition-colors leading-none">Pizza doce</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">Mapeia a produção de pizzas doces</p>
                  </div>
                  <input id="sweetQty" class="w-14 px-2 py-1.5 text-[13px] font-bold text-center bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-100 transition-all" type="number" min="0" max="50" value="1" inputmode="numeric" disabled>
                </label>
              </div>
            </div>

          </div>

          <!-- Footer Actions -->
          <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50/80 border-t border-gray-100 shrink-0">
            <button id="clearCommandBtn" type="button"
              class="inline-flex items-center px-4 py-2.5 text-[13px] font-semibold text-gray-700 bg-gray-100 border border-transparent rounded-xl hover:bg-gray-200 transition-colors">
              <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              Limpar
            </button>
            <button id="addCommandBtn" type="button"
              class="inline-flex items-center px-5 py-2.5 text-[13px] font-bold text-white bg-[#2f9e64] rounded-xl hover:bg-[#248150] active:scale-[0.98] transition-all shadow-sm">
              <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-8H7v8M7 3v5h8"/>
              </svg>
              Salvar comanda
            </button>
          </div>
        </aside>

      </div>
    </main>

  <!-- Modais Globais -->
  <?php require_once __DIR__ . '/../components/modals.php'; ?>
  
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
