<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking System - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#073823',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}"
                        class="text-2xl font-bold text-primary hover:text-green-700 transition">
                        Booking Muara Rahong Hiils
                    </a>
                </div>
                <div class="flex items-center space-x-6">
                    @auth
                        <a href="{{ route('user.bookings') }}"
                            class="text-gray-700 hover:text-primary transition font-medium">
                            My Bookings
                        </a>
                        <div class="flex items-center space-x-3 pl-4 border-l border-gray-300">
                            <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-700 font-medium">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-primary text-white px-5 py-2 rounded-lg hover:bg-green-800 transition font-medium">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-green-600 to-green-800 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-5xl font-bold mb-4">Wujudkan Liburan Impian Anda</h2>
            <p class="text-xl mb-8">Booking paket wisata terbaik dengan harga terjangkau</p>
            <a href="{{ route('booking.wizard') }}"
                class="bg-white text-green-600 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-gray-100 inline-block">
                Booking Sekarang
            </a>
        </div>
    </section>

    <!-- Featured Packages -->
    <section class="max-w-7xl mx-auto px-4 py-16">
        <h3 class="text-3xl font-bold text-center mb-12">Paket Populer</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach (\App\Models\Package::published()->limit(3)->get() as $package)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="h-48 bg-gradient-to-r from-green-400 to-green-600"></div>
                    <div class="p-6">
                        <h4 class="text-xl font-semibold mb-2">{{ $package->name }}</h4>
                        <p class="text-gray-600 mb-4">{{ $package->week_type }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-green-600">{{ $package->formatted_price }}</span>
                            <a href="{{ route('booking.wizard') }}"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                Pesan
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-green-200">&copy; {{ date('Y') }} Booking App - muararahonghills.com. All rights
                reserved.</p>
        </div>
    </footer>
</body>

</html>
