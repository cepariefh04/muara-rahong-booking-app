@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Packages -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Paket</p>
                    <p class="text-3xl font-bold">{{ \App\Models\Package::count() }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.packages') }}" class="text-blue-600 text-sm mt-4 inline-block hover:underline">
                Lihat semua paket →
            </a>
        </div>

        <!-- Total Bookings -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Booking</p>
                    <p class="text-3xl font-bold">{{ \App\Models\Booking::count() }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.bookings') }}" class="text-green-600 text-sm mt-4 inline-block hover:underline">
                Lihat semua booking →
            </a>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Pendapatan</p>
                    <p class="text-3xl font-bold">Rp
                        {{ number_format(\App\Models\Payment::where('status', 'paid')->sum('amount'), 0, ',', '.') }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.payments') }}" class="text-yellow-600 text-sm mt-4 inline-block hover:underline">
                Lihat pembayaran →
            </a>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="mt-6 bg-white rounded-lg shadow">
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4">Booking Terbaru</h2>
            <table class="min-w-full">
                <thead class="border-b">
                    <tr>
                        <th class="text-left py-3 text-sm font-medium text-gray-500">Kode</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-500">Customer</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-500">Paket</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-500">Status</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-500">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach (\App\Models\Booking::with(['user', 'package'])->latest()->limit(5)->get() as $booking)
                        <tr>
                            <td class="py-3 text-sm">{{ $booking->booking_code }}</td>
                            <td class="py-3 text-sm">{{ $booking->user->name }}</td>
                            <td class="py-3 text-sm">{{ $booking->package->name }}</td>
                            <td class="py-3 text-sm">{!! $booking->status_badge !!}</td>
                            <td class="py-3 text-sm font-medium">{{ $booking->formatted_total_price }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
