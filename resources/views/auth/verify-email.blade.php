<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email — MyGallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-white min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-8 py-10 text-center">

        {{-- Icon envelope --}}
        <div class="w-20 h-20 bg-violet-900/40 border border-violet-700 rounded-full
                    flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8
                         M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5
                         a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>

        {{-- Logo --}}
        <h1 class="text-2xl font-bold mb-1">
            My<span class="text-violet-400">Gallery</span>
        </h1>

        <h2 class="text-xl font-semibold mb-3">Cek Email Kamu</h2>

        <p class="text-gray-400 text-sm leading-relaxed mb-2">
            Kami sudah kirim link verifikasi ke
        </p>
        <p class="text-violet-400 font-semibold mb-6">
            {{ auth()->user()->email }}
        </p>
        <p class="text-gray-500 text-xs mb-8">
            Buka email kamu dan klik tombol verifikasi untuk mengaktifkan akun.
            Kalau tidak ada di inbox, cek folder <span class="text-gray-400">Spam</span>.
        </p>

        {{-- Flash: registrasi berhasil --}}
        @if(session('success'))
            <div class="mb-5 px-4 py-3 bg-green-900/40 border border-green-700
                            text-green-300 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Flash: kirim ulang berhasil --}}
        @if(session('message'))
            <div class="mb-5 px-4 py-3 bg-blue-900/40 border border-blue-700
                            text-blue-300 rounded-xl text-sm">
                {{ session('message') }}
            </div>
        @endif

        {{-- Tombol kirim ulang --}}
        <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
            @csrf
            <button type="submit" class="w-full py-3 bg-violet-600 hover:bg-violet-700 rounded-xl
                           font-medium transition-colors text-sm">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-gray-500 hover:text-gray-300 text-sm transition-colors">
                ← Keluar dan ganti akun
            </button>
        </form>

    </div>
</body>

</html>