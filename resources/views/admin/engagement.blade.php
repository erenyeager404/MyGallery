@extends('layouts.admin')

@section('content')
    <div>
        <h2 class="text-2xl font-bold mb-8">&#128202; Engagement &amp; Statistik</h2>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-5">
                <p class="text-gray-400 text-xs mb-1">&#127757; Online Hari Ini</p>
                <p class="text-3xl font-bold text-green-400">{{ $onlineToday }}</p>
                <p class="text-gray-500 text-xs mt-1">dari {{ $totalUsers }} user</p>
                <div class="mt-3 h-1.5 bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 rounded-full"
                        style="width: {{ $totalUsers > 0 ? round(($onlineToday / $totalUsers) * 100) : 0 }}%">
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-5">
                <p class="text-gray-400 text-xs mb-1">&#128100; Total User</p>
                <p class="text-3xl font-bold text-violet-400">{{ $totalUsers }}</p>
                <p class="text-gray-500 text-xs mt-1">Terdaftar</p>
            </div>
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-5">
                <p class="text-gray-400 text-xs mb-1">&#128247; Total Foto</p>
                <p class="text-3xl font-bold text-blue-400">{{ $totalPhotos }}</p>
                <p class="text-gray-500 text-xs mt-1">Diupload</p>
            </div>
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-5">
                <p class="text-gray-400 text-xs mb-1">&#10084; Likes Terbanyak</p>
                <p class="text-3xl font-bold text-red-400">{{ $topPhoto ? $topPhoto->likes_count : 0 }}</p>
                <p class="text-gray-500 text-xs mt-1">Pada 1 foto</p>
            </div>
        </div>

        {{-- Foto terpopuler --}}
        @if($topPhoto && $topPhoto->files->isNotEmpty())
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6">
                <h3 class="font-semibold mb-4 text-gray-300">&#127942; Foto Paling Disukai</h3>
                <div class="flex gap-6 items-start">
                    <img src="{{ $topPhoto->files->first()->url }}" alt="{{ $topPhoto->caption }}"
                        class="w-48 h-48 object-cover rounded-xl">
                    <div>
                        <p class="text-xl font-bold mb-1">{{ $topPhoto->caption }}</p>
                        <p class="text-gray-400 text-sm mb-1">{{ $topPhoto->description }}</p>
                        <p class="text-gray-500 text-sm mb-1">
                            Oleh: <span class="text-red-400">{{ $topPhoto->user->name }}</span>
                        </p>
                        <p class="text-gray-500 text-sm mb-4">
                            Diupload: {{ $topPhoto->created_at->format('d M Y') }}
                        </p>
                        <p class="text-3xl font-bold text-red-400">
                            &#10084; {{ $topPhoto->likes_count }} likes
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection