<!-- Sidebar -->
<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    
    <!-- Logo Section -->
    <div class="h-16 flex items-center justify-center border-b border-gray-200 bg-gradient-to-r from-yellow-400 to-yellow-500">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow">
                <svg class="w-6 h-6 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
            </div>
            <div>
                <span class="text-xl font-bold">
                    <span class="text-white">SI</span><span class="text-blue-800">SRI</span>
                </span>
                <p class="text-xs text-blue-800 font-medium -mt-1">FT UTM</p>
            </div>
        </a>
    </div>

    <!-- User Info -->
    <div class="p-4 border-b border-gray-200">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-blue-700 truncate">{{ Auth::user()->name }}</p>
                @if(auth()->user()->isMahasiswa() && auth()->user()->mahasiswa)
                    <p class="text-xs text-blue-500">{{ auth()->user()->mahasiswa->nim }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->mahasiswa->prodi->nama ?? '' }}</p>
                @elseif(auth()->user()->isDosen() && auth()->user()->dosen)
                    <p class="text-xs text-blue-500">{{ auth()->user()->dosen->nidn ?? auth()->user()->dosen->nip ?? '' }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->dosen->prodi->nama ?? '' }}</p>
                @elseif(auth()->user()->isKoordinator() && auth()->user()->koordinatorProdi)
                    <p class="text-xs text-gray-500 truncate">Koordinator Prodi</p>
                @else
                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Role Label -->
    <div class="px-4 py-2 bg-gray-50">
        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
            {{ strtoupper(auth()->user()->role) }}
        </span>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto py-4">
        {{-- Admin Menu --}}
        @if(auth()->user()->isAdmin())
            <x-sidebar-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" icon="home">
                Beranda
            </x-sidebar-link>
            <x-sidebar-link :href="route('admin.mahasiswa.index')" :active="request()->routeIs('admin.mahasiswa.*')" icon="users">
                Mahasiswa
            </x-sidebar-link>
            <x-sidebar-link :href="route('admin.dosen.index')" :active="request()->routeIs('admin.dosen.*')" icon="academic">
                Dosen
            </x-sidebar-link>
            <x-sidebar-link :href="route('admin.periode.index')" :active="request()->routeIs('admin.periode.*')" icon="calendar">
                Periode
            </x-sidebar-link>
            <x-sidebar-link :href="route('admin.unit.index')" :active="request()->routeIs('admin.unit.*')" icon="building">
                Unit
            </x-sidebar-link>
        @endif

        {{-- Mahasiswa Menu --}}
        @if(auth()->user()->isMahasiswa())
            <x-sidebar-link :href="route('mahasiswa.dashboard')" :active="request()->routeIs('mahasiswa.dashboard')" icon="home">
                Beranda
            </x-sidebar-link>
            <x-sidebar-dropdown label="Proposal" icon="document" :active="request()->routeIs('mahasiswa.topik.*')">
                <x-sidebar-dropdown-link :href="route('mahasiswa.topik.index')" :active="request()->routeIs('mahasiswa.topik.index')">
                    Topik Skripsi
                </x-sidebar-dropdown-link>
                <x-sidebar-dropdown-link :href="route('mahasiswa.bimbingan.index', ['jenis' => 'proposal'])" :active="request()->routeIs('mahasiswa.bimbingan.*') && request('jenis') === 'proposal'">
                    Bimbingan Proposal
                </x-sidebar-dropdown-link>
            </x-sidebar-dropdown>
            <x-sidebar-dropdown label="Skripsi" icon="book" :active="request()->routeIs('mahasiswa.bimbingan.*') && request('jenis') === 'skripsi'">
                <x-sidebar-dropdown-link :href="route('mahasiswa.bimbingan.index', ['jenis' => 'skripsi'])" :active="request()->routeIs('mahasiswa.bimbingan.*') && request('jenis') === 'skripsi'">
                    Bimbingan Skripsi
                </x-sidebar-dropdown-link>
            </x-sidebar-dropdown>
            <x-sidebar-link :href="route('mahasiswa.sidang.index')" :active="request()->routeIs('mahasiswa.sidang.*')" icon="presentation">
                Sidang
            </x-sidebar-link>
        @endif

        {{-- Dosen Menu --}}
        @if(auth()->user()->isDosen())
            <x-sidebar-link :href="route('dosen.dashboard')" :active="request()->routeIs('dosen.dashboard')" icon="home">
                Beranda
            </x-sidebar-link>
            <x-sidebar-dropdown label="Proposal" icon="document" :active="request()->routeIs('dosen.validasi-usulan.*') || (request()->routeIs('dosen.bimbingan.*') && request('jenis') === 'proposal') || (request()->routeIs('dosen.persetujuan-sidang.*') && request('jenis') === 'proposal')">
                <x-sidebar-dropdown-link :href="route('dosen.validasi-usulan.index')" :active="request()->routeIs('dosen.validasi-usulan.*')">
                    Validasi Usulan
                </x-sidebar-dropdown-link>
                <x-sidebar-dropdown-link :href="route('dosen.bimbingan.index', ['jenis' => 'proposal'])" :active="request()->routeIs('dosen.bimbingan.*') && request('jenis') === 'proposal'">
                    Bimbingan Proposal
                </x-sidebar-dropdown-link>
                <x-sidebar-dropdown-link :href="route('dosen.persetujuan-sidang.index', ['jenis' => 'proposal'])" :active="request()->routeIs('dosen.persetujuan-sidang.*') && request('jenis') === 'proposal'">
                    Persetujuan Seminar
                </x-sidebar-dropdown-link>
            </x-sidebar-dropdown>
            <x-sidebar-dropdown label="Skripsi" icon="book" :active="(request()->routeIs('dosen.bimbingan.*') && request('jenis') === 'skripsi') || (request()->routeIs('dosen.persetujuan-sidang.*') && request('jenis') === 'skripsi')">
                <x-sidebar-dropdown-link :href="route('dosen.bimbingan.index', ['jenis' => 'skripsi'])" :active="request()->routeIs('dosen.bimbingan.*') && request('jenis') === 'skripsi'">
                    Bimbingan Skripsi
                </x-sidebar-dropdown-link>
                <x-sidebar-dropdown-link :href="route('dosen.persetujuan-sidang.index', ['jenis' => 'skripsi'])" :active="request()->routeIs('dosen.persetujuan-sidang.*') && request('jenis') === 'skripsi'">
                    Persetujuan Sidang
                </x-sidebar-dropdown-link>
            </x-sidebar-dropdown>
            <x-sidebar-dropdown label="Nilai" icon="chart" :active="request()->routeIs('dosen.nilai.*')">
                <x-sidebar-dropdown-link :href="route('dosen.nilai.index')" :active="request()->routeIs('dosen.nilai.*')">
                    Penilaian Sidang
                </x-sidebar-dropdown-link>
            </x-sidebar-dropdown>
        @endif

        {{-- Koordinator Menu --}}
        @if(auth()->user()->isKoordinator())
            <x-sidebar-link :href="route('koordinator.dashboard')" :active="request()->routeIs('koordinator.dashboard')" icon="home">
                Beranda
            </x-sidebar-link>
            <x-sidebar-link :href="route('koordinator.bidang-minat.index')" :active="request()->routeIs('koordinator.bidang-minat.*')" icon="tag">
                Bidang Minat
            </x-sidebar-link>
            <x-sidebar-link :href="route('koordinator.penjadwalan.index')" :active="request()->routeIs('koordinator.penjadwalan.*')" icon="calendar">
                Penjadwalan
            </x-sidebar-link>
            <x-sidebar-link :href="route('koordinator.daftar-nilai.index')" :active="request()->routeIs('koordinator.daftar-nilai.*')" icon="chart">
                Daftar Nilai
            </x-sidebar-link>
        @endif
    </nav>
</aside>

<!-- Overlay for mobile (shows when sidebar is open on small screens) -->
<div x-show="sidebarOpen" 
     @click="sidebarOpen = false" 
     class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
     x-cloak
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
</div>
