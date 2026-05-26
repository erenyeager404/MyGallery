@extends('layouts.app')
@section('title', 'Pencarian')

@section('content')
    <div>
        <form method="GET" action="{{ route('search') }}" class="mb-8">
            <div class="relative">
                <input type="text" name="q" value="{{ $query }}" placeholder="Cari foto, tag, atau user..." autofocus
                    class="form-input-glass w-full py-4 pr-14 text-base">
                <button type="submit"
                    class="absolute right-3 top-1/2 -translate-y-1/2 btn-primary w-10 h-10 !p-0 !rounded-xl">⌕</button>
            </div>
        </form>

        @if($query)
            @if($tags->isNotEmpty())
                <div class="flex flex-wrap gap-2 mb-6">
                    @foreach($tags as $tag)
                        <a href="{{ route('search') }}?q={{ $tag->name }}" class="tag-chip-active">#{{ $tag->name }}
                            ({{ $tag->photos_count }})</a>
                    @endforeach
                </div>
            @endif

            <p class="text-gray-400 text-sm mb-6">
                {{ $photos->count() }} hasil untuk <span class="text-white font-medium">"{{ $query }}"</span>
            </p>

            @if($photos->isEmpty())
                <div class="text-center py-20 text-gray-600">
                    <p class="text-7xl mb-4">⌕</p>
                    <p class="text-xl">Tidak ada foto ditemukan</p>
                </div>
            @else
                <div class="dash-grid">
                    @foreach($photos as $photo)
                        @if($photo->files->isNotEmpty())
                            <div class="card-photo cursor-pointer" onclick="window.location='{{ route('photos.show', $photo) }}'">
                                <img src="{{ $photo->files->first()->url }}" alt="{{ $photo->caption }}" class="w-full object-cover">
                                <div class="p-4">
                                    <p class="font-semibold text-sm mb-1 truncate">{{ $photo->caption }}</p>
                                    <div class="flex items-center gap-2 mb-2">
                                        <img src="{{ $photo->user->avatar_url }}" class="w-4 h-4 rounded-full">
                                        <p class="text-gray-500 text-xs">{{ $photo->user->name }}</p>
                                    </div>
                                    @if($photo->tags->isNotEmpty())
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($photo->tags as $tag)
                                                <a href="{{ route('search') }}?q={{ $tag->name }}" class="tag-chip">#{{ $tag->name }}</a>
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
            <div class="text-center py-10">
                <p class="text-gray-500 text-sm mb-8">Cari berdasarkan caption, tag, atau nama user</p>
                @if($popularTags->isNotEmpty())
                    <p class="text-gray-400 text-sm mb-4">Tag Populer</p>
                    <div class="flex flex-wrap gap-2 justify-center">
                        @foreach($popularTags as $tag)
                            <a href="{{ route('search') }}?q={{ $tag->name }}"
                                class="px-4 py-2 bg-white/8 border border-white/15 text-gray-300 text-sm rounded-full hover:border-violet-500 hover:text-violet-400 transition-colors">
                                #{{ $tag->name }} <span class="text-gray-600 text-xs ml-1">{{ $tag->photos_count }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div id="detailModal" class="modal-backdrop" onclick="if(event.target===this)closeDetail()">
        <div class="detail-modal-bg" id="dBg"><img id="dBgImg" src="" alt="">
            <div class="detail-modal-overlay"></div>
        </div>
        <div class="relative z-10 w-full max-w-5xl mx-4 my-6 max-h-screen overflow-y-auto">
            <div id="dContent" class="modal-box-lg"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        async function openDetail(id, bg) {
            const m = document.getElementById('detailModal');
            document.getElementById('dBgImg').src = bg;
            document.getElementById('dContent').innerHTML = '<div class="flex items-center justify-center py-24 text-gray-400 text-3xl">◌</div>';
            m.classList.add('open');
            document.body.style.overflow = 'hidden';
            const res = await fetch(`/photo/${id}?partial=1`);
            document.getElementById('dContent').innerHTML = await res.text();
        }
        function closeDetail() { document.getElementById('detailModal').classList.remove('open'); document.body.style.overflow = ''; }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDetail(); });
    </script>
@endpush