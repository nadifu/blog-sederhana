<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Laravel')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- STYLE MANUAL UNTUK MODUL 11 --}}
    <style>
        body {
            background: #f5f5f5 !important;
        }

        .post-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.06);
        }

        .meta {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .btn {
            padding: 6px 14px;
            border-radius: 6px;
            background: #3490dc;
            color: white !important;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .btn:hover {
            opacity: .85;
        }
    </style>
</head>

<body class="font-sans antialiased bg-white">
    
    <div class="min-h-screen">
        @include('layouts.navigation')

        <main class="max-w-4xl mx-auto py-10 px-5">
            @yield('content')
        </main>
    </div>

</body>
</html>
