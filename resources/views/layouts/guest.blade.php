<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Dynamic Page Title (Title එක අදාළ Page එක අනුව වෙනස් වන පරිදි) -->
        <title>{{ $title ?? config('app.name', 'BCI Organic Hub') }}</title>

        <!-- Favicon (Browser Tab Icon එක සඳහා - අවශ්‍ය නම් public/favicon.ico එකතු කරන්න) -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts (Preconnect optimized) -->
        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS & Vite Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#07090e] text-slate-100 min-h-screen overflow-x-hidden selection:bg-emerald-500 selection:text-white flex flex-col justify-between">
        
        <!-- Main View Content Slot -->
        <main class="w-full flex-grow">
            {{ $slot }}
        </main>

        <!-- Dynamic Minimal Footer -->
        <footer class="py-4 text-center text-[11px] text-slate-600 tracking-wider uppercase font-medium">
            &copy; {{ date('Y') }} BCI Organic Hub. All rights reserved.
        </footer>

    </body>
</html>