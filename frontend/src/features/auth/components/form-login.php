<form action="#" method="POST" class="space-y-6">
    
    <!-- Campo Usuário -->
    <div class="space-y-1.5">
        <label for="username" class="block text-sm font-semibold text-gray-700">Usuário</label>
        <div class="input-group">
            <i class="ph ph-user text-xl"></i>
            <input type="text" id="username" name="username" placeholder="Digite seu usuário" class="input-minimal" required>
        </div>
    </div>

    <!-- Campo Senha -->
    <div class="space-y-1.5">
        <div class="flex items-center justify-between">
            <label for="password" class="block text-sm font-semibold text-gray-700">Senha</label>
        </div>
        <div class="input-group">
            <i class="ph ph-lock-key text-xl"></i>
            <input type="password" id="password" name="password" placeholder="Sua senha" class="input-minimal" required>
            
            <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 px-3 flex items-center justify-center focus:outline-none" tabindex="-1">
                <i class="ph ph-eye text-xl toggle-password hover:text-gray-700 transition-colors" id="togglePasswordIcon"></i>
            </button>
        </div>
        <div class="flex justify-end mt-1">
             <a href="#" class="text-sm font-medium text-imperial-red hover:underline">Esqueceu a senha?</a>
        </div>
    </div>

    <!-- Manter conectado -->
    <div class="flex items-center pt-2">
        <input type="checkbox" id="remember" name="remember" class="checkbox-minimal">
        <label for="remember" class="ml-2.5 block text-sm font-medium text-gray-700 cursor-pointer select-none">
            Manter conectado
        </label>
    </div>

    <!-- Botão Entrar -->
    <button type="submit" class="w-full bg-imperial-red hover:bg-[#B91C1C] text-white font-semibold py-3.5 px-4 rounded-lg transition-colors duration-200 mt-8 flex justify-center items-center gap-2">
        Entrar no Sistema
        <i class="ph-bold ph-arrow-right"></i>
    </button>
</form>
