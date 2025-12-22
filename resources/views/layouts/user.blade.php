<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Booking System')</title>
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
    @livewireStyles
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}"
                        class="text-2xl font-bold text-primary hover:text-green-700 transition">
                        Booking System
                    </a>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary transition font-medium">
                        Home
                    </a>
                    <a href="{{ route('booking.wizard') }}"
                        class="text-gray-700 hover:text-primary transition font-medium">
                        Booking
                    </a>
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
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary transition font-medium">
                            Login
                        </a>
                        <a href="{{ route('booking.wizard') }}"
                            class="bg-primary text-white px-5 py-2 rounded-lg hover:bg-green-800 transition font-medium">
                            Mulai Booking
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-green-200">&copy; {{ date('Y') }} Booking System. All rights reserved.</p>
        </div>
    </footer>

    @livewireScripts
</body>

</html>
