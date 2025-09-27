<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Prevent dark mode flash -->
        <script>
            (function(){
                try {
                    var ls = localStorage.getItem('theme');
                    var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                    var dark = ls ? (ls === 'dark') : prefersDark;
                    if (dark) { document.documentElement.classList.add('dark'); }
                } catch(e) { /* no-op */ }
            })();
        </script>
        <style>[x-cloak]{display:none!important}</style>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..900&family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles & Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body x-data="{ dark: localStorage.getItem('theme')==='dark' }" x-init="document.documentElement.classList.toggle('dark', dark)" x-on:theme-toggled.window="dark = !dark; localStorage.setItem('theme', dark ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', dark)" class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>

            <!-- Page Content -->
            <main class="text-gray-900 dark:text-gray-100">
                <!-- Flash Messages -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 space-y-4">
                    @if (session('success'))
                        <x-alert type="success" dismissible class="notification-message animate-slide-up">
                            {{ session('success') }}
                        </x-alert>
                    @endif
                    @if (session('status'))
                        <x-alert type="success" dismissible class="notification-message animate-slide-up">
                            {{ session('status') }}
                        </x-alert>
                    @endif
                    @if (session('error'))
                        <x-alert type="error" dismissible class="notification-message animate-slide-up">
                            {{ session('error') }}
                        </x-alert>
                    @endif
                    @if ($errors->any())
                        <x-alert type="warning" dismissible class="animate-slide-up">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </x-alert>
                    @endif
                </div>
                
                {{ $slot }}
            </main>
        </div>

        <script>
            // Auto-dismiss session flash messages after 1.5 seconds
            document.addEventListener('DOMContentLoaded', function() {
                const notificationMessages = document.querySelectorAll('.notification-message');
                notificationMessages.forEach(function(notification) {
                    setTimeout(function() {
                        notification.style.transition = 'opacity 0.3s ease-out';
                        notification.style.opacity = '0';
                        setTimeout(function() {
                            notification.remove();
                        }, 300);
                    }, 1500);
                });
            });
        </script>
    </body>
</html>
