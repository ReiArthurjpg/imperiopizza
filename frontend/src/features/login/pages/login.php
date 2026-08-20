<div class="flex-1 flex min-h-full items-center justify-center p-4 sm:p-8">
  <div class="w-full max-w-md bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-[#E7E7E7] overflow-hidden flex flex-col">
    <!-- Header Area -->
    <div class="pt-10 px-8 pb-6 flex flex-col items-center text-center">
      <div class="w-16 h-16 bg-[#FDECEB] text-[#B5120B] rounded-2xl flex items-center justify-center mb-6 shadow-sm rotate-3">
        <i data-lucide="chef-hat" class="w-8 h-8 -rotate-3"></i>
      </div>
      <h1 class="text-2xl font-bold text-[#171717] tracking-tight">Bem-vindo(a) de volta</h1>
      <p class="text-sm text-[#737373] mt-2">Faça login para acessar o painel de operações da Imperial Pizza.</p>
    </div>

    <!-- Form Area -->
    <div class="px-8 pb-10">
      <form action="/" method="GET" class="space-y-5">
        <div>
          <label for="username" class="block text-sm font-medium text-[#171717] mb-1.5">Usuário</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
              <i data-lucide="user" class="w-4 h-4"></i>
            </div>
            <input type="text" id="username" name="username" class="block w-full pl-10 pr-4 py-2.5 bg-gray-50/50 border border-[#E7E7E7] rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#B5120B]/20 focus:border-[#B5120B] transition-all duration-200" placeholder="Digite seu usuário" required>
          </div>
        </div>
        
        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label for="password" class="block text-sm font-medium text-[#171717]">Senha</label>
            <a href="#" class="text-[13px] font-medium text-[#173F69] hover:text-[#1F6FB2] hover:underline transition-all">Esqueceu a senha?</a>
          </div>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
              <i data-lucide="lock" class="w-4 h-4"></i>
            </div>
            <input type="password" id="password" name="password" class="block w-full pl-10 pr-10 py-2.5 bg-gray-50/50 border border-[#E7E7E7] rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#B5120B]/20 focus:border-[#B5120B] transition-all duration-200" placeholder="••••••••" required>
            <button type="button" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
              <i data-lucide="eye" class="w-4 h-4"></i>
            </button>
          </div>
        </div>

        <div class="flex items-center mt-2 mb-6">
          <input id="remember" type="checkbox" class="w-4 h-4 text-[#B5120B] bg-gray-100 border-gray-300 rounded focus:ring-[#B5120B] focus:ring-2 accent-[#B5120B] cursor-pointer">
          <label for="remember" class="ml-2 text-sm text-[#737373] cursor-pointer">Lembrar de mim</label>
        </div>

        <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 bg-[#B5120B] hover:bg-[#9a0f09] text-white text-sm font-semibold rounded-xl shadow-[0_4px_10px_rgba(181,18,11,0.2)] hover:shadow-[0_6px_15px_rgba(181,18,11,0.3)] active:scale-[0.98] transition-all duration-200">
          Entrar no sistema
          <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>
      </form>
    </div>
    
    <!-- Footer Area -->
    <div class="bg-gray-50 py-4 px-8 border-t border-[#E7E7E7] flex justify-center items-center">
      <p class="text-xs font-medium text-gray-500">Imperial Pizza &copy; <?= date('Y') ?></p>
    </div>
  </div>
</div>
