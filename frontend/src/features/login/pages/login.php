<div class="flex min-h-screen w-full bg-white">
  
  <!-- Left Side: Visual / Brand Area (Hidden on mobile) -->
  <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-[#173F69] via-[#0d243c] to-[#0a1b2d] items-center justify-center p-12">
    <!-- Decorate Circles -->
    <div class="absolute top-[-10%] left-[-10%] w-[40rem] h-[40rem] bg-[#B5120B] rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-pulse"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40rem] h-[40rem] bg-[#1F6FB2] rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
    
    <div class="relative z-10 w-full max-w-lg text-white">
      <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mb-8 border border-white/20 shadow-xl">
        <i data-lucide="chef-hat" class="w-10 h-10 text-white"></i>
      </div>
      
      <h1 class="text-4xl lg:text-5xl font-bold tracking-tight mb-6 leading-tight">
        Operação Integrada <br />
        <span class="text-[#FCA5A5]">Imperial Pizza</span>
      </h1>
      
      <p class="text-lg text-blue-100/80 mb-12 font-light leading-relaxed max-w-md">
        Controle de comandas, monitoramento de cozinha em tempo real e gestão de equipe em um único lugar.
      </p>

      <!-- Glassmorphism Testimonial/Stats Card -->
      <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-2xl">
        <div class="flex items-center gap-4 mb-4">
          <div class="w-12 h-12 rounded-full bg-gradient-to-r from-[#B5120B] to-[#E53E3E] flex items-center justify-center text-sm font-bold shadow-inner">
            v4
          </div>
          <div>
            <p class="text-sm font-medium text-white">Sistema Atualizado</p>
            <p class="text-xs text-blue-200">Versão 4.0 Integrada</p>
          </div>
        </div>
        <p class="text-sm text-blue-50/80 italic">"Desempenho máximo para operações de alto volume."</p>
      </div>
    </div>
  </div>

  <!-- Right Side: Login Form -->
  <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12 lg:p-24 bg-[#F7F7F5] lg:bg-white relative">
    
    <!-- Mobile Logo (Visible only on mobile) -->
    <div class="lg:hidden w-16 h-16 bg-[#FDECEB] text-[#B5120B] rounded-2xl flex items-center justify-center mb-8 shadow-sm">
      <i data-lucide="chef-hat" class="w-8 h-8"></i>
    </div>

    <div class="w-full max-w-md">
      <div class="mb-10 text-center lg:text-left">
        <h2 class="text-3xl font-bold text-[#171717] tracking-tight mb-2">Acesse sua conta</h2>
        <p class="text-sm text-[#737373]">Insira suas credenciais para entrar no painel.</p>
      </div>

      <form action="/" method="GET" class="space-y-6">
        
        <!-- Input Group: Usuário -->
        <div class="group">
          <label for="username" class="block text-sm font-semibold text-[#171717] mb-2 group-focus-within:text-[#B5120B] transition-colors">Usuário</label>
          <div class="relative flex items-center">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#B5120B] transition-colors">
              <i data-lucide="user" class="w-5 h-5"></i>
            </div>
            <input 
              type="text" 
              id="username" 
              name="username" 
              class="block w-full pl-12 pr-4 py-3.5 bg-white lg:bg-gray-50/50 border border-gray-200 rounded-xl text-[15px] text-[#171717] placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#B5120B]/10 focus:border-[#B5120B] transition-all duration-300 hover:border-gray-300 shadow-sm" 
              placeholder="Digite seu usuário" 
              required
            >
          </div>
        </div>
        
        <!-- Input Group: Senha -->
        <div class="group">
          <div class="flex items-center justify-between mb-2">
            <label for="password" class="block text-sm font-semibold text-[#171717] group-focus-within:text-[#B5120B] transition-colors">Senha</label>
            <a href="#" class="text-sm font-medium text-[#173F69] hover:text-[#B5120B] transition-colors">Esqueceu a senha?</a>
          </div>
          <div class="relative flex items-center">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#B5120B] transition-colors">
              <i data-lucide="lock" class="w-5 h-5"></i>
            </div>
            <input 
              type="password" 
              id="password" 
              name="password" 
              class="block w-full pl-12 pr-12 py-3.5 bg-white lg:bg-gray-50/50 border border-gray-200 rounded-xl text-[15px] text-[#171717] placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#B5120B]/10 focus:border-[#B5120B] transition-all duration-300 hover:border-gray-300 shadow-sm" 
              placeholder="••••••••" 
              required
            >
            <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
              <i data-lucide="eye" class="w-5 h-5"></i>
            </button>
          </div>
        </div>

        <!-- Checkbox -->
        <div class="flex items-center pt-2">
          <div class="relative flex items-center cursor-pointer">
            <input id="remember" type="checkbox" class="peer sr-only">
            <div class="w-5 h-5 bg-white border-2 border-gray-300 rounded peer-checked:bg-[#B5120B] peer-checked:border-[#B5120B] peer-focus:ring-4 peer-focus:ring-[#B5120B]/20 transition-all flex items-center justify-center">
              <i data-lucide="check" class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
            </div>
          </div>
          <label for="remember" class="ml-3 text-sm font-medium text-[#737373] cursor-pointer hover:text-[#171717] transition-colors">Manter conectado</label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="group relative w-full flex justify-center items-center gap-3 py-4 px-4 bg-gradient-to-r from-[#B5120B] to-[#9a0f09] hover:from-[#9a0f09] hover:to-[#7a0c07] text-white text-[15px] font-bold rounded-xl shadow-[0_8px_20px_rgba(181,18,11,0.25)] hover:shadow-[0_12px_25px_rgba(181,18,11,0.35)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 overflow-hidden mt-8">
          <span class="relative z-10 flex items-center gap-2">
            Entrar no Painel
            <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
          </span>
          <div class="absolute inset-0 h-full w-full bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
        </button>
      </form>
      
      <!-- Footer -->
      <div class="mt-12 text-center">
        <p class="text-xs text-gray-400 font-medium">Imperial Pizza &copy; <?= date('Y') ?>. Todos os direitos reservados.</p>
      </div>
    </div>
  </div>
</div>
