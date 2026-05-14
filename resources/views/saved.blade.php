@extends('layouts.app')
@section('content')
    <div>
        <h2 class="text-2xl font-bold mb-8">Foto yang Kamu Simpan</h2>

        @if($photos->isEmpty())
            <div class="text-center py-20 text-gray-500">
                <p class="text-xl mb-2">Belum ada foto tersimpan</p>
                <p class="text-sm">Klik tombol save di dashboard untuk menyimpan foto</p>
            </div>
        @else
            <div class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-4 space-y-4">
                @foreach($photos as $photo)
                    <div class="break-inside-avoid bg-gray-900 rounded-2xl overflow-hidden border border-gray-800">
                        <img src="{{ Storage::url($photo->file_path) }}" alt="{{ $photo->caption }}" class="w-full object-cover">
                        <div class="p-4">
                            <p class="font-semibold text-sm mb-1">{{ $photo->caption }}</p>
                            <p class="text-gray-500 text-xs">
                                oleh <span class="text-violet-400">{{ $photo->user->name }}</span>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection