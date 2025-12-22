<div class="max-w-6xl mx-auto p-6">
    <!-- Progress Bar -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            @for ($i = 1; $i <= $totalSteps; $i++)
                <div class="flex items-center {{ $i < $totalSteps ? 'flex-1' : '' }}">
                    <div class="relative flex flex-col items-center">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center {{ $currentStep >= $i ? 'bg-green-600 text-white' : 'bg-gray-300 text-gray-600' }}">
                            {{ $i }}
                        </div>
                        <span class="absolute top-12 text-xs whitespace-nowrap">
                            @if ($i == 1)
                                Pilih Paket
                            @elseif($i == 2)
                                Login/Register
                            @elseif($i == 3)
                                Konfirmasi
                            @else
                                Pembayaran
                            @endif
                        </span>
                    </div>
                    @if ($i < $totalSteps)
                        <div class="flex-1 h-1 mx-2 {{ $currentStep > $i ? 'bg-green-600' : 'bg-gray-300' }}"></div>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    <!-- Step 1: Package Selection -->
    @if ($currentStep == 1)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-6">Pilih Paket</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($packages as $package)
                    <div class="border rounded-lg overflow-hidden hover:shadow-lg transition">
                        @if ($package->photo)
                            <img src="{{ $package->photo }}" alt="{{ $package->name }}"
                                class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="text-xl font-semibold mb-2">{{ $package->name }}</h3>
                            <p class="text-gray-600 mb-2">{{ $package->week_type }}</p>
                            <p class="text-2xl font-bold text-green-600 mb-3">
                                {{ $package->formatted_price }} <span
                                    class="text-sm text-gray-600">{{ $package->price_type_text }}</span>
                            </p>
                            @if ($package->benefits)
                                <ul class="mb-4 space-y-1">
                                    @foreach ($package->benefits as $benefit)
                                        <li class="text-sm text-gray-700">✓ {{ $benefit }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            <button wire:click="selectPackage({{ $package->id }})"
                                class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                                Pilih Paket
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Step 2: Login/Register -->
    @if ($currentStep == 2)
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md mx-auto">
            <h2 class="text-2xl font-bold mb-6">{{ $isLogin ? 'Login' : 'Registrasi' }} Akun</h2>

            @if (!$isLogin)
                <!-- Registration Form -->
                <form wire:submit.prevent="register">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Nama Lengkap</label>
                        <input type="text" wire:model="name" class="w-full border rounded-lg px-4 py-2">
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Email</label>
                        <input type="email" wire:model="email" class="w-full border rounded-lg px-4 py-2">
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">No. Telepon</label>
                        <input type="text" wire:model="phone" class="w-full border rounded-lg px-4 py-2">
                        @error('phone')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Password</label>
                        <input type="password" wire:model="password" class="w-full border rounded-lg px-4 py-2">
                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2">Konfirmasi Password</label>
                        <input type="password" wire:model="password_confirmation"
                            class="w-full border rounded-lg px-4 py-2">
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                        <span wire:loading.remove>Daftar</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </form>
            @else
                <!-- Login Form -->
                <form wire:submit.prevent="login">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Email</label>
                        <input type="email" wire:model="email" wire:key="log-email"
                            class="w-full border rounded-lg px-4 py-2">
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Password</label>
                        <input type="password" wire:model="password" wire:key="log-password"
                            class="w-full border rounded-lg px-4 py-2">
                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                        <span wire:loading.remove>Login</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </form>
            @endif

            <div class="mt-4 text-center">
                <button wire:click="toggleLoginMode" class="text-green-600 hover:underline">
                    {{ $isLogin ? 'Belum punya akun? Daftar' : 'Sudah punya akun? Login' }}
                </button>
            </div>

            <div class="mt-4">
                <button wire:click="previousStep" class="text-gray-600 hover:underline">
                    ← Kembali ke Pilih Paket
                </button>
            </div>
        </div>
    @endif

    <!-- Step 3: Booking Confirmation -->
    @if ($currentStep == 3 && $selectedPackage)
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl mx-auto">
            <h2 class="text-2xl font-bold mb-6">Konfirmasi Booking</h2>

            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="font-semibold mb-2">Detail Paket:</h3>
                <p class="text-lg">{{ $selectedPackage->name }}</p>
                <p class="text-gray-600">{{ $selectedPackage->week_type }}</p>
                <p class="text-xl font-bold text-green-600 mt-2">{{ $selectedPackage->formatted_price }}
                    {{ $selectedPackage->price_type_text }}</p>
            </div>

            <form wire:submit.prevent="confirmBooking">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">
                        Jumlah {{ $selectedPackage->price_type == 'pack' ? 'Orang' : 'Malam' }}
                    </label>
                    <input type="number" wire:model.live="quantity" min="1"
                        class="w-full border rounded-lg px-4 py-2">
                    @error('quantity')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                @if ($selectedPackage->price_type == 'night')
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Tanggal Mulai</label>
                            <input type="date" wire:model="startDate" class="w-full border rounded-lg px-4 py-2">
                            @error('startDate')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Tanggal Selesai</label>
                            <input type="date" wire:model="endDate" class="w-full border rounded-lg px-4 py-2">
                            @error('endDate')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endif

                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2">Catatan (Opsional)</label>
                    <textarea wire:model="notes" rows="3" class="w-full border rounded-lg px-4 py-2"></textarea>
                </div>

                <div class="mb-6 p-4 bg-green-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold">Total Harga:</span>
                        <span class="text-2xl font-bold text-green-600">
                            Rp {{ number_format($selectedPackage->price * $quantity, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700">
                    Lanjut ke Pembayaran
                </button>
            </form>
        </div>
    @endif

    <!-- Step 4: Payment -->
    @if ($currentStep == 4 && $booking)
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl mx-auto">
            <h2 class="text-2xl font-bold mb-6">Pembayaran</h2>

            @if (session()->has('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
                <p class="text-green-800 font-semibold">✓ Booking Berhasil Dibuat!</p>
                <p class="text-sm text-green-700 mt-1">Kode Booking:
                    <span class="font-bold">{{ $booking->booking_code }}</span>
                </p>
            </div>

            <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                <h3 class="font-semibold mb-2">Informasi Transfer:</h3>
                <div class="bg-white p-3 rounded border mb-3">
                    <p class="font-semibold text-green-800">Bank BCA</p>
                    <p class="text-xl font-bold">1234567890</p>
                    <p class="text-sm text-gray-600">a.n. PT Booking System</p>
                </div>
                <p class="text-xs text-gray-500">Status:
                    <span
                        class="px-2 py-0.5 rounded {{ $booking->payment->status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ strtoupper($booking->payment->status) }}
                    </span>
                </p>
            </div>

            <div class="mb-6 p-6 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50">
                <h3 class="font-bold text-gray-800 mb-4 text-center">Upload Bukti Transfer</h3>

                <form wire:submit.prevent="uploadProof">
                    <div class="mb-4 flex justify-center">
                        @if ($proofPayment)
                            <div class="relative">
                                <img src="{{ $proofPayment->temporaryUrl() }}"
                                    class="w-48 h-48 object-cover rounded-lg shadow-md border-4 border-white">
                                <button type="button" wire:click="$set('proofPayment', null)"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow hover:bg-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @else
                            <div
                                class="w-48 h-48 bg-gray-200 rounded-lg flex items-center justify-center border-2 border-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <input type="file" wire:model="proofPayment" id="upload-{{ $booking->id }}"
                            class="hidden">
                        <label for="upload-{{ $booking->id }}"
                            class="cursor-pointer block w-full text-center py-2 px-4 border border-green-600 text-green-600 rounded-lg hover:bg-green-50 transition">
                            {{ $proofPayment ? 'Ganti Gambar' : 'Pilih Foto Bukti Transfer' }}
                        </label>
                        @error('proofPayment')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full bg-green-600 text-white py-2 rounded-lg font-bold hover:bg-green-700 transition disabled:opacity-50">
                        <span wire:loading.remove wire:target="uploadProof">Upload Bukti Pembayaran</span>
                        <span wire:loading wire:target="uploadProof">Sedang Mengunggah...</span>
                    </button>
                </form>
            </div>

            <a href="{{ route('user.bookings') }}"
                class="block w-full border bg-green-600 border-gray-300 text-white py-3 rounded-lg text-center hover:bg-green-700 transition">
                Lihat Daftar Booking Saya
            </a>
        </div>
    @endif
</div>
