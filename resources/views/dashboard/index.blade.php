@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div>
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold">Semua Kenangan</h2>
            <a href="{{ route('upload') }}" class="btn-primary">＋ Upload</a>
        </div>

        @if($photos->isEmpty())
            <div class="text-center py-24 text-gray-500">
                <p class="text-7xl mb-4">📷</p>
                <p class="text-xl mb-2">Belum ada kenangan</p>
                <a href="{{ route('upload') }}" class="btn-primary mt-4 inline-flex">Upload Sekarang</a>
            </div>
        @else
            <div class="dash-grid">
                @foreach($photos as $photo)
                    @if($photo->files->isNotEmpty())
                        <div class="card-photo">
                            {{-- Foto --}}
                            <div class="slider-wrap cursor-pointer" onclick="window.location='{{ route('photos.show', $photo) }}'">
                                @if($photo->files->count() > 1)
                                    <div class="slider-track" id="st-{{ $photo->id }}" style="width:{{ $photo->files->count() * 100 }}%">
                                        @foreach($photo->files as $f)
                                            <div style="width:{{ 100 / $photo->files->count() }}%">
                                                <img src="{{ $f->url }}" alt="{{ $photo->caption }}" class="w-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                    <button onclick="event.stopPropagation();slide('{{ $photo->id }}',-1)" class="slider-prev">‹</button>
                                    <button onclick="event.stopPropagation();slide('{{ $photo->id }}',1)" class="slider-next">›</button>
                                    <div class="slider-dots">
                                        @foreach($photo->files as $i => $_)
                                            <div class="slider-dot {{ $i === 0 ? 'active' : '' }}" id="sd-{{ $photo->id }}-{{ $i }}"></div>
                                        @endforeach
                                    </div>
                                @else
                                    <img src="{{ $photo->files->first()->url }}" alt="{{ $photo->caption }}" class="w-full object-cover">
                                @endif
                            </div>

                            <div class="p-4">
                                <p class="font-semibold text-sm mb-0.5 truncate">{{ $photo->caption }}</p>
                                @if($photo->description)
                                    <p class="text-gray-400/70 text-xs mb-2 line-clamp-2">{{ $photo->description }}</p>
                                @endif
                                <div class="flex items-center gap-2 mb-3">
                                    <img src="{{ $photo->user->avatar_url }}" class="w-5 h-5 rounded-full object-cover flex-shrink-0">
                                    <span class="text-gray-400/80 text-xs truncate">{{ $photo->user->name }}</span>
                                    <span class="text-gray-600 text-xs ml-auto">{{ $photo->created_at->diffForHumans() }}</span>
                                </div>
                                @if($photo->tags->isNotEmpty())
                                    <div class="flex flex-wrap gap-1 mb-3">
                                        @foreach($photo->tags->take(3) as $tag)
                                            <a href="{{ route('search') }}?q={{ $tag->name }}" class="tag-chip">#{{ $tag->name }}</a>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="action-bar">
                                    <button onclick="toggleLike({{ $photo->id }},this)"
                                        class="btn-like {{ $photo->isLikedBy(auth()->id()) ? 'liked' : '' }}">
                                        <span>{{ $photo->isLikedBy(auth()->id()) ? '♥' : '♡' }}</span>
                                        <span class="like-count">{{ $photo->likes->count() }}</span>
                                    </button>
                                    <button onclick="toggleSave({{ $photo->id }},this)"
                                        class="btn-save {{ $photo->isSavedBy(auth()->id()) ? 'saved' : '' }}">
                                        <span>{{ $photo->isSavedBy(auth()->id()) ? '◈' : '◇' }}</span>
                                    </button>
                                    <button onclick="toggleCmt({{ $photo->id }})" class="btn-comment">
                                        <span>◯</span><span class="cmt-count">{{ $photo->comments->count() }}</span>
                                    </button>
                                    <span class="view-count">👁 {{ number_format($photo->views) }}</span>
                                    <div class="dropdown-wrap">
                                        <button onclick="toggleDD(this)" class="action-btn px-1">⋯</button>
                                        <div class="dropdown-menu">
                                            <a href="{{ route('photos.download', $photo) }}" class="dropdown-item">⬇ Download</a>
                                            <button onclick="copyLink('{{ route('photos.show', $photo) }}')" class="dropdown-item">🔗
                                                Salin Link</button>
                                            @if($photo->user_id === auth()->id())
                                                <form method="POST" action="{{ route('photos.destroy', $photo) }}">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Hapus foto?')"
                                                        class="dropdown-item text-red-400 hover:bg-red-900/20">🗑 Hapus</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div id="cs-{{ $photo->id }}" class="hidden mt-3">
                                    <div id="cl-{{ $photo->id }}" class="comment-list">
                                        @foreach($photo->comments->take(5) as $c)
                                            <div class="comment-item"><span class="comment-name">{{ $c->user->name }}</span><span
                                                    class="text-gray-300 ml-1">{{ $c->body }}</span></div>
                                        @endforeach
                                    </div>
                                    <div class="comment-wrap">
                                        <input type="text" id="ci-{{ $photo->id }}" placeholder="Tulis komentar..."
                                            class="comment-input">
                                        <button onclick="submitCmt({{ $photo->id }})"
                                            class="btn-primary px-3 py-1.5 text-xs">Kirim</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="mt-8">{{ $photos->links() }}</div>
        @endif
    </div>

    {{-- Detail Modal --}}
    <div id="detailModal" class="modal-backdrop" onclick="if(event.target===this)closeDetail()">
        <div class="detail-modal-bg" id="dBg"><img id="dBgImg" src="" alt="">
            <div class="detail-modal-overlay"></div>
        </div>
        <div class="relative z-10 w-full max-w-5xl mx-4 my-6 max-h-screen overflow-y-auto">
            <div id="dContent" class="modal-box-lg"></div>
        </div>
    </div>

    {{-- Toast --}}
    <div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[100]
             bg-gray-800 border border-gray-700 text-white text-sm px-5 py-3
             rounded-xl shadow-xl hidden">
        🔗 Link disalin!
    </div>
