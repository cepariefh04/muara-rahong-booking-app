<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Payment;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

class BookingWizard extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    public $totalSteps = 4;

    // Step 1: Package Selection
    public $selectedPackage;
    public $packageId;

    // Step 2: Registration / Login
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $phone;
    public $isLogin = false;

    // Step 3: Booking Confirmation
    public $quantity = 1;
    public $startDate;
    public $endDate;
    public $notes;

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
        'startDate' => 'nullable|date',
        'endDate' => 'nullable|date|after:startDate',
    ];

    public function mount()
    {
        if (session()->has('booking_current_step')) {
            $this->currentStep = session('booking_current_step');
            $this->packageId = session('booking_package_id');
            $this->selectedPackage = Package::find($this->packageId);

            // Hapus session setelah diambil agar tidak nyangkut selamanya
            session()->forget(['booking_current_step', 'booking_package_id']);
        }
    }

    public function selectPackage($packageId)
    {
        $this->packageId = $packageId;
        $this->selectedPackage = Package::find($packageId);

        if (Auth::check()) {
            $this->currentStep = 3; // Skip login step
        } else {
            $this->currentStep = 2;
        }
    }

    public function toggleLoginMode()
    {
        $this->isLogin = !$this->isLogin;
        $this->resetErrorBag();
    }

    public function register()
    {
        $this->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required|string',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'phone' => $this->phone,
            'role' => 'user',
        ]);

        Auth::login($user);

        session(['booking_package_id' => $this->packageId]);
        session(['booking_current_step' => 3]);

        return redirect()->to('/booking');
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session(['booking_package_id' => $this->packageId]);
            session(['booking_current_step' => 3]);

            return redirect()->to('/booking');
        } else {
            $this->addError('email', 'Email atau password salah.');
        }
    }

    public function confirmBooking()
    {
        $this->validate([
            'quantity' => 'required|integer|min:1',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after:startDate',
        ]);

        $totalPrice = $this->selectedPackage->price * $this->quantity;

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'package_id' => $this->packageId,
            'quantity' => $this->quantity,
            'total_price' => $totalPrice,
            'booking_date' => now(),
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
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
    }

    public function uploadProof()
    {
        $this->validate([
            'proofPayment' => 'required|image|max:2048', // Validasi gambar max 2MB
        ]);

        $booking = Booking::with('payment')->find($this->bookingId);

        if ($booking && $booking->payment) {
            // Simpan file ke folder storage/app/public/proofs
            $path = $this->proofPayment->store('proofs', 'public');

            // Update data payment
            $booking->payment->update([
                'proof_of_payment' => $path,
                'status' => 'paid', // Update status menjadi paid sesuai permintaan
            ]);

            // Opsional: Update status booking juga jika diperlukan
            // $booking->update(['status' => 'confirmed']);

            session()->flash('success', 'Bukti pembayaran berhasil diunggah!');
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
        $packages = Package::published()->get();
        $booking = $this->bookingId ? Booking::with('package', 'payment')->find($this->bookingId) : null;

        return view('livewire.booking-wizard', compact('packages', 'booking'));
    }
}
