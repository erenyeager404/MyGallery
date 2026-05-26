@extends('layouts.admin')

@section('content')
    <div>
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold">⊞ Dashboard Admin</h2>
            <span class="badge-red">Administrator</span>
        </div>
        <div class="dash-grid">
            @foreach($photos as $photo)
                @if($photo->files->isNotEmpty())
                    <div class="admin-photo-card">
                        <img src="{{ $photo->files->first()->url }}" alt="{{ $photo->caption }}" class="w-full object-cover">
                        <div class="p-4">
                            <p class="font-semibold text-sm mb-1 truncate">{{ $photo->caption }}</p>
                            <div class="flex items-center gap-2 mb-3">
                                <img src="{{ $photo->user->avatar_url }}" class="w-4 h-4 rounded-full">
                                <p class="text-gray-500 text-xs">{{ $photo->user->name }}</p>
                            </div>
                            <div class="action-bar">
                                <span class="action-btn">♡ {{ $photo->likes->count() }}</span>
                                <span class="action-btn">◯ {{ $photo->comments->count() }}</span>
                                <span class="action-btn">👁 {{ number_format($photo->views) }}</span>
                                <form method="POST" action="{{ route('admin.photos.destroy', $photo) }}" class="ml-auto">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus foto ini?')" class="admin-del-btn">🗑
                                        Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="mt-8">{{ $photos->links() }}</div>
    </div>
@endsection