<x-guest-layout>
    <div class="w-full max-w-md bg-white rounded-xl shadow-xl overflow-hidden">
        <!-- Imagen superior -->
        <div class="h-36 bg-cover bg-center" style="background-image: url('{{ asset('assets/img/img-login.png') }}');">
            <div class="flex items-center justify-center h-full bg-black/30">
                <h2 class="text-2xl font-bold text-white">REGISTRARSE</h2>
            </div>
        </div>

        <!-- Formulario -->
        <div class="p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Nombre -->
                <div>
                    <label for="name" class="block text-sm font-semibold mb-1">Nombre Completo</label>
                    <input type="text" id="name" name="name" placeholder="Ingrese su nombre completo" value="{{ old('name') }}" required autofocus
                        class="w-full border-b border-gray-300 focus:outline-none focus:border-gray-600 text-sm py-1.5" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500 text-xs" />
                </div>

                <!-- Correo -->
                <div>
                    <label for="email" class="block text-sm font-semibold mb-1">Correo Electrónico</label>
                    <input type="email" id="email" name="email" placeholder="Ingrese su correo electrónico" value="{{ old('email') }}" required
                        class="w-full border-b border-gray-300 focus:outline-none focus:border-gray-600 text-sm py-1.5" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs" />
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="block text-sm font-semibold mb-1">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Ingrese una contraseña" required
                        class="w-full border-b border-gray-300 focus:outline-none focus:border-gray-600 text-sm py-1.5" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs" />
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold mb-1">Confirmar Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repita la contraseña" required
                        class="w-full border-b border-gray-300 focus:outline-none focus:border-gray-600 text-sm py-1.5" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-xs" />
                </div>

                <!-- Botón -->
                <div class="mt-4">
                    <button type="submit"
                        class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-full transition">
                        Registrarse
                    </button>
                </div>

                <!-- Enlace a login -->
                <p class="mt-4 text-sm text-center">
                    ¿Ya tienes una cuenta?
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Inicia sesión aquí</a>
                </p>
            </form>
        </div>
    </div>
</x-guest-layout>
