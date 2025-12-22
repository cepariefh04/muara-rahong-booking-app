<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Manajemen Booking</h1>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-500 text-sm">Total Booking</p>
            <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg shadow">
            <p class="text-gray-500 text-sm">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg shadow">
            <p class="text-gray-500 text-sm">Confirmed</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['confirmed'] }}</p>
        </div>
        <div class="bg-red-50 p-4 rounded-lg shadow">
            <p class="text-gray-500 text-sm">Cancelled</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['cancelled'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="text" wire:model.live="search" placeholder="Cari booking..."
            class="border rounded-lg px-4 py-2">
        <select wire:model.live="filterStatus" class="border rounded-lg px-4 py-2">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="cancelled">Cancelled</option>
            <option value="completed">Completed</option>
        </select>
        <input type="date" wire:model.live="filterDateFrom" placeholder="Dari tanggal"
            class="border rounded-lg px-4 py-2">
        <input type="date" wire:model.live="filterDateTo" placeholder="Sampai tanggal"
            class="border rounded-lg px-4 py-2">
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Booking</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paket</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($bookings as $booking)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $booking->booking_code }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $booking->user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $booking->package->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $booking->formatted_total_price }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $booking->booking_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {!! $booking->status_badge !!}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button wire:click="showDetail({{ $booking->id }})"
                                class="text-blue-600 hover:text-blue-900">Detail</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data booking.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $bookings->links() }}
    </div>

    <!-- Detail Modal -->
    @if ($detailModalOpen && $selectedBooking)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Detail Booking</h3>
                    <button wire:click="closeDetailModal" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>

                <div class="space-y-4">
                    <!-- Booking Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold mb-2">Informasi Booking</h4>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-gray-600">Kode Booking:</span>
                                <span class="font-medium ml-2">{{ $selectedBooking->booking_code }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Status:</span>
                                <span class="ml-2">{!! $selectedBooking->status_badge !!}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Tanggal Booking:</span>
                                <span
                                    class="font-medium ml-2">{{ $selectedBooking->booking_date->format('d M Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Quantity:</span>
                                <span class="font-medium ml-2">{{ $selectedBooking->quantity }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold mb-2">Informasi Customer</h4>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-gray-600">Nama:</span>
                                <span class="font-medium ml-2">{{ $selectedBooking->user->name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium ml-2">{{ $selectedBooking->user->email }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Telepon:</span>
                                <span class="font-medium ml-2">{{ $selectedBooking->user->phone ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Package Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold mb-2">Informasi Paket</h4>
                        <div class="text-sm">
                            <p class="font-medium">{{ $selectedBooking->package->name }}</p>
                            <p class="text-gray-600">{{ $selectedBooking->package->week_type }} -
                                {{ $selectedBooking->package->formatted_price }}</p>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    @if ($selectedBooking->payment)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold mb-2">Informasi Pembayaran</h4>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="text-gray-600">Total:</span>
                                    <span
                                        class="font-bold text-blue-600 ml-2">{{ $selectedBooking->formatted_total_price }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Status:</span>
                                    <span class="ml-2">{!! $selectedBooking->payment->status_badge !!}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Metode:</span>
                                    <span
                                        class="font-medium ml-2">{{ $selectedBooking->payment->payment_method }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Notes -->
                    @if ($selectedBooking->notes)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold mb-2">Catatan</h4>
                            <p class="text-sm text-gray-700">{{ $selectedBooking->notes }}</p>
                        </div>
                    @endif

                    <!-- Update Status -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-semibold mb-3">Update Status Booking</h4>
                        <div class="flex gap-2">
                            <button wire:click="updateStatus({{ $selectedBooking->id }}, 'confirmed')"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                                Confirm
                            </button>
                            <button wire:click="updateStatus({{ $selectedBooking->id }}, 'cancelled')"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                                Cancel
                            </button>
                            <button wire:click="updateStatus({{ $selectedBooking->id }}, 'completed')"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                Complete
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button wire:click="closeDetailModal"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
