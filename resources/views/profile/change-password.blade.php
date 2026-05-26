@extends('layouts.app')
@section('title', 'Ganti Password')

@section('content')
    <div class="max-w-md mx-auto">
        <a href="{{ route('profile') }}"
            class="inline-flex items-center gap-2 text-gray-400 hover:text-white text-sm mb-8 transition-colors">← Kembali
            ke Profile</a>
        <h2 class="text-2xl font-bold mb-8">🔒 Ganti Password</h2>
        <div class="card-info">
            <form method="POST" action="{{ route('profile.password') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="form-label">Password Lama</label>
                    <input type="password" name="current_password" class="form-input" placeholder="Masukkan password lama">
                    @error('current_password') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" id="nPw" class="form-input" placeholder="Min. 6 karakter"
                        oninput="chk(this.value)">
                    <div class="flex gap-1 mt-2">
                        <div id="s1" class="h-1 flex-1 rounded-full bg-gray-700 transition-colors"></div>
                        <div id="s2" class="h-1 flex-1 rounded-full bg-gray-700 transition-colors"></div>
                        <div id="s3" class="h-1 flex-1 rounded-full bg-gray-700 transition-colors"></div>
                    </div>
                    <p id="sLbl" class="text-xs mt-1"></p>
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-input"
                        placeholder="Ulangi password baru">
                </div>
                <button type="submit" class="btn-submit">Simpan Password</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function chk(v) {
            let s = 0;
            if (v.length >= 6) s++;
            if (v.length >= 10) s++;
            if (/[A-Z]/.test(v) && /[0-9]/.test(v)) s++;
            const c = ['', 'bg-red-500', 'bg-yellow-500', 'bg-green-500'];
            const l = ['', 'Lemah', 'Sedang', 'Kuat'];
            [1, 2, 3].forEach(i => {
                const el = document.getElementById('s' + i);
                el.className = `h-1 flex-1 rounded-full transition-colors ${i <= s ? c[s] : 'bg-gray-700'}`;
            });
            const lbl = document.getElementById('sLbl');
            lbl.textContent = v ? l[s] : '';
            lbl.style.color = s === 1 ? '#ef4444' : s === 2 ? '#eab308' : '#22c55e';
        }
    </script>
@endpush