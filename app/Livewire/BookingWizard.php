<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class BookingWizard extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    public $totalSteps = 4;

    // Step 1: Package Selection
    public $selectedPackage;
    public $packageId;
    public $disabledDates = [];

    // Step 2: Booking Details (Tanggal)
    public $quantity = 1;
    public $checkInDate;
    public $checkOutDate;
    public $notes;
    public $totalNights = 0;

    // Step 3: Registration / Login
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $phone;
    public $isLogin = false;

    // Step 4: Payment
    public $bookingId;
    public $paymentMethod = 'manual_transfer';
    public $proofPayment;

    protected $rules = [
        'packageId' => 'required|exists:packages,id',
        'name' => 'required|string|min:3',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
        'phone' => 'nullable|string',
        'quantity' => 'required|integer|min:1',
        'checkInDate' => 'required|date|after_or_equal:today',
        'checkOutDate' => 'nullable|date|after:checkInDate',
        'proofPayment' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        if (session()->has('booking_current_step')) {
            $this->currentStep = session('booking_current_step');

            // Ambil kembali data dari session ke property class
            $this->packageId    = session('b_package_id');
            $this->checkInDate  = session('b_check_in');
            $this->checkOutDate = session('b_check_out');
            $this->quantity     = session('b_quantity');
            $this->notes        = session('b_notes');
            $this->totalNights  = session('b_nights');

            $this->selectedPackage = Package::find($this->packageId);

            // Jika sampai di step 4 dan belum ada bookingId, buat bookingnya sekarang
            if ($this->currentStep == 4 && Auth::check()) {
                $this->createBooking();
            }

            // Bersihkan session
            session()->forget(['booking_current_step', 'b_package_id', 'b_check_in', 'b_check_out', 'b_quantity', 'b_nights']);
        }
    }


    // STEP 1: Select Package
    public function selectPackage($packageId)
    {
        $this->packageId = $packageId;
        $this->selectedPackage = Package::find($packageId);
        $this->quantity = $this->selectedPackage->min_capacity ?? 1;

        $this->disabledDates = Booking::where('package_id', $packageId)
            ->whereIn('status', ['confirmed', 'success']) // Sesuaikan dengan status booking Anda
            ->pluck('start_date')
            ->map(fn($date) => \Carbon\Carbon::parse($date)->format('Y-m-d'))
            ->toArray();

        // Always go to step 2 (date selection)
        $this->currentStep = 2;
    }

    // STEP 2: Confirm Booking Details
    public function updatedCheckInDate()
    {
        // 1. Reset error segera agar UI Frontend tahu status validasi dimulai dari nol
        $this->resetErrorBag('checkInDate');
        $this->totalNights = 0;

        if ($this->checkInDate && $this->selectedPackage) {
            // 2. Validasi Full Booked
            if (in_array($this->checkInDate, $this->disabledDates)) {
                $this->addError('checkInDate', 'Maaf, tanggal ini sudah penuh.');
                $this->checkInDate = null;
                $this->checkOutDate = null; // Penting: Reset checkout agar frontend tidak bingung
                return;
            }

            $date = Carbon::parse($this->checkInDate);
            $isWeekend = $date->isWeekend();

            // 3. Validasi Weekend/Weekday
            if ($this->selectedPackage->week_type === 'weekends' && !$isWeekend) {
                $this->addError('checkInDate', 'Paket ini hanya tersedia di akhir pekan.');
                $this->checkInDate = null;
                $this->checkOutDate = null;
                return;
            }

            if ($this->selectedPackage->week_type === 'weekdays' && $isWeekend) {
                $this->addError('checkInDate', 'Paket ini hanya tersedia di hari kerja.');
                $this->checkInDate = null;
                $this->checkOutDate = null;
                return;
            }

            // 4. Sinkronisasi dengan CheckOut (Bagian ini yang krusial untuk respons UI)
            if ($this->checkOutDate) {
                $checkOut = Carbon::parse($this->checkOutDate);
                // Jika CheckIn baru ternyata sama atau melampaui CheckOut yang lama
                if ($date->greaterThanOrEqualTo($checkOut)) {
                    $this->checkOutDate = null; // Paksa reset agar input checkout enable kembali dengan benar
                    $this->totalNights = 0;
                } else {
                    $this->calculateNights();
                }
            }
        } else {
            // Jika checkIn dihapus oleh user, kosongkan checkout juga
            $this->checkOutDate = null;
        }
    }

    public function updatedCheckOutDate()
    {
        $this->calculateNights();
    }

    private function calculateNights()
    {
        if ($this->checkInDate && $this->checkOutDate) {
            $checkIn = Carbon::parse($this->checkInDate);
            $checkOut = Carbon::parse($this->checkOutDate);
            $this->totalNights = $checkIn->diffInDays($checkOut);
        }
    }

    // Tambahkan method ini di dalam class BookingWizard

    public function updatedQuantity()
    {
        // Validasi realtime kapasitas
        if ($this->selectedPackage) {
            if ($this->quantity > $this->selectedPackage->max_capacity) {
                $this->addError('quantity', 'Jumlah tamu melebihi kapasitas maksimal (' . $this->selectedPackage->max_capacity . ' orang).');
            } elseif ($this->quantity < ($this->selectedPackage->price_type === 'pack' ? $this->selectedPackage->min_capacity : 1)) {
                // Validasi minimum (opsional, tapi baik untuk UX)
                $min = $this->selectedPackage->price_type === 'pack' ? $this->selectedPackage->min_capacity : 1;
                $this->addError('quantity', 'Jumlah tamu kurang dari minimum (' . $min . ' orang).');
            } else {
                // Hapus error jika input sudah benar
                $this->resetErrorBag('quantity');
            }
        }
    }

    public function confirmBooking()
    {
        $rules = [
            'checkInDate' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string',
        ];

        if ($this->selectedPackage->price_type === 'night') {
            $rules['checkOutDate'] = 'required|date|after:checkInDate';
        }

        $rules['quantity'] = 'required|integer|min:' . ($this->selectedPackage->min_capacity ?? 1) . '|max:' . $this->selectedPackage->max_capacity;

        $this->validate($rules);

        // SIMPAN DATA KE SESSION agar tidak hilang saat redirect login
        session([
            'b_package_id' => $this->packageId,
            'b_check_in'   => $this->checkInDate,
            'b_check_out'  => $this->checkOutDate,
            'b_quantity'   => $this->quantity,
            'b_notes'      => $this->notes,
            'b_nights'     => $this->totalNights,
        ]);

        if (Auth::check()) {
            $this->createBooking(); // Jika sudah login, langsung buat booking
        } else {
            $this->currentStep = 3; // Ke step Login/Register
        }
    }

    public function toggleLoginMode()
    {
        $this->isLogin = !$this->isLogin;
        $this->resetErrorBag();
    }

    // STEP 3: Login/Register
    private function createBookingAndGoToPayment()
    {
        // Calculate total price
        if ($this->selectedPackage->price_type === 'night') {
            $totalPrice = $this->selectedPackage->price * $this->totalNights;
        } else {
            $totalPrice = $this->selectedPackage->price;
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'package_id' => $this->packageId,
            'quantity' => $this->quantity,
            'total_price' => $totalPrice,
            'booking_date' => now(),
            'start_date' => $this->checkInDate,
            'end_date' => $this->checkOutDate ?? $this->checkInDate,
            'notes' => $this->notes,
            'status' => 'pending',
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'amount' => $totalPrice,
            'payment_method' => $this->paymentMethod,
            'status' => 'pending',
        ]);

        session(['booking_id' => $booking->id]);

        $this->bookingId = $booking->id;
        $this->currentStep = 4;

        session()->flash('success', 'Booking berhasil dibuat!');
    }

    // Update method register (panggil createBookingAndGoToPayment setelah register)
    public function register()
    {
        $this->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'phone' => $this->phone,
            'role' => 'user',
        ]);

        Auth::login($user);

        // Create booking setelah register
        session(['booking_current_step' => 4]);

        return redirect()->to('/booking');
    }

    // Update method login (panggil createBookingAndGoToPayment setelah login)
    public function login()
    {
        $this->validate(['email' => 'required|email', 'password' => 'required']);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session(['booking_current_step' => 4]); // Target step setelah login
            return redirect()->to('/booking');
        }
        $this->addError('email', 'Email atau password salah.');
    }


    // Create Booking (called after login/register)
    private function createBooking()
    {
        // Calculate total price
        if ($this->selectedPackage->price_type === 'night') {
            // Harga per malam × jumlah malam
            $totalPrice = $this->selectedPackage->price * $this->totalNights;
        } else {
            // Harga per paket (fixed)
            $totalPrice = $this->selectedPackage->price;
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'package_id' => $this->packageId,
            'quantity' => $this->quantity,
            'total_price' => $totalPrice,
            'booking_date' => now(),
            'start_date' => $this->checkInDate,
            'end_date' => $this->checkOutDate ?? $this->checkInDate,
            'notes' => $this->notes,
            'status' => 'pending',
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'amount' => $totalPrice,
            'payment_method' => $this->paymentMethod,
            'status' => 'pending',
        ]);

        $this->bookingId = $booking->id;
        $this->currentStep = 4;

        session()->flash('success', 'Booking berhasil dibuat!');
    }

    // STEP 4: Upload Payment Proof
    public function uploadProof()
    {
        $this->validate([
            'proofPayment' => 'required|image|max:2048',
        ]);

        if ($this->proofPayment) {
            $path = $this->proofPayment->store('payment-proofs', 'public');

            $booking = Booking::find($this->bookingId);
            if ($booking && $booking->payment) {
                $booking->payment->update([
                    'proof_of_payment' => '/storage/' . $path,
                    'status' => 'pending', // Status tetap pending sampai admin approve
                ]);

                session()->flash('success', 'Bukti pembayaran berhasil diupload!');
                $this->proofPayment = null;
            }
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function render()
    {
        // 1. Ambil semua paket yang published
        $allPackages = Package::published()->get();

        // Array untuk menampung hasil grouping
        $groupedPackages = [];

        // 2. Logika Khusus: Camping (Group by week_type)
        $campPackages = $allPackages->where('package_type', 'camp');

        // Kita urutkan agar Weekdays muncul duluan, baru Weekends (opsional)
        foreach ($campPackages->groupBy('week_type')->sortKeysDesc() as $weekType => $packages) {
            // Buat label judul yang user-friendly
            $label = $weekType === 'weekdays'
                ? 'Camping - Hari Kerja (Senin-Jumat)'
                : 'Camping - Akhir Pekan (Sabtu-Minggu)';

            $groupedPackages[$label] = $packages;
        }

        // 3. Logika Umum: Non-Camping (Group by package_type)
        $otherPackages = $allPackages->where('package_type', '!=', 'camp');

        foreach ($otherPackages->groupBy('package_type') as $type => $packages) {
            // Ubah key jadi Huruf Besar (misal: 'glamping' -> 'Glamping')
            $label = ucfirst($type);
            $groupedPackages[$label] = $packages;
        }

        $booking = $this->bookingId ? Booking::with('package', 'payment')->find($this->bookingId) : null;

        // Kirim variable $groupedPackages ke view
        return view('livewire.booking-wizard', compact('groupedPackages', 'booking'));
    }
}
