<div id="authModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm"
    onclick="handleModalBackdrop(event)">

    <div class="bg-gray-900 border border-gray-700/50 rounded-2xl shadow-2xl w-full max-w-md mx-4">

        <div class="p-6 pb-0">
            <div class="flex items-center justify-between mb-5">
                <span class="text-xl font-bold">My<span class="text-violet-400">Gallery</span></span>
                <button onclick="closeAuthModal()" class="w-8 h-8 flex items-center justify-center text-gray-500
                               hover:text-white hover:bg-gray-800 rounded-lg transition-colors text-lg">
                    &#10005;
                </button>
            </div>

            <div id="modalContextMsg" class="hidden mb-4 px-4 py-3 bg-violet-950/60 border border-violet-800/50
                        rounded-xl text-sm text-violet-300">
            </div>

            <div class="flex gap-1 p-1 bg-gray-800/60 rounded-xl mb-5">
                <button id="tabLogin" onclick="switchTab('login')"
                    class="flex-1 py-2 rounded-lg text-sm font-medium transition-all bg-violet-600 text-white">
                    Masuk
                </button>
                <button id="tabRegister" onclick="switchTab('register')"
                    class="flex-1 py-2 rounded-lg text-sm font-medium transition-all text-gray-400 hover:text-white">
                    Daftar
                </button>
            </div>
        </div>

        {{-- Form Login --}}
        <div id="formLogin" class="px-6 pb-6">
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@kamu.com" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-xl
                                  text-sm text-white placeholder-gray-600 focus:outline-none
                                  focus:border-violet-500 transition-colors">
                    @error('email')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="loginPassword" placeholder="••••••••" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-xl
                                      text-sm text-white placeholder-gray-600 focus:outline-none
                                      focus:border-violet-500 transition-colors pr-10">
                        <button type="button" onclick="togglePw('loginPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500
                                       hover:text-gray-300 transition-colors text-sm">
                            &#128065;
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 accent-violet-500">
                    <label for="remember" class="text-xs text-gray-400">Ingat saya</label>
                </div>
                <button type="submit"
                    class="w-full py-2.5 bg-violet-600 hover:bg-violet-700 rounded-xl text-sm font-medium transition-colors">
                    Masuk
                </button>
            </form>

            <div class="flex items-center gap-3 my-4">
                <hr class="flex-1 border-gray-800">
                <span class="text-gray-600 text-xs">atau</span>
                <hr class="flex-1 border-gray-800">
            </div>

            <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-2.5 py-2.5
                      bg-gray-800 hover:bg-gray-700 border border-gray-700
                      rounded-xl text-sm text-gray-300 transition-colors">
                <svg width="16" height="16" viewBox="0 0 48 48">
                    <path fill="#EA4335"
                        d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z" />
                    <path fill="#4285F4"
                        d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z" />
                    <path fill="#FBBC05"
                        d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z" />
                    <path fill="#34A853"
                        d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z" />
                </svg>
                Lanjutkan dengan Google
            </a>

            <p class="text-center text-gray-600 text-xs mt-4">
                Belum punya akun?
                <button onclick="switchTab('register')" class="text-violet-400 hover:underline">Daftar sekarang</button>
            </p>
        </div>

        {{-- Form Register --}}
        <div id="formRegister" class="px-6 pb-6 hidden">
            <form method="POST" action="{{ route('register') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5">Nama lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama kamu" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-xl
                                  text-sm text-white placeholder-gray-600 focus:outline-none
                                  focus:border-violet-500 transition-colors">
                    @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@kamu.com" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-xl
                                  text-sm text-white placeholder-gray-600 focus:outline-none
                                  focus:border-violet-500 transition-colors">
                    @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="regPassword" placeholder="Minimal 6 karakter"
                            oninput="checkStrength(this.value)" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-xl
                                      text-sm text-white placeholder-gray-600 focus:outline-none
                                      focus:border-violet-500 transition-colors pr-10">
                        <button type="button" onclick="togglePw('regPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500
                                       hover:text-gray-300 transition-colors text-sm">
                            &#128065;
                        </button>
                    </div>
                    <div class="flex gap-1 mt-1.5">
                        <div id="str1" class="h-1 flex-1 rounded-full bg-gray-800 transition-colors"></div>
                        <div id="str2" class="h-1 flex-1 rounded-full bg-gray-800 transition-colors"></div>
                        <div id="str3" class="h-1 flex-1 rounded-full bg-gray-800 transition-colors"></div>
                    </div>
                    <p id="strLabel" class="text-xs mt-1"></p>
                    @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5">Konfirmasi password</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password" class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-xl
                                  text-sm text-white placeholder-gray-600 focus:outline-none
                                  focus:border-violet-500 transition-colors">
                </div>
                <button type="submit"
                    class="w-full py-2.5 bg-violet-600 hover:bg-violet-700 rounded-xl text-sm font-medium transition-colors mt-1">
                    Daftar Sekarang
                </button>
            </form>
            <p class="text-center text-gray-600 text-xs mt-4">
                Sudah punya akun?
                <button onclick="switchTab('login')" class="text-violet-400 hover:underline">Masuk di sini</button>
            </p>
        </div>
    </div>
</div>