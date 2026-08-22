<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GIHES 2026 - Global Islamic Holistic Education Summit</title>
    <meta name="description" content="Join the Global Islamic Holistic Education Summit (GIHES) 2026. Empowering Islamic Holistic education through technology, innovation, and global collaboration.">
    <meta name="keywords" content="GIHES 2026, Islamic Education, Holistic Education, Conference, Summit 2026, Laravel, Futsal Cup">
    <meta name="author" content="Salaka Tech">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="GIHES 2026 - Global Islamic Holistic Education Summit">
    <meta property="og:description" content="Register now for GIHES 2026. Connect with global leaders in Islamic Holistic Education.">
    <meta property="og:image" content="{{ asset('images/og-share-gihes.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="GIHES 2026 - Global Islamic Holistic Education Summit">
    <meta name="twitter:description" content="Register now for GIHES 2026. Connect with global leaders in Islamic Holistic Education.">
    <meta name="twitter:image" content="{{ asset('images/og-share-gihes.jpg') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1; 
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #C0A062; 
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a3854d; 
        }
    </style>
</head>
<body class="font-sans antialiased text-[#1B1B1B] bg-[#F4F3EF]">

    <header class="fixed w-full bg-white/90 backdrop-blur-sm z-50 border-b border-[#E5E4DF]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                
                <div class="flex items-center gap-4 md:gap-6">
                    <img src="{{ asset('images/logo-100-gontor.png') }}" alt="100 Tahun Gontor" class="h-12 md:h-14 w-auto object-contain min-w-[60px]" onerror="this.outerHTML='<div class=\'h-12 w-20 bg-gray-200 border border-gray-300 flex items-center justify-center text-[10px] text-gray-500 rounded\'>Logo 100Th</div>'">
                    
                    <div class="h-10 w-[1px] bg-[#8C8A7D]"></div>
                    
                    <img src="{{ asset('images/gihes.png') }}" alt="GIHES 2026" class="h-12 md:h-14 w-auto object-contain min-w-[100px]" onerror="this.outerHTML='<div class=\'h-12 w-28 bg-gray-200 border border-gray-300 flex items-center justify-center text-[10px] text-gray-500 rounded\'>Logo GIHES</div>'">
                </div>
                
                <div class="flex items-center">
                    @auth
                        <a href="/panel" class="bg-[#2B2D26] hover:bg-[#1a1c17] text-white px-6 py-2.5 rounded-sm font-semibold tracking-wide transition shadow-lg">
                            Dashboard Panel
                        </a>
                    @else
                        <a href="/register" class="bg-[#2B2D26] hover:bg-[#1a1c17] text-white px-6 py-2.5 rounded-sm font-semibold tracking-wide transition shadow-lg">
                            Register
                        </a>
                    @endauth
                </div>
                
            </div>
        </div>
    </header>

    <section class="relative pt-36 pb-20 lg:pt-48 lg:pb-32 bg-[#F4F3EF] overflow-hidden">
        
        <img 
            src="{{ asset('images/mandala-pattern.png') }}"
            class="absolute top-1/2 -translate-y-1/2 -left-[250px] md:-left-[400px] w-[500px] md:w-[800px] h-[500px] md:h-[800px] object-contain text-[#8C8A7D] opacity-10 pointer-events-none z-0"
            alt=""
        >

        <img 
            src="{{ asset('images/mandala-pattern.png') }}"
            class="absolute top-1/2 -translate-y-1/2 -right-[250px] md:-right-[400px] w-[500px] md:w-[800px] h-[500px] md:h-[800px] object-contain text-[#8C8A7D] opacity-10 pointer-events-none z-0"
            alt=""
        >

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            <p class="text-xs md:text-sm font-bold tracking-[0.25em] uppercase text-[#1B1B1B] mb-6">
                GIHES 2026 | 100th Anniversary of Pondok Modern Darussalam Gontor
            </p>
            
            <h1 class="text-4xl md:text-6xl lg:text-[5.5rem] font-black text-[#1B1B1B] leading-[1.05] tracking-tight mb-4 uppercase">
                Global Islamic<br>
                Holistic<br>
                Education Summit
            </h1>
            
            <p class="text-xl md:text-2xl text-[#716C59] italic font-medium mb-8">
                Timeless Values. Future Civilizations.
            </p>
            
            <div class="h-[2px] w-full max-w-2xl mx-auto bg-[#1B1B1B] mb-8"></div>
            
            <h2 class="text-2xl md:text-3xl text-[#5A5B58] italic font-light mb-16 leading-snug">
                Exploring and Revealing<br>
                the Pesantren Education System
            </h2>

            <div class="flex justify-center items-center gap-4 md:gap-6 text-[#1B1B1B] font-medium mb-12 text-sm md:text-base">
                <div>5-6 September 2026</div>
                <div class="w-1.5 h-1.5 rounded-full bg-[#8C8A7D]"></div>
                <div>Hotel Borobudur, Jakarta</div>
            </div>

            <div class="mb-12">
                <p class="text-sm text-[#716C59] mb-1">Presented by:</p>
                <p class="font-bold text-[#1B1B1B] text-lg">Forum Pesantren Alumni Gontor (FPAG)</p>
            </div>
            
            <a href="/register" class="inline-block bg-[#2B2D26] hover:bg-[#1a1c17] text-white px-10 py-4 rounded-sm font-bold tracking-widest uppercase transition-all shadow-xl hover:-translate-y-1">
                Secure Your Seat
            </a>
            
        </div>
    </section>

    <section id="about" class="py-24 bg-white relative z-10">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-20 items-center">
                
                <div class="lg:col-span-7">
                    <div class="space-y-6 text-[1.1rem] md:text-lg text-[#5A5B58] leading-relaxed font-light">
                        <p>
                            Dalam rangka <strong class="text-[#1B1B1B] font-bold">100 Tahun Pondok Modern Darussalam Gontor</strong>, kami menghadirkan <strong class="text-[#1B1B1B] font-bold">Global Islamic Holistic Education Summit (GIHES)</strong> sebagai forum internasional untuk menggali dan memperkenalkan kontribusi sistem pondok modern terhadap masa depan pendidikan dunia.
                        </p>
                        
                        <p>
                            Hari ini, dunia menghadapi tantangan yang sama. Banyak lulusan dinilai unggul secara akademik, tetapi masih lemah dalam <em class="italic">soft skills</em>, seperti kepemimpinan, kolaborasi, etos kerja, kreativitas, dan orientasi pelayanan. Berbagai laporan World Economic Forum juga menunjukkan bahwa kompetensi inilah yang semakin dibutuhkan di masa depan.
                        </p>
                        
                        <p>
                            Di berbagai negara, pendidikan holistik dipahami sebagai pendekatan yang mengembangkan seluruh potensi manusia. Bagi kami, pendidikan seperti itu bukan sekadar konsep. Sistem pondok modern telah mempraktikkannya melalui <strong class="text-[#1B1B1B] font-medium">pembinaan yang berlangsung selama 24 jam</strong>.
                        </p>
                        
                        <p>
                            Karena itu, GIHES hadir sebagai ruang untuk mempertemukan pengalaman, gagasan, dan kolaborasi, serta memperkenalkan <strong class="text-[#1B1B1B] font-bold">kontribusi pendidikan Islam kepada dunia</strong>.
                        </p>
                    </div>
                </div>

                <div class="lg:col-span-5 relative mt-10 lg:mt-0 pb-16 lg:pb-0">
                    <div class="bg-[#122B22] rounded-md p-10 md:p-14 text-white relative shadow-2xl">
                        
                        <span class="text-7xl text-[#C0A062] font-serif leading-none absolute top-8 left-8 opacity-80">
                            &ldquo;
                        </span>
                        
                        <blockquote class="relative z-10 mt-8 mb-20">
                            <p class="text-[1.35rem] md:text-3xl font-light leading-snug uppercase tracking-wide text-[#E5E4DF]">
                                Membentuk Manusia Seutuhnya,<br>
                                Menginspirasi Peradaban Dunia.
                            </p>
                            <div class="w-16 h-1 bg-[#C0A062] mt-8"></div>
                        </blockquote>

                        <div class="absolute -bottom-16 left-1/2 -translate-x-1/2 w-48 h-48 md:w-56 md:h-56 rounded-full border-4 border-[#C0A062] overflow-hidden bg-gray-200 shadow-xl z-20">
                            <img src="{{ asset('images/speakers/prof-hamid.jpg') }}" alt="Tokoh GIHES" class="w-full h-full object-cover" onerror="this.outerHTML='<div class=\'w-full h-full flex flex-col items-center justify-center text-gray-500 text-sm bg-gray-100\'><span>Space Foto</span><span>Tokoh</span></div>'">
                        </div>
                        
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                
                <div class="lg:col-span-6 pr-0 lg:pr-12 relative z-10">
                    
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-2 h-2 rotate-45 bg-[#5A6446]"></div>
                        <h3 class="text-sm font-bold tracking-[0.25em] uppercase text-[#5A6446]">
                            Tujuan GIHES
                        </h3>
                        <div class="h-[1.5px] flex-grow bg-gray-300"></div>
                    </div>
                    
                    <h2 class="text-[3.5rem] md:text-7xl font-black leading-[0.95] tracking-tight mb-8 uppercase">
                        <span class="block text-[#1B1B1B]">Tujuan</span>
                        <span class="block text-[#5A6446]">GIHES</span>
                    </h2>
                    
                    <p class="text-[1.1rem] md:text-lg text-gray-600 leading-relaxed mb-12">
                        GIHES hadir sebagai <strong class="text-gray-900 font-bold">forum internasional</strong> untuk mempertemukan pemikir, praktisi, pengelola pesantren, pembuat kebijakan, dan mitra global dalam mengangkat pesantren sebagai <strong class="text-gray-900 font-bold">model pendidikan holistik Islami.</strong>
                    </p>
                    
                    <div>
                        <h4 class="text-sm font-bold tracking-[0.25em] uppercase text-[#5A6446] mb-4">
                            Output
                        </h4>
                        <div class="border-t-[1.5px] border-gray-200">
                            
                            <div class="flex items-center py-4 border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <span class="text-3xl font-extrabold text-[#5A6446] w-12 text-center">01</span>
                                <div class="w-px h-8 bg-gray-300 mx-4 md:mx-6"></div>
                                <p class="text-gray-800 text-sm md:text-base font-medium">Menghadirkan pesantren Indonesia sebagai model pendidikan holistik Islami.</p>
                            </div>
                            
                            <div class="flex items-center py-4 border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <span class="text-3xl font-extrabold text-[#5A6446] w-12 text-center">02</span>
                                <div class="w-px h-8 bg-gray-300 mx-4 md:mx-6"></div>
                                <p class="text-gray-800 text-sm md:text-base font-medium">Mendokumentasikan <em class="italic">best practice</em> pesantren.</p>
                            </div>
                            
                            <div class="flex items-center py-4 border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <span class="text-3xl font-extrabold text-[#5A6446] w-12 text-center">03</span>
                                <div class="w-px h-8 bg-gray-300 mx-4 md:mx-6"></div>
                                <p class="text-gray-800 text-sm md:text-base font-medium">Membangun platform kolaborasi global.</p>
                            </div>
                            
                            <div class="flex items-center py-4 border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <span class="text-3xl font-extrabold text-[#5A6446] w-12 text-center">04</span>
                                <div class="w-px h-8 bg-gray-300 mx-4 md:mx-6"></div>
                                <p class="text-gray-800 text-sm md:text-base font-medium">Membentuk <em class="italic">Working Groups</em> lintas negara.</p>
                            </div>
                            
                            <div class="flex items-center py-4 border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <span class="text-3xl font-extrabold text-[#5A6446] w-12 text-center">05</span>
                                <div class="w-px h-8 bg-gray-300 mx-4 md:mx-6"></div>
                                <p class="text-gray-800 text-sm md:text-base font-medium">Menghasilkan <em class="italic">GIHES Declaration</em>.</p>
                            </div>
                            
                            <div class="flex items-center py-4 border-b-[1.5px] border-gray-200 hover:bg-gray-50 transition-colors">
                                <span class="text-3xl font-extrabold text-[#5A6446] w-12 text-center">06</span>
                                <div class="w-px h-8 bg-gray-300 mx-4 md:mx-6"></div>
                                <p class="text-gray-800 text-sm md:text-base font-medium">Menguatkan <em class="italic">soft power</em> pendidikan Islam Indonesia.</p>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6 relative mt-16 lg:mt-0 h-[600px] lg:h-[750px] w-full flex justify-end">
                    
                    <div class="hidden lg:block absolute left-4 top-1/2 -translate-y-1/2 w-[300px] h-[120%] border-r border-[#5A6446]/40 rounded-r-full z-20 pointer-events-none">
                        <div class="absolute top-1/2 -translate-y-1/2 -right-1.5 w-3 h-3 rounded-full bg-[#5A6446]"></div>
                    </div>
                    
                    <div class="w-full lg:w-[95%] h-full rounded-2xl lg:rounded-l-[12rem] lg:rounded-r-none overflow-hidden shadow-2xl relative z-10">
                        <img src="{{ asset('images/tujuan-hall.jpg') }}" alt="Suasana GIHES" class="w-full h-full object-cover object-center hover:scale-105 transition-transform duration-[20s]" onerror="this.outerHTML='<div class=\'w-full h-full bg-gray-200 flex items-center justify-center text-gray-500\'>Space Foto Hall/Ruangan</div>'">
                    </div>
                    
                </div>

            </div>
        </div>
    </section>

    <section class="py-24 bg-[#F4F3EF] relative z-10 border-t border-[#E5E4DF]">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-12">
                
                <div class="lg:col-span-5 flex flex-col h-full">
                    
                    <div class="mb-6">
                        <span class="bg-[#2B2D26] text-white text-xs font-bold tracking-[0.2em] px-4 py-2 uppercase">
                            Mengapa GIHES
                        </span>
                    </div>

                    <h2 class="text-[3.5rem] md:text-7xl font-black text-[#1B1B1B] leading-[0.95] tracking-tight mb-6 uppercase">
                        Mengapa<br>GIHES?
                    </h2>

                    <h3 class="text-xl md:text-2xl font-bold text-[#5A6446] mb-4 uppercase tracking-wide">
                        GIHES bukan sekadar konferensi.<br>Bukan event tahunan.
                    </h3>

                    <p class="text-lg text-gray-700 font-medium mb-10 leading-relaxed">
                        GIHES adalah <strong class="text-[#1B1B1B]">platform internasional</strong> yang menghubungkan <strong class="text-[#1B1B1B]">praktik terbaik pesantren</strong> dengan jejaring pendidikan global.
                    </p>

                    <div class="space-y-8 mb-10">
                        <div class="flex gap-5 items-start">
                            <div class="w-14 h-14 rounded-full border border-[#5A6446] flex items-center justify-center text-[#5A6446] shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-[#5A6446] tracking-widest uppercase mb-1">Perwakilan</p>
                                <p class="text-3xl font-black text-[#1B1B1B] leading-none mb-2">5 <span class="text-xl font-bold">BENUA</span></p>
                                <p class="text-sm text-gray-600 font-medium uppercase leading-snug">Amerika, Eropa, Afrika,<br>Australia, Asia.</p>
                            </div>
                        </div>

                        <div class="flex gap-5 items-start">
                            <div class="w-14 h-14 rounded-full border border-[#5A6446] flex items-center justify-center text-[#5A6446] shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <div class="flex items-baseline gap-2 mb-2">
                                    <p class="text-4xl font-black text-[#1B1B1B] leading-none">300+</p>
                                    <p class="text-sm font-bold text-[#5A6446] tracking-widest uppercase">Invited Leaders</p>
                                </div>
                                <p class="text-xs text-gray-600 font-medium uppercase leading-relaxed">Regional-International Organization, Government, Educational Institution, Philanthropy, Education Observer, Boarding School Principal.</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 border-l-4 border-[#5A6446] shadow-sm mb-10">
                        <p class="font-bold text-[#1B1B1B] text-lg leading-snug">
                            GIHES dibangun untuk menghasilkan <span class="text-[#5A6446]">kolaborasi yang berkelanjutan</span>, bukan berhenti pada penyelenggaraan sebuah acara.
                        </p>
                    </div>

                    <div class="mt-auto">
                        <a href="/register" class="inline-flex justify-center items-center bg-[#2B2D26] hover:bg-[#1a1c17] text-white px-8 py-4 rounded-sm font-bold tracking-widest uppercase transition-all duration-300 shadow-lg hover:-translate-y-1">
                            Be Part of The Movement
                        </a>
                    </div>

                </div>

                <div class="lg:col-span-7">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <div class="bg-white p-6 border border-gray-200 rounded-sm hover:shadow-md transition-shadow group">
                            <div class="w-12 h-12 bg-[#2B2D26] group-hover:bg-[#5A6446] transition-colors rounded flex items-center justify-center text-white mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                            </div>
                            <h4 class="font-bold text-[#1B1B1B] uppercase mb-2 text-sm tracking-wide">GIHES Declaration</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">Panduan nilai & arah global pendidikan Islam.</p>
                        </div>

                        <div class="bg-white p-6 border border-gray-200 rounded-sm hover:shadow-md transition-shadow group">
                            <div class="w-12 h-12 bg-[#2B2D26] group-hover:bg-[#5A6446] transition-colors rounded flex items-center justify-center text-white mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <h4 class="font-bold text-[#1B1B1B] uppercase mb-2 text-sm tracking-wide">Research & Publication</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">Pengetahuan untuk perubahan berbasis data dan bukti.</p>
                        </div>

                        <div class="bg-white p-6 border border-gray-200 rounded-sm hover:shadow-md transition-shadow group">
                            <div class="w-12 h-12 bg-[#2B2D26] group-hover:bg-[#5A6446] transition-colors rounded flex items-center justify-center text-white mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                            </div>
                            <h4 class="font-bold text-[#1B1B1B] uppercase mb-2 text-sm tracking-wide">Global Collaboration</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">Jaringan lintas institusi dan negara untuk dampak yang lebih luas.</p>
                        </div>

                        <div class="bg-white p-6 border border-gray-200 rounded-sm hover:shadow-md transition-shadow group">
                            <div class="w-12 h-12 bg-[#2B2D26] group-hover:bg-[#5A6446] transition-colors rounded flex items-center justify-center text-white mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <h4 class="font-bold text-[#1B1B1B] uppercase mb-2 text-sm tracking-wide">Working Group</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">Kolaborasi berkelanjutan untuk solusi nyata di berbagai fokus strategis.</p>
                        </div>

                        <div class="bg-white p-6 border border-gray-200 rounded-sm hover:shadow-md transition-shadow group">
                            <div class="w-12 h-12 bg-[#2B2D26] group-hover:bg-[#5A6446] transition-colors rounded flex items-center justify-center text-white mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            </div>
                            <h4 class="font-bold text-[#1B1B1B] uppercase mb-2 text-sm tracking-wide">Ecosystem Development</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">Dampak jangka panjang melalui penguatan ekosistem pendidikan Islam.</p>
                        </div>

                        <div class="bg-white p-6 border border-gray-200 rounded-sm hover:shadow-md transition-shadow group">
                            <div class="w-12 h-12 bg-[#2B2D26] group-hover:bg-[#5A6446] transition-colors rounded flex items-center justify-center text-white mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14v7"></path></svg>
                            </div>
                            <h4 class="font-bold text-[#1B1B1B] uppercase mb-2 text-sm tracking-wide">Student & Teacher Exchange</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">Pertukaran pelajar dan pendidik untuk memperluas wawasan dan pengalaman.</p>
                        </div>

                        <div class="bg-white p-6 border border-gray-200 rounded-sm hover:shadow-md transition-shadow group">
                            <div class="w-12 h-12 bg-[#2B2D26] group-hover:bg-[#5A6446] transition-colors rounded flex items-center justify-center text-white mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h4 class="font-bold text-[#1B1B1B] uppercase mb-2 text-sm tracking-wide">Policy Brief</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">Rekomendasi kebijakan untuk penguatan pendidikan di tingkat lokal dan global.</p>
                        </div>

                        <div class="bg-white p-6 border border-gray-200 rounded-sm hover:shadow-md transition-shadow group">
                            <div class="w-12 h-12 bg-[#2B2D26] group-hover:bg-[#5A6446] transition-colors rounded flex items-center justify-center text-white mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            </div>
                            <h4 class="font-bold text-[#1B1B1B] uppercase mb-2 text-sm tracking-wide">Knowledge Platform</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">Platform digital untuk berbagi sumber daya, praktik baik, dan kolaborasi berkelanjutan.</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="speakers" class="py-24 bg-[#12241C] relative z-10 overflow-hidden">
        <div class="max-w-[90rem] mx-auto px-4 md:px-12">
            
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-white tracking-widest uppercase">
                    Invited Speakers
                </h2>
                <div class="w-24 h-1 bg-[#C0A062] mx-auto mt-6"></div>
            </div>

            <style>
                .hide-scrollbar::-webkit-scrollbar { display: none; }
                .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            </style>

            <div class="flex flex-nowrap md:flex-wrap justify-start md:justify-center gap-6 md:gap-8 overflow-x-auto md:overflow-visible snap-x snap-mandatory hide-scrollbar pb-8 md:pb-0 mb-8 md:mb-16 px-4 md:px-0">
                
                <div class="flex flex-col items-center text-center shrink-0 snap-center w-[70vw] sm:w-[40vw] md:w-48">
                    <div class="w-40 h-40 rounded-full border-4 border-white overflow-hidden bg-gray-200 shadow-xl mb-4">
                        <img src="{{ asset('images/speakers/ahmad-muzani.jpg') }}" alt="Ahmad Muzani" class="w-full h-full object-cover" onerror="this.outerHTML='<div class=\'w-full h-full flex items-center justify-center text-gray-500 text-xs\'>Foto</div>'">
                    </div>
                    <h4 class="text-white font-bold text-lg mb-1 leading-snug">Ahmad Muzani</h4>
                    <p class="text-gray-400 text-[11px] leading-relaxed">Ketua Majelis Permusyawaratan Rakyat Republik Indonesia (MPR RI)</p>
                </div>

                <div class="flex flex-col items-center text-center shrink-0 snap-center w-[70vw] sm:w-[40vw] md:w-48">
                    <div class="w-40 h-40 rounded-full border-4 border-white overflow-hidden bg-gray-200 shadow-xl mb-4">
                        <img src="{{ asset('images/speakers/prof-nasarudin.jpg') }}" alt="Prof. Dr. H. Nasaruddin Umar" class="w-full h-full object-cover" onerror="this.outerHTML='<div class=\'w-full h-full flex items-center justify-center text-gray-500 text-xs\'>Foto</div>'">
                    </div>
                    <h4 class="text-white font-bold text-lg mb-1 leading-snug">Prof. Dr. H. Nasaruddin Umar, M.A.</h4>
                    <p class="text-gray-400 text-[11px] leading-relaxed">Menteri Agama Indonesia ke-25</p>
                </div>

                <div class="flex flex-col items-center text-center shrink-0 snap-center w-[70vw] sm:w-[40vw] md:w-48">
                    <div class="w-40 h-40 rounded-full border-4 border-white overflow-hidden bg-gray-200 shadow-xl mb-4">
                        <img src="{{ asset('images/speakers/prof-muti.jpg') }}" alt="Prof. Dr. Abdul Mu'ti" class="w-full h-full object-cover" onerror="this.outerHTML='<div class=\'w-full h-full flex items-center justify-center text-gray-500 text-xs\'>Foto</div>'">
                    </div>
                    <h4 class="text-white font-bold text-lg mb-1 leading-snug">Prof. Dr. Abdul Mu'ti, M.Ed.</h4>
                    <p class="text-gray-400 text-[11px] leading-relaxed">Menteri Pendidikan Dasar dan Menengah</p>
                </div>

                <div class="flex flex-col items-center text-center shrink-0 snap-center w-[70vw] sm:w-[40vw] md:w-48">
                    <div class="w-40 h-40 rounded-full border-4 border-white overflow-hidden bg-gray-200 shadow-xl mb-4">
                        <img src="{{ asset('images/speakers/prof-hnw.jpg') }}" alt="Dr. K.H. M. Hidayat Nur Wahid" class="w-full h-full object-cover" onerror="this.outerHTML='<div class=\'w-full h-full flex items-center justify-center text-gray-500 text-xs\'>Foto</div>'">
                    </div>
                    <h4 class="text-white font-bold text-lg mb-1 leading-snug">Dr. K.H. M. Hidayat Nur Wahid, M.A.</h4>
                    <p class="text-gray-400 text-[11px] leading-relaxed">Ketua Badan Wakaf Pondok Modern Darussalam Gontor</p>
                </div>

                <div class="flex flex-col items-center text-center shrink-0 snap-center w-[70vw] sm:w-[40vw] md:w-48">
                    <div class="w-40 h-40 rounded-full border-4 border-white overflow-hidden bg-gray-200 shadow-xl mb-4">
                        <img src="{{ asset('images/speakers/kh-hasan.jpeg') }}" alt="K.H. Hasan Abdullah Sahal" class="w-full h-full object-cover" onerror="this.outerHTML='<div class=\'w-full h-full flex items-center justify-center text-gray-500 text-xs\'>Foto</div>'">
                    </div>
                    <h4 class="text-white font-bold text-lg mb-1 leading-snug">K.H. Hasan Abdullah Sahal</h4>
                    <p class="text-gray-400 text-[11px] leading-relaxed">Pimpinan Pondok Modern Darussalam Gontor</p>
                </div>

                <div class="flex flex-col items-center text-center shrink-0 snap-center w-[70vw] sm:w-[40vw] md:w-48">
                    <div class="w-40 h-40 rounded-full border-4 border-white overflow-hidden bg-gray-200 shadow-xl mb-4">
                        <img src="{{ asset('images/speakers/prof-hamid.jpg') }}" alt="Prof. Hamid Fahmy Zarkasyi" class="w-full h-full object-cover" onerror="this.outerHTML='<div class=\'w-full h-full flex items-center justify-center text-gray-500 text-xs\'>Foto</div>'">
                    </div>
                    <h4 class="text-white font-bold text-lg mb-1 leading-snug">Prof. Hamid Fahmy Zarkasyi</h4>
                    <p class="text-gray-400 text-[11px] leading-relaxed">Rektor Universitas Darussalam Gontor</p>
                </div>

            </div>


            <div class="flex flex-nowrap md:flex-wrap justify-start md:justify-center gap-6 md:gap-8 overflow-x-auto md:overflow-visible snap-x snap-mandatory hide-scrollbar pb-8 md:pb-0 px-4 md:px-0">
                
                <div class="flex flex-col items-center text-center shrink-0 snap-center w-[70vw] sm:w-[40vw] md:w-48 mt-4 md:mt-6">
                    <div class="relative mb-4">
                        <span class="absolute -top-4 left-1/2 -translate-x-1/2 bg-white text-[#12241C] text-xs font-bold px-3 py-1 rounded shadow-md z-10 uppercase tracking-widest">Turkey</span>
                        <div class="w-40 h-40 rounded-full border-4 border-white overflow-hidden bg-gray-200 shadow-xl relative z-0">
                            <img src="{{ asset('images/speakers/prof-nuri.jpg') }}" alt="Prof. Dr. Nuri Tinaz" class="w-full h-full object-cover" onerror="this.outerHTML='<div class=\'w-full h-full flex items-center justify-center text-gray-500 text-xs\'>Foto</div>'">
                        </div>
                    </div>
                    <h4 class="text-white font-bold text-lg mb-1 leading-snug">Prof. Dr. Nuri Tınaz</h4>
                    <p class="text-gray-400 text-[11px] leading-relaxed">Professor at Department of Sociology, Turkey</p>
                </div>

                <div class="flex flex-col items-center text-center shrink-0 snap-center w-[70vw] sm:w-[40vw] md:w-48 mt-4 md:mt-6">
                    <div class="relative mb-4">
                        <span class="absolute -top-4 left-1/2 -translate-x-1/2 bg-white text-[#12241C] text-xs font-bold px-3 py-1 rounded shadow-md z-10 uppercase tracking-widest">Indonesia</span>
                        <div class="w-40 h-40 rounded-full border-4 border-white overflow-hidden bg-gray-200 shadow-xl relative z-0">
                            <img src="{{ asset('images/speakers/prof-amin.jpg') }}" alt="Prof. Dr. M. Amin Abdullah" class="w-full h-full object-cover" onerror="this.outerHTML='<div class=\'w-full h-full flex items-center justify-center text-gray-500 text-xs\'>Foto</div>'">
                        </div>
                    </div>
                    <h4 class="text-white font-bold text-lg mb-1 leading-snug">Prof. Dr. M. Amin Abdullah</h4>
                    <p class="text-gray-400 text-[11px] leading-relaxed">Cendekiawan Muslim Indonesia</p>
                </div>

                <div class="flex flex-col items-center text-center shrink-0 snap-center w-[70vw] sm:w-[40vw] md:w-48 mt-4 md:mt-6">
                    <div class="relative mb-4">
                        <span class="absolute -top-4 left-1/2 -translate-x-1/2 bg-white text-[#12241C] text-xs font-bold px-3 py-1 rounded shadow-md z-10 uppercase tracking-widest">UK</span>
                        <div class="w-40 h-40 rounded-full border-4 border-white overflow-hidden bg-gray-200 shadow-xl relative z-0">
                            <img src="{{ asset('images/speakers/datuk-afifi.jpg') }}" alt="Datuk Dr. Afifi Al-Akiti" class="w-full h-full object-cover" onerror="this.outerHTML='<div class=\'w-full h-full flex items-center justify-center text-gray-500 text-xs\'>Foto</div>'">
                        </div>
                    </div>
                    <h4 class="text-white font-bold text-lg mb-1 leading-snug">Datuk Dr. Afifi Al-Akiti</h4>
                    <p class="text-gray-400 text-[11px] leading-relaxed">Dosen Fakultas Teologi dan Agama Universitas Oxford</p>
                </div>

                <div class="flex flex-col items-center text-center shrink-0 snap-center w-[70vw] sm:w-[40vw] md:w-48 mt-4 md:mt-6">
                    <div class="relative mb-4">
                        <span class="absolute -top-4 left-1/2 -translate-x-1/2 bg-white text-[#12241C] text-xs font-bold px-3 py-1 rounded shadow-md z-10 uppercase tracking-widest">India</span>
                        <div class="w-40 h-40 rounded-full border-4 border-white overflow-hidden bg-gray-200 shadow-xl relative z-0">
                            <img src="{{ asset('images/speakers/dr-mahmoud.webp') }}" alt="Dr. Mahamood Shihab" class="w-full h-full object-cover" onerror="this.outerHTML='<div class=\'w-full h-full flex items-center justify-center text-gray-500 text-xs\'>Foto</div>'">
                        </div>
                    </div>
                    <h4 class="text-white font-bold text-lg mb-1 leading-snug">Dr. Mahamood Shihab K M</h4>
                    <p class="text-gray-400 text-[11px] leading-relaxed">Principal, Ansar Training College</p>
                </div>

                <div class="flex flex-col items-center text-center shrink-0 snap-center w-[70vw] sm:w-[40vw] md:w-48 mt-4 md:mt-6">
                    <div class="relative mb-4">
                        <span class="absolute -top-4 left-1/2 -translate-x-1/2 bg-white text-[#12241C] text-xs font-bold px-3 py-1 rounded shadow-md z-10 uppercase tracking-widest">Malaysia</span>
                        <div class="w-40 h-40 rounded-full border-4 border-white overflow-hidden bg-gray-200 shadow-xl relative z-0">
                            <img src="{{ asset('images/speakers/dr-asmawati.webp') }}" alt="Assoc Prof. Dr. Asmawati Suhid" class="w-full h-full object-cover" onerror="this.outerHTML='<div class=\'w-full h-full flex items-center justify-center text-gray-500 text-xs\'>Foto</div>'">
                        </div>
                    </div>
                    <h4 class="text-white font-bold text-lg mb-1 leading-snug">Assoc Prof. Dr. Asmawati Suhid</h4>
                    <p class="text-gray-400 text-[11px] leading-relaxed">Professor in Islamic Education, Universiti Putra Malaysia</p>
                </div>

            </div>

        </div>
    </section>

    <section class="py-16 bg-gray-50 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                
                <div>
                    <h2 class="text-2xl font-black text-[#1B1B1B] uppercase tracking-wide mb-6 border-l-4 border-[#C0A062] pl-3">
                        GIHES Documentation
                    </h2>
                    <div class="space-y-6">
                        @forelse($videos as $video)
                            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                                <div class="aspect-w-16 aspect-h-9">
                                    <iframe class="w-full h-64 md:h-80" src="{{ $video->youtube_url }}" title="{{ $video->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-bold text-gray-900">{{ $video->title }}</h3>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 italic">No documentation videos available yet.</p>
                        @endforelse
                    </div>
                </div>

                <div>
                    <h2 class="text-2xl font-black text-[#1B1B1B] uppercase tracking-wide mb-6 border-l-4 border-[#5A6446] pl-3">
                        In The News
                    </h2>
                    
                    <div class="space-y-4 max-h-[600px] overflow-y-auto pr-3 custom-scrollbar">
                        @forelse($news as $item)
                            <a href="{{ $item->external_url }}" target="_blank" class="block bg-white rounded-xl shadow-sm border border-gray-200 hover:border-[#C0A062] hover:shadow-md transition-all duration-300 overflow-hidden group">
                                <div class="flex items-center">
                                    <div class="w-1/3 h-32 shrink-0 bg-gray-100 relative overflow-hidden">
                                        @if($item->image_path)
                                            @php
                                                $imageUrl = str_starts_with($item->image_path, 'http') 
                                                    ? $item->image_path 
                                                    : asset('storage/' . $item->image_path);
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="w-2/3 p-4">
                                        <span class="text-[10px] font-black text-[#5A6446] uppercase tracking-widest mb-1 block">{{ $item->publisher_name }}</span>
                                        <h3 class="font-bold text-gray-900 leading-tight group-hover:text-[#C0A062] transition-colors line-clamp-3">
                                            {{ $item->title }}
                                        </h3>
                                        <span class="text-xs text-gray-400 mt-2 block flex items-center gap-1">
                                            Read Article <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <p class="text-gray-500 italic">No news updates available yet.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-20 bg-[#2B2D26] relative z-10 border-t-4 border-[#C0A062]">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                
                <div>
                    <h2 class="text-3xl md:text-5xl font-black text-white leading-tight tracking-tight mb-6 uppercase">
                        Take Part in Shaping<br>
                        <span class="text-[#C0A062]">The Future</span>
                    </h2>
                    <p class="text-gray-300 text-lg mb-8 leading-relaxed max-w-lg">
                        Jadilah bagian dari pergerakan global ini. Daftarkan diri Anda sebagai peserta, atau berkolaborasi bersama kami melalui program sponsorship.
                    </p>
                    
                    <div class="flex items-center gap-3 text-gray-400">
                        <svg class="w-5 h-5 text-[#C0A062]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span class="text-sm font-medium">For Inquiries: <a href="mailto:gihesindonesia2026@gmail.com" class="text-white hover:text-[#C0A062] transition-colors">gihesindonesia2026@gmail.com</a></span>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    
                    <a href="/register" class="w-full flex justify-between items-center bg-[#C0A062] hover:bg-[#a3854d] text-[#12241C] px-8 py-5 rounded-sm font-black tracking-widest uppercase transition-all shadow-lg group">
                        <span>Secure Your Seat (Registration)</span>
                        <svg class="w-6 h-6 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>

                    <a href="https://wa.me/6281319383871?text=Halo%20Mas%20Akrimul%20Hakim,%20saya%20tertarik%20untuk%20mengetahui%20lebih%20lanjut%20mengenai%20peluang%20Sponsorship%20di%20GIHES%202026." target="_blank" class="w-full flex justify-between items-center bg-white hover:bg-gray-100 text-[#12241C] px-8 py-4 rounded-sm font-bold tracking-wide transition-all shadow-md group">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            <span>Join Sponsorship (WA: M. Akrimul Hakim)</span>
                        </div>
                        <svg class="w-5 h-5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <a href="#" class="flex justify-center items-center gap-2 border border-gray-500 hover:border-white text-gray-300 hover:text-white px-6 py-3 rounded-sm font-semibold tracking-wide transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span>PDF Sponsorship</span>
                        </a>

                        <a href="#" class="flex justify-center items-center gap-2 border border-gray-500 hover:border-white text-gray-300 hover:text-white px-6 py-3 rounded-sm font-semibold tracking-wide transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span>Event Schedule</span>
                        </a>
                        
                    </div>

                </div>
                
            </div>
        </div>
    </section>


    <footer class="bg-[#2B2D26] text-white py-8 text-center text-sm text-gray-400">
        <p>&copy; 2026 Global Islamic Holistic Education Summit. Forum Pesantren Alumni Gontor (FPAG).</p>
    </footer>

</body>
</html>