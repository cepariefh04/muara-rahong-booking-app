<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;

class BookingManagement extends Component
{
    use WithPagination;

    public $detailModalOpen = false;
    public $selectedBooking;

    // Filters
    public $search = '';
    public $filterStatus = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';

    public function showDetail($bookingId)
    {
        $this->selectedBooking = Booking::with(['user', 'package', 'payment'])->findOrFail($bookingId);
        $this->detailModalOpen = true;
    }

    public function updateStatus($bookingId, $status)
    {
        $booking = Booking::findOrFail($bookingId);
        $booking->update(['status' => $status]);

        session()->flash('message', 'Status booking berhasil diperbarui.');

        if ($this->selectedBooking && $this->selectedBooking->id == $bookingId) {
            $this->selectedBooking = Booking::with(['user', 'package', 'payment'])->find($bookingId);
        }
    }

    public function closeDetailModal()
    {
        $this->detailModalOpen = false;
        $this->selectedBooking = null;
    }

    public function render()
    {
        $bookings = Booking::with(['user', 'package', 'payment'])
            ->when($this->search, function ($q) {
                $q->where('booking_code', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', fn($query) => $query->where('name', 'like', '%' . $this->search . '%'));
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterDateFrom, fn($q) => $q->whereDate('booking_date', '>=', $this->filterDateFrom))
            ->when($this->filterDateTo, fn($q) => $q->whereDate('booking_date', '<=', $this->filterDateTo))
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('livewire.admin.booking-management', compact('bookings', 'stats'));
    }
}
