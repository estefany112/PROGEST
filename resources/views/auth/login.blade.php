<x-guest-layout>

    <div class="w-full max-w-md bg-white rounded-xl shadow-xl overflow-hidden">
        <!-- Imagen dentro de la tarjeta -->
        <div class="h-36 bg-cover bg-center" style="background-image: url('{{ asset('assets/img/img-login.png') }}');">
            <div class="flex items-center justify-center h-full bg-black/30">
                <h2 class="text-2xl font-bold text-white">INICIAR SESIÓN</h2>
            </div>
        </div>

        <!-- Formulario -->
        <div class="p-8">
            <x-auth-session-status class="mb-4 text-sm text-green-600" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold mb-1">Correo Electrónico</label>
                    <input type="email" id="email" name="email" placeholder="Ingrese Correo Electrónico" required autofocus
                        class="w-full border-b border-gray-300 focus:outline-none focus:border-gray-600 text-sm py-1.5" />
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold mb-1">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Ingrese Contraseña" required
                        class="w-full border-b border-gray-300 focus:outline-none focus:border-gray-600 text-sm py-1.5" />
                </div>

                <div class="flex items-center justify-between text-sm mt-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="remember" class="mr-2 border-gray-300 rounded">
                        Recordarme
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-gray-500 hover:underline">Olvidate tu contraseña?</a>
                    @endif
                </div>

                <div class="mt-4">
                    <button type="submit"
                        class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-full transition">
                        Login
                    </button>
                </div>
                <p class="mt-4 text-sm text-center">
                    ¿No tienes cuenta?
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Regístrate aquí</a>
                </p>

            </form>
        </div>
    </div>

</x-guest-layout>



