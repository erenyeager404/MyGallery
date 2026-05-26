@extends(auth()->check() ? 'layouts.app' : 'layouts.guest')
@section('title', $photo->caption)

@section('content')
    <div class="max-w-5xl mx-auto {{ auth()->check() ? '' : 'pt-28 px-6 pb-20' }}">

        <a href="{{ url()->previous() }}"
            class="inline-flex items-center gap-2 text-gray-400 hover:text-white text-sm mb-6 transition-colors">
            &#8592; Kembali
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- Foto / Slider --}}
            <div>
                @php
                    $albumPhotos = $photo->album_id
                        ? \App\Models\Photo::where('album_id', $photo->album_id)->orderBy('id')->get()
                        : collect([$photo]);
                    $totalSlides = $albumPhotos->count();
                @endphp

                <div class="rounded-2xl overflow-hidden border border-white/10 relative">
                    <div class="flex transition-transform duration-300" id="detailSlider"
                        style="width: {{ $totalSlides * 100 }}%">
                        @foreach($albumPhotos as $ap)
                            @foreach($ap->files as $file)
                                <div style="width: {{ 100 / $totalSlides }}%">
                                    <img src="{{ $file->url }}" alt="{{ $ap->caption }}" class="w-full object-cover">
                                </div>
                            @endforeach
                        @endforeach
                    </div>

                    @if($totalSlides > 1)
                        <button onclick="detailSlide(-1)" class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9
                                           bg-black/60 hover:bg-black/80 text-white rounded-full
                                           flex items-center justify-center transition-colors">
                            &#8592;
                        </button>
                        <button onclick="detailSlide(1)" class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9
                                           bg-black/60 hover:bg-black/80 text-white rounded-full
                                           flex items-center justify-center transition-colors">
                            &#8594;
                        </button>
                        <div class="absolute bottom-3 left-0 right-0 flex justify-center gap-1.5">
                            @for($i = 0; $i < $totalSlides; $i++)
                                <div
                                    class="detail-dot w-2 h-2 rounded-full {{ $i === 0 ? 'bg-white' : 'bg-white/40' }} transition-colors">
                                </div>
                            @endfor
                        </div>
                    @endif
                </div>

                {{-- Thumbnail strip --}}
                @if($totalSlides > 1)
                    <div class="flex gap-2 mt-3 overflow-x-auto pb-2">
                        @foreach($albumPhotos as $i => $ap)
                            <button onclick="detailGoTo({{ $i }})" id="thumb-{{ $i }}"
                                class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-colors {{ $i === 0 ? 'border-violet-500' : 'border-transparent' }}">
                                <img src="{{ $ap->files->first()->url }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex flex-col">

                {{-- User --}}
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $photo->user->avatar_url }}" alt="{{ $photo->user->name }}"
                            class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <p class="font-semibold text-sm">{{ $photo->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $photo->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @auth
                        @if(auth()->id() !== $photo->user_id)
                                <button onclick="toggleFollow({{ $photo->user_id }}, this)" class="px-4 py-1.5 text-xs rounded-full font-medium transition-colors
                                                       {{ auth()->user()->isFollowing($photo->user_id)
                            ? 'bg-gray-700 text-gray-300 hover:bg-red-900/30 hover:text-red-400'
                            : 'bg-violet-600 hover:bg-violet-700 text-white' }}">
                                    {{ auth()->user()->isFollowing($photo->user_id) ? '&#10003; Mengikuti' : '&#43; Ikuti' }}
                                </button>
                        @endif
                    @endauth
                </div>

                <h1 class="text-xl font-bold mb-2">{{ $photo->caption }}</h1>

                @if($photo->description)
                    <p class="text-gray-400 text-sm mb-4 leading-relaxed">{{ $photo->description }}</p>
                @endif

                @if($photo->tags->isNotEmpty())
                    <div class="flex flex-wrap gap-1 mb-4">
                        @foreach($photo->tags as $tag)
                            <a href="{{ route('search') }}?q={{ $tag->name }}"
                                class="px-2 py-0.5 bg-gray-800 text-gray-400 text-xs rounded-full hover:text-violet-400 transition-colors">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Stats --}}
                <div class="flex items-center gap-5 py-4 border-y border-gray-800 mb-4">
                    @auth
                        <button onclick="toggleLikeDetail({{ $photo->id }}, this)"
                            class="flex items-center gap-2 transition-colors
                                           {{ $photo->isLikedBy(auth()->id()) ? 'text-red-400' : 'text-gray-400 hover:text-red-400' }}">
                            <span>{{ $photo->isLikedBy(auth()->id()) ? '&#10084;' : '&#9825;' }}</span>
                            <span id="detail-like-count">{{ $photo->likes->count() }}</span>
                        </button>
                    @else
                        <button onclick="openAuthModal('login','like')"
                            class="flex items-center gap-2 text-gray-400 hover:text-red-400 transition-colors">
                            <span>&#9825;</span>
                            {{ $photo->likes->count() }}
                        </button>
                    @endauth

                    <span class="flex items-center gap-2 text-gray-400">
                        <span>&#128172;</span>
                        <span id="detail-comment-count">{{ $photo->comments->count() }}</span>
                    </span>

                    <span class="flex items-center gap-2 text-gray-500 text-sm ml-auto">
                        &#128065; {{ number_format($photo->views) }}
                    </span>
                </div>

                {{-- Tombol aksi --}}
                <div class="flex gap-2 mb-4">
                    <a href="{{ route('photos.download', $photo) }}" class="flex-1 flex items-center justify-center gap-2 py-2.5
                              bg-gray-800 hover:bg-gray-700 border border-gray-700
                              rounded-xl text-sm text-gray-300 transition-colors">
                        &#8681; Download
                    </a>
                    <button onclick="copyDetailLink()" class="flex-1 flex items-center justify-center gap-2 py-2.5
                                   bg-gray-800 hover:bg-gray-700 border border-gray-700
                                   rounded-xl text-sm text-gray-300 transition-colors">
                        &#128279; <span id="copyBtnText">Salin Link</span>
                    </button>
                    <a href="https://wa.me/?text={{ urlencode($photo->caption . ' ' . route('photos.show', $photo)) }}"
                        target="_blank" class="flex items-center justify-center gap-2 px-4 py-2.5
                              bg-green-800 hover:bg-green-700 rounded-xl text-sm text-white transition-colors">
                        &#128242; WA
                    </a>
                </div>

                {{-- Komentar --}}
                <div class="flex-1 overflow-y-auto max-h-56 space-y-3 mb-4" id="detail-comments">
                    @forelse($photo->comments as $comment)
                        <div class="flex gap-2 text-sm">
                            <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}"
                                class="w-7 h-7 rounded-full flex-shrink-0 object-cover">
                            <div>
                                <span class="text-violet-400 font-medium text-xs">{{ $comment->user->name }}</span>
                                <span class="text-gray-300 text-xs ml-1">{{ $comment->body }}</span>
                                <p class="text-gray-600 text-xs mt-0.5">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600 text-xs text-center py-4">Belum ada komentar</p>
                    @endforelse
                </div>

                {{-- Input komentar --}}
                @auth
                    <div class="flex gap-2">
                        <input type="text" id="detail-comment-input" placeholder="Tulis komentar..."
                            class="flex-1 px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-xl
                                          text-sm text-white placeholder-gray-500 focus:outline-none focus:border-violet-500 transition-colors">
                        <button onclick="submitDetailComment({{ $photo->id }})"
                            class="px-4 py-2.5 bg-violet-600 hover:bg-violet-700 rounded-xl text-sm transition-colors">
                            Kirim
                        </button>
                    </div>
                @else
                    <button onclick="openAuthModal('login','comment')" class="w-full py-2.5 bg-gray-800 hover:bg-gray-700 border border-gray-700
                                       rounded-xl text-sm text-gray-400 transition-colors">
                        &#128172; Login untuk berkomentar
                    </button>
                @endauth

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
        let detailIndex = 0;
        const totalSlides = {{ $totalSlides }};

        function detailSlide(dir) {
            detailIndex = (detailIndex + dir + totalSlides) % totalSlides;
            detailGoTo(detailIndex);
        }
        function detailGoTo(i) {
            detailIndex = i;
            const s = document.getElementById('detailSlider');
            s.style.transform = `translateX(-${i * (100 / totalSlides)}%)`;
            document.querySelectorAll('.detail-dot').forEach((d, j) => {
                d.classList.toggle('bg-white', j === i);
                d.classList.toggle('bg-white/40', j !== i);
            });
            document.querySelectorAll('[id^="thumb-"]').forEach((t, j) => {
                t.classList.toggle('border-violet-500', j === i);
                t.classList.toggle('border-transparent', j !== i);
            });
        }

        async function toggleLikeDetail(photoId, btn) {
            const res = await fetch(`/photos/${photoId}/like`, {
                method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' }
            });
            const data = await res.json();
            const icon = btn.querySelector('span');
            document.getElementById('detail-like-count').textContent = data.total;
            if (data.liked) { icon.innerHTML = '&#10084;'; btn.classList.replace('text-gray-400', 'text-red-400'); }
            else { icon.innerHTML = '&#9825;'; btn.classList.replace('text-red-400', 'text-gray-400'); }
        }

        async function submitDetailComment(photoId) {
            const input = document.getElementById('detail-comment-input');
            const body = input.value.trim();
            if (!body) return;
            const res = await fetch(`/photos/${photoId}/comment`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' },
                body: JSON.stringify({ body })
            });
            const data = await res.json();
            const list = document.getElementById('detail-comments');
            const div = document.createElement('div');
            div.className = 'flex gap-2 text-sm';
            div.innerHTML = `
            <div class="w-7 h-7 rounded-full bg-violet-800 flex-shrink-0 flex items-center justify-center text-xs font-bold">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div>
                <span class="text-violet-400 font-medium text-xs">${data.comment.user_name}</span>
                <span class="text-gray-300 text-xs ml-1">${data.comment.body}</span>
            </div>
        `;
            list.appendChild(div);
            const cnt = document.getElementById('detail-comment-count');
            if (cnt) cnt.textContent = data.total;
            input.value = '';
            list.scrollTop = list.scrollHeight;
        }

        async function toggleFollow(userId, btn) {
            const res = await fetch(`/users/${userId}/follow`, {
                method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' }
            });
            const data = await res.json();
            if (data.is_following) {
                btn.innerHTML = '&#10003; Mengikuti';
                btn.className = btn.className.replace('bg-violet-600 hover:bg-violet-700 text-white', 'bg-gray-700 text-gray-300 hover:bg-red-900/30 hover:text-red-400');
            } else {
                btn.innerHTML = '&#43; Ikuti';
                btn.className = btn.className.replace('bg-gray-700 text-gray-300 hover:bg-red-900/30 hover:text-red-400', 'bg-violet-600 hover:bg-violet-700 text-white');
            }
        }

        function copyDetailLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const btn = document.getElementById('copyBtnText');
                btn.textContent = '&#10003; Disalin!';
                setTimeout(() => btn.textContent = 'Salin Link', 2000);
            });
        }
    </script>
@endpush