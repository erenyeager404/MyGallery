@extends('layouts.app')
@section('title', 'Saved')

@section('content')
    <div>
        <h2 class="text-2xl font-bold mb-8">◈ Foto Tersimpan</h2>
        @if($photos->isEmpty())
            <div class="text-center py-24 text-gray-500">
                <p class="text-7xl mb-4">◇</p>
                <p class="text-xl mb-2">Belum ada foto tersimpan</p>
                <p class="text-sm text-gray-600">Klik ◇ di dashboard untuk menyimpan foto</p>
            </div>
        @else
            <div class="dash-grid">
                @foreach($photos as $photo)
                    @if($photo && $photo->files->isNotEmpty())
                        <div class="card-photo cursor-pointer" onclick="window.location='{{ route('photos.show', $photo) }}'">
                            <img src="{{ $photo->files->first()->url }}" alt="{{ $photo->caption }}" class="w-full object-cover">
                            <div class="p-4">
                                <p class="font-semibold text-sm mb-1">{{ $photo->caption }}</p>
                                <div class="flex items-center gap-2">
                                    <img src="{{ $photo->user->avatar_url }}" class="w-4 h-4 rounded-full">
                                    <p class="text-gray-400 text-xs">{{ $photo->user->name }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
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