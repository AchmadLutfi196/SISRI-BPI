<!-- Top Navigation Bar -->
<header class="bg-white shadow-sm border-b border-gray-200 h-16 flex items-center justify-between px-6">
    <!-- Left side: Toggle button -->
    <div class="flex items-center">
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <!-- Right side: User menu -->
    <div class="flex items-center gap-4">
        <!-- Fullscreen button -->
        <button onclick="toggleFullscreen()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
            </svg>
        </button>

        <!-- User dropdown -->
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="flex items-center gap-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                    @php
                        $userFotoUrl = null;
                        $userInitials = 'U';
                        
                        if (auth()->user()->isMahasiswa() && auth()->user()->mahasiswa) {
                            $userFotoUrl = auth()->user()->mahasiswa->foto_url;
                            $userInitials = auth()->user()->mahasiswa->initials ?? strtoupper(substr(auth()->user()->name, 0, 2));
                        } elseif ((auth()->user()->isDosen() || auth()->user()->isKoordinator()) && auth()->user()->dosen) {
                            $userFotoUrl = auth()->user()->dosen->foto_url;
                            $userInitials = auth()->user()->dosen->initials ?? strtoupper(substr(auth()->user()->name, 0, 2));
                        } else {
                            $userInitials = strtoupper(substr(auth()->user()->name, 0, 2));
                        }
                    @endphp
                    
                    @if($userFotoUrl)
                        <img src="{{ $userFotoUrl }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover ring-2 ring-yellow-500">
                    @else
                        <div class="w-8 h-8 rounded-full bg-yellow-500 text-white flex items-center justify-center font-semibold text-sm ring-2 ring-yellow-600">
                            {{ $userInitials }}
                        </div>
                    @endif
                </button>
            </x-slot>

            <x-slot name="content">
                <div class="px-4 py-2 border-b border-gray-100">
                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                </div>
                
                <x-dropdown-link :href="route('profile.edit')">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    {{ __('Profile') }}
                </x-dropdown-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <x-dropdown-link href="#"
                            onclick="event.preventDefault(); confirmLogout('logout-form');">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>

<script>
function toggleFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
}
</script>
