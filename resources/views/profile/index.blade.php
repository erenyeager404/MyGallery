@extends('layouts.app')
@section('title', 'Profile')

@section('content')
    <div>
        {{-- Profile Header --}}
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 mb-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                        class="w-16 h-16 rounded-full object-cover border-2 border-violet-500">
                    <div>
                        <h2 class="text-xl font-bold">{{ $user->name }}</h2>
                        <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                        <p class="text-gray-500 text-xs mt-0.5">
                            Bergabung {{ $user->created_at->format('d M Y') }}
                        </p>
                    </div>
                </div>
                <div class="flex gap-6 text-center">
                    <div>
                        <p class="text-xl font-bold">{{ $photos->count() }}</p>
                        <p class="text-gray-500 text-xs">Foto</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold">{{ $user->following()->count() }}</p>
                        <p class="text-gray-500 text-xs">Following</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold">{{ $user->followers()->count() }}</p>
                        <p class="text-gray-500 text-xs">Followers</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ganti Password --}}
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 mb-6">
            <h3 class="font-semibold mb-4">&#128274; Ganti Password</h3>
            <form method="POST" action="{{ route('profile.password') }}" class="grid grid-cols-3 gap-4">
                @csrf
                <div>
                    <label class="block text-xs text-gray-400 mb-1">Password Lama</label>
                    <input type="password" name="current_password" class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg
                                  text-sm text-white focus:outline-none focus:border-violet-500">
                    @error('current_password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1">Password Baru</label>
                    <input type="password" name="password" class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg
                                  text-sm text-white focus:outline-none focus:border-violet-500">
                    @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1">Konfirmasi</label>
                    <input type="password" name="password_confirmation" class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg
                                  text-sm text-white focus:outline-none focus:border-violet-500">
                </div>
                <div class="col-span-3">
                    <button type="submit"
                        class="px-6 py-2 bg-violet-600 hover:bg-violet-700 rounded-lg text-sm transition-colors">
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>

        {{-- Foto milik user --}}
        <h3 class="font-semibold mb-4">&#128247; Foto Kamu ({{ $photos->count() }})</h3>
        @if($photos->isEmpty())
            <div class="text-center py-16 text-gray-500">
                <p class="text-4xl mb-3">&#128247;</p>
                <p>Kamu belum upload foto apapun.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($photos as $photo)
                    @if($photo->files->isNotEmpty())
                        <a href="{{ route('photos.show', $photo) }}"
                            class="relative group rounded-xl overflow-hidden bg-white/10 border border-white/20 block">
                            <img src="{{ $photo->files->first()->url }}" alt="{{ $photo->caption }}"
                                class="w-full aspect-square object-cover group-hover:opacity-90 transition-opacity">

                            {{-- Badge status --}}
                            <span class="absolute top-2 left-2 px-2 py-0.5 text-xs rounded-full
                                                     {{ $photo->status === 'private'
                                ? 'bg-gray-800/80 text-gray-300'
                                : 'bg-violet-900/80 text-violet-300' }}">
                                {{ $photo->status === 'private' ? '&#128274;' : '&#127757;' }}
                                {{ $photo->status }}
                            </span>

                            {{-- Overlay --}}
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100
                                                    transition-opacity flex items-end justify-between p-3">
                                <p class="text-xs font-medium truncate flex-1">{{ $photo->caption }}</p>
                                <form method="POST" action="{{ route('photos.destroy', $photo) }}" class="ml-2">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="event.stopPropagation(); return confirm('Hapus foto ini?')" class="w-7 h-7 bg-red-600 hover:bg-red-700 rounded-lg
                                                               flex items-center justify-center text-xs transition-colors">
                                        &#128465;
                                    </button>
                                </form>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
@endsection