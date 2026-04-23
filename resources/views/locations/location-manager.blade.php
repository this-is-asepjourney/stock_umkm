<div>
    <div class="p-6 bg-gray-900 min-h-screen">

        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">📍 Manajemen Lokasi</h1>
                    <p class="text-gray-400 mt-1">Kelola gudang, rak, dan bin penyimpanan</p>
                </div>
                <button wire:click="create"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    <span class="mr-2">➕</span> Tambah Lokasi
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 bg-indigo-600/20 rounded-lg">
                        <span class="text-2xl">📍</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-400 text-sm">Total Lokasi</p>
                        <p class="text-white text-2xl font-bold">{{ $statistics['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-600/20 rounded-lg">
                        <span class="text-2xl">🏭</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-400 text-sm">Gudang</p>
                        <p class="text-white text-2xl font-bold">{{ $statistics['warehouses'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 bg-emerald-600/20 rounded-lg">
                        <span class="text-2xl">📦</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-400 text-sm">Rak</p>
                        <p class="text-white text-2xl font-bold">{{ $statistics['racks'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 bg-amber-600/20 rounded-lg">
                        <span class="text-2xl">🗃️</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-400 text-sm">Bin</p>
                        <p class="text-white text-2xl font-bold">{{ $statistics['bins'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left Column - Tree View -->
            <div class="lg:col-span-1">
                <div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden">
                    <div class="p-4 border-b border-gray-700">
                        <h2 class="text-white font-semibold flex items-center">
                            <span class="mr-2">🌳</span> Struktur Lokasi
                        </h2>
                    </div>

                    <div class="p-4 max-h-96 overflow-y-auto">
                        @if($treeData->count() > 0)
                            @foreach($treeData as $root)
                                @include('livewire.locations.partials.tree-item', ['location' => $root])
                            @endforeach
                        @else
                            <p class="text-gray-400 text-center py-4">Belum ada lokasi</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Table View -->
            <div class="lg:col-span-2">

                <!-- Filters -->
                <div class="bg-gray-800 rounded-lg border border-gray-700 p-4 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-gray-400 text-sm mb-1">🔍 Cari</label>
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari lokasi..."
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-gray-400 text-sm mb-1">📋 Tipe</label>
                            <select wire:model.live="filterType"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Semua Tipe</option>
                                @foreach($typeOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-400 text-sm mb-1">📊 Per Halaman</label>
                            <select wire:model.live="perPage"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button wire:click="resetFilters"
                                class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition">
                                Reset Filter
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-900">
                                <tr>
                                    <th class="px-4 py-3 text-left text-gray-400 text-sm font-medium cursor-pointer hover:text-white"
                                        wire:click="sortBy('code')">
                                        Kode
                                        @if($sortField === 'code')
                                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                        @endif
                                    </th>
                                    <th class="px-4 py-3 text-left text-gray-400 text-sm font-medium cursor-pointer hover:text-white"
                                        wire:click="sortBy('name')">
                                        Nama
                                        @if($sortField === 'name')
                                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                        @endif
                                    </th>
                                    <th class="px-4 py-3 text-left text-gray-400 text-sm font-medium">Tipe</th>
                                    <th class="px-4 py-3 text-left text-gray-400 text-sm font-medium">Parent</th>
                                    <th class="px-4 py-3 text-left text-gray-400 text-sm font-medium">Deskripsi</th>
                                    <th class="px-4 py-3 text-right text-gray-400 text-sm font-medium">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($locations as $location)
                                    <tr class="border-t border-gray-700 hover:bg-gray-750">
                                        <td class="px-4 py-3">
                                            <span class="text-white font-mono text-sm">{{ $location->code }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <span class="text-white">{{ $location->name }}</span>
                                                @if($location->children_count > 0)
                                                    <span
                                                        class="ml-2 px-2 py-0.5 bg-gray-700 text-gray-300 text-xs rounded-full">
                                                        {{ $location->children_count }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs rounded-full text-white"
                                                style="background-color: {{ $location->type_color }}40; color: {{ $location->type_color }}">
                                                {{ $location->type_label }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-gray-300 text-sm">
                                                {{ $location->parent ? $location->parent->name : '-' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-gray-400 text-sm">
                                                {{ Str::limit($location->description, 30) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex justify-end space-x-2">
                                                <button wire:click="edit({{ $location->id }})"
                                                    class="p-1.5 text-blue-400 hover:bg-blue-600/20 rounded transition"
                                                    title="Edit">
                                                    ✏️
                                                </button>
                                                <button wire:click="confirmDelete({{ $location->id }})"
                                                    class="p-1.5 text-red-400 hover:bg-red-600/20 rounded transition"
                                                    title="Hapus">
                                                    🗑️
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                            Tidak ada data lokasi
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-4 border-t border-gray-700">
                        {{ $locations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg w-full max-w-lg border border-gray-700 shadow-xl">
                <div class="p-4 border-b border-gray-700">
                    <h2 class="text-white text-xl font-semibold">
                        {{ $isEditing ? '✏️ Edit Lokasi' : '➕ Tambah Lokasi Baru' }}
                    </h2>
                </div>

                <form wire:submit="save" class="p-4 space-y-4">
                    <!-- Tipe Lokasi -->
                    <div>
                        <label class="block text-gray-400 text-sm mb-1">Tipe Lokasi *</label>
                        <select wire:model="type"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Pilih Tipe</option>
                            @foreach($typeOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Parent Lokasi -->
                    <div>
                        <label class="block text-gray-400 text-sm mb-1">Parent Lokasi</label>
                        <select wire:model="parent_id"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Tidak Ada (Root)</option>
                            @foreach($parentOptions as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nama Lokasi -->
                    <div>
                        <label class="block text-gray-400 text-sm mb-1">Nama Lokasi *</label>
                        <input type="text" wire:model="name" placeholder="Contoh: Gudang Utama"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kode Lokasi -->
                    <div>
                        <label class="block text-gray-400 text-sm mb-1">Kode Lokasi</label>
                        <input type="text" wire:model="code" placeholder="Otomatis jika dikosongkan"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('code') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-gray-400 text-sm mb-1">Deskripsi</label>
                        <textarea wire:model="description" rows="3" placeholder="Deskripsi lokasi (opsional)"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                        @error('description') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-700">
                        <button type="button" wire:click="resetForm"
                            class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            {{ $isEditing ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal && $locationToDelete)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg w-full max-w-md border border-gray-700 shadow-xl">
                <div class="p-4 border-b border-gray-700">
                    <h2 class="text-white text-xl font-semibold">⚠️ Konfirmasi Hapus</h2>
                </div>

                <div class="p-4">
                    <p class="text-gray-300">
                        Apakah Anda yakin ingin menghapus lokasi <strong
                            class="text-white">{{ $locationToDelete->name }}</strong>?
                    </p>

                    @if($locationToDelete->hasChildren())
                        <div class="mt-4 p-3 bg-yellow-600/20 border border-yellow-600/30 rounded-lg">
                            <p class="text-yellow-400 text-sm">
                                ⚠️ Lokasi ini memiliki sub-lokasi. Harap pindahkan atau hapus sub-lokasi terlebih dahulu.
                            </p>
                        </div>
                    @endif

                    <div class="flex justify-end space-x-3 mt-6">
                        <button wire:click="$set('showDeleteModal', false)"
                            class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition">
                            Batal
                        </button>
                        <button wire:click="delete"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                            @if($locationToDelete->hasChildren()) disabled @endif>
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if(session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="fixed bottom-4 right-4 bg-emerald-600 text-white px-4 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            class="fixed bottom-4 right-4 bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif
</div>