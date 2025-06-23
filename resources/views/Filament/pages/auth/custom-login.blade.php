<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white">
    <div class="flex min-h-screen">
        <div class="w-full md:w-1/2 p-10 flex flex-col justify-center">
            <h2 class="text-3xl font-bold mb-2 text-center">WELCOME BACK</h2>
            <p class="mb-6 text-gray-500 text-center">Welcome back! Please enter your details.</p>

           <form method="POST" action="/admin/login"
           class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input name="email" type="email" required
                           class="w-full mt-1 px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-red-200"
                           placeholder="Enter your email" />
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input name="password" type="password" required
                           class="w-full mt-1 px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-red-200"
                           placeholder="********" />
                </div>
                <div class="flex justify-between items-center text-sm">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="remember" class="form-checkbox">
                        <span>Remember me</span>
                    </label>
                    <a href="{{ route('filament.password.request') }}" class="text-gray-500 hover:underline">Forgot password</a>
                </div>
                <button type="submit"
                        class="w-full bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 transition">
                    Sign in
                </button>
            </form>

            <div class="mt-4">
                <a href="{{ route('auth.google.redirect') }}"
                   class="flex items-center justify-center border px-4 py-2 rounded w-full hover:bg-gray-100 transition">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5 mr-2">

                    <span>Sign in with Google</span>
                </a>
            </div>

            <p class="text-sm mt-4 text-center">Don't have an account?
                <a href="#" class="text-red-500 hover:underline">Sign up to free!</a>
            </p>
        </div>

        <div class="hidden md:flex items-center justify-center w-1/2 bg-gray-100">
          <img src="{{ asset('storage/image/login admin.svg') }}" 
     class="w-full h-auto" 
     alt="Login Illustration">
        </div>
    </div>
</body>
</html>
