@extends('layouts.app')
@section('title', 'Upload')

@section('content')
    <div class="upload-wrap">
        <h2 class="text-2xl font-bold mb-8">📷 Upload Kenangan</h2>
        <form method="POST" action="{{ route('upload') }}" enctype="multipart/form-data" class="upload-card" id="uForm">
            @csrf
            <div>
                <label class="form-label">Foto (maks. 10) *</label>
                <div class="dropzone" id="dz" onclick="document.getElementById('pi').click()"
                    ondragover="event.preventDefault();this.classList.add('drag-over')"
                    ondragleave="this.classList.remove('drag-over')" ondrop="onDrop(event)">
                    <div class="drop-placeholder" id="dp">
                        <span class="drop-placeholder-icon text-5xl mb-3">📷</span>
                        <p class="text-sm font-medium">Klik atau seret foto ke sini</p>
                        <p class="text-xs mt-1 text-gray-600">JPG, PNG, WebP — maks 5MB per foto</p>
                    </div>
                    <div class="preview-grid hidden" id="pg"></div>
                </div>
                <input type="file" name="photos[]" id="pi" accept="image/*" multiple class="hidden"
                    onchange="handleFiles(this.files)">
                @error('photos') <p class="form-error">{{ $message }}</p> @enderror
                @error('photos.*') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Caption *</label>
                <input type="text" name="caption" value="{{ old('caption') }}" placeholder="Tulis caption..."
                    class="form-input-glass">
                @error('caption') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Deskripsi <span class="text-gray-600">(opsional)</span></label>
                <textarea name="description" rows="3" placeholder="Ceritakan momen ini..."
                    class="form-textarea">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="form-label">Tags <span class="text-gray-600">(pisah koma)</span></label>
                <input type="text" name="tags" value="{{ old('tags') }}" placeholder="alam, pantai, sunset"
                    class="form-input-glass">
            </div>

            <div>
                <label class="form-label">Visibilitas</label>
                <div class="flex gap-3">
                    <label class="vis-option selected flex-1" id="vPub" onclick="setVis('public')">
                        <input type="radio" name="status" value="public" checked class="hidden">
                        <div>
                            <p class="text-sm font-medium">🌍 Public</p>
                            <p class="text-xs text-gray-500">Semua orang bisa lihat</p>
                        </div>
                    </label>
                    <label class="vis-option flex-1" id="vPrv" onclick="setVis('private')">
                        <input type="radio" name="status" value="private" class="hidden">
                        <div>
                            <p class="text-sm font-medium">🔒 Private</p>
                            <p class="text-xs text-gray-500">Hanya kamu</p>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-submit">⬆ Upload Foto</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        let FILES = [];

        function handleFiles(incoming) {
            Array.from(incoming).forEach(f => { if (FILES.length < 10) FILES.push(f); });
            render();
        }
        function onDrop(e) {
            e.preventDefault();
            document.getElementById('dz').classList.remove('drag-over');
            handleFiles(e.dataTransfer.files);
        }
        function render() {
            const pg = document.getElementById('pg'), dp = document.getElementById('dp');
            if (!FILES.length) { pg.classList.add('hidden'); dp.classList.remove('hidden'); sync(); return; }
            dp.classList.add('hidden'); pg.classList.remove('hidden');
            pg.innerHTML = '';
            FILES.forEach((f, i) => {
                const r = new FileReader();
                r.onload = e => {
                    const d = document.createElement('div'); d.className = 'preview-item';
                    d.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">${i === 0 ? '<div class="preview-badge">Utama</div>' : ''}<button type="button" onclick="removeFile(${i})" class="preview-remove">✕</button>`;
                    pg.insertBefore(d, pg.querySelector('.preview-add'));
                };
                r.readAsDataURL(f);
            });
            if (FILES.length < 10) {
                const a = document.createElement('div'); a.className = 'preview-add';
                a.innerHTML = '＋'; a.onclick = () => document.getElementById('pi').click();
                pg.appendChild(a);
            }
            sync();
        }
        function removeFile(i) { FILES.splice(i, 1); render(); }
        function sync() {
            const dt = new DataTransfer();
            FILES.forEach(f => dt.items.add(f));
            document.getElementById('pi').files = dt.files;
        }
        function setVis(v) {
            document.querySelector(`input[value="${v}"]`).checked = true;
            document.getElementById('vPub').classList.toggle('selected', v === 'public');
            document.getElementById('vPrv').classList.toggle('selected', v === 'private');
        }
    </script>
@endpush