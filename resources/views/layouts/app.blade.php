<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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

    <link rel="icon" type="image/png" href="{{ asset('images/logo-gihes.png') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F4F3EF] font-sans text-[#1B1B1B] antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#12241C] text-white transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col shadow-2xl">
            
            <div class="bg-white/90 flex items-center justify-center h-20 border-b border-gray-700/50 relative">
                <!-- <img src="{{ asset('images/logo-100-gontor.png') }}" alt="100 Tahun Gontor" class="h-10 w-auto" onerror="this.outerHTML='<div class=\'text-xl font-black tracking-widest text-[#C0A062]\'>100Th Gontor</div>'">
                <div class="h-10 w-[1px] bg-[#8C8A7D]"></div>
                <img src="{{ asset('images/logo-gihes.png') }}" alt="GIHES" class="h-10 w-auto" onerror="this.outerHTML='<div class=\'text-xl font-black tracking-widest text-[#C0A062]\'>GIHES 2026</div>'"> -->
                <div class="flex items-center gap-4 md:gap-6">
                    <img src="{{ asset('images/logo-100-gontor.png') }}" alt="100 Tahun Gontor" class="h-10 md:h-12 w-auto object-contain min-w-[60px]" onerror="this.outerHTML='<div class=\'h-12 w-20 bg-gray-200 border border-gray-300 flex items-center justify-center text-[10px] text-gray-500 rounded\'>Logo 100Th</div>'">
                    
                    <div class="h-10 w-[1px] bg-[#8C8A7D]"></div>
                    
                    <img src="{{ asset('images/logo-gihes.png') }}" alt="GIHES 2026" class="h-10 md:h-12 w-auto object-contain min-w-[100px]" onerror="this.outerHTML='<div class=\'h-12 w-28 bg-gray-200 border border-gray-300 flex items-center justify-center text-[10px] text-gray-500 rounded\'>Logo GIHES</div>'">
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden absolute right-4 text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
                <a href="{{ route('participant.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-md font-bold transition-colors {{ request()->routeIs('participant.dashboard') ? 'bg-[#5A6446]/40 text-[#C0A062]' : 'text-gray-300 hover:bg-[#5A6446]/40 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                
                <a href="{{ route('panel.profil') }}" class="flex items-center gap-3 px-4 py-3 rounded-md font-bold transition-colors {{ request()->routeIs('panel.profil') ? 'bg-[#5A6446]/40 text-[#C0A062]' : 'text-gray-300 hover:bg-[#5A6446]/40 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Account Info
                </a>
                
                <a href="{{ route('panel.accommodation') }}" class="flex items-center gap-3 px-4 py-3 rounded-md font-bold transition-colors {{ request()->routeIs('panel.accommodation') ? 'bg-[#5A6446]/40 text-[#C0A062]' : 'text-gray-300 hover:bg-[#5A6446]/40 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Accommodation
                </a>

                <a href="{{ route('panel.classes') }}" class="flex items-center gap-3 px-4 py-3 rounded-md font-bold transition-colors {{ request()->routeIs('panel.classes') ? 'bg-[#5A6446]/40 text-[#C0A062]' : 'text-gray-300 hover:bg-[#5A6446]/40 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Class Selection
                </a>

                <a href="{{ route('panel.schedule') }}" class="flex items-center gap-3 px-4 py-3 rounded-md font-bold transition-colors {{ request()->routeIs('panel.schedule') ? 'bg-[#5A6446]/40 text-[#C0A062]' : 'text-gray-300 hover:bg-[#5A6446]/40 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Event Schedule
                </a>

                <a href="{{ route('panel.gallery') }}" class="flex items-center gap-3 px-4 py-3 rounded-md font-bold transition-colors {{ request()->routeIs('panel.gallery') ? 'bg-[#5A6446]/40 text-[#C0A062]' : 'text-gray-300 hover:bg-[#5A6446]/40 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Gallery
                </a>

                <a href="{{ route('panel.certificate') }}" class="flex items-center gap-3 px-4 py-3 rounded-md font-bold transition-colors {{ request()->routeIs('panel.certificate') ? 'bg-[#5A6446]/40 text-[#C0A062]' : 'text-gray-300 hover:bg-[#5A6446]/40 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    E-Certificate
                </a>
            </nav>

            <div class="p-4 border-t border-gray-700/50">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-sm font-bold text-red-400 hover:text-red-300 hover:bg-red-900/20 rounded-md transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout Account
                    </button>
                </form>
            </div>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden" style="display: none;"></div>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <header class="bg-white shadow-sm border-b border-gray-200 h-20 flex items-center justify-between px-6 z-10">
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-600 focus:outline-none">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                
                <div class="lg:hidden"></div>

                <div class="flex items-center gap-4 ml-auto">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-[#1B1B1B]">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->institution_name }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-[#C0A062] text-[#12241C] flex items-center justify-center font-black text-lg border-2 border-[#12241C]">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-[#F4F3EF] p-6 lg:p-10">
                {{ $slot }}
            </main>

        </div>
    </div>
</body>
</html>