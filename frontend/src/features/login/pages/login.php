<div class="flex min-h-screen w-full bg-[#0F1115] font-sans selection:bg-[#D92D20] selection:text-white">
  
  <!-- Left Side: Login Form -->
  <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12 lg:p-24 relative bg-[#0F1115]">
    
    <!-- Mobile Brand (Visible only on mobile) -->
    <div class="lg:hidden flex flex-col items-center mb-10">
      <div class="w-16 h-16 bg-gradient-to-br from-[#D92D20] to-[#B91C1C] rounded-2xl flex items-center justify-center shadow-lg mb-4 border border-white/10">
        <i data-lucide="crown" class="w-8 h-8 text-[#F5B82E]"></i>
      </div>
      <h1 class="text-2xl font-extrabold text-[#F8FAFC] tracking-tight">Imperial<span class="text-[#F5B82E]">OS</span></h1>
    </div>

    <!-- Form Container -->
    <div class="w-full max-w-sm sm:max-w-md">
      <!-- Header -->
      <div class="mb-10 text-center lg:text-left">
        <h2 class="text-3xl font-extrabold text-[#F8FAFC] tracking-tight mb-2">Acesse sua conta</h2>
        <p class="text-base text-[#A1A1AA] font-medium">Insira suas credenciais para entrar no Imperial OS.</p>
      </div>

      <!-- Form -->
      <form action="/" method="GET" class="space-y-6">
        
        <!-- Input Group: Usuário -->
        <div>
          <label for="username" class="block text-sm font-semibold text-[#F8FAFC] mb-2">Usuário</label>
          <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-300">
              <i data-lucide="user" class="w-5 h-5 text-[#A1A1AA] group-focus-within:text-[#D92D20]"></i>
            </div>
            <input 
              type="text" 
              id="username" 
              name="username" 
              class="block w-full pl-11 pr-4 py-3.5 bg-[#181B21] border border-[#20242C] rounded-xl text-[#F8FAFC] placeholder-[#A1A1AA] focus:bg-[#20242C] focus:outline-none focus:ring-2 focus:ring-[#D92D20]/50 focus:border-[#D92D20] transition-all duration-300 shadow-inner" 
              placeholder="Digite seu usuário" 
              required
            >
          </div>
        </div>
        
        <!-- Input Group: Senha -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <label for="password" class="block text-sm font-semibold text-[#F8FAFC]">Senha</label>
            <a href="#" class="text-sm font-semibold text-[#F5B82E] hover:text-yellow-400 hover:underline transition-colors">Esqueceu a senha?</a>
          </div>
          <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-300">
              <i data-lucide="lock" class="w-5 h-5 text-[#A1A1AA] group-focus-within:text-[#D92D20]"></i>
            </div>
            <input 
              type="password" 
              id="password" 
              name="password" 
              class="block w-full pl-11 pr-12 py-3.5 bg-[#181B21] border border-[#20242C] rounded-xl text-[#F8FAFC] placeholder-[#A1A1AA] focus:bg-[#20242C] focus:outline-none focus:ring-2 focus:ring-[#D92D20]/50 focus:border-[#D92D20] transition-all duration-300 shadow-inner" 
              placeholder="••••••••" 
              required
            >
            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#A1A1AA] hover:text-[#F8FAFC] focus:outline-none transition-colors">
              <i data-lucide="eye" id="eye-icon" class="w-5 h-5"></i>
            </button>
          </div>
        </div>

        <!-- Checkbox -->
        <div class="flex items-center pt-2">
          <label class="relative flex items-center cursor-pointer group">
            <input id="remember" name="remember" type="checkbox" class="peer sr-only">
            <div class="w-5 h-5 bg-[#181B21] border border-[#20242C] rounded peer-checked:bg-[#D92D20] peer-checked:border-[#D92D20] peer-focus:ring-2 peer-focus:ring-[#D92D20]/30 transition-all flex items-center justify-center">
              <i data-lucide="check" class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
            </div>
            <span class="ml-3 text-sm font-medium text-[#A1A1AA] group-hover:text-[#F8FAFC] transition-colors">Manter conectado</span>
          </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full flex justify-center items-center gap-2 py-4 px-4 bg-gradient-to-r from-[#D92D20] to-[#B91C1C] hover:from-[#B91C1C] hover:to-[#991b1b] text-white text-[16px] font-extrabold rounded-xl shadow-[0_4px_14px_0_rgba(217,45,32,0.39)] hover:shadow-[0_6px_20px_rgba(217,45,32,0.23)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 mt-8 border border-white/10">
          Entrar no Sistema
          <i data-lucide="log-in" class="w-5 h-5"></i>
        </button>
      </form>
    </div>
    
    <!-- Footer -->
    <div class="absolute bottom-6 w-full text-center">
      <p class="text-xs text-[#A1A1AA] font-medium">Imperial Pizza &copy; <?= date('Y') ?>. Todos os direitos reservados.</p>
    </div>
  </div>

  <!-- Right Side: Visual / Brand Area (Hidden on mobile) -->
  <div class="hidden lg:!flex lg:w-1/2 relative items-center justify-center overflow-hidden bg-[#0F1115]">
    
    <!-- Deep Brand Background -->
    <div class="absolute inset-0 bg-[#0F1115]"></div>
    <div class="absolute inset-0 bg-gradient-to-bl from-[#181B21] via-[#0F1115] to-[#0F1115]"></div>
    
    <!-- Abstract Glows (Fire & Gold) -->
    <div class="absolute -top-32 -right-32 w-[500px] h-[500px] bg-[#D92D20] rounded-full blur-[150px] mix-blend-screen opacity-20 animate-pulse"></div>
    <div class="absolute -bottom-32 -left-32 w-[500px] h-[500px] bg-[#F5B82E] rounded-full blur-[150px] mix-blend-screen opacity-10" style="animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite; animation-delay: 2s;"></div>
    
    <!-- Subtle Pattern Overlay (Dots) -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wMykiLz48L3N2Zz4=')] opacity-50"></div>

    <!-- Content -->
    <div class="relative z-10 flex flex-col items-center lg:items-start text-center lg:text-left px-12 w-full max-w-lg">
      
      <!-- Brand Icon (Crown) -->
      <div class="w-20 h-20 bg-[#181B21]/80 backdrop-blur-xl border border-[#20242C] rounded-2xl flex items-center justify-center shadow-2xl mb-8 group cursor-default hover:border-[#D92D20]/50 transition-colors duration-500">
        <div class="w-12 h-12 bg-gradient-to-br from-[#D92D20] to-[#B91C1C] rounded-xl flex items-center justify-center shadow-inner group-hover:scale-105 transition-transform duration-500">
          <i data-lucide="crown" class="w-7 h-7 text-[#F5B82E]"></i>
        </div>
      </div>
      
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#181B21] border border-[#20242C] mb-6 shadow-sm">
        <span class="flex h-2 w-2 rounded-full bg-[#D92D20] animate-pulse"></span>
        <span class="text-xs font-bold text-[#A1A1AA] uppercase tracking-wider">Imperial OS v4.0</span>
      </div>

      <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight text-[#F8FAFC] mb-6 leading-tight">
        Qualidade e Rapidez <br />
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#F5B82E] to-[#D92D20]">REAL! 👑🍕</span>
      </h1>
      
      <p class="text-lg text-[#A1A1AA] font-medium leading-relaxed max-w-md mb-12">
        O sistema operacional definitivo para alta gastronomia. Controle de comandas, agilidade no forno <i data-lucide="flame" class="inline w-4 h-4 text-[#D92D20]"></i> e gestão em tempo real.
      </p>

      <!-- Cards / Stats -->
      <div class="grid grid-cols-2 gap-4 w-full max-w-sm">
        <div class="bg-[#181B21]/60 backdrop-blur-md border border-[#20242C] rounded-2xl p-4 flex flex-col items-start hover:bg-[#20242C] transition-colors shadow-lg group">
          <i data-lucide="zap" class="w-6 h-6 text-[#F5B82E] mb-2 group-hover:scale-110 transition-transform"></i>
          <p class="text-sm font-bold text-[#F8FAFC]">Alta Velocidade</p>
          <p class="text-xs text-[#A1A1AA]">Sincronização ⚡</p>
        </div>
        <div class="bg-[#181B21]/60 backdrop-blur-md border border-[#20242C] rounded-2xl p-4 flex flex-col items-start hover:bg-[#20242C] transition-colors shadow-lg group">
          <i data-lucide="pizza" class="w-6 h-6 text-[#D92D20] mb-2 group-hover:scale-110 transition-transform"></i>
          <p class="text-sm font-bold text-[#F8FAFC]">Gestão Focada</p>
          <p class="text-xs text-[#A1A1AA]">Fluxo contínuo 🔥</p>
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
