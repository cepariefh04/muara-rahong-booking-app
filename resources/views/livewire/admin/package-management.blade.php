<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Manajemen Paket</h1>
        <button wire:click="openCreateModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Tambah Paket
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        <input type="text" wire:model.live="search" placeholder="Cari paket..." class="border rounded-lg px-4 py-2">
        <select wire:model.live="filterWeekType" class="border rounded-lg px-4 py-2">
            <option value="">Semua Tipe</option>
            <option value="weekdays">Weekdays</option>
            <option value="weekends">Weekends</option>
        </select>
        <select wire:model.live="filterPublished" class="border rounded-lg px-4 py-2">
            <option value="">Semua Status</option>
            <option value="1">Published</option>
            <option value="0">Unpublished</option>
        </select>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kapasitas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($packages as $package)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if ($package->photo)
                                    <img src="{{ $package->photo }}" class="h-10 w-10 rounded object-cover mr-3">
                                @endif
                                <div class="text-sm font-medium text-gray-900">{{ $package->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $package->week_type }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $package->formatted_price }} / {{ $package->price_type }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $package->min_capacity }}-{{ $package->max_capacity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button wire:click="togglePublish({{ $package->id }})"
                                class="px-2 py-1 text-xs rounded-full {{ $package->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $package->is_published ? 'Published' : 'Unpublished' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button wire:click="openEditModal({{ $package->id }})"
                                class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                            <button wire:click="delete({{ $package->id }})"
                                wire:confirm="Yakin ingin menghapus paket ini?"
                                class="text-red-600 hover:text-red-900">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data paket.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $packages->links() }}
    </div>

    <!-- Modal -->
    @if ($modalOpen)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">
                        {{ $modalMode === 'create' ? 'Tambah Paket Baru' : 'Edit Paket' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Nama Paket</label>
                            <input type="text" wire:model="name" class="w-full border rounded-lg px-4 py-2">
                            @error('name')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Tipe Hari</label>
                                <select wire:model="week_type" class="w-full border rounded-lg px-4 py-2">
                                    <option value="weekdays">Weekdays</option>
                                    <option value="weekends">Weekends</option>
                                </select>
                                @error('week_type')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Tipe Harga</label>
                                <select wire:model="price_type" class="w-full border rounded-lg px-4 py-2">
                                    <option value="pack">Per Paket</option>
                                    <option value="night">Per Malam</option>
                                </select>
                                @error('price_type')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Harga</label>
                            <input type="number" wire:model="price" class="w-full border rounded-lg px-4 py-2">
                            @error('price')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Min Kapasitas</label>
                                <input type="number" wire:model="min_capacity"
                                    class="w-full border rounded-lg px-4 py-2">
                                @error('min_capacity')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Max Kapasitas</label>
                                <input type="number" wire:model="max_capacity"
                                    class="w-full border rounded-lg px-4 py-2">
                                @error('max_capacity')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Total Menginap</label>
                            <input type="text" wire:model="total_stays" placeholder="Contoh: 2 hari 1 malam"
                                class="w-full border rounded-lg px-4 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Benefit</label>
                            <div class="flex gap-2 mb-2">
                                <input type="text" wire:model="benefitInput" placeholder="Tambah benefit"
                                    class="flex-1 border rounded-lg px-4 py-2">
                                <button type="button" wire:click="addBenefit"
                                    class="bg-gray-600 text-white px-4 py-2 rounded-lg">+</button>
                            </div>
                            <ul class="space-y-1">
                                @foreach ($benefits as $index => $benefit)
                                    <li class="flex items-center justify-between bg-gray-50 px-3 py-2 rounded">
                                        <span class="text-sm">{{ $benefit }}</span>
                                        <button type="button" wire:click="removeBenefit({{ $index }})"
                                            class="text-red-600 text-sm">Hapus</button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Foto</label>
                            @if ($existingPhoto && !$photo)
                                <img src="{{ $existingPhoto }}" class="w-32 h-32 object-cover rounded mb-2">
                            @endif
                            <input type="file" wire:model="photo" accept="image/*"
                                class="w-full border rounded-lg px-4 py-2">
                            @error('photo')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="is_published" id="is_published" class="mr-2">
                            <label for="is_published" class="text-sm font-medium">Publikasikan paket ini</label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
