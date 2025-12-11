<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <img src="{{ asset('images/logo.png') }}" alt="SISRI-UTM" class="h-9 w-auto">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    {{-- Admin Menu --}}
                    @if(auth()->user()->isAdmin())
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.mahasiswa.index')" :active="request()->routeIs('admin.mahasiswa.*')">
                            {{ __('Mahasiswa') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.dosen.index')" :active="request()->routeIs('admin.dosen.*')">
                            {{ __('Dosen') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.periode.index')" :active="request()->routeIs('admin.periode.*')">
                            {{ __('Periode') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.unit.index')" :active="request()->routeIs('admin.unit.*')">
                            {{ __('Unit') }}
                        </x-nav-link>
                    @endif

                    {{-- Mahasiswa Menu --}}
                    @if(auth()->user()->isMahasiswa())
                        <x-nav-link :href="route('mahasiswa.dashboard')" :active="request()->routeIs('mahasiswa.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('mahasiswa.topik.index')" :active="request()->routeIs('mahasiswa.topik.*')">
                            {{ __('Topik Skripsi') }}
                        </x-nav-link>
                        <x-nav-link :href="route('mahasiswa.bimbingan.index')" :active="request()->routeIs('mahasiswa.bimbingan.*')">
                            {{ __('Bimbingan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('mahasiswa.sidang.index')" :active="request()->routeIs('mahasiswa.sidang.*')">
                            {{ __('Sidang') }}
                        </x-nav-link>
                        <x-nav-link :href="route('mahasiswa.revisi.index')" :active="request()->routeIs('mahasiswa.revisi.*')">
                            {{ __('Revisi') }}
                        </x-nav-link>
                    @endif

                    {{-- Dosen Menu --}}
                    @if(auth()->user()->isDosen())
                        <x-nav-link :href="route('dosen.dashboard')" :active="request()->routeIs('dosen.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('dosen.validasi-usulan.index')" :active="request()->routeIs('dosen.validasi-usulan.*')">
                            {{ __('Validasi Usulan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('dosen.bimbingan.index')" :active="request()->routeIs('dosen.bimbingan.*')">
                            {{ __('Bimbingan') }}
                        </x-nav-link>
                        
                        {{-- Dropdown Jadwal Ujian --}}
                        <div class="hidden sm:flex sm:items-center" x-data="{ open: false }">
                            <div class="relative">
                                <button @click="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out
                                    {{ request()->routeIs('dosen.jadwal-ujian.*') ? 'border-indigo-400 text-gray-900 focus:border-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300' }}">
                                    {{ __('Jadwal Ujian') }}
                                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1">
                                        <a href="{{ route('dosen.jadwal-ujian.sempro') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dosen.jadwal-ujian.sempro') ? 'bg-gray-100' : '' }}">
                                            Seminar Proposal
                                        </a>
                                        <a href="{{ route('dosen.jadwal-ujian.sidang') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dosen.jadwal-ujian.sidang') ? 'bg-gray-100' : '' }}">
                                            Sidang Skripsi
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <x-nav-link :href="route('dosen.nilai-sempro.index')" :active="request()->routeIs('dosen.nilai-sempro.*')">
                            {{ __('Nilai Sempro') }}
                        </x-nav-link>
                        <x-nav-link :href="route('dosen.nilai-sidang.index')" :active="request()->routeIs('dosen.nilai-sidang.*')">
                            {{ __('Nilai Sidang') }}
                        </x-nav-link>
                        <x-nav-link :href="route('dosen.persetujuan-sidang.index')" :active="request()->routeIs('dosen.persetujuan-sidang.*')">
                            {{ __('Persetujuan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('dosen.validasi-revisi.index')" :active="request()->routeIs('dosen.validasi-revisi.*')">
                            {{ __('Validasi Revisi') }}
                        </x-nav-link>
                    @endif

                    {{-- Koordinator Menu --}}
                    @if(auth()->user()->isKoordinator())
                        <x-nav-link :href="route('koordinator.dashboard')" :active="request()->routeIs('koordinator.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('koordinator.bidang-minat.index')" :active="request()->routeIs('koordinator.bidang-minat.*')">
                            {{ __('Bidang Minat') }}
                        </x-nav-link>
                        <x-nav-link :href="route('koordinator.penjadwalan.index')" :active="request()->routeIs('koordinator.penjadwalan.*')">
                            {{ __('Penjadwalan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('koordinator.daftar-nilai.index')" :active="request()->routeIs('koordinator.daftar-nilai.*')">
                            {{ __('Daftar Nilai') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                {{-- Role Badge --}}
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    @if(auth()->user()->isAdmin()) bg-red-100 text-red-800
                    @elseif(auth()->user()->isMahasiswa()) bg-blue-100 text-blue-800
                    @elseif(auth()->user()->isDosen()) bg-green-100 text-green-800
                    @elseif(auth()->user()->isKoordinator()) bg-purple-100 text-purple-800
                    @endif mr-3">
                    {{ ucfirst(auth()->user()->role) }}
                </span>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            {{-- Admin Responsive Menu --}}
            @if(auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.mahasiswa.index')" :active="request()->routeIs('admin.mahasiswa.*')">
                    {{ __('Mahasiswa') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.dosen.index')" :active="request()->routeIs('admin.dosen.*')">
                    {{ __('Dosen') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.periode.index')" :active="request()->routeIs('admin.periode.*')">
                    {{ __('Periode') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.unit.index')" :active="request()->routeIs('admin.unit.*')">
                    {{ __('Unit') }}
                </x-responsive-nav-link>
            @endif

            {{-- Mahasiswa Responsive Menu --}}
            @if(auth()->user()->isMahasiswa())
                <x-responsive-nav-link :href="route('mahasiswa.dashboard')" :active="request()->routeIs('mahasiswa.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('mahasiswa.topik.index')" :active="request()->routeIs('mahasiswa.topik.*')">
                    {{ __('Topik Skripsi') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('mahasiswa.bimbingan.index')" :active="request()->routeIs('mahasiswa.bimbingan.*')">
                    {{ __('Bimbingan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('mahasiswa.sidang.index')" :active="request()->routeIs('mahasiswa.sidang.*')">
                    {{ __('Sidang') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('mahasiswa.revisi.index')" :active="request()->routeIs('mahasiswa.revisi.*')">
                    {{ __('Revisi') }}
                </x-responsive-nav-link>
            @endif

            {{-- Dosen Responsive Menu --}}
            @if(auth()->user()->isDosen())
                <x-responsive-nav-link :href="route('dosen.dashboard')" :active="request()->routeIs('dosen.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dosen.validasi-usulan.index')" :active="request()->routeIs('dosen.validasi-usulan.*')">
                    {{ __('Validasi Usulan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dosen.bimbingan.index')" :active="request()->routeIs('dosen.bimbingan.*')">
                    {{ __('Bimbingan') }}
                </x-responsive-nav-link>
                
                {{-- Jadwal Ujian Sub Menu --}}
                <div class="pl-4 border-l-2 border-gray-200 ml-4">
                    <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase">Jadwal Ujian</p>
                    <x-responsive-nav-link :href="route('dosen.jadwal-ujian.sempro')" :active="request()->routeIs('dosen.jadwal-ujian.sempro')">
                        {{ __('Seminar Proposal') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('dosen.jadwal-ujian.sidang')" :active="request()->routeIs('dosen.jadwal-ujian.sidang')">
                        {{ __('Sidang Skripsi') }}
                    </x-responsive-nav-link>
                </div>
                
                <x-responsive-nav-link :href="route('dosen.nilai-sempro.index')" :active="request()->routeIs('dosen.nilai-sempro.*')">
                    {{ __('Nilai Sempro') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dosen.nilai-sidang.index')" :active="request()->routeIs('dosen.nilai-sidang.*')">
                    {{ __('Nilai Sidang') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dosen.persetujuan-sidang.index')" :active="request()->routeIs('dosen.persetujuan-sidang.*')">
                    {{ __('Persetujuan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dosen.validasi-revisi.index')" :active="request()->routeIs('dosen.validasi-revisi.*')">
                    {{ __('Validasi Revisi') }}
                </x-responsive-nav-link>
            @endif

            {{-- Koordinator Responsive Menu --}}
            @if(auth()->user()->isKoordinator())
                <x-responsive-nav-link :href="route('koordinator.dashboard')" :active="request()->routeIs('koordinator.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('koordinator.bidang-minat.index')" :active="request()->routeIs('koordinator.bidang-minat.*')">
                    {{ __('Bidang Minat') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('koordinator.penjadwalan.index')" :active="request()->routeIs('koordinator.penjadwalan.*')">
                    {{ __('Penjadwalan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('koordinator.daftar-nilai.index')" :active="request()->routeIs('koordinator.daftar-nilai.*')">
                    {{ __('Daftar Nilai') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1
                    @if(auth()->user()->isAdmin()) bg-red-100 text-red-800
                    @elseif(auth()->user()->isMahasiswa()) bg-blue-100 text-blue-800
                    @elseif(auth()->user()->isDosen()) bg-green-100 text-green-800
                    @elseif(auth()->user()->isKoordinator()) bg-purple-100 text-purple-800
                    @endif">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
