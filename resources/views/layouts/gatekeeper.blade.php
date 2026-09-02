<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, shrink-to-fit=no">

    <title>Scanner | GIHES 2026</title>

    <meta name="description" content="GateKeeper Scanner GIHES 2026. Scan QR Code peserta untuk mencatat kehadiran pada agenda yang diikuti.">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Dashboard | GIHES 2026">
    <meta property="og:description" content="Participant Portal GIHES 2026. Kelola registrasi dan akomodasi Anda di sini.">
    <meta property="og:image" content="{{ asset('images/logo-gihes-rev.png') }}">

    <meta name="twitter:card" content="summary">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="Dashboard | GIHES 2026">
    <meta name="twitter:description" content="Participant Portal GIHES 2026. Kelola registrasi dan akomodasi Anda di sini.">
    <meta name="twitter:image" content="{{ asset('images/logo-gihes-rev.png') }}">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            overscroll-behavior-y: contain;
        }
    </style>
</head>
<body class="bg-[#F4F3EF] font-sans text-[#1B1B1B] antialiased flex flex-col min-h-screen selection:bg-[#5A6446] selection:text-white">

    <main class="w-full h-full max-w-md mx-auto relative bg-gray-900 shadow-2xl min-h-screen">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>