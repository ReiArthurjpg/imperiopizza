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

    <!-- Botão Entrar -->
    <button type="submit" class="btn-submit mt-8">
        Entrar no Sistema
        <i class="ph-bold ph-arrow-right"></i>
    </button>

    <!-- Divisor "Ou" -->
    <div class="flex items-center my-5">
        <div class="flex-grow border-t border-gray-200"></div>
        <span class="mx-4 text-sm text-gray-400 font-medium select-none">— Ou —</span>
        <div class="flex-grow border-t border-gray-200"></div>
    </div>

    <!-- Botão Continuar com Google -->
    <button type="button" class="btn-google">
        <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
        </svg>
        <span>Continuar com Google</span>
    </button>
</form>
