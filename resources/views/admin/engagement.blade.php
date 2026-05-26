@extends('layouts.admin')

@section('content')
    <div>
        <h2 class="text-2xl font-bold mb-8">⎇ Engagement &amp; Statistik</h2>

        <div class="admin-stats">
            <div class="admin-stat">
                <p class="text-gray-500 text-xs">🟢 Online Hari Ini</p>
                <p class="admin-stat-num text-green-400">{{ $onlineToday }}</p>
                <p class="admin-stat-lbl">dari {{ $totalUsers }} user</p>
                <div class="admin-progress mt-3">
                    <div class="admin-prog-bar bg-green-500"
                        style="width:{{ $totalUsers > 0 ? round(($onlineToday / $totalUsers) * 100) : 0 }}%"></div>
                </div>
            </div>
            <div class="admin-stat">
                <p class="text-gray-500 text-xs">👥 Total User</p>
                <p class="admin-stat-num text-violet-400">{{ $totalUsers }}</p>
                <p class="admin-stat-lbl">Terdaftar</p>
            </div>
            <div class="admin-stat">
                <p class="text-gray-500 text-xs">📷 Total Foto</p>
                <p class="admin-stat-num text-blue-400">{{ $totalPhotos }}</p>
                <p class="admin-stat-lbl">Diupload</p>
            </div>
            <div class="admin-stat">
                <p class="text-gray-500 text-xs">♥ Like Terbanyak</p>
                <p class="admin-stat-num text-red-400">{{ $topPhoto ? $topPhoto->likes_count : 0 }}</p>
                <p class="admin-stat-lbl">Pada 1 foto</p>
            </div>
        </div>

        @if($topPhoto && $topPhoto->files->isNotEmpty())
            <div class="card-solid p-6">
                <h3 class="font-semibold mb-5 text-gray-300">🏆 Foto Paling Disukai</h3>
                <div class="flex gap-6 items-start">
                    <img src="{{ $topPhoto->files->first()->url }}" alt="{{ $topPhoto->caption }}"
                        class="w-48 h-48 object-cover rounded-xl flex-shrink-0">
                    <div>
                        <p class="text-xl font-bold mb-1">{{ $topPhoto->caption }}</p>
                        @if($topPhoto->description)
                            <p class="text-gray-400 text-sm mb-2">{{ $topPhoto->description }}</p>
                        @endif
                        <p class="text-gray-500 text-sm mb-1">Oleh: <span
                                class="text-red-400">{{ $topPhoto->user->name }}</span></p>
                        <p class="text-gray-500 text-sm mb-4">{{ $topPhoto->created_at->format('d M Y') }}</p>
                        <p class="text-3xl font-bold text-red-400">♥ {{ $topPhoto->likes_count }} likes</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection