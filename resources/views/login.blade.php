<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/app.css')
    <title>Login</title>
</head>
<body class="bg-blue-50 flex justify-center items-center h-screen px-5">
    <div class="bg-white p-5 rounded-xl w-full max-w-[400px] shadow-lg">
        <h1 class="text-xl font-medium text-center">Login Akun</h1>

        @if ($errors->any())
            <div class="bg-red-500 text-white p-2 rounded mt-3">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" class="mt-5">
            @csrf
            <div class="mb-5">
                <label for="email" class="block font-medium">Email</label>
                <input type="email" name="email" id="email" class="w-full border border-gray-300 rounded-lg p-2" placeholder="youremail@example.com" required>
            </div>

            <div class="mb-5">
                <label for="password" class="block font-medium">Password</label>
                <input type="password" name="password" id="password" class="w-full border border-gray-300 rounded-lg p-2" placeholder="example123" required>
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg p-2 w-full">
                Login
            </button>
        </form>
    </div>
</body>
</html>
