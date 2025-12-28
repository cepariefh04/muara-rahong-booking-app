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
                Pilih Tanggal
              @elseif($i == 3)
                Login/Register
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
      <h2 class="text-2xl font-bold mb-6 text-center border-b pb-4">Pilih Paket Wisata</h2>

      {{-- Loop Utama: Kategori Paket --}}
      @foreach ($groupedPackages as $categoryTitle => $packages)
        <div class="mb-10 last:mb-0">

          {{-- Judul Kategori (Sticky header agar rapi saat scroll) --}}
          <h3 class="text-xl font-bold text-gray-800 mb-4 px-2 border-l-4 border-green-600 bg-gray-50 py-2">
            {{ $categoryTitle }}
          </h3>

          {{-- Grid Paket --}}
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($packages as $package)
              <div
                class="border rounded-lg overflow-hidden hover:shadow-lg transition flex flex-col h-full bg-white group">

                {{-- Gambar --}}
                <div class="relative overflow-hidden h-48">
                  @if ($package->photo)
                    <img src="{{ $package->photo }}" alt="{{ $package->name }}"
                      class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                  @else
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                      <span class="text-gray-400">No Image</span>
                    </div>
                  @endif

                  {{-- Badge Tipe Hari (Opsional, untuk memperjelas) --}}
                  <div class="absolute top-2 right-2">
                    <span
                      class="px-2 py-1 text-xs font-bold rounded shadow
                     {{ $package->week_type == 'weekends' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }}">
                      {{ $package->week_type == 'weekends' ? 'Weekend' : 'Weekday' }}
                    </span>
                  </div>
                </div>

                <div class="p-4 flex flex-col flex-grow">
                  <h3 class="text-xl font-semibold mb-1">{{ $package->name }}</h3>

                  {{-- Harga --}}
                  <p class="text-2xl font-bold text-green-600 mb-3">
                    {{ $package->formatted_price }}
                    <span class="text-sm text-gray-500 font-normal">/ {{ $package->price_type_text }}</span>
                  </p>

                  {{-- Benefits List --}}
                  <div class="flex-grow">
                    @if ($package->benefits)
                      <ul class="mb-4 space-y-1">
                        @foreach (array_slice($package->benefits, 0, 4) as $benefit)
                          <li class="text-sm text-gray-600 flex items-start">
                            <span class="text-green-500 mr-2">✓</span> {{ $benefit }}
                          </li>
                        @endforeach
                        @if (count($package->benefits) > 4)
                          <li class="text-xs text-gray-400 italic pl-5">+ {{ count($package->benefits) - 4 }} fasilitas
                            lainnya</li>
                        @endif
                      </ul>
                    @endif
                  </div>

                  {{-- Button --}}
                  <button wire:click="selectPackage({{ $package->id }})" wire:loading.attr="disabled"
                    class="w-full mt-auto bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition duration-200 transform active:scale-95 flex justify-center items-center">
                    <span wire:loading.remove wire:target="selectPackage({{ $package->id }})">
                      Pilih Paket
                    </span>
                    <span wire:loading wire:target="selectPackage({{ $package->id }})">
                      <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                          stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                      </svg>
                    </span>
                  </button>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endforeach

      {{-- Pesan jika tidak ada paket sama sekali --}}
      @if (empty($groupedPackages))
        <div class="text-center py-10 text-gray-500">
          <p>Belum ada paket wisata yang tersedia saat ini.</p>
        </div>
      @endif
    </div>
  @endif

  <!-- Step 2: Booking Confirmation -->
  @if ($currentStep == 2 && $selectedPackage)
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl mx-auto">
      <h2 class="text-2xl font-bold mb-6">Konfirmasi Booking</h2>

      <div class="mb-6 p-4 bg-gray-50 rounded-lg">
        <h3 class="font-semibold mb-2">Detail Paket:</h3>
        <p class="text-lg font-semibold">{{ $selectedPackage->name }}</p>
        <div class="mt-2 space-y-1 text-sm text-gray-600">
          <p>
            <span class="font-medium">Tipe Hari:</span>
            <span class="capitalize">
              {{ $selectedPackage->week_type === 'weekends' ? 'Akhir Pekan (Sabtu-Minggu)' : 'Hari Kerja (Senin-Jumat)' }}
            </span>
          </p>
          @if ($selectedPackage->price_type === 'night')
            <p><span class="font-medium">Kapasitas Maksimal:</span> {{ $selectedPackage->max_capacity }} orang</p>
          @else
            <p><span class="font-medium">Kapasitas:</span> {{ $selectedPackage->min_capacity }} -
              {{ $selectedPackage->max_capacity }} orang</p>
          @endif
          @if ($selectedPackage->total_stays)
            <p><span class="font-medium">Durasi:</span> {{ $selectedPackage->total_stays }}</p>
          @endif
        </div>
        <p class="text-xl font-bold text-green-600 mt-3">
          {{ $selectedPackage->formatted_price }}
          <span class="text-sm text-gray-600">{{ $selectedPackage->price_type_text }}</span>
        </p>
      </div>

      <form wire:submit.prevent="confirmBooking">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Tanggal Check-in <span class="text-red-500">*</span>
            </label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-green-500 transition-colors" fill="none"
                  viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <input type="date" wire:model.live="checkInDate" min="{{ date('Y-m-d') }}"
                class="w-full pl-10 pr-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all @error('checkInDate') border-red-500 @enderror"
                required>
            </div>
            @error('checkInDate')
              <span class="text-red-500 text-xs block mt-1 font-medium">{{ $message }}</span>
            @enderror
            <div wire:loading wire:target="checkInDate" class="text-[10px] text-blue-500 mt-1 animate-pulse">
              ⌛ Mengecek ketersediaan...
            </div>
          </div>

          @if ($selectedPackage->price_type === 'night')
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Tanggal Check-out <span class="text-red-500">*</span>
              </label>
              <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400 group-focus-within:text-green-500 transition-colors" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <input type="date" wire:model.live="checkOutDate"
                  min="{{ $checkInDate ? date('Y-m-d', strtotime($checkInDate . ' +1 day')) : date('Y-m-d', strtotime('+1 day')) }}"
                  class="w-full pl-10 pr-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all disabled:bg-gray-50 disabled:text-gray-400 disabled:cursor-not-allowed"
                  required @if (!$checkInDate || $errors->has('checkInDate')) disabled @endif>
              </div>

              @if (!$checkInDate || $errors->has('checkInDate'))
                <p class="text-[10px] text-gray-400 mt-1 italic font-medium">Pilih check-in dulu</p>
              @endif

              @error('checkOutDate')
                <span class="text-red-500 text-xs block mt-1 font-medium">{{ $message }}</span>
              @enderror

              @if ($totalNights > 0 && !$errors->has('checkInDate'))
                <div
                  class="inline-flex items-center mt-2 px-2 py-1 bg-green-50 text-green-700 rounded-md text-xs font-bold border border-green-100">
                  ✨ {{ $totalNights }} Malam
                </div>
              @endif
            </div>
          @endif
        </div>

        <div class="mb-4">
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            Jumlah Tamu <span class="text-red-500">*</span>
          </label>
          <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400 group-focus-within:text-green-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </div>
            <input type="number" wire:model.live="quantity"
              min="{{ $selectedPackage->price_type === 'pack' ? $selectedPackage->min_capacity : 1 }}"
              class="w-full pl-10 pr-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-green-500 outline-none transition-all @error('quantity') border-red-500 @enderror"
              required>
          </div>
          @error('quantity')
            <span class="text-red-500 text-sm font-semibold block mt-1">{{ $message }}</span>
          @enderror

          <p class="text-xs text-gray-500 mt-1">
            @if ($selectedPackage->price_type === 'pack')
              Minimal {{ $selectedPackage->min_capacity }} orang, maksimal {{ $selectedPackage->max_capacity }} orang
            @else
              Maksimal {{ $selectedPackage->max_capacity }} orang
            @endif
          </p>
        </div>

        <div class="mb-6">
          <label class="block text-sm font-medium mb-2">Catatan (Opsional)</label>
          <textarea wire:model="notes" rows="3"
            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none transition-all"
            placeholder="Tambahkan permintaan khusus atau informasi tambahan..."></textarea>
        </div>

        <div
          class="mb-6 p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg border border-green-200 relative overflow-hidden">

          <div wire:loading wire:target="checkInDate, checkOutDate, quantity"
            class="absolute inset-0 bg-white/70 backdrop-blur-sm z-10 flex items-center justify-center">
            <div class="flex items-center space-x-2 text-green-600 font-semibold">
              <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                  stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
              </svg>
              <span>Menghitung...</span>
            </div>
          </div>

          <h4 class="font-semibold text-gray-700 mb-3">Ringkasan Pembayaran</h4>

          @if ($selectedPackage->price_type === 'night')
            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-600">Harga per malam:</span>
                <span class="font-medium">{{ $selectedPackage->formatted_price }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Jumlah malam:</span>
                <span class="font-medium">{{ $totalNights }} malam</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Jumlah tamu:</span>
                <span class="font-medium">{{ $quantity }} orang</span>
              </div>
              <div class="border-t border-green-300 pt-2 mt-2 flex justify-between items-center">
                <span class="font-semibold text-gray-800">Total Harga:</span>
                <span class="text-2xl font-bold text-green-600">
                  Rp {{ number_format($selectedPackage->price * $totalNights, 0, ',', '.') }}
                </span>
              </div>
            </div>
          @else
            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-600">Harga paket:</span>
                <span class="font-medium">{{ $selectedPackage->formatted_price }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Jumlah tamu:</span>
                <span class="font-medium">{{ $quantity }} orang</span>
              </div>
              <div class="border-t border-green-300 pt-2 mt-2 flex justify-between items-center">
                <span class="font-semibold text-gray-800">Total Harga:</span>
                <span class="text-2xl font-bold text-green-600">
                  {{ $selectedPackage->formatted_price }}
                </span>
              </div>
            </div>
          @endif

          <p class="text-xs text-gray-600 mt-3">
            <svg class="w-4 h-4 inline-block" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                clip-rule="evenodd" />
            </svg>
            Harga sudah termasuk pajak dan layanan
          </p>
        </div>

        <div class="flex gap-3">
          <button type="button" wire:click="previousStep"
            class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg hover:bg-gray-300 transition">
            ← Kembali
          </button>

          <button type="submit"
            @if (
                $errors->any() ||
                    empty($checkInDate) ||
                    empty($quantity) ||
                    ($selectedPackage->price_type === 'night' && empty($checkOutDate))) disabled
            class="flex-1 bg-gray-400 text-white py-3 rounded-lg cursor-not-allowed font-semibold opacity-70"
          @else
            class="flex-1 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-semibold" @endif>
            Lanjut ke Login/Register →
          </button>
        </div>
      </form>
    </div>
  @endif

  <!-- Step 3: Login/Register -->
  @if ($currentStep == 3)
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
            <input type="password" wire:model="password_confirmation" class="w-full border rounded-lg px-4 py-2">
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

  <!-- Step 4: Payment -->
  @if ($currentStep == 4 && $booking)
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden max-w-2xl mx-auto border border-gray-100">
      <div class="bg-green-600 p-6 text-white text-center">
        <h2 class="text-2xl font-bold">Konfirmasi Pembayaran</h2>
        <p class="opacity-90">Selesaikan pesanan Anda untuk #{{ $booking->booking_code }}</p>
      </div>

      <div class="p-8">
        <div class="mb-8">
          <label class="block text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider">Pilih Metode
            Pembayaran</label>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <label
              class="relative flex flex-col p-4 border-2 rounded-xl cursor-pointer transition-all {{ $paymentMethod === 'manual_transfer' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300' }}">
              <input type="radio" wire:model.live="paymentMethod" value="manual_transfer" class="hidden">
              <span class="font-bold text-gray-800">Transfer Bank</span>
              <span class="text-xs text-gray-500 mt-1 italic">Konfirmasi instan via upload bukti.</span>
            </label>

            <label
              class="relative flex flex-col p-4 border-2 rounded-xl cursor-pointer transition-all {{ $paymentMethod === 'on_arrival' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300' }}">
              <input type="radio" wire:model.live="paymentMethod" value="on_arrival" class="hidden">
              <span class="font-bold text-gray-800">Bayar di Tempat</span>
              <span class="text-xs text-gray-500 mt-1 italic">Bayar tunai saat hari-H.</span>
            </label>
          </div>
        </div>

        @if ($paymentMethod === 'manual_transfer')
          <div class="animate-fadeIn">
            <div class="bg-gray-50 p-6 rounded-2xl border mb-6">
              <h3 class="font-semibold text-gray-800 mb-4">Informasi Rekening Transfer:</h3>
              <div class="bg-white p-4 rounded-xl border-l-4 border-green-500 shadow-sm">
                <p class="text-xs text-gray-500 uppercase font-bold">Bank BCA</p>
                <p class="text-2xl font-black text-gray-800 tracking-wider">1234567890</p>
                <p class="text-sm text-gray-600">a.n. PT Booking System</p>
              </div>
            </div>

            <div class="mb-6">
              <h3 class="font-bold text-gray-800 mb-4">Upload Bukti Transfer</h3>
              <form wire:submit.prevent="uploadProof">
                <div
                  class="flex flex-col items-center p-6 border-2 border-dashed border-gray-300 rounded-2xl bg-gray-50 hover:bg-gray-100 transition">
                  @if ($proofPayment)
                    <img src="{{ $proofPayment->temporaryUrl() }}"
                      class="w-40 h-40 object-cover rounded-xl shadow-lg border-4 border-white mb-4">
                  @else
                    <svg class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                  @endif

                  <input type="file" wire:model="proofPayment" id="upload-{{ $booking->id }}" class="hidden">
                  <label for="upload-{{ $booking->id }}"
                    class="px-6 py-2 bg-white border border-gray-300 rounded-full text-sm font-semibold text-gray-700 cursor-pointer hover:shadow-sm">
                    {{ $proofPayment ? 'Ganti Foto' : 'Pilih Foto' }}
                  </label>
                </div>

                <button type="submit" wire:loading.attr="disabled"
                  class="w-full mt-4 bg-green-600 text-white py-3 rounded-xl font-bold shadow-lg hover:bg-green-700 transition">
                  <span wire:loading.remove>Kirim Bukti Pembayaran</span>
                  <span wire:loading>Mengunggah...</span>
                </button>
              </form>
            </div>
          </div>
        @else
          <div class="animate-fadeIn bg-blue-50 p-6 rounded-2xl border border-blue-100 text-center">
            <div
              class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="font-bold text-blue-800 text-lg mb-2">Pemesanan Disimpan!</h3>
            <p class="text-sm text-blue-700 leading-relaxed mb-4">
              Anda memilih pembayaran <strong>Bayar di Tempat (Cash on Arrival)</strong>. <br>
              Silakan tunjukkan <strong>Kode Booking: {{ $booking->booking_code }}</strong> kepada staf kami saat tiba
              di lokasi pada hari-H.
            </p>
            <div
              class="bg-white py-2 px-4 inline-block rounded-lg font-mono font-bold text-blue-600 border border-blue-200">
              {{ $booking->booking_code }}
            </div>
          </div>
        @endif

        <div class="mt-8 border-t pt-6">
          <a href="{{ route('user.bookings') }}"
            class="flex items-center justify-center text-gray-500 hover:text-green-600 font-medium transition">
            <span>Lihat Daftar Booking Saya</span>
            <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </a>
        </div>
      </div>
    </div>
  @endif
</div>