@endsection

@push('scripts')
    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const SS = {};

        function slide(id, dir) {
            const t = document.getElementById(`st-${id}`);
            const dots = document.querySelectorAll(`[id^="sd-${id}-"]`);
            const n = dots.length;
            SS[id] = ((SS[id] || 0) + dir + n) % n;
            t.style.transform = `translateX(-${SS[id] * (100 / n)}%)`;
            dots.forEach((d, i) => d.classList.toggle('active', i === SS[id]));
        }

        async function openDetail(id, bg) {
            const m = document.getElementById('detailModal');
            document.getElementById('dBgImg').src = bg;
            document.getElementById('dContent').innerHTML = '<div class="flex items-center justify-center py-24 text-gray-400 text-3xl">◌</div>';
            m.classList.add('open');
            document.body.style.overflow = 'hidden';
            const res = await fetch(`/photo/${id}?partial=1`);
            document.getElementById('dContent').innerHTML = await res.text();
        }

        function closeDetail() {
            document.getElementById('detailModal').classList.remove('open');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDetail(); });

        async function toggleLike(id, btn) {
            const r = await fetch(`/photos/${id}/like`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' } });
            const d = await r.json();
            btn.querySelector('span').innerHTML = d.liked ? '♥' : '♡';
            btn.querySelector('.like-count').textContent = d.total;
            btn.classList.toggle('liked', d.liked);
        }

        async function toggleSave(id, btn) {
            const r = await fetch(`/photos/${id}/save`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' } });
            const d = await r.json();
            btn.querySelector('span').innerHTML = d.saved ? '◈' : '◇';
            btn.classList.toggle('saved', d.saved);
        }

        function toggleCmt(id) { document.getElementById(`cs-${id}`).classList.toggle('hidden'); }

        async function submitCmt(id) {
            const inp = document.getElementById(`ci-${id}`);
            const body = inp.value.trim(); if (!body) return;
            const r = await fetch(`/photos/${id}/comment`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' }, body: JSON.stringify({ body }) });
            const d = await r.json();
            const el = document.createElement('div'); el.className = 'comment-item';
            el.innerHTML = `<span class="comment-name">${d.comment.user_name}</span><span class="text-gray-300 ml-1">${d.comment.body}</span>`;
            document.getElementById(`cl-${id}`).appendChild(el);
            const c = document.querySelector(`button[onclick="toggleCmt(${id})"] .cmt-count`);
            if (c) c.textContent = d.total;
            inp.value = '';
        }

        function toggleDD(btn) {
            const m = btn.nextElementSibling;
            document.querySelectorAll('.dropdown-menu').forEach(x => { if (x !== m) x.classList.add('hidden'); });
            m.classList.toggle('hidden');
            const close = e => { if (!btn.contains(e.target) && !m.contains(e.target)) { m.classList.add('hidden'); document.removeEventListener('click', close); } };
            setTimeout(() => document.addEventListener('click', close), 0);
        }

        function copyLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                const t = document.getElementById('toast');
                t.classList.remove('hidden');
                setTimeout(() => t.classList.add('hidden'), 2000);
            });
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
        }
    </script>
@endpush