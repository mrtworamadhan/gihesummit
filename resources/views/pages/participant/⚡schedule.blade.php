<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Schedule;

new #[Layout('layouts::app')] class extends Component
{
    public $day1Schedules;
    public $day2Schedules;

    public function mount()
    {
        $this->day1Schedules = Schedule::where('day', 1)->orderBy('id')->get();
        $this->day2Schedules = Schedule::where('day', 2)->orderBy('id')->get();
    }
};
?>

<div class="max-w-6xl mx-auto space-y-8 pb-12" x-data="{ activeTab: 1 }">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <a href="{{ route('participant.dashboard') }}" class="text-sm text-gray-500 hover:text-[#C0A062] flex items-center gap-1 mb-2 transition-colors">
                &larr; Back to Dashboard
            </a>
            <h1 class="text-3xl font-black text-[#1B1B1B] uppercase tracking-tight">Official Schedule</h1>
            <p class="text-gray-500 mt-1">GIHES 2026 Summit Agenda and Itinerary.</p>
        </div>
    </div>

    <div class="flex gap-4 border-b border-gray-200">
        <button @click="activeTab = 1" 
                :class="activeTab === 1 ? 'border-[#12241C] text-[#12241C]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="px-6 py-3 font-black uppercase tracking-widest text-sm border-b-4 transition-colors">
            Day 1 (Hari Pertama)
        </button>
        <button @click="activeTab = 2" 
                :class="activeTab === 2 ? 'border-[#12241C] text-[#12241C]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="px-6 py-3 font-black uppercase tracking-widest text-sm border-b-4 transition-colors">
            Day 2 (Hari Kedua)
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-white uppercase bg-[#12241C]">
                    <tr>
                        <th class="px-6 py-4 font-bold w-40">Waktu</th>
                        <th class="px-6 py-4 font-bold w-1/4">Sesi</th>
                        <th class="px-6 py-4 font-bold w-2/5">Topik / Pembahasan</th>
                        <th class="px-6 py-4 font-bold">Pembicara / PJ</th>
                    </tr>
                </thead>
                
                <tbody x-show="activeTab === 1" class="divide-y divide-gray-200">
                    @foreach($day1Schedules as $schedule)
                        <tr class="{{ $schedule->is_break ? 'bg-[#F4F3EF]' : 'bg-white hover:bg-gray-50' }} transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-[#5A6446]">
                                {{ $schedule->time_range }}
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">
                                {{ $schedule->session_name }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 whitespace-pre-line leading-relaxed">
                                {{ $schedule->topic_description }}
                            </td>
                            <td class="px-6 py-4 text-gray-800 font-medium">
                                {{ $schedule->speaker }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                <tbody x-show="activeTab === 2" x-cloak class="divide-y divide-gray-200">
                    @foreach($day2Schedules as $schedule)
                        <tr class="{{ $schedule->is_break ? 'bg-[#F4F3EF]' : 'bg-white hover:bg-gray-50' }} transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-[#5A6446]">
                                {{ $schedule->time_range }}
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">
                                {{ $schedule->session_name }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 whitespace-pre-line leading-relaxed">
                                {{ $schedule->topic_description }}
                            </td>
                            <td class="px-6 py-4 text-gray-800 font-medium">
                                {{ $schedule->speaker }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>