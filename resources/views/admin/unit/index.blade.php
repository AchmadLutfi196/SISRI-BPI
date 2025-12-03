<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                @php
                    $title = match($currentType ?? null) {
                        'fakultas' => 'Kelola Fakultas',
                        'prodi' => 'Kelola Program Studi',
                        default => 'Kelola Unit',
                    };
                    $subtitle = match($currentType ?? null) {
                        'fakultas' => 'Daftar fakultas',
                        'prodi' => 'Daftar program studi',
                        default => 'Daftar fakultas, jurusan, dan program studi',
                    };
                @endphp
                <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
            </div>
                <a href="{{ route('admin.unit.create', ['type' => $currentType]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah {{ $currentType === 'fakultas' ? 'Fakultas' : ($currentType === 'prodi' ? 'Prodi' : 'Unit') }}
                </a>
            </div>

        <!-- Flash Message -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($units as $index => $unit)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $units->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-mono text-gray-900">{{ $unit->kode }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $unit->nama }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $typeColors = [
                                                'fakultas' => 'bg-red-100 text-red-800',
                                                'jurusan' => 'bg-blue-100 text-blue-800',
                                                'prodi' => 'bg-green-100 text-green-800',
                                            ];
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $typeColors[$unit->type] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($unit->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            {{ $unit->parent->nama ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.unit.edit', $unit) }}" class="text-yellow-600 hover:text-yellow-900">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.unit.destroy', $unit) }}" method="POST" class="inline" id="delete-unit-{{ $unit->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('delete-unit-{{ $unit->id }}', '{{ $unit->nama }}')" class="text-red-600 hover:text-red-900">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada data unit
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($units->hasPages())
                    <div class="mt-6">
                        {{ $units->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
