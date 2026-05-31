@php
    $files = $photo->files;
    $total = $files->count();
    $firstUrl = $files->first()?->url ?? '';
@endphp

@extends(auth()->check() ? 'layouts.app' : 'layouts.guest')
@section('title', $photo->caption)

@push('head')
    <style>
        .detail-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            transition: all .15s ease;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, .1);
            background: rgba(255, 255, 255, .07);
            color: #cbd5e1;
        }

        .detail-btn:hover {
            background: rgba(255, 255, 255, .13);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, .3);
        }

        .detail-btn:active {
            transform: scale(.97);
        }

        .detail-btn.primary {
            background: rgba(124, 58, 237, .4);
            border-color: rgba(124, 58, 237, .55);
            color: #e9d5ff;
        }

        .detail-btn.primary:hover {
            background: rgba(124, 58, 237, .58);
            color: #fff;
        }

        .like-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 99px;
            font-size: 13px;
            transition: all .15s ease;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, .1);
            background: rgba(255, 255, 255, .07);
            color: #94a3b8;
        }

        .like-btn:hover {
            background: rgba(239, 68, 68, .15);
            border-color: rgba(239, 68, 68, .4);
            color: #f87171;
            transform: scale(1.05);
        }

        .like-btn:active {
            transform: scale(.97);
        }

        .like-btn.liked {
            background: rgba(239, 68, 68, .18);
            border-color: rgba(239, 68, 68, .5);
            color: #f87171;
        }
    </style>
@endpush

