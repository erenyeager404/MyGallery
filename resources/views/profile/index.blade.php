@extends('layouts.app')
@section('title', 'Profile')

@section('content')
    <div>
        <div class="card-info mb-6">
            <div class="flex items-start gap-6">
                <div class="profile-av-wrap">
                    <img src="{{ $user->avatar_url }}" class="profile-av" id="avImg" alt="{{ $user->name }}">
                    <label for="avInput" class="profile-av-edit" title="Ganti foto">✎
                        <input type="file" id="avInput" class="hidden" accept="image/*" onchange="uploadAv(this)">
                    </label>
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                    <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                    <p class="text-gray-500 text-xs mt-0.5">Bergabung {{ $user->created_at->format('d M Y') }}</p>
                    <div class="profile-stats">
                        <div class="profile-stat"><span class="profile-stat-num">{{ $photos->count() }}</span><span
                                class="profile-stat-lbl">Foto</span></div>
                        <div class="profile-stat"><span
                                class="profile-stat-num">{{ $user->following()->count() }}</span><span
                                class="profile-stat-lbl">Following</span></div>
                        <div class="profile-stat"><span
                                class="profile-stat-num">{{ $user->followers()->count() }}</span><span
                                class="profile-stat-lbl">Followers</span></div>
                    </div>
                </div>
                <a href="{{ route('profile.password.page') }}" class="btn-ghost text-sm self-start">🔒 Ganti Password</a>
            </div>
        </div>

        <div class="sort-bar">
            <span class="text-sm text-gray-400 mr-1">Urutkan:</span>
            @foreach(['newest' => 'Terbaru', 'oldest' => 'Terlama', 'public' => '🌍 Public', 'private' => '🔒 Private'] as $val => $lbl)
                <a href="{{ route('profile') }}?sort={{ $val }}"
                    class="sort-btn {{ request('sort', 'newest') === $val ? 'active' : '' }}">{{ $lbl }}</a>
            @endforeach
        </div>

        <h3 class="font-semibold mb-4 text-gray-300">📷 Foto Kamu ({{ $photos->count() }})</h3>

        @if($photos->isEmpty())
            <div class="text-center py-20 text-gray-600">
                <p class="text-6xl mb-4">📷</p>
                <p class="text-lg mb-4">Belum ada foto</p>
                <a href="{{ route('upload') }}" class="btn-primary">Upload sekarang</a>
            </div>
        @else
            <div class="photo-grid-profile">
                @foreach($photos as $photo)
                    @if($photo->files->isNotEmpty())
                        <div class="photo-grid-item group" onclick="window.location='{{ route('photos.show', $photo) }}'">
                            <img src="{{ $photo->files->first()->url }}" alt="{{ $photo->caption }}" class="photo-grid-img">
                            <div class="photo-grid-status">
                                @if($photo->status === 'private')
                                    <span class="badge-gray text-xs">🔒</span>
                                @else
                                    <span class="badge-violet text-xs">🌍</span>
                                @endif
                            </div>
                            <div class="photo-grid-overlay">
                                <p class="text-xs text-white truncate flex-1">{{ $photo->caption }}</p>
                                <form method="POST" action="{{ route('photos.destroy', $photo) }}" onclick="event.stopPropagation()">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus foto?')"
                                        class="w-7 h-7 bg-red-600 hover:bg-red-700 rounded-lg flex items-center justify-center text-xs">🗑</button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
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
@endsection

@push('scripts')
    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

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

        async function uploadAv(input) {
            const f = input.files[0]; if (!f) return;
            const fd = new FormData(); fd.append('avatar', f); fd.append('_token', csrf);
            const r = await fetch('{{ route("profile.avatar") }}', { method: 'POST', body: fd });
            const d = await r.json();
            if (d.url) document.getElementById('avImg').src = d.url;
        }
    </script>
@endpush