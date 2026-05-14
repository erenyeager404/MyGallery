@extends('layouts.app')
@section('content')
    <div class="max-w-xl mx-auto">
        <h2 class="text-2xl font-bold mb-8">Upload Foto</h2>

        <form method="POST" action="{{ route('upload') }}" enctype="multipart/form-data"
            class="bg-gray-900 border border-gray-800 rounded-2xl p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm text-gray-400 mb-2">Foto</label>
                <div id="preview-container"
                    class="w-full h-64 bg-gray-800 border-2 border-dashed border-gray-700 rounded-xl flex items-center justify-center overflow-hidden cursor-pointer"
                    onclick="document.getElementById('photo-input').click()">
                    <img id="preview-img" src="" alt="" class="hidden w-full h-full object-cover">
                    <div id="preview-placeholder" class="text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm">Klik untuk pilih foto</p>
                        <p class="text-xs mt-1">JPG, PNG, WebP — maks 5MB</p>
                    </div>
                </div>
                <input type="file" name="photo" id="photo-input" accept="image/*" class="hidden"
                    onchange="previewPhoto(this)">
                @error('photo')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1">Caption</label>
                <input type="text" name="caption" value="{{ old('caption') }}"
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-violet-500 transition-colors"
                    placeholder="Tulis caption singkat...">
                @error('caption')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1">Deskripsi <span
                        class="text-gray-600">(opsional)</span></label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-violet-500 transition-colors resize-none"
                    placeholder="Ceritakan momen di balik foto ini...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-3">Visibilitas</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="public" checked class="accent-violet-500">
                        <div>
                            <p class="text-sm font-medium">Public</p>
                            <p class="text-xs text-gray-500">Muncul di dashboard semua user</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="private" class="accent-violet-500">
                        <div>
                            <p class="text-sm font-medium">Private</p>
                            <p class="text-xs text-gray-500">Hanya terlihat di profile kamu</p>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit"
                class="w-full py-3 bg-violet-600 hover:bg-violet-700 rounded-xl font-medium transition-colors">
                Upload Foto
            </button>
        </form>
    </div>

    <script>
        function previewPhoto(input) {
            const file = input.files[0];
            if (!file) return;

            const reader = new FileReader();

            reader.onload = function (e) {
                const img = document.getElementById('preview-img');
                const placeholder = document.getElementById('preview-placeholder');
                img.src = e.target.result;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };

            reader.readAsDataURL(file);
        }
    </script>
@endsection