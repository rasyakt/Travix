  <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar | Travix — Premium Travel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-tv-bg h-screen antialiased overflow-hidden">
    <div class="flex h-screen">
        {{-- Left Side: Background Imagery --}}
        <div class="hidden lg:block w-1/2 relative overflow-hidden">
            <img src="https://images.unsplash.com/photo-1533105079780-92b9be482077?auto=format&fit=crop&q=80&w=2070"
                alt="Travel background" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-linear-to-t from-tv-secondary/80 to-transparent"></div>

            <div class="absolute bottom-16 left-16 max-w-md">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-white p-1.5 rounded-xl border border-white/30 shadow-sm w-12 h-12 overflow-hidden">
                        <img src="{{ asset('img/pln.png') }}" alt="Travix Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="text-2xl font-extrabold text-white tracking-tight">Travix Club</span>
                </div>
                <h1 class="text-5xl font-extrabold text-white mb-6 leading-tight">
                    Join the elite circle of travelers.
                </h1>
                <p class="text-white/80 text-lg font-medium leading-relaxed">
                    Create an account to unlock exclusive member rates, earn points on every journey, and enjoy a
                    seamless booking experience.
                </p>
            </div>
        </div>

        {{-- Right Side: Registration Form --}}
        <div class="w-full lg:w-1/2 bg-white overflow-y-auto scrollbar-thin">
            <div class="min-h-full flex flex-col justify-center py-20 lg:py-28 px-8">
                <div class="w-full max-w-md mx-auto">
                    <div class="mb-12 relative">
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center gap-2 text-tv-muted hover:text-tv-primary transition-colors mb-8 group">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span class="text-xs font-black uppercase tracking-widest">Back to Home</span>
                    </a>
                    <h2 class="text-3xl font-extrabold text-tv-text mb-2">Create Account</h2>
                    <p class="text-tv-muted">Start your premium journey with us today.</p>
                </div>

                {{-- Login/Signup Toggle --}}
                <div class="bg-gray-100 p-1.5 rounded-2xl flex mb-10 shadow-inner">
                    <a href="{{ route('login') }}"
                        class="flex-1 py-3 text-sm font-bold rounded-xl text-tv-muted hover:text-tv-text transition-all text-center">Login</a>
                    <button
                        class="flex-1 py-3 text-sm font-bold rounded-xl bg-white shadow-sm text-tv-text transition-all">Sign
                        Up</button>
                </div>

                <form action="{{ route('register') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="name" class="tv-label">Full Name</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-tv-muted group-focus-within:text-tv-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" id="name" name="name"
                                class="tv-input pl-11 @error('name') border-red-500 @enderror" placeholder="John Doe"
                                value="{{ old('name') }}" required autofocus>
                        </div>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="tv-label">Email Address</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-tv-muted group-focus-within:text-tv-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="email" id="email" name="email"
                                class="tv-input pl-11 @error('email') border-red-500 @enderror"
                                placeholder="name@company.com" value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="tv-label">Password</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-tv-muted group-focus-within:text-tv-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="password" name="password"
                                class="tv-input pl-11 pr-12 @error('password') border-red-500 @enderror"
                                placeholder="••••••••" required>
                            <button type="button" onclick="togglePasswordVisibility('password', this)"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-tv-muted hover:text-tv-primary transition-colors">
                                <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.815 7.815L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="tv-label">Confirm Password</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-tv-muted group-focus-within:text-tv-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="tv-input pl-11 pr-12" placeholder="••••••••" required>
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-tv-muted hover:text-tv-primary transition-colors">
                                <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.815 7.815L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="btn-tv-primary w-full py-4 text-lg shadow-lg shadow-blue-100 hover:shadow-xl hover:shadow-blue-200 transition-all mt-4">
                        Create Account
                    </button>
                </form>

                <div class="my-8 relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-tv-border"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-tv-muted">Or join with</span>
                    </div>
                </div>

                {{-- Social Login (Google Only) --}}
                <div class="grid grid-cols-1 gap-4">
                    <a href="{{ route('login.google') }}"
                        class="flex items-center justify-center gap-3 px-4 py-3 border border-tv-border rounded-xl font-bold text-tv-text hover:bg-gray-50 transition-all group">
                        <svg class="w-5 h-5 transition-transform group-hover:scale-110" viewBox="0 0 24 24">
                            <path fill="#4285F4"
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853"
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05"
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" />
                            <path fill="#EA4335"
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        Google
                    </a>
                </div>

                <p class="mt-10 text-center text-sm text-tv-muted font-medium">
                    Already have an account? <a href="{{ route('login') }}"
                        class="text-tv-primary font-bold hover:underline">Sign In</a>
                </p>
                </div>
            </div>
        </div>
    </div>
    <script>
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            
            const eyeIcon = btn.querySelector('.eye-icon');
            const eyeOffIcon = btn.querySelector('.eye-off-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOffIcon.classList.add('hidden');
                eyeIcon.classList.remove('hidden');
            }
        }
    </script>
</body>

</html>