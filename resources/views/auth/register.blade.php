<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — OurMemora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
</head>

<body class="bg-gray-950 text-white min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold">Our<span class="text-violet-400">Memora</span></h1>
            <p class="text-gray-400 mt-2">Buat akun baru</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm text-gray-400 mb-1">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-violet-500 transition-colors"
                    placeholder="Nama kamu">
                {{-- ↑ old('name') = isi ulang field dengan input sebelumnya jika ada error --}}
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
                {{-- ↑ @error('name') = tampilkan pesan error validasi untuk field 'name' --}}
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-violet-500 transition-colors"
                    placeholder="email@kamu.com">
                @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1">Password</label>
                <input type="password" name="password"
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-violet-500 transition-colors"
                    placeholder="Minimal 6 karakter">
                @error('password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-violet-500 transition-colors"
                    placeholder="Ulangi password">
            </div>

            <button type="submit"
                class="w-full py-3 bg-violet-600 hover:bg-violet-700 rounded-xl font-medium transition-colors mt-2">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center text-gray-400 text-sm mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-violet-400 hover:underline">Login di sini</a>
        </p>
    </div>
</body>

</html>