@section('content')

    {{-- Dynamic blurred background --}}
    <div class="fixed inset-0 z-0 transition-all duration-700" id="photoBg">
        <img id="bgImg" src="{{ $firstUrl }}" alt="" class="w-full h-full object-cover"
            style="filter:blur(40px);transform:scale(1.1);opacity:.2">
        <div class="absolute inset-0"
            style="background:linear-gradient(to bottom,rgba(9,11,20,.72) 0%,rgba(9,11,20,.45) 40%,rgba(9,11,20,.88) 100%)">
        </div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto">

        {{-- Back --}}
        <a href="{{ url()->previous() }}"
            class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white mb-6 px-4 py-2 rounded-xl transition-all hover:-translate-x-1"
            style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_420px] gap-8 items-start">

            {{-- ── Kiri: Foto + Slider ── --}}
            <div>
                <div class="rounded-2xl overflow-hidden relative"
                    style="border:1px solid rgba(255,255,255,.12);box-shadow:0 24px 64px rgba(0,0,0,.5)">
                    <div class="flex transition-transform duration-400 ease-out" id="ds" style="width:{{ $total * 100 }}%">
                        @foreach($files as $f)
                            <div style="width:{{ 100 / $total }}%">
                                <img src="{{ $f->url }}" alt="{{ $photo->caption }}" class="w-full object-cover"
                                    style="max-height:560px;display:block">
                            </div>
                        @endforeach
                    </div>

                    @if($total > 1)
                        <button onclick="dSlide(-1)"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full flex items-center justify-center text-white transition-all z-10 hover:scale-110 active:scale-95"
                            style="background:rgba(0,0,0,.55);border:1px solid rgba(255,255,255,.18);backdrop-filter:blur(10px)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button onclick="dSlide(1)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full flex items-center justify-center text-white transition-all z-10 hover:scale-110 active:scale-95"
                            style="background:rgba(0,0,0,.55);border:1px solid rgba(255,255,255,.18);backdrop-filter:blur(10px)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        <div class="absolute bottom-3 left-0 right-0 flex justify-center gap-2 z-10">
                            @for($i = 0; $i < $total; $i++)
                                <div id="dd-{{ $i }}"
                                    class="rounded-full transition-all duration-300 {{ $i === 0 ? 'w-4 h-2 bg-white' : 'w-2 h-2 bg-white/40' }}">
                                </div>
                            @endfor
                        </div>
                    @endif
                </div>

                {{-- Thumbnail strip --}}
                @if($total > 1)
                    <div class="flex gap-2 mt-3 overflow-x-auto pb-2">
                        @foreach($files as $i => $f)
                            <button onclick="dGoTo({{ $i }})" id="dt-{{ $i }}"
                                class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden border-2 transition-all hover:opacity-100"
                                style="border-color:{{ $i === 0 ? 'rgba(124,58,237,.8)' : 'rgba(255,255,255,0)' }};opacity:{{ $i === 0 ? 1 : .5 }}">
                                <img src="{{ $f->thumb_url }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif

                {{-- Tags --}}
                @if($photo->tags->isNotEmpty())
                    <div class="flex flex-wrap gap-2 mt-4">
                        @foreach($photo->tags as $tag)
                            <a href="{{ route('search') }}?q={{ $tag->name }}"
                                class="px-3 py-1 text-xs rounded-full text-gray-400 hover:text-violet-400 transition-all hover:scale-105"
                                style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1)">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── Kanan: Info Panel ── --}}
            <div class="flex flex-col gap-4">

                {{-- Card: Uploader --}}
                <div class="rounded-2xl p-4"
                    style="background:rgba(9,11,20,.75);border:1px solid rgba(255,255,255,.1);backdrop-filter:blur(20px)">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ $photo->user->avatar_url }}" alt="{{ $photo->user->name }}"
                                class="w-11 h-11 rounded-full object-cover ring-2 ring-violet-500/30">
                            <div>
                                <p class="font-semibold text-[14px]">{{ $photo->user->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $photo->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @auth
                            @if(auth()->id() !== $photo->user_id)
                                <button onclick="doFollow({{ $photo->user_id }}, this)"
                                    class="detail-btn {{ auth()->user()->isFollowing($photo->user_id) ? '' : 'primary' }} text-xs px-4 py-2">
                                    @if(auth()->user()->isFollowing($photo->user_id))
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Mengikuti
                                    @else
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Ikuti
                                    @endif
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>

                {{-- Card: Caption + Stats --}}
                <div class="rounded-2xl p-5"
                    style="background:rgba(9,11,20,.75);border:1px solid rgba(255,255,255,.1);backdrop-filter:blur(20px)">

                    <h1 class="text-xl font-bold mb-1 leading-tight">{{ $photo->caption }}</h1>
                    @if($photo->description)
                        <p class="text-gray-400 text-sm leading-relaxed mb-4">{{ $photo->description }}</p>
                    @endif

                    {{-- Stats --}}
                    <div class="flex items-center gap-3 py-3 mb-3"
                        style="border-top:1px solid rgba(255,255,255,.07);border-bottom:1px solid rgba(255,255,255,.07)">
                        @auth
                            <button onclick="dLike({{ $photo->id }}, this)"
                                class="like-btn {{ $photo->isLikedBy(auth()->id()) ? 'liked' : '' }}">
                                <svg class="w-4 h-4" fill="{{ $photo->isLikedBy(auth()->id()) ? 'currentColor' : 'none' }}"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span id="dlc">{{ $photo->likes->count() }}</span>
                            </button>
                        @else
                            <button onclick="openModal('login','like')" class="like-btn">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                {{ $photo->likes->count() }}
                            </button>
                        @endauth

                        <span class="flex items-center gap-2 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span id="dcc">{{ $photo->comments->count() }}</span>
                        </span>

                        <span class="flex items-center gap-1.5 text-xs text-gray-600 ml-auto">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ number_format($photo->views) }}
                        </span>

                        {{-- Owner menu --}}
                        @auth
                            @if(auth()->id() === $photo->user_id)
                                <div class="relative ml-1">
                                    <button onclick="toggleMenu(this)" class="detail-btn w-9 h-9 !p-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                                d="M5 12h.01M12 12h.01M19 12h.01" />
                                        </svg>
                                    </button>
                                    <div class="hidden absolute right-0 bottom-full mb-2 rounded-xl overflow-hidden shadow-2xl z-50"
                                        style="background:#0f111a;border:1px solid rgba(255,255,255,.1);min-width:160px">
                                        <form method="POST" action="{{ route('photos.destroy', $photo) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus foto ini secara permanen?')"
                                                class="flex items-center gap-2.5 px-4 py-3 text-[12px] text-red-400 w-full text-left transition-colors hover:bg-red-900/20">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus Foto
                                            </button>
                                        </form>
                                        <button onclick="copyLink('{{ route('photos.show', $photo) }}')"
                                            class="flex items-center gap-2.5 px-4 py-3 text-[12px] text-gray-300 w-full text-left transition-colors hover:bg-white/5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            Salin Link
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endauth
                    </div>

                    {{-- Download --}}
                    <a href="{{ route('photos.download', $photo) }}" class="detail-btn w-full justify-center mb-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Foto Original
                    </a>

                    {{-- Comments --}}
                    <div id="comments" class="overflow-y-auto space-y-3 mb-4" style="max-height:240px">
                        @forelse($photo->comments as $c)
                            <div class="flex gap-3">
                                <img src="{{ $c->user->avatar_url }}"
                                    class="w-7 h-7 rounded-full flex-shrink-0 object-cover mt-0.5">
                                <div>
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-violet-300 font-medium text-[12px]">{{ $c->user->name }}</span>
                                        <span class="text-gray-600 text-[10px]">{{ $c->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-300 text-[12px] mt-0.5 leading-relaxed">{{ $c->body }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-600 text-sm py-6">Belum ada komentar</p>
                        @endforelse
                    </div>

                    @auth
                        <div class="flex gap-2">
                            <input type="text" id="dci" placeholder="Tulis komentar..."
                                class="flex-1 px-4 py-2.5 text-sm text-white placeholder-gray-600 rounded-xl focus:outline-none transition-all"
                                style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1)"
                                onkeydown="if(event.key==='Enter')dComment({{ $photo->id }})">
                            <button onclick="dComment({{ $photo->id }})" class="detail-btn primary px-4 py-2.5">
                                Kirim
                            </button>
                        </div>
                    @else
                        <button onclick="openModal('login','comment')" class="detail-btn w-full justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Login untuk berkomentar
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div id="toast"
        class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[100] px-5 py-3 rounded-xl text-sm text-white shadow-xl hidden transition-all"
        style="background:#0f111a;border:1px solid rgba(255,255,255,.12);backdrop-filter:blur(12px)">
        🔗 Link disalin!
    </div>
@endsection

@push('scripts')
    <script>
        const _c = document.querySelector('meta[name="csrf-token"]')?.content || '';
        let dIdx = 0;
        const dTot = {{ $total }};

        // Background update
        function updateBg(src) {
            const bg = document.getElementById('bgImg');
            if (!bg) return;
            bg.style.opacity = '0';
            setTimeout(() => { bg.src = src; bg.style.opacity = '.2'; }, 200);
        }
        updateBg('{{ $firstUrl }}');

        function dSlide(dir) { dIdx = (dIdx + dir + dTot) % dTot; dGoTo(dIdx); }
        function dGoTo(i) {
            dIdx = i;
            const s = document.getElementById('ds');
            if (s) s.style.transform = `translateX(-${i * (100 / dTot)}%)`;
            document.querySelectorAll('[id^="dd-"]').forEach((d, j) => {
                d.className = j === i
                    ? 'rounded-full bg-white w-4 h-2 transition-all duration-300'
                    : 'rounded-full bg-white/40 w-2 h-2 transition-all duration-300';
            });
            document.querySelectorAll('[id^="dt-"]').forEach((t, j) => {
                t.style.borderColor = j === i ? 'rgba(124,58,237,.8)' : 'rgba(255,255,255,0)';
                t.style.opacity = j === i ? '1' : '.5';
            });
            const imgs = document.querySelectorAll('#ds img');
            if (imgs[i]) updateBg(imgs[i].src);
        }

        // Keyboard
        document.addEventListener('keydown', e => {
            if (e.key === 'ArrowLeft') dSlide(-1);
            if (e.key === 'ArrowRight') dSlide(1);
            if (e.key === 'Escape') {
                document.querySelectorAll('.hidden.absolute').forEach(m => m.classList.add('hidden'));
            }
        });

        // Like
        async function dLike(id, btn) {
            const r = await fetch(`/photos/${id}/like`, {
                method: 'POST', headers: { 'X-CSRF-TOKEN': _c, 'Content-Type': 'application/json' }
            });
            const d = await r.json();
            const svg = btn.querySelector('svg');
            svg.setAttribute('fill', d.liked ? 'currentColor' : 'none');
            document.getElementById('dlc').textContent = d.total;
            btn.classList.toggle('liked', d.liked);
        }

        // Comment
        async function dComment(id) {
            const inp = document.getElementById('dci');
            const body = inp.value.trim(); if (!body) return;
            inp.disabled = true;
            const r = await fetch(`/photos/${id}/comment`, {
                method: 'POST', headers: { 'X-CSRF-TOKEN': _c, 'Content-Type': 'application/json' },
                body: JSON.stringify({ body })
            });
            const d = await r.json();
            const el = document.createElement('div');
            el.className = 'flex gap-3';
            el.innerHTML = `
            <div class="w-7 h-7 rounded-full bg-violet-800 flex-shrink-0 flex items-center justify-center text-xs font-bold mt-0.5">
                ${d.comment.user_name[0]}
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <span class="text-violet-300 font-medium text-[12px]">${d.comment.user_name}</span>
                    <span class="text-gray-600 text-[10px]">baru saja</span>
                </div>
                <p class="text-gray-300 text-[12px] mt-0.5 leading-relaxed">${d.comment.body}</p>
            </div>`;
            const list = document.getElementById('comments');
            const empty = list.querySelector('.text-center');
            if (empty) empty.remove();
            list.appendChild(el);
            const cnt = document.getElementById('dcc'); if (cnt) cnt.textContent = d.total;
            inp.value = ''; inp.disabled = false;
            list.scrollTop = list.scrollHeight;
        }

        // Follow
        async function doFollow(id, btn) {
            const r = await fetch(`/users/${id}/follow`, {
                method: 'POST', headers: { 'X-CSRF-TOKEN': _c, 'Content-Type': 'application/json' }
            });
            const d = await r.json();
            if (d.is_following) {
                btn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Mengikuti`;
                btn.className = btn.className.replace('primary', '');
            } else {
                btn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Ikuti`;
                btn.className += ' primary';
            }
        }

        // More menu
        function toggleMenu(btn) {
            const m = btn.nextElementSibling;
            m.classList.toggle('hidden');
            const close = e => {
                if (!btn.contains(e.target) && !m.contains(e.target)) {
                    m.classList.add('hidden');
                    document.removeEventListener('click', close);
                }
            };
            setTimeout(() => document.addEventListener('click', close), 0);
        }

        // Copy link
        function copyLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                const t = document.getElementById('toast');
                t.classList.remove('hidden');
                setTimeout(() => t.classList.add('hidden'), 2000);
            });
            document.querySelectorAll('.hidden.absolute').forEach(m => m.classList.add('hidden'));
        }
    </script>
@endpush