<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OurMemora')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-gray-950 text-white min-h-screen">

<nav class="landing-nav">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="{{ route('landing') }}" class="text-xl font-bold tracking-tight">
            Our<span class="text-violet-400">Memora</span>
        </a>
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary">Dashboard</a>
            @else
                <button onclick="openModal('login')"  class="btn-ghost">Masuk</button>
                <button onclick="openModal('register')" class="btn-primary">Daftar Gratis</button>
            @endauth
        </div>
    </div>
</nav>

@if(session('success'))
    <div class="fixed top-20 left-1/2 -translate-x-1/2 z-50 px-5 py-3
                bg-green-900/90 border border-green-700/50 text-green-200
                rounded-xl text-sm shadow-xl whitespace-nowrap backdrop-blur-md">
        ✓ {{ session('success') }}
    </div>
@endif

<main>@yield('content')</main>
@yield('modals')

@guest
    @include('layouts.partials.auth-modal')
@endguest

<script>
const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

const CTX = {
    like:    '♡  Login dulu untuk memberi like',
    save:    '◇  Login dulu untuk menyimpan foto',
    comment: '◯  Login dulu untuk menulis komentar',
};

function openModal(tab = 'login', action = null) {
    const m   = document.getElementById('authModal');
    const ctx = document.getElementById('modalCtx');
    if (!m) return;
    if (action && CTX[action]) { ctx.textContent = CTX[action]; ctx.classList.remove('hidden'); }
    else ctx.classList.add('hidden');
    switchTab(tab);
    m.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const m = document.getElementById('authModal');
    if (!m) return;
    m.classList.remove('open');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

function switchTab(tab) {
    const isL = tab === 'login';
    document.getElementById('fLogin')?.classList.toggle('hidden', !isL);
    document.getElementById('fReg')?.classList.toggle('hidden', isL);
    const tl = document.getElementById('tLogin');
    const tr = document.getElementById('tReg');
    if (tl) tl.className = `tab-btn${isL ? ' active' : ''}`;
    if (tr) tr.className = `tab-btn${!isL ? ' active' : ''}`;
}

function togglePw(id) {
    const el = document.getElementById(id);
    if (el) el.type = el.type === 'password' ? 'text' : 'password';
}

function checkStrength(val) {
    let s = 0;
    if (val.length >= 6) s++;
    if (val.length >= 10) s++;
    if (/[A-Z]/.test(val) && /[0-9]/.test(val)) s++;
    const c = ['','bg-red-500','bg-yellow-500','bg-green-500'];
    const l = ['','Lemah','Sedang','Kuat'];
    [1,2,3].forEach(i => {
        const el = document.getElementById('pw'+i);
        if (el) el.className = `h-1 flex-1 rounded-full transition-colors ${i<=s?c[s]:'bg-gray-700'}`;
    });
    const lbl = document.getElementById('pwLabel');
    if (lbl) { lbl.textContent = val ? l[s] : ''; lbl.style.color = s===1?'#ef4444':s===2?'#eab308':'#22c55e'; }
}

@if($errors->any())
document.addEventListener('DOMContentLoaded', () => {
    @if($errors->has('name')) openModal('register');
    @else openModal('login');
    @endif
});
@endif
</script>

@stack('scripts')
</body>
</html>