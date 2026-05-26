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
                <p class="text-6xl mb-4">📷</p>
                <p class="text-xl mb-2">Belum ada foto</p>
            </div>
        @else
            <div class="dashboard-grid">
                @foreach($photos as $photo)
                    @if($photo->files->isNotEmpty())
                        <div class="card-photo">
                            {{-- Foto --}}
                            <div class="photo-slider-wrap cursor-pointer"
                                onclick="openPhotoDetail({{ $photo->id }}, '{{ Storage::url($photo->files->first()->file_path) }}')">
                                @if($photo->files->count() > 1)
                                    <div class="photo-slider-track" id="slider-{{ $photo->id }}"
                                        style="width:{{ $photo->files->count() * 100 }}%">
                                        @foreach($photo->files as $file)
                                            <div style="width:{{ 100 / $photo->files->count() }}%">
                                                <img src="{{ $file->url }}" alt="{{ $photo->caption }}" class="w-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                    <button onclick="event.stopPropagation(); slide('{{ $photo->id }}', -1)"
                                        class="slider-btn slider-btn-prev">‹</button>
                                    <button onclick="event.stopPropagation(); slide('{{ $photo->id }}', 1)"
                                        class="slider-btn slider-btn-next">›</button>
                                    <div class="slider-dots">
                                        @foreach($photo->files as $i => $__)
                                            <div class="slider-dot {{ $i === 0 ? 'active' : '' }}" id="dot-{{ $photo->id }}-{{ $i }}"></div>
                                        @endforeach
                                    </div>
                                @else
                                    <img src="{{ $photo->files->first()->url }}" alt="{{ $photo->caption }}" class="w-full object-cover">
                                @endif
                            </div>

                            <div class="p-4">
                                <p class="font-semibold text-sm mb-0.5 truncate">{{ $photo->caption }}</p>
                                @if($photo->description)
                                    <p class="text-gray-400/80 text-xs mb-2 line-clamp-2">{{ $photo->description }}</p>
                                @endif

                                {{-- Uploader --}}
                                <div class="flex items-center gap-2 mb-3">
                                    <img src="{{ $photo->user->avatar_url }}" alt="{{ $photo->user->name }}"
                                        class="w-5 h-5 rounded-full object-cover">
                                    <p class="text-gray-400/80 text-xs">{{ $photo->user->name }}</p>
                                    <span class="text-gray-700 text-xs ml-auto">{{ $photo->created_at->diffForHumans() }}</span>
                                </div>

                                {{-- Tags --}}
                                @if($photo->tags->isNotEmpty())
                                    <div class="flex flex-wrap gap-1 mb-3">
                                        @foreach($photo->tags->take(3) as $tag)
                                            <a href="{{ route('search') }}?q={{ $tag->name }}" class="tag-chip">
                                                #{{ $tag->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Action bar --}}
                                <div class="action-bar">
                                    <button onclick="toggleLike({{ $photo->id }}, this)"
                                        class="action-btn-like {{ $photo->isLikedBy(auth()->id()) ? 'liked' : '' }}">
                                        <span>{{ $photo->isLikedBy(auth()->id()) ? '♥' : '♡' }}</span>
                                        <span class="like-count">{{ $photo->likes->count() }}</span>
                                    </button>
                                    <button onclick="toggleSave({{ $photo->id }}, this)"
                                        class="action-btn-save {{ $photo->isSavedBy(auth()->id()) ? 'saved' : '' }}">
                                        <span>{{ $photo->isSavedBy(auth()->id()) ? '◈' : '◇' }}</span>
                                    </button>
                                    <button onclick="toggleComment({{ $photo->id }})" class="action-btn-comment">
                                        <span>◯</span>
                                        <span class="comment-count">{{ $photo->comments->count() }}</span>
                                    </button>
                                    <span class="view-count">
                                        👁 {{ number_format($photo->views) }}
                                    </span>
                                    {{-- More --}}
                                    <div class="relative ml-1">
                                        <button onclick="toggleDropdown(this)" class="action-btn px-1">⋯</button>
                                        <div class="dropdown-menu">
                                            <a href="{{ route('photos.download', $photo) }}" class="dropdown-item">
                                                ⬇ Download
                                            </a>
                                            <button onclick="copyLink('{{ route('photos.show', $photo) }}')" class="dropdown-item">
                                                🔗 Salin Link
                                            </button>
                                            @if($photo->user_id === auth()->id())
                                                <form method="POST" action="{{ route('photos.destroy', $photo) }}">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Hapus foto ini?')"
                                                        class="dropdown-item text-red-400 hover:bg-red-900/20">
                                                        🗑 Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Comment section --}}
                                <div id="comment-section-{{ $photo->id }}" class="comment-box hidden">
                                    <div id="comments-{{ $photo->id }}" class="space-y-1.5 max-h-28 overflow-y-auto mb-2">
                                        @foreach($photo->comments->take(5) as $c)
                                            <div class="comment-item">
                                                <span class="comment-name">{{ $c->user->name }}</span>
                                                <span class="text-gray-300 ml-1">{{ $c->body }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="comment-input-wrap">
                                        <input type="text" id="comment-input-{{ $photo->id }}" placeholder="Tulis komentar..."
                                            class="comment-input">
                                        <button onclick="submitComment({{ $photo->id }})"
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

    {{-- Photo Detail Modal --}}
    <div id="photoDetailModal" class="modal-backdrop" onclick="handleModalBg(event)">
        <div class="photo-detail-bg" id="modalBg">
            <img id="modalBgImg" src="" alt="">
            <div class="photo-detail-overlay"></div>
        </div>
        <div class="relative z-10 w-full max-w-5xl mx-4 my-8">
            <div id="modalContent" class="card overflow-hidden">
                <div class="flex items-center justify-center py-20 text-gray-400">
                    <span class="text-4xl animate-spin">◌</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Share toast --}}
    <div id="shareToast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50
         bg-gray-800 border border-gray-700 text-white text-sm px-5 py-3
         rounded-xl shadow-xl hidden transition-all">
        🔗 Link disalin!
    </div>

