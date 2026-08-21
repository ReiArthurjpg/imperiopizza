<div class="flex min-h-screen w-full bg-white font-sans">
  
  <!-- Left Side: Login Form -->
  <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12 lg:p-24 relative bg-white">
    
    <!-- Mobile Brand (Visible only on mobile) -->
    <div class="lg:hidden flex flex-col items-center mb-10">
      <div class="w-16 h-16 bg-gradient-to-br from-[#B5120B] to-[#9a0f09] rounded-2xl flex items-center justify-center shadow-lg mb-4">
        <i data-lucide="chef-hat" class="w-8 h-8 text-white"></i>
      </div>
      <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Imperial Pizza</h1>
    </div>

    <!-- Form Container -->
    <div class="w-full max-w-sm sm:max-w-md bg-white">
      <!-- Header -->
      <div class="mb-10 text-center lg:text-left">
        <h2 class="text-3xl font-bold text-gray-900 tracking-tight mb-2">Acesse sua conta</h2>
        <p class="text-base text-gray-500">Insira suas credenciais para entrar no painel.</p>
      </div>

      <!-- Form -->
      <form action="/" method="GET" class="space-y-6">
        
        <!-- Input Group: Usuário -->
        <div>
          <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Usuário</label>
          <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-300">
              <i data-lucide="user" class="w-5 h-5 text-gray-400 group-focus-within:text-[#B5120B]"></i>
            </div>
            <input 
              type="text" 
              id="username" 
              name="username" 
              class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#B5120B]/20 focus:border-[#B5120B] transition-all duration-300" 
              placeholder="Digite seu usuário" 
              required
            >
          </div>
        </div>
        
        <!-- Input Group: Senha -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <label for="password" class="block text-sm font-semibold text-gray-700">Senha</label>
            <a href="#" class="text-sm font-medium text-[#B5120B] hover:text-[#9a0f09] hover:underline transition-colors">Esqueceu a senha?</a>
          </div>
          <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-300">
              <i data-lucide="lock" class="w-5 h-5 text-gray-400 group-focus-within:text-[#B5120B]"></i>
            </div>
            <input 
              type="password" 
              id="password" 
              name="password" 
              class="block w-full pl-11 pr-12 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#B5120B]/20 focus:border-[#B5120B] transition-all duration-300" 
              placeholder="••••••••" 
              required
            >
            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#B5120B] focus:outline-none transition-colors">
              <i data-lucide="eye" id="eye-icon" class="w-5 h-5"></i>
            </button>
          </div>
        </div>

        <!-- Checkbox -->
        <div class="flex items-center pt-2">
          <label class="relative flex items-center cursor-pointer group">
            <input id="remember" name="remember" type="checkbox" class="peer sr-only">
            <div class="w-5 h-5 bg-gray-50 border border-gray-300 rounded peer-checked:bg-[#B5120B] peer-checked:border-[#B5120B] peer-focus:ring-2 peer-focus:ring-[#B5120B]/20 transition-all flex items-center justify-center">
              <i data-lucide="check" class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
            </div>
            <span class="ml-3 text-sm font-medium text-gray-600 group-hover:text-gray-900 transition-colors">Manter conectado</span>
          </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full flex justify-center items-center gap-2 py-4 px-4 bg-[#B5120B] hover:bg-[#9a0f09] text-white text-[16px] font-bold rounded-xl shadow-[0_4px_14px_0_rgba(181,18,11,0.39)] hover:shadow-[0_6px_20px_rgba(181,18,11,0.23)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 mt-8">
          Entrar
          <i data-lucide="log-in" class="w-5 h-5"></i>
        </button>
      </form>
    </div>
    
    <!-- Footer -->
    <div class="absolute bottom-6 w-full text-center">
      <p class="text-xs text-gray-400 font-medium">Imperial Pizza &copy; <?= date('Y') ?>. Todos os direitos reservados.</p>
    </div>
  </div>

  <!-- Right Side: Visual / Brand Area (Hidden on mobile) -->
  <div class="hidden lg:!flex lg:w-1/2 relative items-center justify-center overflow-hidden">
    <!-- Rich Background -->
    <div class="absolute inset-0 bg-gradient-to-bl from-[#173F69] via-[#0d243c] to-[#0a1b2d]"></div>
    
    <!-- Abstract Glows -->
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-[#B5120B] rounded-full blur-[128px] mix-blend-screen opacity-30 animate-pulse"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-[#1F6FB2] rounded-full blur-[128px] mix-blend-screen opacity-20" style="animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite; animation-delay: 2s;"></div>
    
    <!-- Subtle Pattern Overlay -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] opacity-30"></div>

    <!-- Content -->
    <div class="relative z-10 flex flex-col items-center lg:items-start text-center lg:text-left px-12 w-full max-w-lg">
      
      <!-- Brand Icon -->
      <div class="w-20 h-20 bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl flex items-center justify-center shadow-2xl mb-8">
        <div class="w-12 h-12 bg-gradient-to-br from-[#B5120B] to-[#9a0f09] rounded-xl flex items-center justify-center shadow-inner">
          <i data-lucide="chef-hat" class="w-7 h-7 text-white"></i>
        </div>
      </div>
      
      <h1 class="text-4xl lg:text-5xl font-bold tracking-tight text-white mb-6 leading-tight">
        Operação Integrada <br />
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FCA5A5] to-[#B5120B]">Imperial Pizza</span>
      </h1>
      
      <p class="text-lg text-blue-100/80 font-light leading-relaxed max-w-md mb-12">
        Controle de comandas, monitoramento de cozinha em tempo real e gestão de equipe em um único lugar.
      </p>

      <!-- Glassmorphism Badge -->
      <div class="inline-flex items-center gap-4 bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-4 shadow-xl">
        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#B5120B] to-[#9a0f09] flex items-center justify-center text-sm font-bold text-white shadow-inner">
          v4
        </div>
        <div class="text-left">
          <p class="text-sm font-semibold text-white">Sistema Atualizado</p>
          <p class="text-xs text-blue-200/80">Desempenho máximo para alto volume</p>
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
