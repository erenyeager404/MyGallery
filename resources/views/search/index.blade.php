@extends('layouts.app')
@section('title', 'Pencarian')

@section('content')
    <div>
        {{-- Search bar --}}
        <form method="GET" action="{{ route('search') }}" class="mb-8">
            <div class="relative">
                <input type="text" name="q" value="{{ $query }}" placeholder="Cari foto, tag, atau user..." autofocus class="w-full px-5 py-4 bg-white/10 backdrop-blur-md border border-white/20
                              rounded-2xl text-white placeholder-gray-500 pr-14
                              focus:outline-none focus:border-violet-500 transition-colors">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 p-2.5
                               bg-violet-600 hover:bg-violet-700 rounded-xl transition-colors">
                    &#128269;
                </button>
            </div>
        </form>

        @if($query)
            {{-- Tag yang cocok --}}
            @if($tags->isNotEmpty())
                <div class="flex flex-wrap gap-2 mb-6">
                    @foreach($tags as $tag)
                        <a href="{{ route('search') }}?q={{ $tag->name }}" class="px-3 py-1 bg-violet-900/40 border border-violet-700 text-violet-300
                                              text-xs rounded-full hover:bg-violet-800/40 transition-colors">
                            #{{ $tag->name }} ({{ $tag->photos_count }})
                        </a>
                    @endforeach
                </div>
            @endif

            <p class="text-gray-400 text-sm mb-6">
                {{ $photos->count() }} hasil untuk
                <span class="text-white font-medium">"{{ $query }}"</span>
            </p>

            @if($photos->isEmpty())
                <div class="text-center py-20 text-gray-500">
                    <p class="text-6xl mb-4">&#128269;</p>
                    <p class="text-xl mb-2">Tidak ada foto ditemukan</p>
                    <p class="text-sm">Coba kata kunci lain</p>
                </div>
            @else
                <div class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-4 space-y-4">
                    @foreach($photos as $photo)
                        @if($photo->files->isNotEmpty())
                            <div class="break-inside-avoid bg-white/10 backdrop-blur-md rounded-2xl
                                                        overflow-hidden border border-white/20">
                                <a href="{{ route('photos.show', $photo) }}">
                                    <img src="{{ $photo->files->first()->url }}" alt="{{ $photo->caption }}"
                                        class="w-full object-cover hover:opacity-90 transition-opacity">
                                </a>
                                <div class="p-4">
                                    <p class="font-semibold text-sm mb-1">{{ $photo->caption }}</p>
                                    <p class="text-gray-500 text-xs mb-2">
                                        oleh <span class="text-violet-400">{{ $photo->user->name }}</span>
                                    </p>
                                    @if($photo->tags->isNotEmpty())
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($photo->tags as $tag)
                                                <a href="{{ route('search') }}?q={{ $tag->name }}"
                                                    class="px-2 py-0.5 bg-gray-800 text-gray-400 text-xs rounded-full hover:text-violet-400 transition-colors">
                                                    #{{ $tag->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

        @else
            {{-- Tag populer --}}
            <div class="text-center py-10">
                <p class="text-gray-500 text-sm mb-6">Cari berdasarkan caption, tag, atau nama user</p>
                @if($popularTags->isNotEmpty())
                    <p class="text-gray-400 text-sm mb-4">Tag Populer</p>
                    <div class="flex flex-wrap gap-2 justify-center">
                        @foreach($popularTags as $tag)
                            <a href="{{ route('search') }}?q={{ $tag->name }}" class="px-4 py-2 bg-white/10 border border-white/20 text-gray-300 text-sm
                                                  rounded-full hover:border-violet-500 hover:text-violet-400 transition-colors">
                                #{{ $tag->name }}
                                <span class="text-gray-500 text-xs ml-1">{{ $tag->photos_count }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection