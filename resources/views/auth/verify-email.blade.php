@extends('layouts.guest')
@section('title', 'Verifikasi Email')
@section('content')
    <div class="min-h-screen flex items-center justify-center px-6">
        <div class="w-full max-w-md text-center py-20">
            <div class="text-7xl mb-6">✉</div>
            <h1 class="text-2xl font-bold mb-2">Cek Email Kamu</h1>
            <p class="text-gray-400 text-sm mb-1">Link verifikasi dikirim ke</p>
            <p class="text-violet-400 font-semibold mb-6">{{ auth()->user()->email }}</p>
            <p class="text-gray-600 text-xs mb-10">
                Buka email dan klik tombol verifikasi.<br>
                Tidak ada di inbox? Cek folder <span class="text-gray-400">Spam</span>.
            </p>
            @foreach(['success', 'warning', 'message'] as $key)
                @if(session($key))
                    <div
                        class="mb-4 p-3 rounded-xl text-sm
                                {{ $key === 'success' ? 'flash-success' : ($key === 'warning' ? 'flash-warning' : 'bg-blue-900/30 border border-blue-700 text-blue-300') }}">
                        {{ session($key) }}
                    </div>
                @endif
            @endforeach
            <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                @csrf
                <button type="submit" class="btn-submit">✉ Kirim Ulang Email</button>
            </form>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-gray-300 text-sm transition-colors">
                    ← Keluar dan ganti akun
                </button>
            </form>
        </div>
    </div>
@endsection