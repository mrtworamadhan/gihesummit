<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | GIHES 2026</title>

    <meta name="description" content="Participant Portal GIHES 2026.">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Dashboard | GIHES 2026">
    <meta property="og:description" content="Participant Portal GIHES 2026. Kelola registrasi dan akomodasi Anda di sini.">
    <meta property="og:image" content="{{ asset('images/logo-gihes.png') }}">

    <meta name="twitter:card" content="summary">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="Dashboard | GIHES 2026">
    <meta name="twitter:description" content="Participant Portal GIHES 2026. Kelola registrasi dan akomodasi Anda di sini.">
    <meta name="twitter:image" content="{{ asset('images/logo-gihes.png') }}">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 flex flex-col min-h-screen">
    
    <header class="w-full bg-white border-b border-gray-200 shadow-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-center md:justify-start">
            
            <div class="flex items-center gap-4 md:gap-6">
                <img src="{{ asset('images/logo-100-gontor.png') }}" alt="100 Tahun Gontor" class="h-10 md:h-12 w-auto object-contain min-w-[60px]" onerror="this.outerHTML='<div class=\'h-12 w-20 bg-gray-200 border border-gray-300 flex items-center justify-center text-[10px] text-gray-500 rounded\'>Logo 100Th</div>'">
                
                <div class="h-10 w-[1px] bg-gray-300"></div>
                
                <img src="{{ asset('images/logo-gihes-rev.png') }}" alt="GIHES 2026" class="h-10 md:h-12 w-auto object-contain min-w-[100px]" onerror="this.outerHTML='<div class=\'h-12 w-28 bg-gray-200 border border-gray-300 flex items-center justify-center text-[10px] text-gray-500 rounded\'>Logo GIHES</div>'">
            </div>

        </div>
    </header>

    <main class="flex-grow w-full">
        {{ $slot }}
    </main>

    <footer class="w-full py-6 text-center text-xs text-gray-400 bg-white border-t border-gray-200 mt-auto">
        &copy; {{ date('Y') }} Forum Pesantren Alumni Gontor. All rights reserved.
    </footer>

    @livewireScripts
</body>
</html>