<nav x-data="{ open: false }" class="bg-gray-900 border-b border-gray-800 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <a href="{{ route(Auth::user()->getRoleNames()->first() . '.dashboard') }}">
                    <img src="{{ asset('assets/img/icono.png') }}" alt="Logo" class="h-10 w-10">
                </a>
                <span class="text-lg font-bold text-white hidden sm:block">ProGest</span>
            </div>

            <!-- Nav links -->
            <div class="hidden sm:flex space-x-6">
                <a href="{{ route(Auth::user()->getRoleNames()->first() . '.dashboard') }}"
                   class="text-sm text-gray-300 hover:text-blue-400 font-medium transition">Dashboard</a>
            </div>

            <!-- User menu -->
            <div class="hidden sm:flex items-center space-x-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center space-x-2 text-sm text-gray-300 hover:text-white">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Perfil
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Cerrar sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile menu -->
            <div class="sm:hidden">
                <button @click="open = !open" class="text-gray-400 hover:text-white focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                              class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                              class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="sm:hidden hidden px-4 py-2 bg-gray-800 text-gray-300">
        <a href="{{ route(Auth::user()->getRoleNames()->first() . '.dashboard') }}"
           class="block py-2 hover:text-blue-400">Dashboard</a>
        <a href="{{ route('profile.edit') }}" class="block py-2 hover:text-blue-400">Perfil</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="block py-2 text-left w-full hover:text-red-400 transition">Cerrar sesión</button>
        </form>
    </div>
</nav>
