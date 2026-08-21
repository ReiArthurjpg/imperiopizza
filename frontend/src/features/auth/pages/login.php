<style>
    <?php require_once __DIR__ . '/../styles/login.css'; ?>
</style>

    <main class="w-full flex flex-col lg:flex-row min-h-screen">
        
        <!-- ========================================================================= -->
        <!-- LADO ESQUERDO: FORMULÁRIO (Clean e Focado)                                 -->
        <!-- ========================================================================= -->
        <section class="w-full lg:w-[45%] xl:w-[40%] flex flex-col justify-center px-8 py-12 sm:px-12 md:px-20 bg-white shadow-[10px_0_30px_rgba(0,0,0,0.02)] z-10">
            
            <div class="w-full max-w-md mx-auto flex-grow flex flex-col justify-center">
                
                <div class="mb-10">
                    <!-- Logo Component -->
                    <?php require_once SRC . '/components/logo.php'; ?>
                    
                    <h1 class="text-3xl font-bold tracking-tight mb-2 text-imperial-dark">Acesse sua conta</h1>
                    <p class="text-gray-500 text-sm">Insira suas credenciais para entrar no Imperial OS.</p>
                </div>

                <!-- Form Component -->
                <?php require_once __DIR__ . '/../components/form-login.php'; ?>
            </div>
            
            <!-- Rodapé esquerdo -->
            <div class="mt-8">
                <p class="text-xs text-gray-400 font-medium">
                    Imperial Pizza &copy; <span id="currentYear"></span>
                </p>
            </div>
        </section>

        <!-- ========================================================================= -->
        <!-- LADO DIREITO: IMAGEM E TEXTO (Visual mais maduro e humano)                 -->
        <!-- ========================================================================= -->
        <section class="hidden lg:flex w-full lg:w-[55%] xl:w-[60%] relative bg-imperial-dark items-center justify-center overflow-hidden">
            
            <!-- Imagem de fundo real (Pizza/Forno) com overlay escuro -->
            <!-- Trocado para uma imagem de pizza artesanal super atrativa e premium -->
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1574071318508-1cdbab80d002?q=80&w=2069&auto=format&fit=crop" alt="Pizza Artesanal" class="w-full h-full object-cover opacity-60">
                <!-- Overlay de gradiente sutil para garantir legibilidade do texto -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#111111] via-[#111111]/70 to-[#111111]/20"></div>
            </div>

            <!-- Conteúdo textual sobre a imagem -->
            <div class="relative z-10 w-full max-w-2xl px-12 xl:px-24 flex flex-col justify-center h-full pt-40">
                
                <!-- Tag estilo "Badge" minimalista -->
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-white/10 backdrop-blur-sm border border-white/20 w-fit mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                    <span class="text-xs font-semibold text-white tracking-widest uppercase">Imperial v2.1</span>
                </div>
                
                <!-- Título Forte e Elegante -->
                <h2 class="text-5xl xl:text-6xl font-bold text-white leading-tight mb-6">
                    Controle total<br>da sua operação.
                </h2>
                
                <p class="text-lg text-gray-300 font-medium leading-relaxed max-w-lg mb-10">
                    O sistema operacional definitivo para alta gastronomia. Gestão de comandas, agilidade na produção e controle financeiro em tempo real.
                </p>

                <!-- Destaques textuais (em vez de cards complexos) -->
                <div class="flex gap-8 border-t border-white/20 pt-6">
                    <div>
                        <div class="text-white font-bold text-lg mb-1">Alta Velocidade</div>
                        <div class="text-gray-400 text-sm">Sincronização imediata.</div>
                    </div>
                    <div>
                        <div class="text-white font-bold text-lg mb-1">Gestão Focada</div>
                        <div class="text-gray-400 text-sm">Fluxo de trabalho contínuo.</div>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <script>
        <?php require_once __DIR__ . '/../scripts/login.js'; ?>
    </script>
