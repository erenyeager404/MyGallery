<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="container mx-auto p-6">
        <div class="bg-white rounded shadow p-6">

            <h1 class="text-2xl font-bold">Dashboard</h1>

            <p>Welcome, {{ Auth::user()->name }}</p>

            <p>Email: {{ Auth::user()->email }}</p>

            <p>
                Role:
                {{ Auth::user()->is_admin ? 'Admin' : 'User' }}
            </p>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded mt-4">
                    Logout
                </button>
            </form>

        </div>
    </div>
</body>

</html>