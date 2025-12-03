<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SISRI') }} - Sistem Informasi Skripsi</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased" x-data="{ sidebarOpen: true }">
        <div class="min-h-screen bg-gray-100 flex">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-h-screen transition-all duration-300" :class="sidebarOpen ? 'ml-64' : 'ml-0'">
                <!-- Top Navigation -->
                @include('layouts.topnav')

                <!-- SweetAlert Flash Messages -->
                <x-sweet-alert />

                <!-- Page Content -->
                <main class="flex-1 p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
