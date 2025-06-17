<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Dashboard') - Cafe</title>
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
        }
        .sidebar-active {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            color: white;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans">
    @include('layouts.sidebar')

    <div class="ml-64 min-h-screen">
        @include('layouts.header')

        <main class="p-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
