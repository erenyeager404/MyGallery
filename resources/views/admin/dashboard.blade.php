@extends('layouts.admin')

@section('content')
    <div>
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold">&#8962; Dashboard Admin</h2>
            <span class="px-3 py-1 bg-red-900/40 text-red-400 text-xs rounded-full border border-red-800">
                Administrator
            </span>
        </div>

        <div class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-4 space-y-4">
            @foreach($photos as $photo)
                @if($photo->files->isNotEmpty())
                    <div class="break-inside-avoid bg-gray-800 rounded-2xl overflow-hidden border border-gray-700">
                        <img src="{{ $photo->files->first()->url }}" alt="{{ $photo->caption }}" class="w-full object-cover">
                        <div class="p-4">
                            <p class="font-semibold text-sm mb-1">{{ $photo->caption }}</p>
                            <p class="text-gray-500 text-xs mb-3">
                                oleh <span class="text-red-400">{{ $photo->user->name }}</span>
                            </p>
                            <div class="flex items-center gap-3 pt-3 border-t border-gray-700">
                                <span class="text-xs text-gray-400">&#9825; {{ $photo->likes->count() }}</span>
                                <span class="text-xs text-gray-400">&#128172; {{ $photo->comments->count() }}</span>
                                <span class="text-xs text-gray-400">&#128065; {{ number_format($photo->views) }}</span>
                                <form method="POST" action="{{ route('admin.photos.destroy', $photo) }}" class="ml-auto">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus foto ini dari platform?')" class="flex items-center gap-1 px-3 py-1.5 bg-red-900/40
                                                           hover:bg-red-900/70 text-red-400 text-xs rounded-lg transition-colors">
                                        &#128465; Hapus
                                    </button>
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