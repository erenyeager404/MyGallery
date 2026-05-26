@extends('layouts.guest')
@section('title', 'OurMemora — Abadikan Setiap Momen')

@section('content')
    <section class="hero-section">
        <div class="hero-blob"></div>
        <div class="relative z-10">
            <div class="hero-badge">
                <span class="w-2 h-2 bg-violet-400 rounded-full animate-pulse"></span>
                Abadikan kenangan, bagikan cerita
            </div>
            <h1 class="hero-title">
                Setiap foto<br>
                menyimpan <span class="text-violet-400">cerita</span>
            </h1>
            <p class="hero-sub">
                OurMemora hadir untuk mengabadikan momen berharga,<br>
                menemukan karya indah, dan terhubung dengan orang-orang terdekat.
            </p>
            @guest
                <div class="hero-cta">
                    <button onclick="openModal('register')" class="btn-primary px-8 py-3 text-base">
                        Mulai Mengabadikan
                    </button>
                    <button onclick="openModal('login')" class="btn-ghost px-8 py-3 text-base">
                        Sudah punya akun
                    </button>
                </div>
            @endguest
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 pb-24">
        @guest
            <p class="text-center text-xs text-gray-600 mb-8">
                ⬇ Download gratis tanpa login &nbsp;·&nbsp; Like, Simpan & Komentar perlu akun
            </p>
        @endguest

        <div class="gallery-grid">
            @forelse($photos as $photo)
                @if($photo->files->isNotEmpty())
                    <div class="card-landing">
                        <a href="{{ route('photos.show', $photo) }}" class="block overflow-hidden">
                            <img src="{{ $photo->files->first()->url }}" alt="{{ $photo->caption }}"
                                class="w-full object-cover transition-transform duration-500 group-hover:scale-105">
                        </a>
                        <div class="photo-hover-overlay">
                            <div>
                                <p class="font-semibold text-sm">{{ $photo->caption }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <img src="{{ $photo->user->avatar_url }}" class="w-5 h-5 rounded-full">
                                    <p class="text-gray-300 text-xs">{{ $photo->user->name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            <p class="font-medium text-sm truncate mb-1">{{ $photo->caption }}</p>
                            <div class="flex items-center gap-2 mb-2">
                                <img src="{{ $photo->user->avatar_url }}" class="w-4 h-4 rounded-full">
                                <p class="text-gray-500 text-xs">{{ $photo->user->name }}</p>
                            </div>
                            @if($photo->tags->isNotEmpty())
                                <div class="flex flex-wrap gap-1 mb-2">
                                    @foreach($photo->tags->take(2) as $tag)
                                        <span class="tag-chip">#{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <div class="action-bar">
                                @auth
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
                                        <span>◯</span>
                                        <span class="comment-count">{{ $photo->comments->count() }}</span>
                                    </button>
                                @else
                                    <button onclick="openModal('login','like')" class="btn-like"><span>♡</span>
                                        {{ $photo->likes->count() }}</button>
                                    <button onclick="openModal('login','save')" class="btn-save"><span>◇</span></button>
                                    <button onclick="openModal('login','comment')" class="btn-comment"><span>◯</span>
                                        {{ $photo->comments->count() }}</button>
                                @endauth
                                <a href="{{ route('photos.download', $photo) }}" class="ml-auto action-btn hover:text-green-400"
                                    title="Download">⬇</a>
                            </div>
                            @auth
                                <div id="cs-{{ $photo->id }}" class="hidden mt-3">
                                    <div id="cl-{{ $photo->id }}" class="comment-list mb-2">
                                        @foreach($photo->comments->take(3) as $c)
                                            <div class="comment-item"><span class="comment-name">{{ $c->user->name }}</span><span
                                                    class="text-gray-400 ml-1">{{ $c->body }}</span></div>
                                        @endforeach
                                    </div>
                                    <div class="comment-wrap">
                                        <input type="text" id="ci-{{ $photo->id }}" placeholder="Tulis komentar..."
                                            class="comment-input">
                                        <button onclick="submitCmt({{ $photo->id }})"
                                            class="btn-primary px-3 py-1.5 text-xs">Kirim</button>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-span-4 text-center py-24 text-gray-600">
                    <p class="text-7xl mb-4">📷</p>
                    <p class="text-xl">Belum ada kenangan di sini</p>
                </div>
            @endforelse
        </div>
        <div class="mt-10">{{ $photos->links() }}</div>
    </section>
@endsection

@push('scripts')
    <script>
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
            el.innerHTML = `<span class="comment-name">${d.comment.user_name}</span><span class="text-gray-400 ml-1">${d.comment.body}</span>`;
            document.getElementById(`cl-${id}`).appendChild(el);
            inp.value = '';
        }
    </script>
@endpush