<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder:text-gray-500 focus:border-purple-500 focus:ring-purple-500">
            @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
            <input id="password" type="password" name="password" required
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-purple-500 focus:ring-purple-500">
            @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between mt-4">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="rounded border-white/20 bg-white/5 text-purple-600 focus:ring-purple-500">
                <span class="ml-2 text-sm text-gray-400">Remember me</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-purple-400 hover:text-purple-300">Lupa password?</a>
            @endif
        </div>

        <button type="submit" class="w-full mt-6 bg-purple-600 hover:bg-purple-700 py-3 rounded-xl font-semibold transition">
            Log In
        </button>

        <p class="text-center text-sm text-gray-400 mt-4">
            Belum punya akun? <a href="{{ route('register') }}" class="text-purple-400 hover:text-purple-300 font-medium">Daftar</a>
        </p>
    </form>
</x-guest-layout>
