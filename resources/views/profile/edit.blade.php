<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Pengaturan Profil</h1>

        <div class="space-y-6">
            <!-- Photo Upload Section -->
            @if(!auth()->user()->isAdmin())
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Foto Profil</h2>
                
                <div class="flex items-center gap-6">
                    @php
                        $userFotoUrl = null;
                        $userInitials = 'U';
                        
                        if (auth()->user()->isMahasiswa() && auth()->user()->mahasiswa) {
                            $userFotoUrl = auth()->user()->mahasiswa->foto_url;
                            $userInitials = auth()->user()->mahasiswa->initials;
                        } elseif ((auth()->user()->isDosen() || auth()->user()->isKoordinator()) && auth()->user()->dosen) {
                            $userFotoUrl = auth()->user()->dosen->foto_url;
                            $userInitials = auth()->user()->dosen->initials;
                        }
                    @endphp
                    
                    <x-avatar 
                        :src="$userFotoUrl" 
                        :initials="$userInitials" 
                        size="2xl" 
                    />
                    
                    <div class="flex-1">
                        <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Foto Baru
                                </label>
                                <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/jpg"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                <p class="mt-1 text-xs text-gray-500">JPG, JPEG, atau PNG. Maksimal 2MB.</p>
                                @error('foto')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    Upload Foto
                                </button>
                                @if($userFotoUrl)
                                    <button type="button" onclick="document.getElementById('delete-photo-form').submit()" 
                                            class="px-4 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200 transition-colors">
                                        Hapus Foto
                                    </button>
                                @endif
                            </div>
                        </form>
                        
                        <form id="delete-photo-form" action="{{ route('profile.photo.delete') }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                        
                        @if (session('status') === 'photo-updated')
                            <p class="text-sm text-green-600 mt-2">Foto profil berhasil diperbarui.</p>
                        @endif
                        
                        @if (session('status') === 'photo-deleted')
                            <p class="text-sm text-green-600 mt-2">Foto profil berhasil dihapus.</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="p-6 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
