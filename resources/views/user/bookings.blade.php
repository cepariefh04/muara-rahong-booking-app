@extends('layouts.user')

@section('title', 'My Bookings')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold">Booking Saya</h1>
            <a href="{{ route('booking.wizard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                + Booking Baru
            </a>
        </div>

        <div class="space-y-4">
            @forelse(auth()->user()->bookings()->with(['package', 'payment'])->latest()->get() as $booking)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-semibold">{{ $booking->package->name }}</h3>
                            <p class="text-gray-600 text-sm">Kode: {{ $booking->booking_code }}</p>
                        </div>
                        {{-- <div class="text-right">
                            {!! $booking->status_badge !!}
                        </div> --}}
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 text-sm">
                        <div>
                            <p class="text-gray-600">Tanggal Booking</p>
                            <p class="font-medium">{{ $booking->booking_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Quantity</p>
                            <p class="font-medium">{{ $booking->quantity }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Total Harga</p>
                            <p class="font-bold text-blue-600">{{ $booking->formatted_total_price }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Status Pembayaran</p>
                            <p class="font-medium">{!! $booking->payment->status_badge ?? '-' !!}</p>
                        </div>
                    </div>

                    @if ($booking->start_date && $booking->end_date)
                        <div class="bg-gray-50 p-3 rounded text-sm">
                            <span class="text-gray-600">Periode:</span>
                            <span class="font-medium">{{ $booking->start_date->format('d M Y') }} -
                                {{ $booking->end_date->format('d M Y') }}</span>
                        </div>
                    @endif

                    @if ($booking->notes)
                        <div class="mt-3 text-sm">
                            <p class="text-gray-600">Catatan:</p>
                            <p class="text-gray-800">{{ $booking->notes }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <p class="text-gray-500 mb-4">Anda belum memiliki booking.</p>
                    <a href="{{ route('booking.wizard') }}"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 inline-block">
                        Mulai Booking
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection
