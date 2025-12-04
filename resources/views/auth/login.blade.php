<x-guest-layout>
    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col lg:flex-row">
        <!-- Left Side - Branding & Illustration -->
        <div class="lg:w-1/2 bg-gradient-to-br from-blue-50 to-blue-100 p-8 flex flex-col items-center justify-center relative">
            <!-- Logo Header -->
            <div class="flex items-center justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="SISRI-UTM" class="h-16 w-auto">
            </div>
            
            <!-- Title Text -->
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-blue-800">Sistem Informasi Skripsi</h2>
                <h3 class="text-lg font-semibold text-blue-700">Fakultas Teknik</h3>
                <p class="text-blue-600 text-sm">Universitas Trunojoyo Madura</p>
            </div>

            <!-- Illustration -->
            <div class="relative w-full max-w-sm">
                <img src="{{ asset('images/college campus-rafiki.svg') }}" alt="Campus Illustration" class="w-full h-auto">
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="lg:w-1/2 p-8 flex flex-col justify-center">
            <!-- SIAKAD Button -->
            <div class="mb-6">
                <a href="#" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-3 rounded-lg font-semibold transition-colors shadow-md">
                    SIAKAD UTM
                </a>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Username/Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Masukkan email">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Masukkan password">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Forgot Password -->
                <div class="mb-6">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition-colors shadow-md">
                    Login
                </button>

                <!-- Remember Me (hidden but functional) -->
                <input type="hidden" name="remember" value="1">
            </form>

            <!-- Demo Accounts Info -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                <p class="text-xs font-semibold text-blue-800 mb-2">🔑 Akun Demo:</p>
                <div class="text-xs text-blue-700 space-y-1">
                    <p><span class="font-medium">Admin:</span> admin@sisri.test</p>
                    <p><span class="font-medium">Mahasiswa:</span> budi@sisri.test</p>
                    <p><span class="font-medium">Dosen:</span> agus@sisri.test</p>
                    <p><span class="font-medium">Koordinator:</span> rina@sisri.test</p>
                    <p class="mt-2 pt-2 border-t border-blue-200">Password: <code class="bg-blue-100 px-1.5 py-0.5 rounded font-mono">password</code></p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