@endsection

@push('scripts')
    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const sliders = {};

        // ── Slider ──────────────────────────────────────────────────────
        function slide(id, dir) {
            const track = document.getElementById(`slider-${id}`);
            const dots = document.querySelectorAll(`[id^="dot-${id}-"]`);
            const total = dots.length;
            sliders[id] = ((sliders[id] || 0) + dir + total) % total;
            track.style.transform = `translateX(-${sliders[id] * (100 / total)}%)`;
            dots.forEach((d, i) => {
                d.classList.toggle('active', i === sliders[id]);
            });
        }

        // ── Photo Detail Modal ──────────────────────────────────────────
        async function openPhotoDetail(photoId, bgSrc) {
            const modal = document.getElementById('photoDetailModal');
            const bgImg = document.getElementById('modalBgImg');
            const content = document.getElementById('modalContent');

            // set background foto yang diklik
            bgImg.src = bgSrc;
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';

            // load detail via fetch
            content.innerHTML = '<div class="flex items-center justify-center py-20 text-gray-400"><span class="text-4xl">◌</span></div>';

            const res = await fetch(`/photo/${photoId}?partial=1`);
            const html = await res.text();
            content.innerHTML = html;
        }

        function closePhotoDetail() {
            document.getElementById('photoDetailModal').classList.remove('open');
            document.body.style.overflow = '';
        }

        function handleModalBg(e) {
            if (e.target === document.getElementById('photoDetailModal')) closePhotoDetail();
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closePhotoDetail();
        });

        // ── Like ─────────────────────────────────────────────────────────
        async function toggleLike(id, btn) {
            const res = await fetch(`/photos/${id}/like`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' } });
            const data = await res.json();
            const icon = btn.querySelector('span:first-child');
            const count = btn.querySelector('.like-count');
            icon.innerHTML = data.liked ? '♥' : '♡';
            count.textContent = data.total;
            btn.classList.toggle('liked', data.liked);
        }

        // ── Save ──────────────────────────────────────────────────────────
        async function toggleSave(id, btn) {
            const res = await fetch(`/photos/${id}/save`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' } });
            const data = await res.json();
            btn.querySelector('span').innerHTML = data.saved ? '◈' : '◇';
            btn.classList.toggle('saved', data.saved);
        }

        // ── Comment ───────────────────────────────────────────────────────
        function toggleComment(id) {
            document.getElementById(`comment-section-${id}`).classList.toggle('hidden');
        }
        async function submitComment(id) {
            const input = document.getElementById(`comment-input-${id}`);
            const body = input.value.trim(); if (!body) return;
            const res = await fetch(`/photos/${id}/comment`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' }, body: JSON.stringify({ body }) });
            const data = await res.json();
            const list = document.getElementById(`comments-${id}`);
            const div = document.createElement('div'); div.className = 'comment-item';
            div.innerHTML = `<span class="comment-name">${data.comment.user_name}</span><span class="text-gray-300 ml-1">${data.comment.body}</span>`;
            list.appendChild(div);
            const cBtn = document.querySelector(`button[onclick="toggleComment(${id})"] span:last-child`);
            if (cBtn) cBtn.textContent = data.total;
            input.value = '';
        }

        // ── Dropdown ──────────────────────────────────────────────────────
        function toggleDropdown(btn) {
            const menu = btn.nextElementSibling;
            document.querySelectorAll('.dropdown-menu').forEach(m => { if (m !== menu) m.classList.add('hidden'); });
            menu.classList.toggle('hidden');
            const close = e => { if (!btn.contains(e.target) && !menu.contains(e.target)) { menu.classList.add('hidden'); document.removeEventListener('click', close); } };
            setTimeout(() => document.addEventListener('click', close), 0);
        }

        // ── Copy link ─────────────────────────────────────────────────────
        function copyLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                const toast = document.getElementById('shareToast');
                toast.classList.remove('hidden');
                setTimeout(() => toast.classList.add('hidden'), 2000);
            });
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
        }
    </script>
@endpush