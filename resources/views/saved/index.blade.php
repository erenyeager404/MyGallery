@extends('layouts.app')
@section('title', 'Saved Photos')

@section('content')
    <div>
        <h2 class="text-2xl font-bold mb-8">&#128278; Foto Tersimpan</h2>

        @if($photos->isEmpty())
            <div class="text-center py-20 text-gray-500">
                <p class="text-6xl mb-4">&#128278;</p>
                <p class="text-xl mb-2">Belum ada foto tersimpan</p>
                <p class="text-sm">Klik ikon simpan di dashboard untuk menyimpan foto</p>
            </div>
        @else
            <div class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-4 space-y-4">
                @foreach($photos as $photo)
                    @if($photo && $photo->files->isNotEmpty())
                        <div class="break-inside-avoid bg-white/10 backdrop-blur-md rounded-2xl
                                                overflow-hidden border border-white/20">
                            <a href="{{ route('photos.show', $photo) }}">
                                <img src="{{ $photo->files->first()->url }}" alt="{{ $photo->caption }}"
                                    class="w-full object-cover hover:opacity-90 transition-opacity">
                            </a>
                            <div class="p-4">
                                <p class="font-semibold text-sm mb-1">{{ $photo->caption }}</p>
                                <p class="text-gray-400 text-xs">
                                    oleh <span class="text-violet-400">{{ $photo->user->name }}</span>
                                </p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
@endsection