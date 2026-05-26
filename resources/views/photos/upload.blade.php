@extends('layouts.app')
@section('title', 'Upload Foto')

@section('content')
    <div class="max-w-xl mx-auto">
        <h2 class="text-2xl font-bold mb-8">&#128247; Upload Foto</h2>

        <form method="POST" action="{{ route('upload') }}" enctype="multipart/form-data"
            class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 space-y-5">
            @csrf

            {{-- Drop zone --}}
            <div>
                <label class="block text-sm text-gray-300 mb-2">Foto (maks. 10)</label>
                <div id="dropzone" class="w-full min-h-48 bg-white/5 border-2 border-dashed border-white/20
                            rounded-xl flex flex-wrap gap-3 p-3 cursor-pointer hover:border-violet-500
                            transition-colors" onclick="document.getElementById('photoInput').click()">
                    <div id="dropPlaceholder" class="w-full flex flex-col items-center justify-center py-8 text-gray-500">
                        <span class="text-4xl mb-2">&#128247;</span>
                        <p class="text-sm">Klik untuk pilih foto</p>
                        <p class="text-xs mt-1">JPG, PNG, WebP &mdash; maks 5MB per foto</p>
                    </div>
                </div>
                <input type="file" name="photos[]" id="photoInput" accept="image/*" multiple class="hidden"
                    onchange="previewPhotos(this)">
                @error('photos') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                @error('photos.*') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Caption --}}
            <div>
                <label class="block text-sm text-gray-300 mb-1.5">Caption <span class="text-red-400">*</span></label>
                <input type="text" name="caption" value="{{ old('caption') }}" placeholder="Tulis caption singkat..." class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white
                              placeholder-gray-500 focus:outline-none focus:border-violet-500 transition-colors">
                @error('caption') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm text-gray-300 mb-1.5">
                    Deskripsi <span class="text-gray-600">(opsional)</span>
                </label>
                <textarea name="description" rows="3" placeholder="Ceritakan momen di balik foto ini..."
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white
                                 placeholder-gray-500 focus:outline-none focus:border-violet-500 transition-colors resize-none">{{ old('description') }}</textarea>
            </div>

            {{-- Tags --}}
            <div>
                <label class="block text-sm text-gray-300 mb-1.5">
                    Tags <span class="text-gray-600">(pisah dengan koma)</span>
                </label>
                <input type="text" name="tags" value="{{ old('tags') }}" placeholder="alam, pantai, sunset, travel" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white
                              placeholder-gray-500 focus:outline-none focus:border-violet-500 transition-colors">
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm text-gray-300 mb-3">Visibilitas</label>
                <div class="flex gap-6">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="radio" name="status" value="public" checked class="mt-0.5 accent-violet-500">
                        <div>
                            <p class="text-sm font-medium">&#127757; Public</p>
                            <p class="text-xs text-gray-500">Muncul di dashboard semua user</p>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="radio" name="status" value="private" class="mt-0.5 accent-violet-500">
                        <div>
                            <p class="text-sm font-medium">&#128274; Private</p>
                            <p class="text-xs text-gray-500">Hanya terlihat di profile kamu</p>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit"
                class="w-full py-3 bg-violet-600 hover:bg-violet-700 rounded-xl font-medium transition-colors">
                &#8679; Upload Foto
            </button>
        </form>
    </div>

@endsection

@push('scripts')
    <script>
        function previewPhotos(input) {
            const files = Array.from(input.files);
            const dz = document.getElementById('dropzone');
            const ph = document.getElementById('dropPlaceholder');
            dz.querySelectorAll('.preview-item').forEach(el => el.remove());
            if (files.length === 0) { ph.classList.remove('hidden'); return; }
            ph.classList.add('hidden');
            files.forEach((file, i) => {
                const reader = new FileReader();
                reader.onload = e => {
                    const div = document.createElement('div');
                    div.className = 'preview-item relative w-24 h-24 rounded-lg overflow-hidden flex-shrink-0';
                    div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover">
                    ${i === 0 ? '<span class="absolute bottom-0 left-0 right-0 bg-violet-600 text-white text-xs text-center py-0.5">Utama</span>' : ''}
                `;
                    dz.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
@endpush