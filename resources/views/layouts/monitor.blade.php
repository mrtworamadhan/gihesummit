<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, shrink-to-fit=no">

    <title>Report Center | GIHES 2026</title>

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
<body class="font-sans antialiased bg-[#0f172a] text-white h-screen m-0 p-0 overflow-hidden">
    {{ $slot }}
    @livewireScripts
</body>
</html>