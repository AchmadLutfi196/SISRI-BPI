<!-- Sidebar -->
<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 flex flex-col"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    
    <!-- Logo Section -->
    <div class="h-16 flex items-center justify-center border-b border-gray-200 bg-white flex-shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <img src="{{ asset('images/logo.png') }}" alt="SISRI-UTM" class="h-10 w-auto">
        </a>
    </div>

    <!-- User Info -->
    <div class="p-4 border-b border-gray-200 flex-shrink-0">
        <div class="flex items-center gap-3">
            @php
                $userFotoUrl = null;
                $userInitials = 'U';
                
                if (auth()->user()->isMahasiswa() && auth()->user()->mahasiswa) {
                    $userFotoUrl = auth()->user()->mahasiswa->foto_url;
                    $userInitials = auth()->user()->mahasiswa->initials;
                } elseif ((auth()->user()->isDosen() || auth()->user()->isKoordinator()) && auth()->user()->dosen) {
                    $userFotoUrl = auth()->user()->dosen->foto_url;
                    $userInitials = auth()->user()->dosen->initials;
                } elseif (auth()->user()->isAdmin()) {
                    $userInitials = strtoupper(substr(auth()->user()->name, 0, 2));
                }
            @endphp
            <x-avatar 
                :src="$userFotoUrl" 
                :initials="$userInitials" 
                size="lg" 
            />
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-blue-700 truncate">{{ Auth::user()->name }}</p>
                @if(auth()->user()->isMahasiswa() && auth()->user()->mahasiswa)
                    <p class="text-xs text-blue-500">{{ auth()->user()->mahasiswa->nim }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->mahasiswa->prodi->nama ?? '' }}</p>
                @elseif(auth()->user()->isDosen() && auth()->user()->dosen)
                    <p class="text-xs text-blue-500">{{ auth()->user()->dosen->nidn ?? auth()->user()->dosen->nip ?? '' }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->dosen->prodi->nama ?? '' }}</p>
                @elseif(auth()->user()->isKoordinator() && auth()->user()->dosen?->activeKoordinatorProdi)
                    <p class="text-xs text-blue-500">{{ auth()->user()->dosen->nidn ?? auth()->user()->dosen->nip ?? '' }}</p>
                    <p class="text-xs text-gray-500 truncate">Koordinator Prodi</p>
                @else
                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Role Label -->
    <div class="px-4 py-2 bg-gray-50 flex-shrink-0">
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
            <x-sidebar-dropdown label="Unit" icon="building" :active="request()->routeIs('admin.unit.*')">
                <x-sidebar-dropdown-link :href="route('admin.unit.index', ['type' => 'fakultas'])" :active="request()->routeIs('admin.unit.*') && request('type') === 'fakultas'">
                    Fakultas
                </x-sidebar-dropdown-link>
                <x-sidebar-dropdown-link :href="route('admin.unit.index', ['type' => 'prodi'])" :active="request()->routeIs('admin.unit.*') && request('type') === 'prodi'">
                    Program Studi
                </x-sidebar-dropdown-link>
            </x-sidebar-dropdown>
            <x-sidebar-link :href="route('admin.koordinator.index')" :active="request()->routeIs('admin.koordinator.*')" icon="badge">
                Koordinator Prodi
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
            <x-sidebar-dropdown label="Jadwal Ujian" icon="calendar" :active="request()->routeIs('dosen.jadwal-ujian.*')">
                <x-sidebar-dropdown-link :href="route('dosen.jadwal-ujian.sempro')" :active="request()->routeIs('dosen.jadwal-ujian.sempro')">
                    Seminar Proposal
                </x-sidebar-dropdown-link>
                <x-sidebar-dropdown-link :href="route('dosen.jadwal-ujian.sidang')" :active="request()->routeIs('dosen.jadwal-ujian.sidang')">
                    Sidang Skripsi
                </x-sidebar-dropdown-link>
            </x-sidebar-dropdown>
            <x-sidebar-dropdown label="Nilai" icon="chart" :active="request()->routeIs('dosen.nilai-sempro.*') || request()->routeIs('dosen.nilai-sidang.*')">
                <x-sidebar-dropdown-link :href="route('dosen.nilai-sempro.index')" :active="request()->routeIs('dosen.nilai-sempro.*')">
                    Nilai Sempro
                </x-sidebar-dropdown-link>
                <x-sidebar-dropdown-link :href="route('dosen.nilai-sidang.index')" :active="request()->routeIs('dosen.nilai-sidang.*')">
                    Nilai Sidang
                </x-sidebar-dropdown-link>
            </x-sidebar-dropdown>
        @endif

        {{-- Koordinator Menu --}}
        @if(auth()->user()->isKoordinator())
            <x-sidebar-link :href="route('koordinator.dashboard')" :active="request()->routeIs('koordinator.dashboard')" icon="home">
                Beranda
            </x-sidebar-link>
            
            {{-- Fitur Koordinator --}}
            <div class="px-4 py-2 mt-2">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Koordinator</span>
            </div>
            <x-sidebar-link :href="route('koordinator.bidang-minat.index')" :active="request()->routeIs('koordinator.bidang-minat.*')" icon="tag">
                Bidang Minat
            </x-sidebar-link>
            
            {{-- Pendaftaran Sempro/Sidang yang perlu diproses --}}
            <x-sidebar-dropdown label="Pendaftaran" icon="clipboard" :active="request()->routeIs('koordinator.pendaftaran.*')">
                <x-sidebar-dropdown-link :href="route('koordinator.pendaftaran.index', ['jenis' => 'sempro'])" :active="request()->routeIs('koordinator.pendaftaran.*') && request('jenis') === 'sempro'">
                    Pendaftaran Sempro
                </x-sidebar-dropdown-link>
                <x-sidebar-dropdown-link :href="route('koordinator.pendaftaran.index', ['jenis' => 'sidang'])" :active="request()->routeIs('koordinator.pendaftaran.*') && request('jenis') === 'sidang'">
                    Pendaftaran Sidang
                </x-sidebar-dropdown-link>
            </x-sidebar-dropdown>
            
            {{-- Jadwal Pelaksanaan Sempro/Sidang --}}
            <x-sidebar-dropdown label="Penjadwalan" icon="calendar" :active="request()->routeIs('koordinator.penjadwalan.*')">
                <x-sidebar-dropdown-link :href="route('koordinator.penjadwalan.index', ['jenis' => 'sempro'])" :active="request()->routeIs('koordinator.penjadwalan.*') && request('jenis') === 'sempro'">
                    Jadwal Sempro
                </x-sidebar-dropdown-link>
                <x-sidebar-dropdown-link :href="route('koordinator.penjadwalan.index', ['jenis' => 'sidang'])" :active="request()->routeIs('koordinator.penjadwalan.*') && request('jenis') === 'sidang'">
                    Jadwal Sidang
                </x-sidebar-dropdown-link>
            </x-sidebar-dropdown>
            
            <x-sidebar-dropdown label="Daftar Nilai" icon="chart" :active="request()->routeIs('koordinator.daftar-nilai.*')">
                <x-sidebar-dropdown-link :href="route('koordinator.daftar-nilai.index', ['jenis' => 'sempro'])" :active="request()->routeIs('koordinator.daftar-nilai.*') && request('jenis') === 'sempro'">
                    Nilai Sempro
                </x-sidebar-dropdown-link>
                <x-sidebar-dropdown-link :href="route('koordinator.daftar-nilai.index', ['jenis' => 'sidang'])" :active="request()->routeIs('koordinator.daftar-nilai.*') && request('jenis') === 'sidang'">
                    Nilai Sidang
                </x-sidebar-dropdown-link>
            </x-sidebar-dropdown>

            {{-- Fitur Dosen (karena koordinator juga dosen) --}}
            <div class="px-4 py-2 mt-4">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Dosen</span>
            </div>
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
            <x-sidebar-dropdown label="Jadwal Ujian" icon="calendar" :active="request()->routeIs('dosen.jadwal-ujian.*')">
                <x-sidebar-dropdown-link :href="route('dosen.jadwal-ujian.sempro')" :active="request()->routeIs('dosen.jadwal-ujian.sempro')">
                    Seminar Proposal
                </x-sidebar-dropdown-link>
                <x-sidebar-dropdown-link :href="route('dosen.jadwal-ujian.sidang')" :active="request()->routeIs('dosen.jadwal-ujian.sidang')">
                    Sidang Skripsi
                </x-sidebar-dropdown-link>
            </x-sidebar-dropdown>
            <x-sidebar-dropdown label="Nilai" icon="chart" :active="request()->routeIs('dosen.nilai-sempro.*') || request()->routeIs('dosen.nilai-sidang.*')">
                <x-sidebar-dropdown-link :href="route('dosen.nilai-sempro.index')" :active="request()->routeIs('dosen.nilai-sempro.*')">
                    Nilai Sempro
                </x-sidebar-dropdown-link>
                <x-sidebar-dropdown-link :href="route('dosen.nilai-sidang.index')" :active="request()->routeIs('dosen.nilai-sidang.*')">
                    Nilai Sidang
                </x-sidebar-dropdown-link>
            </x-sidebar-dropdown>
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
