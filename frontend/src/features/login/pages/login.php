<div class="flex min-h-screen w-full bg-[#F7F7F5] font-sans text-[#171717]">
  
  <!-- Left Side: Login Form (White) -->
  <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12 lg:p-24 relative bg-white lg:border-r border-[#E7E7E7] shadow-[4px_0_24px_rgba(0,0,0,0.02)] z-10">
    
    <!-- Mobile Brand (Visible only on mobile) -->
    <div class="lg:hidden flex flex-col items-center mb-10">
      <div class="w-16 h-16 bg-[#FDECEB] rounded-2xl flex items-center justify-center mb-4 border border-[#FCA5A5]/30">
        <i data-lucide="crown" class="w-8 h-8 text-[#B5120B]"></i>
      </div>
      <h1 class="text-2xl font-extrabold text-[#171717] tracking-tight">Imperial<span class="text-[#B5120B]">OS</span></h1>
    </div>

    <!-- Form Container -->
    <div class="w-full max-w-sm sm:max-w-md">
      <!-- Header -->
      <div class="mb-10 text-center lg:text-left">
        <h2 class="text-3xl font-extrabold text-[#171717] tracking-tight mb-2">Acesse sua conta</h2>
        <p class="text-base text-[#737373] font-medium">Insira suas credenciais para entrar no Imperial OS.</p>
      </div>

      <!-- Form -->
      <form action="/" method="GET" class="space-y-6">
        
        <!-- Input Group: Usuário -->
        <div>
          <label for="username" class="block text-sm font-semibold text-[#171717] mb-2">Usuário</label>
          <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-300">
              <i data-lucide="user" class="w-5 h-5 text-[#737373] group-focus-within:text-[#B5120B]"></i>
            </div>
            <input 
              type="text" 
              id="username" 
              name="username" 
              class="block w-full pl-11 pr-4 py-3.5 bg-gray-50/50 border border-[#E7E7E7] rounded-xl text-[#171717] placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#B5120B]/20 focus:border-[#B5120B] transition-all duration-300" 
              placeholder="Digite seu usuário" 
              required
            >
          </div>
        </div>
        
        <!-- Input Group: Senha -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <label for="password" class="block text-sm font-semibold text-[#171717]">Senha</label>
            <a href="#" class="text-sm font-semibold text-[#B5120B] hover:text-[#99100A] hover:underline transition-colors">Esqueceu a senha?</a>
          </div>
          <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-300">
              <i data-lucide="lock" class="w-5 h-5 text-[#737373] group-focus-within:text-[#B5120B]"></i>
            </div>
            <input 
              type="password" 
              id="password" 
              name="password" 
              class="block w-full pl-11 pr-12 py-3.5 bg-gray-50/50 border border-[#E7E7E7] rounded-xl text-[#171717] placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#B5120B]/20 focus:border-[#B5120B] transition-all duration-300" 
              placeholder="••••••••" 
              required
            >
            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#737373] hover:text-[#171717] focus:outline-none transition-colors">
              <i data-lucide="eye" id="eye-icon" class="w-5 h-5"></i>
            </button>
          </div>
        </div>

        <!-- Checkbox -->
        <div class="flex items-center pt-2">
          <label class="relative flex items-center cursor-pointer group">
            <input id="remember" name="remember" type="checkbox" class="peer sr-only">
            <div class="w-5 h-5 bg-white border border-[#E7E7E7] rounded peer-checked:bg-[#B5120B] peer-checked:border-[#B5120B] peer-focus:ring-2 peer-focus:ring-[#B5120B]/30 transition-all flex items-center justify-center">
              <i data-lucide="check" class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
            </div>
            <span class="ml-3 text-sm font-medium text-[#737373] group-hover:text-[#171717] transition-colors">Manter conectado</span>
          </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full flex justify-center items-center gap-2 py-4 px-4 bg-[#B5120B] hover:bg-[#99100A] text-white text-[16px] font-extrabold rounded-xl shadow-[0_4px_14px_0_rgba(181,18,11,0.25)] hover:shadow-[0_6px_20px_rgba(181,18,11,0.3)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 mt-8">
          Entrar no Sistema
          <i data-lucide="log-in" class="w-5 h-5"></i>
        </button>
      </form>
    </div>
    
    <!-- Footer -->
    <div class="absolute bottom-6 w-full text-center lg:w-1/2">
      <p class="text-xs text-[#737373] font-medium">Imperial Pizza &copy; <?= date('Y') ?>. Todos os direitos reservados.</p>
    </div>
  </div>

  <!-- Right Side: Visual / Brand Area (Hidden on mobile) -->
  <div class="hidden lg:!flex lg:w-1/2 relative items-center justify-center overflow-hidden bg-[#F7F7F5]">
    
    <!-- Abstract Elements -->
    <div class="absolute -top-32 -right-32 w-[500px] h-[500px] bg-[#B5120B] rounded-full blur-[120px] mix-blend-multiply opacity-[0.03] animate-pulse"></div>
    <div class="absolute -bottom-32 -left-32 w-[500px] h-[500px] bg-[#F5B82E] rounded-full blur-[120px] mix-blend-multiply opacity-[0.05]" style="animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite; animation-delay: 2s;"></div>
    
    <!-- Subtle Pattern Overlay (Dots) -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMCwwLDAsMC4wMikiLz48L3N2Zz4=')] opacity-50"></div>

    <!-- Content -->
    <div class="relative z-10 flex flex-col items-center lg:items-start text-center lg:text-left px-12 w-full max-w-lg">
      
      <!-- Brand Icon (Crown) -->
      <div class="w-20 h-20 bg-white border border-[#E7E7E7] rounded-2xl flex items-center justify-center shadow-sm mb-8 group cursor-default hover:border-[#B5120B]/30 transition-colors duration-500">
        <div class="w-12 h-12 bg-[#FDECEB] rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform duration-500">
          <i data-lucide="crown" class="w-7 h-7 text-[#B5120B]"></i>
        </div>
      </div>
      
      <!-- Badge -->
      <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-[#E7E7E7] mb-6 shadow-sm">
        <span class="flex h-2 w-2 rounded-full bg-[#B5120B] animate-pulse"></span>
        <span class="text-xs font-bold text-[#171717] uppercase tracking-wider">Imperial OS v4.0</span>
      </div>

      <!-- Main Heading -->
      <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight text-[#171717] mb-6 leading-tight">
        Qualidade e Rapidez <br />
        <span class="text-[#B5120B]">REAL! 👑🍕</span>
      </h1>
      
      <!-- Description -->
      <p class="text-lg text-[#737373] font-medium leading-relaxed max-w-md mb-12">
        O sistema operacional definitivo para alta gastronomia. Controle de comandas, agilidade no forno <i data-lucide="flame" class="inline w-4 h-4 text-[#B5120B]"></i> e gestão em tempo real.
      </p>

      <!-- Glassmorphism Stats Cards (Adapted to Light Theme) -->
      <div class="grid grid-cols-2 gap-4 w-full max-w-sm">
        <div class="bg-white/80 backdrop-blur-md border border-[#E7E7E7] rounded-2xl p-4 flex flex-col items-start hover:bg-white transition-colors shadow-[0_2px_10px_rgba(0,0,0,0.02)] group">
          <div class="p-2 bg-[#FDF6E3] rounded-lg mb-3">
            <i data-lucide="zap" class="w-5 h-5 text-[#F5B82E] group-hover:scale-110 transition-transform"></i>
          </div>
          <p class="text-sm font-bold text-[#171717]">Alta Velocidade</p>
          <p class="text-xs text-[#737373]">Sincronização ⚡</p>
        </div>
        
        <div class="bg-white/80 backdrop-blur-md border border-[#E7E7E7] rounded-2xl p-4 flex flex-col items-start hover:bg-white transition-colors shadow-[0_2px_10px_rgba(0,0,0,0.02)] group border-l-4 border-l-[#B5120B]">
          <div class="p-2 bg-[#FDECEB] rounded-lg mb-3">
            <i data-lucide="pizza" class="w-5 h-5 text-[#B5120B] group-hover:scale-110 transition-transform"></i>
          </div>
          <p class="text-sm font-bold text-[#171717]">Gestão Focada</p>
          <p class="text-xs text-[#737373]">Fluxo contínuo 🔥</p>
        </div>
      </div>
      
    </div>
  </div>

</div>

<script>
  function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');
    
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      eyeIcon.setAttribute('data-lucide', 'eye-off');
    } else {
      passwordInput.type = 'password';
      eyeIcon.setAttribute('data-lucide', 'eye');
    }
    
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  }
</script>
