<?php

namespace App\Livewire\Admin;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';

    public function updatePaymentStatus($paymentId, $status)
    {
        $payment = Payment::findOrFail($paymentId);

        $data = ['status' => $status];

        if ($status === 'paid') {
            $data['paid_at'] = now();

            // Update booking status juga
            $payment->booking->update(['status' => 'confirmed']);
        }

        $payment->update($data);

        session()->flash('message', 'Status pembayaran berhasil diperbarui.');
    }

    public function render()
    {
        $payments = Payment::with(['booking.user', 'booking.package'])
            ->when($this->search, function ($q) {
                $q->whereHas('booking', function ($query) {
                    $query->where('booking_code', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'));
                });
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(15);

        $stats = [
            'total_amount' => Payment::where('status', 'paid')->sum('amount'),
            'pending' => Payment::where('status', 'pending')->count(),
            'paid' => Payment::where('status', 'paid')->count(),
        ];

        return view('livewire.admin.payment-management', compact('payments', 'stats'));
    }
}
