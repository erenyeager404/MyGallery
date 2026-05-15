<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OurMemora — Bagikan Momenmu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
</head>

<body class="bg-gray-950 text-white">

    <nav class="fixed top-0 w-full bg-gray-950/80 backdrop-blur-sm border-b border-gray-800 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <h1 class="text-xl font-bold">Our<span class="text-violet-400">Memora</span></h1>
            <div class="flex gap-3">
                <a href="{{ route('login') }}"
                    class="px-4 py-2 text-sm text-gray-300 hover:text-white transition-colors">
                    Login
                </a>
                <a href="{{ route('register') }}"
                    class="px-4 py-2 text-sm bg-violet-600 hover:bg-violet-700 rounded-lg transition-colors">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </nav>


    <section class="pt-32 pb-16 px-6 text-center">
        <h2 class="text-5xl font-bold mb-4 leading-tight">
            Bagikan <span class="text-violet-400">momen</span> terbaikmu
        </h2>
        <p class="text-gray-400 text-lg mb-8 max-w-xl mx-auto">
            Upload foto, tambahkan cerita, dan temukan karya indah dari komunitas OurMemora.
        </p>
        <a href="{{ route('register') }}"
            class="inline-block px-8 py-4 bg-violet-600 hover:bg-violet-700 rounded-xl text-lg font-medium transition-colors">
            Mulai Sekarang — Gratis
        </a>
    </section>


    <section class="max-w-7xl mx-auto px-6 pb-20">
        <h3 class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-6">
            Foto terbaru dari komunitas
        </h3>
        <div class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4">

            @forelse($photos as $photo)
                <div class="break-inside-avoid rounded-xl overflow-hidden group relative">

                    <img src="{{ Storage::url($photo->file_path) }}" alt="{{ $photo->caption }}"
                        class="w-full object-cover">


                    <div
                        class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">

                        <div>
                            <p class="font-medium text-sm">{{ $photo->caption }}</p>
                            <p class="text-gray-300 text-xs">oleh {{ $photo->user->name }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 col-span-4 text-center py-20">
                    Belum ada foto. Jadilah yang pertama upload!
                </p>
            @endforelse

        </div>
    </section>

</body>

</html>