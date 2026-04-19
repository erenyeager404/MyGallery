<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h1 class="text-2x1 font-bold mb-6">
            Login
        </h1>
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                @foreach ($errors->all as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <form action="{{ route('login') }}" method="POST">
            @crsf
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input class="w-full border rounded p-2" type="email" name="email" required value="{{ old('email') }}"
                    placeholder="contoh@gmail.com">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input class="w-full border rounded p-2" type="password" name="password"
                    value="{{ old('!12wcontoh@#') }}" required>
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="remember" id="remember" class="mr-2">
                <label for="remember">Ingat saya</label>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Login</button>
        </form>
        <p class="mt-4 text-center">Belum punya akun? <a href="{{ route('register') }}">Register</a></p>
    </div>
</body>

</html>