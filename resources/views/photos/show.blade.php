@php
    $isPartial = request()->boolean('partial');
    $albumPhotos = $photo->album_id
        ? \App\Models\Photo::where('album_id',$photo->album_id)->with('files')->orderBy('id')->get()
        : collect([$photo]);
    $total = $albumPhotos->count();
@endphp

@if(!$isPartial)
    @extends(auth()->check()?'layouts.app':'layouts.guest')
    @section('title',$photo->caption)
    @section('content')
@endif

<div class="{{ $isPartial ? 'p-6' : 'max-w-5xl mx-auto pt-6' }}">

    @if($isPartial)
        <div class="flex justify-end mb-4">
            <button onclick="closeDetail()" class="modal-close">✕</button>
        </div>
    @else
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white text-sm mb-6 transition-colors">← Kembali</a>
    @endif

    <div class="photo-detail-grid">

        {{-- Foto --}}
        <div>
            <div class="photo-main-frame">
                <div class="flex transition-transform duration-300" id="ds" style="width:{{ $total*100 }}%">
                    @foreach($albumPhotos as $ap)
                        @foreach($ap->files as $f)
                            <div style="width:{{ 100/$total }}%">
                                <img src="{{ $f->url }}" alt="{{ $ap->caption }}" class="w-full object-cover"
                                     {{ $isPartial ? 'onload=updateDetailBg(this.src)' : '' }}>
                            </div>
                        @endforeach
                    @endforeach
                </div>
                @if($total>1)
                    <button onclick="dSlide(-1)" class="slider-prev">‹</button>
                    <button onclick="dSlide(1)"  class="slider-next">›</button>
                    <div class="slider-dots">
                        @for($i=0;$i<$total;$i++)
                            <div class="slider-dot {{ $i===0?'active':'' }}" id="dd-{{ $i }}"></div>
                        @endfor
                    </div>
                @endif
            </div>
            @if($total>1)
                <div class="thumb-strip">
                    @foreach($albumPhotos as $i=>$ap)
                        <button onclick="dGoTo({{ $i }})" id="dt-{{ $i }}" class="thumb-item {{ $i===0?'active':'' }}">
                            <img src="{{ $ap->files->first()->url }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="info-panel">
            <div class="uploader-row">
                <div class="flex items-center gap-3">
                    <img src="{{ $photo->user->avatar_url }}" class="uploader-av">
                    <div>
                        <p class="font-semibold text-sm">{{ $photo->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $photo->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @auth
                    @if(auth()->id()!==$photo->user_id)
                        <button onclick="toggleFollow({{ $photo->user_id }},this)"
                                class="{{ auth()->user()->isFollowing($photo->user_id)?'btn-ghost text-xs':'btn-primary text-xs' }}">
                            {{ auth()->user()->isFollowing($photo->user_id)?'✓ Mengikuti':'+ Ikuti' }}
                        </button>
                    @endif
                @endauth
            </div>

            <h1 class="photo-caption">{{ $photo->caption }}</h1>
            @if($photo->description)
                <p class="photo-desc">{{ $photo->description }}</p>
            @endif

            @if($photo->tags->isNotEmpty())
                <div class="flex flex-wrap gap-1 mb-4">
                    @foreach($photo->tags as $tag)
                        <a href="{{ route('search') }}?q={{ $tag->name }}" class="tag-chip">#{{ $tag->name }}</a>
                    @endforeach
                </div>
            @endif

            <div class="stats-row">
                @auth
                    <button onclick="dLike({{ $photo->id }},this)"
                            class="stat-btn {{ $photo->isLikedBy(auth()->id())?'text-red-400':'text-gray-400 hover:text-red-400' }}">
                        <span>{{ $photo->isLikedBy(auth()->id())?'♥':'♡' }}</span>
                        <span id="dlc">{{ $photo->likes->count() }}</span>
                    </button>
                @else
                    <button onclick="openModal('login','like')" class="stat-btn text-gray-400 hover:text-red-400">
                        ♡ {{ $photo->likes->count() }}
                    </button>
                @endauth
                <span class="stat-btn text-gray-400">◯ <span id="dcc">{{ $photo->comments->count() }}</span></span>
                <span class="stat-btn text-gray-500 ml-auto">👁 {{ number_format($photo->views) }}</span>
            </div>

            <div class="photo-actions">
                <a href="{{ route('photos.download',$photo) }}" class="photo-action-btn">⬇ Download</a>
                <button onclick="dCopy('{{ route('photos.show',$photo) }}')" class="photo-action-btn" id="dCopyBtn">🔗 Salin Link</button>
                <a href="https://wa.me/?text={{ urlencode($photo->caption.' '.route('photos.show',$photo)) }}" target="_blank" class="photo-action-btn">📱 WA</a>
            </div>

            <div class="comments-list" id="dcl">
                @forelse($photo->comments as $c)
                    <div class="comment-d-item">
                        <img src="{{ $c->user->avatar_url }}" class="comment-d-av">
                        <div>
                            <span class="text-violet-300 font-medium text-xs">{{ $c->user->name }}</span>
                            <span class="text-gray-300 text-xs ml-1">{{ $c->body }}</span>
                            <p class="text-gray-600 text-xs mt-0.5">{{ $c->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-600 text-xs py-8">Belum ada komentar</p>
                @endforelse
            </div>

            @auth
                <div class="comment-d-input">
                    <input type="text" id="dci" placeholder="Tulis komentar..." class="form-input flex-1 py-2 text-sm">
                    <button onclick="dComment({{ $photo->id }})" class="btn-primary px-4">Kirim</button>
                </div>
            @else
                <button onclick="openModal('login','comment')" class="btn-ghost w-full text-sm mt-2">◯ Login untuk berkomentar</button>
            @endauth
        </div>
    </div>
</div>

<script>
const _csrf = document.querySelector('meta[name="csrf-token"]')?.content||'';
let dIdx=0, dTot={{ $total }};

function dSlide(dir) { dIdx=(dIdx+dir+dTot)%dTot; dGoTo(dIdx); }
function dGoTo(i) {
    dIdx=i;
    const s=document.getElementById('ds');
    if(s) s.style.transform=`translateX(-${i*(100/dTot)}%)`;
    document.querySelectorAll('[id^="dd-"]').forEach((d,j)=>d.classList.toggle('active',j===i));
    document.querySelectorAll('[id^="dt-"]').forEach((t,j)=>t.classList.toggle('active',j===i));
    const imgs=document.querySelectorAll('#ds img');
    if(imgs[i]) updateDetailBg(imgs[i].src);
}
function updateDetailBg(src) {
    const bg=document.getElementById('dBgImg');
    if(bg) bg.src=src;
}

async function dLike(id,btn) {
    const r=await fetch(`/photos/${id}/like`,{method:'POST',headers:{'X-CSRF-TOKEN':_csrf,'Content-Type':'application/json'}});
    const d=await r.json();
    btn.querySelector('span').innerHTML=d.liked?'♥':'♡';
    document.getElementById('dlc').textContent=d.total;
    btn.classList.toggle('text-red-400',d.liked);
    btn.classList.toggle('text-gray-400',!d.liked);
}

async function dComment(id) {
    const inp=document.getElementById('dci');
    const body=inp.value.trim(); if(!body) return;
    const r=await fetch(`/photos/${id}/comment`,{method:'POST',headers:{'X-CSRF-TOKEN':_csrf,'Content-Type':'application/json'},body:JSON.stringify({body})});
    const d=await r.json();
    const el=document.createElement('div'); el.className='comment-d-item';
    el.innerHTML=`<div class="w-7 h-7 rounded-full bg-violet-800 flex-shrink-0 flex items-center justify-center text-xs">${d.comment.user_name[0]}</div><div><span class="text-violet-300 font-medium text-xs">${d.comment.user_name}</span><span class="text-gray-300 text-xs ml-1">${d.comment.body}</span></div>`;
    const list=document.getElementById('dcl'); list.appendChild(el);
    const cnt=document.getElementById('dcc'); if(cnt) cnt.textContent=d.total;
    inp.value=''; list.scrollTop=list.scrollHeight;
}

async function toggleFollow(id,btn) {
    const r=await fetch(`/users/${id}/follow`,{method:'POST',headers:{'X-CSRF-TOKEN':_csrf,'Content-Type':'application/json'}});
    const d=await r.json();
    btn.textContent=d.is_following?'✓ Mengikuti':'+ Ikuti';
    btn.className=d.is_following?'btn-ghost text-xs':'btn-primary text-xs';
}

function dCopy(url) {
    navigator.clipboard.writeText(url).then(()=>{
        const b=document.getElementById('dCopyBtn');
        b.textContent='✓ Disalin!';
        setTimeout(()=>b.textContent='🔗 Salin Link',2000);
    });
}
</script>

@if(!$isPartial)
    @endsection
@endif