<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login Portal | GIHES 2026</title>

    <meta name="description" content="Login Participant Portal GIHES 2026.">

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
<body class="bg-[#F4F3EF] font-sans text-[#1B1B1B] antialiased flex flex-col min-h-screen selection:bg-[#5A6446] selection:text-white">

    <header class="bg-white/90 backdrop-blur-md border-b border-[#E5E4DF] sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                
                <div class="flex items-center gap-3 md:gap-4 shrink-0">
                    <a href="/" class="flex items-center gap-3 group">
                        <div class="flex items-center gap-4 md:gap-6">
                            <img src="{{ asset('images/logo-100-gontor.png') }}" alt="100 Tahun Gontor" class="h-10 md:h-10 w-auto object-contain min-w-[60px]" onerror="this.outerHTML='<div class=\'h-12 w-20 bg-gray-200 border border-gray-300 flex items-center justify-center text-[10px] text-gray-500 rounded\'>Logo 100Th</div>'">
                            
                            <div class="h-10 w-[1px] bg-[#8C8A7D]"></div>
                            
                            <img src="{{ asset('images/gihes.png') }}" alt="GIHES 2026" class="h-10 md:h-10 w-auto object-contain min-w-[100px]" onerror="this.outerHTML='<div class=\'h-12 w-28 bg-gray-200 border border-gray-300 flex items-center justify-center text-[10px] text-gray-500 rounded\'>Logo GIHES</div>'">
                        </div>

                        <div class="hidden sm:block h-6 w-px bg-gray-300"></div>
                        
                        <div class="hidden sm:block">
                            <h1 class="font-bold text-[#12241C] text-sm md:text-base tracking-wide leading-none">Participant Portal</h1>
                            <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">GIHES 2026</p>
                        </div>
                    </a>
                </div>
                
                <div class="flex items-center gap-4">
                    
                    @auth
                        <div class="flex items-center gap-3">
                            <div class="text-right hidden md:block">
                                <p class="text-xs font-bold text-[#1B1B1B] leading-none">{{ auth()->user()->name ?? 'Participant' }}</p>
                                <p class="text-[10px] text-[#C0A062] mt-1">Delegation</p>
                            </div>
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-[#12241C] text-white flex items-center justify-center font-bold text-sm shadow-inner cursor-pointer hover:bg-[#5A6446] transition-colors">
                                {{ substr(auth()->user()->name ?? 'P', 0, 1) }}
                            </div>
                        </div>
                    @else
                        <a href="/" class="flex items-center gap-2 text-xs md:text-sm font-medium text-gray-500 hover:text-[#C0A062] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            <span class="hidden sm:inline">Back to Website</span>
                            <span class="sm:hidden">Back</span>
                        </a>
                    @endauth
                    
                </div>

            </div>
        </div>
    </header>

    <main class="flex-grow flex flex-col relative w-full pt-6 pb-12 px-4 sm:px-6 lg:px-8 z-10">
        {{ $slot }}
    </main>

    <footer class="py-6 text-center text-[11px] md:text-xs text-gray-400 bg-[#12241C] mt-auto">
        <p>&copy; 2026 Global Islamic Holistic Education Summit (GIHES). Forum Pesantren Alumni Gontor.</p>
    </footer>

</body>
</html>