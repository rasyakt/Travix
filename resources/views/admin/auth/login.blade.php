<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login — {{ config('app.name', 'Travix') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>

<body class="bg-tv-bg antialiased">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="text-center mb-10">
                <div class="inline-flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-white p-1.5 shadow-sm border border-tv-border">
                        <img src="{{ asset('img/pln.png') }}" alt="Travix Logo" class="w-full h-full object-contain">
                    </div>
                    <div class="text-left">
                        <span class="text-2xl font-extrabold text-tv-secondary tracking-tight">Travix</span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-tv-accent block">Admin Panel</span>
                    </div>
                </div>
                <h2 class="text-2xl font-extrabold text-tv-text">Welcome, Admin</h2>
                <p class="text-tv-muted text-sm mt-1">Sign in to manage your travel platform</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-tv-border p-8">
                <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="tv-label">Email Address</label>
                        <input type="email" id="email" name="email" class="tv-input @error('email') border-red-500 @enderror"
                            placeholder="admin@travix.com" value="{{ old('email') }}" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="tv-label">Password</label>
                        <input type="password" id="password" name="password" class="tv-input @error('password') border-red-500 @enderror"
                            placeholder="••••••••" required>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                            class="w-4 h-4 text-tv-primary border-tv-border rounded focus:ring-tv-primary/20">
                        <label for="remember" class="ml-2 text-sm font-medium text-tv-muted">Remember me</label>
                    </div>

                    <button type="submit" class="btn-tv-primary w-full py-3.5 text-sm">
                        Sign In to Admin
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-xs font-bold text-tv-muted hover:text-tv-primary transition-colors">
                        Back to User Login
                    </a>
                </div>
            </div>

            <p class="text-center mt-8 text-xs text-tv-muted">
                &copy; {{ date('Y') }} Travix Inc. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
