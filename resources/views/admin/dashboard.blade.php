@extends('layouts.admin')
@section('content')
    <div>
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold">Dashboard Admin</h2>
            <span class="px-3 py-1 bg-red-900/40 text-red-400 text-xs rounded-full border border-red-800">
                Administrator
            </span>
        </div>

        <div class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-4 space-y-4">
            @foreach($photos as $photo)
                <div class="break-inside-avoid bg-gray-900 rounded-2xl overflow-hidden border border-gray-800">
                    <img src="{{ Storage::url($photo->file_path) }}" alt="{{ $photo->caption }}" class="w-full object-cover">
                    <div class="p-4">
                        <p class="font-semibold text-sm mb-1">{{ $photo->caption }}</p>
                        <p class="text-gray-500 text-xs mb-3">
                            oleh <span class="text-red-400">{{ $photo->user->name }}</span>
                        </p>

                        <div class="flex items-center gap-3 pt-3 border-t border-gray-800">
                            <span class="flex items-center gap-1 text-xs text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                {{ $photo->likes->count() }}
                            </span>
                            <span class="flex items-center gap-1 text-xs text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                {{ $photo->comments->count() }}
                            </span>

                            {{-- TOMBOL HAPUS — hanya ada di admin --}}
                            <form method="POST" action="{{ route('admin.photos.destroy', $photo) }}" class="ml-auto">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus foto ini dari platform?')"
                                    class="flex items-center gap-1 px-2 py-1 bg-red-900/40 hover:bg-red-900/70 text-red-400 text-xs rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection