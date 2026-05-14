@extends('layouts.admin')
@section('content')
    <div>
        <h2 class="text-2xl font-bold mb-8">Engagement & Statistik</h2>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
                <p class="text-gray-400 text-xs mb-1">Online Hari Ini</p>
                <p class="text-3xl font-bold text-green-400">{{ $onlineToday }}</p>
                <p class="text-gray-500 text-xs mt-1">dari {{ $totalUsers }} user</p>
                <div class="mt-3 h-1.5 bg-gray-800 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 rounded-full"
                        style="width: {{ $totalUsers > 0 ? round(($onlineToday / $totalUsers) * 100) : 0 }}%">
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
                <p class="text-gray-400 text-xs mb-1">Total User</p>
                <p class="text-3xl font-bold text-violet-400">{{ $totalUsers }}</p>
                <p class="text-gray-500 text-xs mt-1">Terdaftar</p>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
                <p class="text-gray-400 text-xs mb-1">Total Foto</p>
                <p class="text-3xl font-bold text-blue-400">{{ $totalPhotos }}</p>
                <p class="text-gray-500 text-xs mt-1">Diupload</p>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
                <p class="text-gray-400 text-xs mb-1">Total Like Terbanyak</p>
                <p class="text-3xl font-bold text-red-400">
                    {{ $topPhoto ? $topPhoto->likes_count : 0 }}
                </p>
                <p class="text-gray-500 text-xs mt-1">Pada 1 foto</p>
            </div>

        </div>

        @if($topPhoto)
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
                <h3 class="font-semibold mb-4 text-gray-300">🏆 Foto Paling Banyak Disukai</h3>
                <div class="flex gap-6 items-start">
                    <img src="{{ Storage::url($topPhoto->file_path) }}" alt="{{ $topPhoto->caption }}"
                        class="w-48 h-48 object-cover rounded-xl">
                    <div>
                        <p class="text-xl font-bold mb-1">{{ $topPhoto->caption }}</p>
                        <p class="text-gray-400 text-sm mb-1">{{ $topPhoto->description }}</p>
                        <p class="text-gray-500 text-sm">
                            Oleh: <span class="text-red-400">{{ $topPhoto->user->name }}</span>
                        </p>
                        <p class="text-gray-500 text-sm">
                            Diupload: {{ $topPhoto->created_at->translatedFormat('d F Y') }}
                        </p>
                        <div class="mt-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span class="text-2xl font-bold text-red-400">{{ $topPhoto->likes_count }}</span>
                            <span class="text-gray-400 text-sm">likes</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection