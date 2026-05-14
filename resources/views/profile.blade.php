@extends('layouts.app')
@section('content')
    <div>
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 mb-8">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                    <p class="text-gray-400 text-sm mt-1">{{ $user->email }}</p>
                    <p class="text-gray-500 text-xs mt-1">
                        Bergabung {{ $user->created_at->translatedFormat('d F Y') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold">{{ $photos->count() }}</p>
                    <p class="text-gray-400 text-xs">Total foto</p>
                </div>
            </div>
        </div>


        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 mb-8">
            <h3 class="font-semibold mb-4">Ganti Password</h3>
            <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Password Lama</label>
                        <input type="password" name="current_password"
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white focus:outline-none focus:border-violet-500">
                        @error('current_password')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Password Baru</label>
                        <input type="password" name="password"
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white focus:outline-none focus:border-violet-500">
                        @error('password')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Konfirmasi</label>
                        <input type="password" name="password_confirmation"
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white focus:outline-none focus:border-violet-500">
                    </div>
                </div>
                <button type="submit"
                    class="px-6 py-2 bg-violet-600 hover:bg-violet-700 rounded-lg text-sm transition-colors">
                    Simpan Password
                </button>
            </form>
        </div>

        <h3 class="font-semibold mb-4">Foto Kamu ({{ $photos->count() }})</h3>

        @if($photos->isEmpty())
            <p class="text-gray-500 text-center py-12">Kamu belum upload foto apapun.</p>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($photos as $photo)
                    <div class="relative group rounded-xl overflow-hidden bg-gray-900 border border-gray-800">
                        <img src="{{ Storage::url($photo->file_path) }}" alt="{{ $photo->caption }}"
                            class="w-full aspect-square object-cover">

                        <span
                            class="absolute top-2 left-2 px-2 py-0.5 text-xs rounded-full font-medium
                                             {{ $photo->status === 'private' ? 'bg-gray-800 text-gray-300' : 'bg-violet-900 text-violet-300' }}">
                            {{ $photo->status }}
                        </span>

                        <div
                            class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2 p-4">
                            <p class="text-xs text-center font-medium">{{ $photo->caption }}</p>
                            <form method="POST" action="{{ route('photos.destroy', $photo) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin hapus foto ini?')"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-xs transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection