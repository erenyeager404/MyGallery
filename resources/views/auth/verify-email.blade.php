@extends('layouts.guest')
@section('title', 'Verifikasi Email')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-6">
        <div class="w-full max-w-md text-center">
            <div class="text-6xl mb-6">&#128140;</div>
            <h1 class="text-2xl font-bold mb-2">Cek Email Kamu</h1>
            <p class="text-gray-400 text-sm mb-2">Link verifikasi sudah dikirim ke</p>
            <p class="text-violet-400 font-semibold mb-6">{{ auth()->user()->email }}</p>
            <p class="text-gray-600 text-xs mb-8">
                Buka email dan klik tombol verifikasi.<br>
                Cek folder <span class="text-gray-400">Spam</span> jika tidak ada di inbox.
            </p>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-900/30 border border-green-700 text-green-300 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('warning'))
                <div class="mb-4 p-3 bg-yellow-900/30 border border-yellow-700 text-yellow-300 rounded-xl text-sm">
                    {{ session('warning') }}
                </div>
            @endif
            @if(session('message'))
                <div class="mb-4 p-3 bg-blue-900/30 border border-blue-700 text-blue-300 rounded-xl text-sm">
                    {{ session('message') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                @csrf
                <button type="submit"
                    class="w-full py-3 bg-violet-600 hover:bg-violet-700 rounded-xl font-medium transition-colors text-sm">
                    &#128231; Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-gray-300 text-sm transition-colors">
                    &#8592; Keluar dan ganti akun
                </button>
            </form>
        </div>
    </div>
@endsection